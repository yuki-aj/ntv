<?php
namespace App\Http\Controllers;
use Session, DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
use App\Models\Coupon;
use App\Models\Custom;
use Stripe\Stripe;
// use Stripe\Customer;
use App\Models\Open_Close;
use Stripe\Charge;
use Stripe\PaymentIntent;
class ShoppingController extends Controller	{
	// お買い物カゴ
	public function Cart(Request $request){
		return view('shopping/cart');
	}
	// ご注文手続き
	public function Pay(Request $request){
		$apptdate = Session::get('apptdate');//曜日
		if($apptdate == null){
			$apptdate = 0;
		}//初回に0を入れる
		Session::put('apptdate',$apptdate);
		$stripe = Session::get('stripe');
		if(!$stripe){
			$stripe = $request->stripe_id;
		}
		$s_id = Store::where('stripe_user_id',$stripe)->first();
		// dd($request->stripe_id);
		// $s_id   = Store::where('stripe_user_id',$stripe)->first();
		// スケジュール配列(week_schedules)テンプレート作成
		$datetime = new DateTime();
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$mark = array('L','D','●','-');
		$week_schedules =array();
		if(12 < $datetime->format('H')){//13時過ぎかチェック
			$datetime->modify('+1 days');//過ぎていたら一日プラス
		}
		for($i = 0; $i < 7; $i++){
			$w = (int)$datetime->format('w');
			$week_schedules[$i]['w']    = $week[$w];
			if($i){
				$week_schedules[$i]['d']    = $datetime->format('j');
			}else{
				$week_schedules[$i]['d']    = $datetime->format('n/j');
			}
			$week_schedules[$i]['week'] = $w;
			$week_schedules[$i]['day']  = $datetime->format('m月d日('.$week[$w].')');
			$datetime->modify('+1 days');
		}
		//カレンダー(営業日)配列(calendars)を取得
	    $calendars = array();
		$work_calendars = Open_Close::where('s_id',$s_id->id)->get();
		foreach($work_calendars as $work_calendar){
			if(strpos($work_calendar->day,'w') !== false){
				$number = explode('w',$work_calendar->day)[1];
				$calendars[$number] = $work_calendar;
			}else{//日付指定なら
				$date = $work_calendar->day;
				$calendars[$date] = $work_calendar;
			}
		}
		$o_times = [];
		// スケジュール配列(week_schedules)に営業状況(mark)をいれる
		foreach ($week_schedules as $key => $week_schedule) {// スケジュール配列をforeach
			if (isset($calendars[7])){//通常日の営業時間があればまず取得する
				$times = explode(",", $calendars[7]->time);
			}
			if (isset($calendars[$week_schedule['week']])){//当該曜日の営業時間があれば上書き
				$times = $o_times[$key] = explode(",", $calendars[$week_schedule['week']]->time);
			}
			if (isset($calendars[$week_schedule['day']])){//当該日の営業時間があれば上書き
				$times = $o_times[$key] = explode(",", $calendars[$week_schedule['day']]->time);
			}
			$temp_time = implode('-',$times);//ハイフンで結合10:00-10:30
			$o_times[$key] = str_replace('-', ',',$temp_time);//ハイフンをカンマに変更,10:00,10:30,
			$o_times[$key] = ltrim($o_times[$key],',');//先頭のカンマを削除10:00,10:30,
			$o_times[$key] = rtrim($o_times[$key],',');//最後のカンマを削除10:00,10:30
			$o_times[$key] = explode(",", $o_times[$key]);//カンマで分割0->10:00 1->10:30
			$x = 0;//時間配列の為の引数
			if($o_times[$key] != ''){//営業している日だけ入る
				foreach ($o_times[$key] as $key2 => $t_open_close) {
					if($o_times[$key][$key2] != ''){//空じゃなかったら
						$o_times[$key][$key2] = new DateTime($o_times[$key][$key2]);//日付のデータに変更
						$o_times[$key][$key2] = $o_times[$key][$key2]->format('H:i');//00:00の型に変更
						$o_times[$key][$key2] = date('H:i',strtotime($o_times[$key][$key2]));//開店時刻
					};
					if(isset($o_times[$key][$key2+1])){//閉店時間じゃなかったら入る
						$t = date('H:i',strtotime($o_times[$key][$key2].'+30min'));//初回の為の30分足した時間
					 	$temp_i = '';//00:00-00:00の形にするための箱
						for($i = $o_times[$key][$key2]; $i < $o_times[$key][$key2+1];){//開店時間から閉店時間まで30分足していく
							$temp_i = $i;//営業開始時間
							$i = $t;//30分足した時刻を入れる
							$temp_i .= '-'.$i;//営業時間に-を入れて30分足した新しい$iを入れる(10:00-10:30など)
							// var_dump($temp_i);//例10:00-10:30が入る
							$week_schedules[$key]['times'][$x] = $temp_i;//00:00-00:00の形の物を入れる
							$t = date('H:i',strtotime($i.'+30min'));//$iに30分足したものを$tに入れる
							$x++;//配列の連番に1を足す
						}
					}
				}
				$check_time = new DateTime();//当日配送時間チェック
				if($week_schedules[0]['d'] == $check_time->format('n/j')){//0番目と今日の日付が一致したら入る
					foreach ($week_schedules[0]['times'] as $key3 => $c_time) {
						$check = substr($c_time, 0, 2);
						if($check < 17){
							unset($week_schedules[0]['times'][$key3]);
						}
					}
					$week_schedules[0]['times'] = array_values($week_schedules[0]['times']);
					// dd($week_schedules[0]['times']);
				}
			}
			if ($times[0] != "" && $times[1] != ""){
				$mark = "●";
			}
			elseif ($times[0] != ""){
				$mark = "L";
			}
			elseif ($times[1] != ""){
				$mark = "D";
			}
			else {
				$mark = "-";
			}
			$week_schedules[$key]['mark'] = $mark;
		}
		// $theday = new DateTime();
		// $datetime =array();// 空の配列
		// if($theday->format('H') < 13){//13時過ぎか否か
		// 	$theday->modify('-1 days');
		// }
		// for($i = 0; $i < 7; $i++){//一週間
		// 	$theday->modify('+1 days');
		// 	$datetime[$i]['value']   = $i;
		// 	$datetime[$i]['display'] = $theday->format('m月d日');
		// }
		$pst_id = '16';//送料のp_id
		$one_coupon = '';
		if(isset($request->coupon)){//クーポンがある場合
			if($request->coupon == 0){//クーポンキャンセルの場合
				$msg = 'クーポンの使用をキャンセルしました。';
				session::flash('flash_message',$msg);
			}else{//ある場合（キャンセル以外）
				$one_coupon = Coupon::find($request->coupon);
				if($one_coupon->p_id == 0){//総額から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id != 0 && $one_coupon->p_id != $pst_id){//商品から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_p_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_p_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_p_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id == $pst_id){//送料から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_post_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_post_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_post_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}

				}
				if($request->coupon != 0){//flashメッセージ
					$msg = $one_coupon->title.'を適用しました。';
					session::flash('flash_message',$msg);
				}
			}
		}
		if(!Session::get('kind')){return redirect()->to('/');};
		//1週間を取得
		$numOfDays = 7; //日付を取得する日数
		$dt      = new Carbon();
		$format  = 'YYYY年MM月DD日(ddd)';//表示する日付の形式(月　日　曜日)
		$now     = date("H");//今何時か確認
		if(15 <= $now){//現在時刻が15時を超えていたら明日の日付を入れる
			$week[0] = $dt->tomorrow()->isoFormat($format);
			// var_dump('もう遅い');
		}else{
			$week[0] = $dt->today()->isoFormat($format);
			// var_dump('まだ間に合う');
		}
		// $week[0] = $dt->today()->isoFormat($format);
		// dd($now);
		// dd($week);
		//Carbonのインスタンスが上書きされないようにcopy()して日付を加算
		for ($i=1; $i < $numOfDays ; $i++) {
		  $week[$i] = $dt->copy()->addDay($i)->isoFormat($format);
		}
		if(isset($request->stripe_id)){
			Session::put('stripe',$request->stripe_id);//全部が同列に並んだカート内の商品
		}
		Session::forget('p_carts');//以前に決済した情報を消す
		$carts = Session::get('pay_carts');//決済カート
		if($carts == []){//空ならTOPに
		  return redirect('/');
		}
		$p_ids  = [];//カートに入っている商品のid一覧（番号）
		foreach ($carts as $key => $cart) {
			if($key == 0){$p_ids[$key] = 0;}//最初に0を入れる(全店舗対象クーポン)
			$p_ids[$key+1] =  $cart['p_id'];
		}
		array_push($p_ids,$pst_id);//$p_idsに送料クーポン追加(16)
		$user = "";
		$u_id    = Session::get('u_id');
		$user    = User::find($u_id);
		$today   = date("Y-m-d");
		$c_ids   = explode(',', $user->coupon_stock);
		$coupons = Coupon::WhereIn('id',$c_ids)->whereIn('s_id',[0,$s_id->id])->whereIn('p_id',$p_ids)->get();
		//$couponsに持っているクーポン->一致する店舗(全店舗含む)->カートの商品idと一致するクーポン(送料クーポン込)->get;
		foreach ($coupons as $key => $coupon) {//有効期限チェック
			if($coupon->to_date < $today){//切れていたら
				foreach ($c_ids as $key2 => $c_id) {
					if($c_id == $coupon->id){//削除
						unset($coupons[$key]);
						unset($c_ids[$key2]);
					}
				}
				$user->coupon_stock = implode(',', $c_ids);//詰め直し
			}
			if(substr($user->coupon_stock,0,1) == ','){
				$user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
			}elseif (substr($user->coupon_stock,-1) == ',') {
				$user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
			}
		}
		$user->save();
		Stripe::setApiKey(env('STRIPE_SECRET'));
		$customer = "";
		$card     = "";
		if($user->stripe_id != ""){//ログインしてカード登録あり
		$customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ取得
		$card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//デフォルトのカード情報取得
		$user->last4   = "********".$card->last4;
		$user->expired = $card->exp_year."年".$card->exp_month."月";
		}
		$stores    = Store::where('t_store.store_status','1')->get();//statusが1のお店（有効）
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
		foreach ($carts as $key => $cart) {
		  foreach ($stores as $store_id => $store) {//storeを回す
			if($store->id == $cart['s_id']){//storeのidとカート内の商品のs_idが同じなら入る
			  foreach ($products as $key2 => $product) {//Productテーブルを回す
				if($product->id == $cart['p_id']){//Productテーブルのidと、カート内の商品idが同じだったら
				  $new_carts[$store->stripe_user_id][$key] = $cart;//$new_carts[stripe_id][商品id]に商品情報を入れる
				  $s_name = Store::find($cart['s_id']);
				  $new_carts[$store->stripe_user_id][$key]['s_name'] = $s_name->name;
				  break;
				}
			  }
			}
		  }
		}
		$all_summary  = 0;//総合計金額（初期値0）
		$temp_summary = 0;//送料計算の為の仮合計
		$p_border     = 1500;//送料価格基準
		$p_upper      = 3000;//送料価格基準
		$d_fee300     = 375;//送料金額（1500円以下）
		$d_fee600     = 750;//送料金額（3000円以上）
		$d_fee        = 0.25;//送料金額（1500~3000円の間）
		$pay_id       = '';
		$postage_price = '';
		$parcent_amount = '';
		$d_amount     = '';//総額からの割引額
		$d_p_amount   = '';//商品からの割引額
		$d_post_amount = '';//送料からの割引額
		$used_coupon_flag = false;
		$summary = 0;//合計金額は（初期値0）
		foreach ($new_carts as $stripe_id => $pay_cart) {
		   if($stripe_id == $stripe){
				$pay_id = $stripe;
				foreach ($pay_cart as $key => $product) {//店舗の商品($s_product)ごとに回す
					if(isset($one_coupon->p_id) && $product['p_id'] == $one_coupon->p_id && isset($fix_p_discount) && !$used_coupon_flag){
					//商品から割引(クーポンがあって、商品id一致、割引固定値の場合)
						$new_carts[$stripe_id][$key]['d_price'] = (float)$fix_p_discount;//商品割引額を詰める
						$new_carts[$stripe_id][$key]['total']   = $product['total'] - (float)$fix_p_discount;//商品総額から固定値を引く
						$used_coupon_flag = true;
					}
					$summary += $new_carts[$stripe_id][$key]['total'];//商品の合計金額を足していく
				}
				if(isset($fix_discount)){//店舗総額から固定値割引の場合
					$d_amount     = $summary - $fix_discount;
					$temp_summary = $all_summary += ($summary - $fix_discount);
				}elseif(isset($pct_discount)){//店舗総額から％引き
					$d_amount     = $summary * $pct_discount;
					$temp_summary = $all_summary += $summary - ($summary * $pct_discount);
				}else{//店舗総額割引なし
					$temp_summary = $all_summary += $summary;
				}
				//店舗合計の送料計算
				if($all_summary < $p_border){//1500円以下
					$postage = Product::where('p_status','2')->where('price',$d_fee300)->first();//DBから送料を取得
					if(isset($fix_post_discount)){//送料値引きあり(金額割引)
						$d_post_amount = $postage->price;//送料
						$postage->price = $postage_price = $postage->price - $fix_post_discount;
						$all_summary = $all_summary + $postage->price;
					}elseif(isset($pct_post_discount)){//送料値引きあり(％割引)
						$d_post_amount = $postage->price;//送料
						$postage->price = $postage_price = $postage->price * $pct_post_discount;
						$all_summary = $all_summary + $postage->price;
					}else{//割引なし
						$d_post_amount = $postage_price = $postage->price;//送料
						$all_summary = $all_summary + $postage->price;
					}
				}elseif($all_summary > $p_upper){//3000円以上
					$postage = Product::where('p_status','2')->where('price',$d_fee600)->first();//DBから送料を取得
					if(isset($fix_post_discount)){
						$d_post_amount = $postage->price;//送料
						$postage->price = $postage_price = $postage->price - $fix_post_discount;
						$all_summary = $all_summary + $postage->price;
					}elseif(isset($pct_post_discount)){//％割引
						$d_post_amount = $postage->price;//送料
						$postage->price = $postage_price = $postage->price * $pct_post_discount;
						$all_summary = $all_summary + $postage->price;
					}else{//割引なし
						$d_post_amount = $postage_price = $postage->price;//送料
						$all_summary = $all_summary + $postage->price;
					}
				}else{
					$postage = Product::where('p_status','2')->where('price',$d_fee)->first();//DBから送料を取得
					if(isset($fix_post_discount)){//金額割引
						$d_post_amount = $all_summary * $postage->price;//送料
						$postage->price = $postage_price = $all_summary * $postage->price - $fix_post_discount;
						//総額×元の送料-新送料（固定値）
						$all_summary = $all_summary + $postage->price;
					}elseif(isset($pct_post_discount)){//％割引
						$d_post_amount = $all_summary * $postage->price;//送料
						$postage->price = $postage_price = ($all_summary * $postage->price) - ($all_summary * $postage->price) * $pct_post_discount;
						//総額×元の送料-(総額×元の送料)×新送料の割引率
						$all_summary = $all_summary + $postage->price;
					}else{//割引なし
						$d_post_amount = $parcent_amount = $all_summary * $postage->price;//送料
						$postage_price = $all_summary * $postage->price;
						$all_summary   = $all_summary + $all_summary * $postage->price;
					}
				}
		   }
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		// dd($week_schedules);
		Session::put('new_carts',$new_carts);//店舗ごとにstripe_id並んだカート内の商品
		Session::put('pay_carts',$carts);//全部が同列に並んだカート内の商品
		Session::put('stripe',$stripe);//全部が同列に並んだカート内の商品
		return view('shopping/pay',compact('parcent_amount','week_schedules','d_post_amount','d_amount','temp_summary','datetime','apptdate','one_coupon','postage_price','postage','all_summary','stores','new_carts','carts','user','week','pay_id','seo','coupons'));
	}
	// ご注文手続き
	public function OnePay(Request $request){//未登録ユーザー購入の場合
	}
	// 決済　確認
	public function Confirm(Request $request){
		return view('shopping/confirm');
	}
	//　注文確定
	public function Ordercompletion(Request $request){
        if($request->isMethod('GET')){//GETのケース
            return redirect('/');
        }
		return view('shopping/ordercompletion');
	}
	//　受注一覧リスト
	public function Orderlist(Request $request){
		return view('shopping/orderlist');
	}
	//　注文詳細
	public function Orderdetails(Request $request){
		return view('shopping/orderdetails');
	}
	//　注文詳細編集
	public function Orderedit(Request $request){
		return view('shopping/orderedit');
	}
}/* EOF */
