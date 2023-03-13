<?php
namespace App\Http\Controllers;
use Session, DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
// use App\Models\Nice;// お気に入り機能 
// use App\Models\Prefecture;//地域で選択なら復活
use App\Models\Time;
use App\Models\Category;
use App\Models\Custom;
use App\Models\Coupon;
use App\Models\Open_Close;
use Stripe\Stripe;
use Illuminate\Pagination\LengthAwarePaginator;
class PublicController extends Controller	{
	// トップ
	public function Top(Request $request){
		CommonController::AccessLog();
		$u_id = Session::get('u_id');
		if(isset($u_id)){//ログインしていれば入る
			$user = User::find($u_id);
		}
		$categories = Custom::where('type','0')->get();
		$stores = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','=','1')//storeのidとproductのs_idが同じ商品かつproductのp_statusが1の商品
		->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
		->get();//店舗と商品を紐づけ

		//特集ページに載せるお店　（storeテーブルのfeature_statusが1のもの）
		// $feature_shop = Store::where('feature_status','1')->first();
		// $file_name = '/img_files/store/store'.$feature_shop->id.'.jpg';
		// if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 	$feature_shop->img = 'storage'.$file_name;
		// }else{
		// 	$feature_shop->img = '/img/store00.jpg';
		// }
		foreach($stores as $key => $store){
			$file_name = '/img_files/store/store'.$store->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$store->img = 'storage'.$file_name;
			}else{
				$store->img = '/img/store00.jpg';
			}
		}

