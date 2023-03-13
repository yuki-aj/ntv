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
	
	// ご注文手続き
	public function Pay(Request $request){
		$apptdate = Session::get('apptdate');
		if($apptdate == null){
			$apptdate = 0;
			Session::put('apptdate',$apptdate);
		}//初回に0を入れる
		//stripe_idではなくてs_idに変更
		if($request->store_id){
			$store = Store::find($request->store_id);
			Session::put('stripe',$store->stripe_user_id);
			Session::put('store_id',$store->id);
			$stripe = $store->stripe_user_id;
		}else{
			$stripe = Session::get('stripe');
		}
		if(!$stripe){//Stripeがなかったらカートページに戻る
			return redirect()->to('add_cart');
		}
		$store = Store::find(Session::get('store_id'));
		//開始日設定(13時過ぎていないかチェック)
		$work_day     = new DateTime();
		$apptdate_day = new DateTime();
		if($work_day->format('H') >= 13){
			$work_day->modify('+1 days');//開始日を一日ずらす
			$apptdate_day->modify('+1 days');//開始日を一日ずらす
			$morning = 0;//午後
		}else{
			$morning = 1;//午前
		}
		$apptdate_day->modify('+'.$apptdate.' days');
		$apptdate_w = (int)$apptdate_day->format('w');
		//開始日から一週間分のスケジュール配列(week_schedules)を作成
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$week_schedules = array();
		for($i = 0; $i < 7; $i++){
			$w = (int)$work_day->format('w');
			$week_schedules[$i]['w'] = $w;
			if($i){
				$week_schedules[$i]['d']  = $work_day->format('j');
			}else{
				$week_schedules[$i]['d']  = $work_day->format('n/j');
			}
			$week_schedules[$i]['week'] = $w;
			$week_schedules[$i]['day']  = $work_day->format('m月d日('.$week[$w].')');
			$work_day->modify('+1 days');
		}
		//該当店舗の営業時間カレンダー配列(calendars)を取得:添字は曜日番号または日付
		$calendars = array();
		$work_calendars = Open_Close::where('s_id',$store->id)->get();
		foreach($work_calendars as $work_calendar){
			if(strpos($work_calendar->day,'w') !== false){
				$number = explode('w',$work_calendar->day)[1];
				$calendars[$number] = $work_calendar;
			}else{//日付指定なら
				$date = $work_calendar->day;
				$calendars[$date] = $work_calendar;
			}
		}
		// スケジュール配列(week_schedules)に営業時間をいれる
		foreach ($week_schedules as $key => $week_schedule) {// スケジュール配列をforeach
			if (isset($calendars[7])){//通常日の営業時間があればまず取得する
				$times = explode(",", $calendars[7]->time);
			}
			if (isset($calendars[$week_schedule['week']])){//当該曜日の営業時間があれば上書き
				$times = explode(",", $calendars[$week_schedule['week']]->time);
			}
			if (isset($calendars[$week_schedule['day']])){//当該日の営業時間があれば上書き
				$times = explode(",", $calendars[$week_schedule['day']]->time);
			}
			$week_schedules[$key]['times'] = $times;
			if ($times[0] == "" && $times[1] == ""){//お休みの場合
				if($week_schedule['w'] == $apptdate_w){//配送希望日がお休みの場合
					$msg = '指定された日は休業日となります。 日付を選び直してください。';
					session::flash('flash_message',$msg);
					//カートページにリダイレクトし、日付を選び直してくださいのメッセージを出す
					return redirect()->to('add_cart')->with('stripe_id',$stripe);//
				}
				unset($week_schedules[$key]);
			}
		}
		//当日配送営業時間チェック
		foreach ($week_schedules as $key => $week_schedule) {
			if($apptdate == 0 && $morning == 1 && $apptdate_w == $week_schedule['w']){
				$times = $week_schedule['times'];
				$flag = 0;//0クローズ
				foreach ($times as $time) {
					$half = explode('-', $time);
					if(isset($half[1])){
						$quarter = explode(':', $half[1]);
						if($quarter[0] >= 17){
							$flag = 1;
						}
					}
				}
				if($flag == 0){
					$msg = '本日の配送時間は終了しました。日付を選び直してください。';
					session::flash('flash_message',$msg);
					return redirect()->to('add_cart')->with('stripe_id',$stripe);
				}
			}
			if($apptdate_w == $week_schedule['w']){//選択した配送希望日のスケジュール取得
				$apptdate_schedules_times = $week_schedule['times'];//[11:00-12:00,15:00-17:00]
			}
		}
		//選択した配送希望日の営業時間一覧($time_schedules)を作成
		$times = array();
		if(isset($apptdate_schedules_times[0])){
			$times[0] = explode("-", $apptdate_schedules_times[0]);//[11:00,12:00]
		}else{
			$times[0] = '';
		}
		if(isset($apptdate_schedules_times[1])){
			$times[1] = explode("-", $apptdate_schedules_times[1]);//[15:00,17:00]
		}else{
			$times[1] = '';
		}
		$time_schedules = array();
		$j = 0;
		foreach ($times as $key => $time) {
			if($time != '' && count($time) == 2){//timeが空じゃなくて要素が二つあったら
				$from = $work = $time[0];
				$to   = $time[1];
				for($i=$j ; $work < $to ; $i++){
					$work = date('H:i',strtotime($from.' +30min'));
					if($work <= $to){
						$time_schedules[$i] = $from.'-'.$work;
						$from = $work;
					}
				}
				$j = $i;
			}
		}
		$pst_id = '16';//送料のp_id
		$one_coupon = '';
		if(isset($request->coupon)){//クーポンがある場合
			if($request->coupon == 0){//クーポンキャンセルの場合
				$msg = 'クーポンの使用をキャンセルしました。';
				session::flash('flash_message',$msg);
			}else{//ある場合（キャンセル以外）
				$one_coupon = Coupon::find($request->coupon);//選んだクーポン
				if($one_coupon->p_id == 0){//総額から引くクーポンの場合
					if(strpos($one_coupon->discount,'円') == true){//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_discount = strstr($one_coupon->discount,'%',true) * 0.01;//　例 )50％OFFのクーポンの場合　50 × 0.01 ＝ 0.5 になる
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id != 0 && $one_coupon->p_id != $pst_id){//商品から引くクーポンの場合
					if(strpos($one_coupon->discount,'円') == true){//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_p_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_p_discount = strstr($one_coupon->discount,'%',true) * 0.01;// 例 )50％OFFのクーポンの場合　50 × 0.01 ＝ 0.5 になる
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_p_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id == $pst_id){//送料から引クーポンの場合
					if(strpos($one_coupon->discount,'円') == true){//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_post_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_post_discount = strstr($one_coupon->discount,'%',true) * 0.01;// 例 )50％OFFのクーポンの場合　50 × 0.01 ＝ 0.5 になる
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
		if(isset($request->stripe_id)){
			Session::put('stripe',$request->stripe_id);//全部が同列に並んだカート内の商品
		}
		Session::forget('p_carts');//以前に決済した情報を消す
		$carts = Session::get('pay_carts');//決済カート
		if($carts == []){//カートが空ならTOPに
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
		$today   = date("Y-m-d");//今日の日付
		$c_ids   = explode(',', $user->coupon_stock);//所持しているクーポン1つ1つ分ける
		$s_ids   = [$store->id,0];//対象の店舗のidと0
		$coupons = Coupon::WhereIn('id',$c_ids)->WhereIn('s_id',$s_ids)->whereIn('p_id',$p_ids)->get();
		//userが所持しているクーポン　->　一致する店舗(全店舗含む)　->　カートの商品IDと一致するクーポン(送料クーポン込)をget
		foreach ($coupons as $key => $coupon) { //有効期限チェック
			if($coupon->to_date < $today){//切れていたら
				foreach ($c_ids as $key2 => $c_id) {
					if($c_id == $coupon->id){//削除
						unset($coupons[$key]);
						unset($c_ids[$key2]);
					}
				}
				$user->coupon_stock = implode(',', $c_ids);//詰め直し
			}
			if(substr($user->coupon_stock,0,1) == ','){//coupon_stockの一番左に "，" がある場合
				$user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
			}elseif (substr($user->coupon_stock,-1) == ',') {//coupon_stockの一番右に "，" がある場合
				$user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
			}
		}
		$user->save();
		Stripe::setApiKey(env('STRIPE_SECRET'));//Stripe連動
		$customer = "";
		$card     = "";
		if($user->stripe_id != ""){//ログインしてカード登録あり
		$customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ取得
		$card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//デフォルトのカード情報取得
		$user->last4   = "********".$card->last4;
		$user->expired = $card->exp_year."年".$card->exp_month."月";
		}
		$products  = Product::where('s_id',$store->id)->where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
		foreach ($carts as $key => $cart) {
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
					if($product['s_id'] == $store->id){
						if(isset($one_coupon->p_id) && $product['p_id'] == $one_coupon->p_id && isset($fix_p_discount) && !$used_coupon_flag){
						//商品から割引(クーポンがあって、商品id一致、割引固定値の場合)
							$new_carts[$stripe_id][$key]['d_price'] = (float)$fix_p_discount;//商品割引額を詰める
							$new_carts[$stripe_id][$key]['total']   = $product['total'] - (float)$fix_p_discount;//商品総額から固定値を引く
							$used_coupon_flag = true;
						}
						$summary += $new_carts[$stripe_id][$key]['total'];//商品の合計金額を足していく
					}
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
						$postage->price = $postage_price = $postage->price - ($postage->price * $pct_post_discount);
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
						$postage->price = $postage_price = $postage->price - ($postage->price * $pct_post_discount);
						$all_summary = $all_summary + $postage->price;
					}else{//割引なし
						$d_post_amount = $postage_price = $postage->price;//送料
						$all_summary = $all_summary + $postage->price;
					}
				}else{//1500円から3000円の間
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
		$cs = Custom::where('type',5)->get();//seo
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		Session::put('new_carts',$new_carts);//店舗ごとにstripe_id並んだカート内の商品
		Session::put('pay_carts',$carts);//全部が同列に並んだカート内の商品
		Session::put('stripe',$stripe);//全部が同列に並んだカート内の商品
		return view('shopping/pay',compact('parcent_amount','week_schedules','time_schedules','d_post_amount','d_amount','temp_summary','apptdate','one_coupon','postage_price','postage','all_summary','new_carts','carts','user','week','pay_id','seo','coupons','store'));
	}

	//　注文確定
	public function Ordercompletion(Request $request){
        if($request->isMethod('GET')){//GETのケース
            return redirect('/');
        }
		return view('shopping/ordercompletion');
	}
}/* EOF */
