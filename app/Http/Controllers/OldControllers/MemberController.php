<?php
namespace App\Http\Controllers;
use Session, Log;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use Stripe\Stripe;
use App\Models\Customer;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use App\Models\Time;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\Category;
use App\Models\Custom;
use App\Models\Coupon;
use App\Models\Open_Close;
use Illuminate\Pagination\LengthAwarePaginator;
class MemberController extends Controller	{
	// マイページ　
	public function Mypage(Request $request){
		$kind = Session::get('kind');
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/mypage',compact('user','seo'));
	}
	// お気に入り　
	public function Favorite(Request $request){
		CommonController::AccessLog();
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		$favorites = explode(',', $user->favorite);
		$stores = Store::WhereIn('id',$favorites)->get();
		$apptdate = Session::get('apptdate');//曜日
		if($apptdate == null){
			$apptdate = date('w');
			$now = date('H');
			if($now < 12){
				$apptdate = $apptdate + 1;
			}
		}

		// 予定日の曜日を計算
		$thisday = new DateTime();
		if($thisday->format('H') >= 13){//13時過ぎか否か
			$thisday->modify('+1 days');
		}
		$thisday->modify('+'.$apptdate.' days');
		$this_day = $thisday->format('Y-m-d');
		$this_weakday = 'w'.date('w',strtotime($this_day));

		$w_calendars = Open_Close::orderByRaw('s_id asc,day desc')->get();
		$calendars = array();
		foreach ($w_calendars as $w_calendar) {
			$day = $w_calendar->day;
			if(strpos($day,'w') === false){
				if($day != $this_day){
					continue;
				}
			}else{
				if($day != 'w7' && $day != $this_weakday){
					continue;
				}
			}
			$calendars[$w_calendar->s_id] = $w_calendar->open;
		}
		$s_ids = array();
		$i = 0;
		foreach ($calendars as $s_id => $open) {
			if($open){
				$s_ids[$i] = $s_id;
				++$i;
			}
		}

		// join =指定された配列内の要素を文字列として連結する 
		$query = Product::join('t_store', 't_store.id', '=', 't_product.s_id');
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');// store_statusとp_statusが1のもの
		// $query->whereIn('t_store.id',$s_ids);// 配達有効な店舗のみ
		$query->WhereIn('t_store.id',$favorites);// お気に入り店舗のみ
		if ($request->has('keyword')) { //キーワード検索
			$keyword = $request->keyword;
			//$query->where('t_product.name', 'like', "%{$request->keyword}%"); // あいまい検索　(productのnameのみ)
			$query->where(function ($query) use ($keyword) {
				$query->where('t_product.name', 'like', '%' . $keyword . '%')
						->orWhere('t_product.note', 'like', '%' . $keyword . '%')
						->orWhere('t_store.name', 'like', '%' . $keyword . '%');
			});
		}
		if ($request->c_id) { //カテゴリー検索　
			$query->where('t_product.c_id',$request->c_id);
		}
		$query->orderByRaw('t_product.updated_at desc');// 更新した順番が新しい順に並べ替え
		$query->select('t_store.id as s_id','t_product.id as p_id','t_store.name as s_name','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.catch_copy as s_catch_copy','t_store.schedule_memo as s_schedule_memo');
		// ↑　テーブルを二つ呼んでいるのでわかりやすいように変換（お店と商品　紐づけ）
		$product_stores = $query->get();// 並び替えと変換を行ったものをget
		$stores = array();
		$lists = array();
		foreach ($product_stores as $product_store) {
			$p_id = $product_store->p_id;
			$s_id = $lists[$p_id]['s_id'] = $product_store->s_id;
			$stores[$s_id]['s_name'] = $lists[$p_id]['s_name'] = $product_store->s_name;
			$stores[$s_id]['s_catch_copy'] = $product_store->s_catch_copy;
			$stores[$s_id]['s_schedule_memo'] = $product_store->s_schedule_memo;
			$file_name = '/img_files/store/store'.$s_id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){	// exists　＝アップロードしようとするファイルが存在するか
				$stores[$s_id]['store_img'] = 'storage'.$file_name;
			}else{
				$stores[$s_id]['store_img'] = '/img/store00.jpg';
			}
			$stores[$s_id]['product'][$p_id]['p_name']  = $lists[$p_id]['p_name'] = $product_store->p_name;
			$stores[$s_id]['product'][$p_id]['p_price'] = $lists[$p_id]['p_price'] = $product_store->p_price;
			$stores[$s_id]['product'][$p_id]['p_note']  = $product_store->p_note;
			$file_name = '/img_files/product/product'.$p_id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = 'storage'.$file_name;
			}else{
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = '/img/product00.jpg';
			}
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}

		$stores = array();
		$lists = array();
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$mark = array('L','D','●','-');
		$week_schedules =array();
		foreach ($product_stores as $product_store) {
			$p_id = $product_store->p_id;
			$s_id = $lists[$p_id]['s_id'] = $product_store->s_id;
			$stores[$s_id]['s_name'] = $lists[$p_id]['s_name'] = $product_store->s_name;
			$stores[$s_id]['s_catch_copy'] = $product_store->s_catch_copy;
			$stores[$s_id]['s_schedule_memo'] = $product_store->s_schedule_memo;
			$file_name = '/img_files/store/store'.$s_id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){	// exists　＝アップロードしようとするファイルが存在するか
				$stores[$s_id]['store_img'] = 'storage'.$file_name;
			}else{
				$stores[$s_id]['store_img'] = '/img/store00.jpg';
			}
			$stores[$s_id]['product'][$p_id]['p_name']  = $lists[$p_id]['p_name'] = $product_store->p_name;
			$stores[$s_id]['product'][$p_id]['p_price'] = $lists[$p_id]['p_price'] = $product_store->p_price;
			$stores[$s_id]['product'][$p_id]['p_note']  = $product_store->p_note;
			$file_name = '/img_files/product/product'.$p_id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = 'storage'.$file_name;
			}else{
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = '/img/product00.jpg';
			}
			// スケジュール配列(week_schedules)テンプレート作成
			$datetime = new DateTime();
			for($i = 0; $i < 7; $i++){
				$w = (int)$datetime->format('w');
				$week_schedules[$i]['w']    = $week[$w];
				$week_schedules[$i]['d']    = $datetime->format('n/j');
				$week_schedules[$i]['week'] = $w;
				$week_schedules[$i]['day']  = $datetime->format('Y-m-d');
				$datetime->modify('+1 days');
			}
			//カレンダー(営業日)配列(calendars)を取得
			$calendars = array();
			$work_calendars = Open_Close::where('s_id',$s_id)->get();
			foreach($work_calendars as $work_calendar){
				if(strpos($work_calendar->day,'w') !== false){
					$number = explode('w',$work_calendar->day)[1];
					$calendars[$number] = $work_calendar;
				}else{//日付指定なら
					$date = $work_calendar->day;
					$calendars[$date] = $work_calendar;
				}
			}
			// スケジュール配列(week_schedules)に営業状況(mark)をいれる
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

			$stores[$s_id]['week_schedules']  = $week_schedules;
		}
		$date = date('Y-m-d');
		$coupon = '';
		foreach($stores as $s_id => $store){
			$coupon = Coupon::where('s_id',$s_id)->where('from_date','<',$date)->where('to_date','>',$date)->first();
			if($coupon != ''){
				$stores[$s_id]['coupon_flag'] = 1;
			}else{
				$stores[$s_id]['coupon_flag'] = 0;
			}
		}
		return view('member/favorite',compact('coupon','stores','lists','query','request','apptdate','seo','week_schedules'));
	}


	// 新規お支払い情報追加
	public function NewPayment(Request $request){
		$stripe_pk = env('STRIPE_KEY');
		Stripe::setApiKey(env('STRIPE_SECRET'));
		$user = User::find(Session::get('u_id'));
		if(is_null($user)){//指定したu_idのユーザが存在しなかった場合
			return Redirect::to('logout')->send();
		}
		$default_card = '';//決済カード
		$sub_card     = array();//登録済みカード
		if(!empty($user->stripe_id)){//顧客の登録データがある場合
				$customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ呼び出し
				$card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//デフォルトのカード情報取得
				$all_card = \Stripe\Customer::allSources($user->stripe_id);//登録された1ユーザーのすべてのカード情報取得
				foreach($all_card['data'] as $key => $card_data){//登録された全てのカード情報を回す
						if($card_data['id'] == $customer['default_source']){//デフォルトのカード情報と一致したら、$default_cardに情報を入れる
								$default_card = [
										'number' => str_repeat('*', 8).$card->last4,
										'brand' => $card->brand,
										'exp_month' => $card->exp_month,
										'exp_year' => $card->exp_year,
										'name' => $card->name,
										'id' => $card->id,
								];
						}else{//一致しなかったら$sub_cardに情報を入れる
								$sub_card[$key] = [
										'number' => str_repeat('*', 8).$card_data->last4,
										'brand' => $card_data->brand,
										'exp_month' => $card_data->exp_month,
										'exp_year' => $card_data->exp_year,
										'name' => $card_data->name,
										'id' => $card_data->id,
								];
						}
				}
		}
		if(isset($user->stripe_id) && isset($customer)){
				Session::put('default_source',$customer->default_source);
				Session::put('default_card',$default_card);
				Session::put('all_card',$all_card);
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/new_payment',compact('stripe_pk','user','sub_card','seo'));
	}
		// お支払い変更　
	public function MyProfilePayment(Request $request){
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = User::find(Session::get('u_id'));
		if(is_null($user)){//指定したu_idのユーザが存在しなかった場合
			return Redirect::to('logout')->send();		
		}
        $default_card = '';//決済カード
        $sub_card     = array();//登録済みカード
        if(!empty($user->stripe_id)){//顧客の登録データがある場合
            $customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ呼び出し
            $card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//デフォルトのカード情報取得
            $all_card = \Stripe\Customer::allSources($user->stripe_id);//登録された1ユーザーのすべてのカード情報取得
            foreach($all_card['data'] as $key => $card_data){//登録された全てのカード情報を回す
                if($card_data['id'] == $customer['default_source']){//デフォルトのカード情報と一致したら、$default_cardに情報を入れる
                    $default_card = [
						'number' => str_repeat('*', 8).$card->last4,
                        'brand' => $card->brand,
                        'exp_month' => $card->exp_month,
                        'exp_year' => $card->exp_year,
                        'name' => $card->name,
                        'id' => $card->id,
                    ];
                }else{//一致しなかったら$sub_cardに情報を入れる
                    $sub_card[$key] = [
						'number' => str_repeat('*', 8).$card_data->last4,
                        'brand' => $card_data->brand,
                        'exp_month' => $card_data->exp_month,
                        'exp_year' => $card_data->exp_year,
                        'name' => $card_data->name,
                        'id' => $card_data->id,
                    ];
                }
            }
        }
        if(isset($user->stripe_id) && isset($customer)){
			Session::put('default_source',$customer->default_source);
            Session::put('default_card',$default_card);
            Session::put('all_card',$all_card);
        }
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		// dd($request);
		return view('member/myprofile_payment',compact('stripe_pk','default_card','sub_card','seo'));
	}
	// パスワード変更　
	public function Password(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		$u_id = Session::get('u_id');
		$alert = array();
		if ($request->isMethod('POST')) {//POSTの場合
			if($request->new_password !== $request->new_password2){
				$alert['class'] = 'warning';
				$alert['text'] = '新しいパスワードが不一致の為、再度入力してください。';
				return view('member/password',compact('alert','seo'));
			}
			$user = User::find($u_id);
			$current_password = CommonController::Bicrypt($this->PROJECT_NAME,$user->password,false);
			if($current_password !== $request->current_password){
				$alert['class'] = 'warning';
				$alert['text'] = '登録済みパスワードに誤りがある為、再度入力してください。';
				return view('member/password',compact('alert','seo'));
			}
			$user->password = CommonController::Bicrypt($this->PROJECT_NAME,$request->new_password);
			$user->save();
			$alert['class'] = 'text-success';
			$alert['text'] = 'パスワードを変更しました';
		}
		return view('member/password',compact('alert','seo'));
	}
	// お名前変更　
	public function Name(Request $request){
		$kind = Session::get('kind');
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		if(is_null($user)){//指定したu_idのユーザが存在しなかった場合
			return Redirect::to('logout')->send();		
		}
		if($request->isMethod('POST')){//更新の場合(post)
			if($request->name){
				$user->name = $request->name;
			}
			if($request->kana){
				$user->kana = $request->kana;
			}
			if($request->tel != null){
				$user->tel = $request->tel;
			}else{
				$user->tel = '';
			}
			if($request->email){
				$user->email = $request->email;
			}
			if($request->postcode != null){
				$user->postcode = $request->postcode;
			}else{
				$user->postcode = '';
			}
			if($request->address != null){
				$user->address = $request->address;
			}else{
				$user->address = '';
			}
			if($request->d_name != null){
				$user->d_name = $request->d_name;
			}else{
				$user->d_name = '';
			}
			if($request->d_postcode != null){
				$user->d_postcode = $request->d_postcode;
			}else{
				$user->d_postcode = '';
			}
			if($request->d_tel != null){
				$user->d_tel = $request->d_tel;
			}else{
				$user->d_tel = '';
			}
			if($request->d_address != null){
				$user->d_address = $request->d_address;
			}else{
				$user->d_address = '';
			}
			if($request->d_name2 != null){
				$user->d_name2 = $request->d_name2;
			}else{
				$user->d_name2 = '';
			}
			if($request->d_postcode2 != null){
				$user->d_postcode2 = $request->d_postcode2;
			}else{
				$user->d_postcode2 = '';
			}
			if($request->d_tel2 != null){
				$user->d_tel2 = $request->d_tel2;
			}else{
				$user->d_tel2 = '';
			}
			if($request->d_address2 != null){
				$user->d_address2 = $request->d_address2;
			}else{
				$user->d_address2 = '';
			}
			$user->save();
		return Redirect::to('mypage')->send();
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/name', compact('user','seo'));
	}
	// お客様情報一覧　
	public function UserCoupon(Request $request){
		$u_id    = Session::get('u_id');
		$user    = User::find($u_id);
		$today   = date("Y-m-d");
		$c_ids   = explode(',', $user->coupon_stock);
		$stores    = Store::where('store_status','1')->get();
		$coupons = Coupon::WhereIn('id',$c_ids)->orderByRaw('t_coupon.to_date asc')->get();
		foreach ($coupons as $key => $coupon) {
			if($coupon->to_date < $today){
				foreach ($c_ids as $key2 => $c_id) {
					if($c_id == $coupon->id){
						unset($coupons[$key]);
						unset($c_ids[$key2]);
					}
				}
				$user->coupon_stock = implode(',', $c_ids);
			}
			if(substr($user->coupon_stock,0,1) == ','){
				$user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
			}elseif (substr($user->coupon_stock,-1) == ',') {
				$user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
			}
			foreach ($stores as $store) {
				if($coupon->s_id == $store->id){
					$coupon->s_name = $store->name;
					break;
				}
				else{
					$coupon->s_name = '全店舗';
				}
			}
		}
		$user->save();
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/user_coupon',compact('coupons','seo','stores'));
	}
	public function StoreCoupon(Request $request){
		CommonController::AccessLog();
		$uid     = Session::get('u_id'); //u_idがあるのをgetする
		$user    = User::find($uid);//購入者
		if(!$user){return redirect::to('/')->send();}
		$c_ids = explode(',',$user->coupon_stock);
		$o_c_ids = explode(',',$user->coupon_used);
		$all_c_ids = array_merge($c_ids,$o_c_ids);//持ってるクーポンと使ったクーポン一つの配列にする
		$coupons = array();
		$store   = '';
		$date    = date('Y-m-d');
		if($request->s_id == 0){
			$coupons = Coupon::where('s_id',0)->where('from_date','<=',$date)->where('to_date','>=',$date)->get();
		}else{
			$store   = Store::find($request->s_id);//選ばれたお店情報
			$coupons = Coupon::where('s_id',$request->s_id)->where('from_date','<=',$date)->where('to_date','>=',$date)->get();
		}
		foreach($coupons as $key => $coupon){
			if(isset($user)){
				$coupons[$key]->coupon_hash = md5('mdl-'.$user->id.$coupon->id);
				foreach ($all_c_ids as $key2 => $all_c_id) {
					if($coupons[$key]->id == $all_c_id){
						unset($coupons[$key]);
						break;
					}
				}
			}
		}
		
		$cs      = Custom::where('type',5)->get();
		$seo     = array();
		foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
		}
		return view('member/shop_coupon',compact('seo','coupons','store'));
	}
	// マイページ　登録 
	public function Register(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/register',compact('seo'));
	}
	//登録情報の確認　
	public function Registration(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/registration',compact('seo'));
	}
	//登録情報の確認　
	public function Unregisteredpay(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/unregistered_pay',compact('seo'));
	}
	//登録情報の確認　
	public function Unregisteredconfirm(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('member/unregistered_confirm',compact('seo'));
	}
	// 領収書　
	public function Receipt(Request $request){
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('receipt/receipt',compact('seo'));
	}
}/* EOF */