		//もしでり推し店をランダムで５件表示する(営業時間内なら条件追加)
		$push_shops = Store::where('store_status','1')->inRandomOrder()->take(5)->get();
		foreach($push_shops as $key => $push_shop){
			$file_name = '/img_files/store/store'.$push_shop->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$push_shop->img = 'storage'.$file_name;
			}else{
				$push_shop->img = '/img/store00.jpg';
			}
		}
		//クーポンをランダムで3件表示する
		$date	= date("Y-m-d");
		$coupons = Coupon::get();
		// $coupons = Coupon::where('coupon_status','=','1')->inRandomOrder()->take(3)->get();
		foreach($coupons as $key => $coupon){
			if(isset($user)){
				$coupons[$key]->coupon_hash = md5('mdl-'.$user->id.$coupon->id);
			}
			$file_name = '/public/coupon_img/'.$coupon->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$coupon->img = 'storage'.$file_name;
			}else{
				$coupon->img = '/img/store00.jpg';
			}
		}
		// join =指定された配列内の要素を文字列として連結する 
		$query = Product::join('t_store', 't_store.id', '=', 't_product.s_id'); 
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');// store_statusとp_statusが1のもの
		if ($request->has('keyword')) { //キーワード検索　
			$query->where('t_product.name', 'like', "%{$request->keyword}%"); // あいまい検索　
		}
		if ($request->has('category')) { //カテゴリー検索　
			$query->where('t_product.name', 'like', "%{$request->category}%"); // あいまい検索　
		}
		$query->orderByRaw('t_product.updated_at desc');// 更新した順番が新しい順に並べ替え
		$query->select('t_store.id as s_id','t_product.id as p_id','t_store.name as s_name','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.catch_copy as s_catch_copy');
		// ↑　テーブルを二つ呼んでいるのでわかりやすいように変換（お店と商品　紐づけ）
		$product_stores = $query->get();// 並び替えと変換を行ったものをget
		$stores = array();
		$lists = array();
		foreach ($product_stores as $product_store) {
			$p_id = $product_store->p_id;
			$s_id = $lists[$p_id]['s_id'] = $product_store->s_id;
			$stores[$s_id]['s_name'] = $lists[$p_id]['s_name'] = $product_store->s_name;
			$stores[$s_id]['s_catch_copy'] = $product_store->s_catch_copy;
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
		
		$theday = new DateTime();
		$datetime =array();// 空の配列
		if($theday->format('H') < 13){//13時過ぎか否か
			$theday->modify('-1 days');
		}
		for($i = 0; $i < 7; $i++){//一週間
			$theday->modify('+1 days');
			$datetime[$i]['value']   = $i;
			$datetime[$i]['display'] = $theday->format('m月d日');
		}

		// storeのnameを取得し、customのtitleと置き換える
		$customs   = Custom::get();
		$stores    = Store::where('store_status','1')->get();
		$custom_ad = Custom::where('type',3)->inRandomOrder()->get();// 広告
		$c_pickup  = Custom::where('type',4)->inRandomOrder()->get();
		// PICKUP
		// dd($c_pickup);
		// $file_name = '/img_files/store/store'.$feature_shop->id.'.jpg';
		// if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 	$feature_shop->img = 'storage'.$file_name;
		// }else{
		// 	$feature_shop->img = '/img/store00.jpg';
		// }
		foreach($c_pickup as $pickup){
			// if($custom->type = 4){
				foreach ($stores as $key => $store) {
					if($pickup->title == $store->id){
						$pickup->name = $store->name;

					}
					$file_name = '/img_files/store/store'.$pickup->id.'.jpg';
					if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
						$pickup->img = 'storage'.$file_name;
					}else{
						$pickup->img = '/img/store00.jpg';
					}
				}
				// $custom->title = Store::where('t_title','t_store.id')->first()->name;
			// }
		}
		// $favos = array();
		// if ($favos->name){
		// }
		// $custom->title = $store->name;
		// $replace = str_replace('$custom->title', '$store->name', $customs);
		$apptdate = Session::get('apptdate');
		if($apptdate == null){
			$apptdate = 0;
		}//初回に0を入れる
		Session::put('apptdate',$apptdate);
		// dd($apptdate);

		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}

		return view('public/top',compact('categories','stores','push_shops','coupons','request','datetime','customs','apptdate','c_pickup','custom_ad','seo'));
	}
	// 検索
	public function Search(Request $request){
		CommonController::AccessLog();
		$apptdate = Session::get('apptdate');
		if($apptdate == null){
            $apptdate = 0;
        }//初回に0を入れる
        Session::put('apptdate',$apptdate);
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
		// dd($w_calendars);
		// $stores = Store::where('store_status','1')->get();
		// $s_ids = array();
		// $i = 0;
		// foreach ($stores as $store) {
		// 	if(isset($calendars[$store->id])){
		// 		if($calendars[$store->id]==1){
		// 			$s_ids[$i] = $store->id;
		// 			++$i;
		// 		}
		// 	}
		// }
		//dd($s_ids);
		//return;

		$categories = Custom::where('t_custom.type','=','0')->get();
		// join =指定された配列内の要素を文字列として連結する 
		$query = Product::join('t_store', 't_store.id', '=', 't_product.s_id');
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');// store_statusとp_statusが1のもの
		$query->whereIn('t_store.id',$s_ids);// 配達有効な店舗のみ
		if ($request->has('keyword')) { //キーワード検索
			$keyword = $request->keyword;
			//$query->where('t_product.name', 'like', "%{$request->keyword}%"); // あいまい検索　(productのnameのみ)
			$query->where(function ($query) use ($keyword) {
				$query->where('t_product.name', 'like', '%' . $keyword . '%')
						->orWhere('t_product.note', 'like', '%' . $keyword . '%')
						->orWhere('t_store.name', 'like', '%' . $keyword . '%');
			});
		}
		$c_id = '';
		if ($request->c_id) { //カテゴリー検索　
			$c_id = $request->c_id;
			$query->where('t_product.c_id',$request->c_id);
		}
		$query->orderByRaw('t_product.updated_at desc');// 更新した順番が新しい順に並べ替え
		$query->select('t_store.id as s_id','t_product.id as p_id','t_store.name as s_name','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.catch_copy as s_catch_copy');
		$product_stores = $query->get();// 並び替えと変換を行ったもの(商品)
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
				if($i){
					$week_schedules[$i]['d']    = $datetime->format('j');
				}else{
					$week_schedules[$i]['d']    = $datetime->format('n/j');
				}
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
					// dd($times);
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
					$mark = "―";
				}
				$week_schedules[$key]['mark'] = $mark;
			}

			$stores[$s_id]['week_schedules']  = $week_schedules;
			// dd($stores[$s_id]['week_schedules']);
		}
		$ids = [];
		if($request->has('price') && $request->price ==1){
			foreach ($lists as $p_id => $list) {
				$ids[] = $list['p_price'];
				$lists[$p_id]['p_id'] = $p_id;
			}
			array_multisort($ids, SORT_ASC, $lists);//安い順
		}elseif ($request->has('price') && $request->price ==2) {
			foreach ($lists as $p_id => $list) {
				$ids[] = $list['p_price'];
				$lists[$p_id]['p_id'] = $p_id;
			}
			array_multisort($ids, SORT_DESC, $lists);//高い順
		}
		$theday = new DateTime();
		$datetime =array();// 空の配列
		if($theday->format('H') < 13){//13時過ぎか否か
			$theday->modify('-1 days');
		}
		for($i = 0; $i < 7; $i++){//一週間
			$theday->modify('+1 days');
			$datetime[$i]['value']   = $i;
			$datetime[$i]['display'] = $theday->format('m月d日');
		}


		// $lists = array();
		// foreach($lists as $key => $list){
		// 	$file_name = '/img_files/product/product'.$list->p_id.'.jpg';
		// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 		$list->img = 'storage'.$file_name;
		// 	}else{
		// 		$list->img = '/img/product00.jpg';
		// 	}
		// 	// var_dump($list->s_name);
		// }
		// $s_list = array();

		// 		// $stores = Store::where('store_status','=','1')->get();	// storeテーブルのstore_statusが1のものをすべてとってきて$storesにいれる。（1＝登録店舗）
		// 		$stores = Store::whereIn('id',$s_list)->get();	// storeテーブルのstore_statusが1のものをすべてとってきて$storesにいれる。（1＝登録店舗）
		// 		foreach($stores as $key => $store){
		// 			$list_products = array();// 空の配列をいれる。
		// 			$file_name = '/img_files/store/store'.$store->id.'.jpg';
		// 			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){	// exists　＝アップロードしようとするファイルが存在するか
		// 			  $store->img = 'storage'.$file_name;
		// 			}else{
		// 			  $store->img = '/img/store00.jpg';
		// 			}	// storeテーブルのidとstorageにある画像が同じidだったらそれを表示する。なかったら'img/store00.jpg'を表示。
		// 			$products = Product::where('s_id',$store->id)->get();// productテーブルのs_idとstoreテーブルのidが同じものをすべてとってきて$productsにいれる。
		// 			$i=0;
		// 			$s_list=array();
		// 			$s_id_past=0;
		// 			foreach($products as $key => $product){
		// 				if($s_id_past != $product->s_id){
		// 					$s_list[$i] = $product->s_id;
		// 					++$i;
		// 					$s_id_past = $product->s_id;
		// 				}
		// 				$file_name = '/img_files/product/product'.$product->id.'.jpg';
		// 				if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 					$product->file_path = 'storage'.$file_name;
		// 				}else{
		// 					$product->file_path = '/img/store00.jpg';
		// 				}
		// 				$s_list[$key] = $product;//  これを加えることでstoreとproductの合体したテーブルができる
		// 			}
		// 			$store->products = $s_list;
					
		// 		}
		
		// $products = Product::where('s_id','t_store'->id)->get();// productテーブルのs_idとstoreテーブルのidが同じものをすべてとってきて$productsにいれる。
		// $i=0;
		// $s_list=array();
		// $s_id_past=0;

		// foreach($products as $key => $product){
		// 	if($s_id_past != $product->s_id){
		// 		$s_list[$i] = $product->s_id;
		// 		++$i;
		// 		$s_id_past = $product->s_id;
		// 	}

		// return;
		// seo
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		// titleにカテゴリー名表示
		$cs = Custom::where('type',0)->get();
		$category_name = array();
		foreach ($cs as $key =>  $c) {
			$category_name[$key+1]  = $c->title;
		}
		return view('public/search',compact('categories','stores','lists','query','request','datetime','apptdate','c_id','seo','category_name'));
	}
	// 店舗(公開)
	public function Store(Request $request){
		CommonController::AccessLog();

		$uid = Session::get('u_id'); //u_idがあるのをgetする
		$u_id = User::find($uid);//購入者
		$store = Store::find($request->s_id);//選ばれたお店情報
		$file_name = '/img_files/store/store'.$store->id.'.jpg';
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$store->img = 'storage'.$file_name;
		}else{
			$store->img = '/img/store00.jpg';
		}
		$store_message = Store::find($request->s_id);
		$file_name = '/img_files/store_message/store_message'.$store_message->id.'.jpg';
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$store_message->img = 'storage'.$file_name;
		}else{
			$store_message->img = '/img/store00.jpg';
		}
		$time = Time::where('t_time.s_id',$request->s_id)->first();//選ばれたお店の時間情報
		$options  = Option::where('s_id',$request->s_id)->get();

		$store_categories = Custom::where('type',10)->where('s_id',$request->s_id)->get();
		$w_products = Product::where('s_id',$request->s_id)->where('p_status',1)->get();
		$products = array();
		foreach($w_products as $key =>$product){
			$file_name = '/img_files/product/product'.$product->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$product->img = 'storage'.$file_name;
			}else{
				$product->img = '/img/product00.jpg';
			}
			$products[$product->sc_id][$product->id] = $product;
		}
		$customs =Custom::get();
		// $push_shops = Store::where('store_status','=','1')->inRandomOrder()->take(3)->get();//もしでり推し店をランダムで５件表示する(営業時間内なら条件追加)
		// foreach($push_shops as $key => $push_shop){
		// 	$file_name = '/img_files/store/store'.$push_shop->id.'.jpg';
		// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 		$push_shop->img = 'storage'.$file_name;
		// 	}else{
		// 		$push_shop->img = '/img/store00.jpg';
		// 	}
		// }
		// dd($store->img);

		// return view('public/shop',compact('store','time','products','options','store_message','push_shops'));

		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		// titleに店舗名表示
		$store_name = $store->name;



		// スケジュール配列(week_schedules)テンプレート作成
		$datetime = new DateTime();
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$mark = array('L','D','●','-');
		$week_schedules =array();
		for($i = 0; $i < 7; $i++){
			$w = (int)$datetime->format('w');
			$week_schedules[$i]['w']    = $week[$w];
			// dd($week_schedules);

			if($i){
				$week_schedules[$i]['d']    = $datetime->format('j');
			}else{
				$week_schedules[$i]['d']    = $datetime->format('n/j');
			}
			$week_schedules[$i]['week'] = $w;
			$week_schedules[$i]['day']  = $datetime->format('Y-m-d');
			$datetime->modify('+1 days');
		}
		// dd($week_schedules);
		//カレンダー(営業日)配列(calendars)を取得
	    $calendars = array();
		$work_calendars = Open_Close::where('s_id',$store->id)->get();
		// dd($work_calendars);
		foreach($work_calendars as $work_calendar){
			if(strpos($work_calendar->day,'w') !== false){
				$number = explode('w',$work_calendar->day)[1];
				$calendars[$number] = $work_calendar;
			}else{//日付指定なら
				$date = $work_calendar->day;
				$calendars[$date] = $work_calendar;
			}
		}
		//dd($calendars);
		// dd($week_schedules[6]);
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
				// dd($times);
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
			//dd($mark);
			$week_schedules[$key]['mark'] = $mark;
		}
		// dd($week_schedules);


		return view('public/store',compact('customs','store','time','products','options','store_message','store_categories','seo','store_name','datetime','week_schedules','work_calendars','calendars','mark'));
	}
	// 時間を更新
	public function UpdateApptdate(Request $request){
		Session::put('apptdate',$request->apptdate);
		return back();
	}
	// お気に入り追加
	public function UpdateFavorite(Request $request){
		if(!is_numeric($request->s_id)){ return; }
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		$favorites = explode(',', $user->favorite);
		$the_s_id = $request->s_id;
		$index = array_search($the_s_id, $favorites);
		if($index === false){//無い場合は
			$user->favorite = $user->favorite.','.$request->s_id;
			$user->favorite = ltrim($user->favorite,',');
		}else{
			array_splice($favorites, $index, 1);
			$user->favorite = implode(',', $favorites);
		}
		$user->save();
		Session::put('favorite',$user->favorite);
		$url = url()->previous();
		return Redirect::to($url)->send();
		//return back();
	}
	// お問合せ
	public function Contact(Request $request) {
		CommonController::AccessLog();
		if($request->isMethod('GET')){
			// $kind = $request->kind;
			// if(is_null($kind)){
			// 	return Redirect::to('logout')->send();
			// }
			// if( $kind !='1' && $kind !='3'){
			// 	return Redirect::to('logout')->send();
			// }
			// $last_url = url()->previous();// 会員登録する前に居たページのURLを取得
			// if(strpos($last_url,'contact')){//strpos:第1引数の文字列に、第2引数の文字列が含まれているかをチェック
			// 	$last_url = '/';
			// }
			// Session::put('last_url',$last_url);// 会員登録する前に居たページのURLを記憶
			// $alert = Session::get('alert');
			// Session::forget('alert');
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('public/contact', compact('seo'));
		}else{//postの場合
		$title = $request->title;
		$email = $request->email;
		$name = $request->name;
		$information = $request->information;
		$date	= date("Y-m-d H:i:s");
		$hash	= md5($email.$date);
		$to		= $email;
		$url	= url("/contact{$hash}");//メールで送った後に開かれるリンク
		$subject	= "【もしもしデリバリー】お問合せ";
		$data	= [	   'url'          => $url,
                       'name'         => $name,
                       'title'       => $title,
                       'email'        => $email,
                       'information' => $information,
                	 ];
		try{
			//メールを飛ばす処理
			Mail::send(['text'=>'emails.contact_thanks'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
				function($send) use ($to,$subject){
					$send->to($to);
					$send->subject($subject);
				}
			);
		}catch(Exception $e){
			$get_message = $e->getMessage();
			Log::error("メール送信エラー address=({$to}) message={$get_message}");
			$alert['class'] = 'warning';
			$alert['text'] = 'メールが送信できませんでした。時間を空けてからお試しください。';
			Session::put('alert',$alert);
			Redirect::to('contact')->send();
			return;
		}
		// $user->hash = $hash;
		// $user->updated_at = date("Y-m-d H:i:s");
		// $user->updated_by = $user->id;
		// $user->save();
		$message = 'お問合せ:'.$email;
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('public/contact_thanks', compact('email','seo'));
	}
	}
}/* EOF */
