<?php
namespace App\Http\Controllers;
use Session, DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;//?
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
use App\Models\Time;
use App\Models\Category;
use App\Models\Custom;
use App\Models\Coupon;
use App\Models\Open_Close;
use Stripe\Stripe;
use Illuminate\Pagination\LengthAwarePaginator;
class PublicController extends Controller	{

	// 準備中
	public function Preparation(Request $request) {

		$cs = Custom::where('type',5)->get();//seo
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('public/preparation',compact('seo'));
	}
	// トップ
	public function Top(Request $request){
		CommonController::AccessLog();//アクセスのログを残す
		$u_id = Session::get('u_id');
		if(isset($u_id)){//ログインしていれば入る
			$user = User::find($u_id);
			$c_ids = explode(',',$user->coupon_stock);//持っているクーポン
			$o_c_ids = explode(',',$user->coupon_used);//使ったクーポン
			$all_c_ids = array_merge($c_ids,$o_c_ids);//持ってるクーポンと使ったクーポン一つの配列にする
		}
		$categories = Custom::where('type','0')->orderByRaw('t_custom.no asc')->get();// カテゴリーをnoが若い順で表示
		$stores    = Store::where('store_status','1')->get();//有効な店舗
		$date	= date("Y-m-d");// 今日の日付
		$coupons = Coupon::where('p_flag','1')->where('from_date','<=',$date)->where('to_date','>=',$date)->orderByRaw('t_coupon.id desc')->get();//公開設定のものかつ期間内(その日も含める)のものを新着順
		$i = 0;//持っているクーポンの数
		$k = 0;//すべてのクーポンの数
		foreach($coupons as $key => $coupon){//すべてのクーポンを回す
			$k = $key+1;
			foreach ($stores as $store) {
				if($coupon->s_id == $store->id){
				$coupon->s_name = $store->name;//店名が入る
				break;
				}
				else{
				$coupon->s_name = '全店舗';//店名がなければこっち
				}
			}
			if(isset($user)){//ログイン済みのユーザーは入る
				$coupons[$key]->coupon_hash = md5('mdl-'.$user->id.$coupon->id);//クーポンをハッシュ化
				foreach ($all_c_ids as $key2 => $all_c_id) {//持っているクーポンと使ったクーポンを1つにしたものを回す
				if($coupons[$key]->id == $all_c_id){//すべてのクーポンと持っているクーポンが一致したら入る
					unset($coupons[$key]);//使用済み・取得済みのクーポンを未定義の状態にする（表示させない）
					$i++;
					break;
					}
				}
			}
		}
		if($i == $k){
		$coupons = '';
		}

		$week = array( "日", "月", "火", "水", "木", "金", "土" );//一週間の曜日の配列
		$theday = new DateTime();
		$datetime =array();// 空の配列
		if($theday->format('H') >= 13){//13時過ぎか否か
			$theday->modify('+1 days');//13時過ぎていたら次の日から表示
		}
		for($i = 0; $i < 7; $i++){//一週間分
			$w = (int)$theday->format('w'); //金曜日だったら'5'が入る
			$datetime[$i]['value']   = $i;
			$datetime[$i]['display'] = $theday->format('m月d日('.$week[$w].')');//例 )11月18日(金)
			$theday->modify('+1 days');
		}
		$customs   = Custom::get();//お知らせ・スライダー用
		$custom_ad = Custom::where('type',3)->where('from_date','<=',$date)->where('to_date','>=',$date)->inRandomOrder()->get();// プロモーション
		$c_pickup  = Custom::where('type',4)->inRandomOrder()->get();//PICKUP　
		// $u_id = Session::get('u_id');
		$user = User::find($u_id);
		if(isset($user->favorite)){//お気に入りのお店
			$user->f_ids = explode(',',$user->favorite);
		}
		foreach($c_pickup as $pickup){//PICKUPを回す
				foreach ($stores as $key => $store) {//有効なお店を回す
					if($pickup->title == $store->id){
							$pickup->name = $store->name;//店名が入る
						$file_name = '/store_image/'.$store->id.'-0.jpg';
						if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
							$pickup->img = 'storage'.$file_name;
						}else{
							$pickup->img = '/img/store00.jpg';//なければNoImage
						}
					}
				}
				if(isset($user->f_ids)){//お気に入りがあれば入る
					foreach ($user->f_ids as $f_id) {//お気に入りを回す
						if($f_id == $pickup->title){//お気に入り店舗とPICKUPの店舗が一致したら入る
							$pickup->favorite = 1;//お気に入り店舗
							break;
						}
						$pickup->favorite = 0;//そうでない店舗
					}
				}
		}
		$apptdate = Session::get('apptdate');//表示可能日の最初の日にちから何日後か
		// dd($apptdate);
		if($apptdate == null){
			Session::put('date_flag',null);
			$apptdate = 0;//設定されていなければ最初の日を設定
			Session::put('apptdate',$apptdate);
		}//初回に0を入れる

		$cs = Custom::where('type',5)->get();//seo
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		$add = Custom::where('type',7)->first();//有料広告の型
		$title_read  = explode(',', $add->title);//　前がタイトル後ろがread文
		$add->title = $title_read[0];//タイトル
		$add->read = $title_read[1];//read文
		$paid_inventorys = Custom::where('type',6)->get();// 有料広告
			foreach($paid_inventorys as $paid_inventory){//有料広告を回す
				foreach ($stores as $key => $store) {//お店を回す
					if($paid_inventory->s_id == $store->id){//有料広告のs_idとお店のidが一致したら入る
				       $paid_inventory->name = $store->name;//店名が入る
					}
				}
			}
		return view('public/top',compact('user','categories','stores','coupons','request','datetime','customs','apptdate','c_pickup','custom_ad','seo','add','paid_inventorys'));
	}
	// 検索
	public function Search(Request $request){
		CommonController::AccessLog();
		$apptdate = Session::get('apptdate');
		if($apptdate == null){
			Session::put('date_flag',null);
			$apptdate = 0;
			Session::put('apptdate',$apptdate);
		}//初回に0を入れる
		$thisday = new DateTime();
		if($thisday->format('H') >= 13){//13時過ぎか否か
			$thisday->modify('+1 days');//13時過ぎていたら次の日から表示
		}
		$thisday->modify('+'.$apptdate.' days');
		$this_day = $thisday->format('Y-m-d');
		$this_weakday = 'w'.date('w',strtotime($this_day));//　例 ）金曜日だったら　w5
		$w_calendars = Open_Close::orderByRaw('s_id asc, day desc')->get();//s_idが若い順かつdayが大きい順
		$calendars = array();
		foreach ($w_calendars as $w_calendar) {//現在時間と比較して、当日午前営業は表示しないように制御する
			$day = $w_calendar->day;
			if(strpos($day,'w') === false){//'w'が含まれなかったら入る
				if($day != $this_day){//今日じゃなかったら
					continue;//抜ける
				}
			}else{//'w'が含まれていたら入る
				if($day != 'w7' && $day != $this_weakday){//1週間予定じゃなくて、今日じゃなかったら
					continue;//抜ける
				}
			}
			if($w_calendar->open){//　1　オープン
				if($apptdate == 0){//当日の場合
					$calendars[$w_calendar->s_id] = 0;//0:クローズ
					$times = explode(',', $w_calendar->time);//午前と午後の時間を分割
					foreach ($times as $time) {
						$half = explode('-', $time);//開始時間と終了時間を分割
						if(isset($half[1])){//終了時間があったら
							$quarter = explode(':', $half[1]);//終了時間の'時'と'分'を分割
							if($quarter[0] >= 17){//終了時間の'時'が17時以降
								$calendars[$w_calendar->s_id] = 1;//1:オープン
							}
						}
					}
				}else{//当日ではない場合
					$calendars[$w_calendar->s_id] = 1;//　オープン　配達有効なお店
				}
			}else{//　0　クローズ
				$calendars[$w_calendar->s_id] = 0;//　クローズ　配達無効なお店
			}
		}
		$s_ids = array();
		$i = 0;
		foreach ($calendars as $s_id => $open) {//配達有効なお店
			if($open){
				$s_ids[$i] = $s_id;
				++$i;
			}
		}

		$categories = Custom::where('t_custom.type','=','0')->orderByRaw('t_custom.no asc')->get();//カテゴリー
		// join =　指定された配列内の要素を文字列として連結する 
		$query = Product::join('t_store', 't_store.id', '=', 't_product.s_id');//storeのidとproductのs_idが同じものを結合
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');// store_statusとp_statusが有効なもの
		$query->whereIn('t_store.id',$s_ids);// 配達有効な店舗のみ
		if ($request->has('keyword')) { //キーワード検索
			$keyword = $request->keyword;
			//$query->where('t_product.name', 'like', "%{$request->keyword}%"); // あいまい検索　(productのnameのみ)
			$query->where(function ($query) use ($keyword) {
				$query->where('t_product.name', 'like', '%' . $keyword . '%')//商品名
						->orWhere('t_product.note', 'like', '%' . $keyword . '%')//説明
						->orWhere('t_product.hashtag', 'like', '%' . $keyword . '%')//ハッシュタグ
						->orWhere('t_store.name', 'like', '%' . $keyword . '%');//店名
			});
		}
		$query->orderByRaw('t_product.updated_at desc');// 更新した順番が新しい順に並べ替え
		$query->select('t_product.c_id as c_id','t_store.id as s_id','t_product.id as p_id','t_store.name as s_name','t_product.name as p_name','t_product.extension as p_extension','t_product.price as p_price','t_product.note as p_note','t_store.catch_copy as s_catch_copy','t_store.schedule_memo as s_schedule_memo');
		$product_stores = $query->get();// 並び替えと変換を行ったもの(商品)
		$c_id = $request->c_id ? $request->c_id: '';
		if ($request->c_id) { //カテゴリー検索
			foreach ($product_stores as $key => $product_store) {//並び替えと変換を行ったもの(商品)を回す
				$c_ids = explode(',',$product_store->c_id);//カテゴリーIDを分割
				$c_id_flag = 0;
				foreach ($c_ids as $key2 => $cat_id) {//カテゴリーIDを回す
					if($cat_id == $request->c_id){
						$c_id_flag = 1;//一致したらflagを1にする
					}
				}
				if(!$c_id_flag){
					unset($product_stores[$key]);//flagがなかったら未定義にする
				}
			}
		}
		$stores = array();
		$lists = array();
		$week = array( "日", "月", "火", "水", "木", "金", "土" );
		$mark = array('L','D','●','-');
		$week_schedules =array();
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		if(isset($user->favorite)){
			$user->f_ids = explode(',',$user->favorite);//お気に入りを分割する
		}
		foreach ($product_stores as $product_store) {
			// dd($product_store);
			$p_id = $product_store->p_id;
			$s_id = $lists[$p_id]['s_id'] = $product_store->s_id;
			$lists[$p_id]['p_id'] = $p_id;
			$stores[$s_id]['s_name'] = $lists[$p_id]['s_name'] = $product_store->s_name;
			$stores[$s_id]['s_catch_copy'] = $product_store->s_catch_copy;
			$stores[$s_id]['s_schedule_memo'] = $product_store->s_schedule_memo;
			for($i=0; $i < 3; $i++){
				$file_name = '/store_image/'.$s_id.'-'.$i.'.jpg';
				$file = '/store_image/'.$s_id.'-';
				if(Storage::exists('public'.$file_name)){	
					$stores[$s_id][$i]['store_img'] = '/storage'.$file_name;
				}else{
					$stores[$s_id][$i]['store_img'] = '/img/store000.jpg';
				}
			}
			$file_name = '/staff_image/'.$s_id.'.jpg';//店長の顔写真
			if(Storage::exists('public'.$file_name)){
				$stores[$s_id]['staff_img'] = '/storage'.$file_name;
			}else{
				$stores[$s_id]['staff_img'] = '/img/store00.jpg';
			}
			$stores[$s_id]['product'][$p_id]['p_name']  = $lists[$p_id]['p_name'] = $product_store->p_name;
			$stores[$s_id]['product'][$p_id]['p_price'] = $lists[$p_id]['p_price'] = $product_store->p_price;
			$stores[$s_id]['product'][$p_id]['p_extension'] = $lists[$p_id]['p_extension'] = $product_store->p_extension;
			$stores[$s_id]['product'][$p_id]['p_note']  = $product_store->p_note;
			$file_name = '/product_image/'.$p_id.$product_store->p_extension;
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = 'storage'.$file_name;
			}else{
				$stores[$s_id]['product'][$p_id]['product_img'] = $lists[$p_id]['img'] = '/img/store00.jpg';
			}
			// スケジュール配列(week_schedules)テンプレート作成
			$datetime = new DateTime();
			for($i = 0; $i < 7; $i++){
				$w = (int)$datetime->format('w');// 　例 ) '5'
				$week_schedules[$i]['w']    = $week[$w];// 　例 )　'金'
				$week_schedules[$i]['d']    = $datetime->format('n/j');//　例 ) '11/18'
				$week_schedules[$i]['week'] = $w; // 　例 ) '5'
				$week_schedules[$i]['day']  = $datetime->format('Y-m-d');//　例 ) '2022-11-18'
				$datetime->modify('+1 days');
			}
			//カレンダー(営業日)配列(calendars)を取得
			$calendars = array();//空の配列
			$work_calendars = Open_Close::where('s_id',$s_id)->get();//そのお店の営業カレンダー取得
			
			foreach($work_calendars as $work_calendar){
				if(strpos($work_calendar->day,'w') !== false){// 'w'がなければ通らない
					$number = explode('w',$work_calendar->day)[1];//'w'の後の数字が入る
					$calendars[$number] = $work_calendar;
				}else{//日付指定ならこっち
					$date = $work_calendar->day;
					$calendars[$date] = $work_calendar;
				}
			}
			// スケジュール配列(week_schedules)に営業状況(mark)をいれる
			foreach ($week_schedules as $key => $week_schedule) {// スケジュール配列を回す
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
				if ($times[0] != "" && $times[1] != ""){//午前と午後にデータがあれば
					$mark = "●";
				}
				elseif ($times[0] != ""){//午前のみ
					$mark = "L";
				}
				elseif ($times[1] != ""){//午後のみ
					$mark = "D";
				}
				else {//それ以外
					$mark = "ー";
				}
				$week_schedules[$key]['mark'] = $mark;
			}

			$stores[$s_id]['week_schedules']  = $week_schedules;	
		}
		$ids = [];
		if($request->has('price') && $request->price ==1){//料金が安い順
			foreach ($lists as $p_id => $list) {
				$ids[] = $list['p_price'];
				$lists[$p_id]['p_id'] = $p_id;
			}
			array_multisort($ids, SORT_ASC, $lists);//安い順
		}elseif ($request->has('price') && $request->price ==2) {//料金が高い順
			foreach ($lists as $p_id => $list) {
				$ids[] = $list['p_price'];
				$lists[$p_id]['p_id'] = $p_id;
			}
			array_multisort($ids, SORT_DESC, $lists);//高い順
		}
		$theday = new DateTime();
		$datetime =array();// 空の配列
		if($theday->format('H') >= 13){//13時過ぎか否か
			$theday->modify('+1 days');//過ぎていたら次の日
		}
		for($i = 0; $i < 7; $i++){//一週間
			$w = (int)$theday->format('w');
			$datetime[$i]['value']   = $i;
			$datetime[$i]['display'] = $theday->format('m月d日('.$week[$w].')');
			$theday->modify('+1 days');
		}
		$cs = Custom::where('type',5)->get();//seo
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
		$coupon = '';
		foreach($stores as $s_id => $store){
			$coupon = Coupon::where('p_flag','1')->where('s_id',$s_id)->where('from_date','<=',$thisday)->where('to_date','>=',$thisday)->first();
			if($coupon != ''){//お店が発行しているクーポンがあったら入る
				$stores[$s_id]['coupon_flag'] = 1;//クーポンが空じゃなかったら
			}else{
				$stores[$s_id]['coupon_flag'] = 0;//クーポンが空だったら
			}
			if(isset($user->f_ids)){//お気に入りがあれば入る
				foreach ($user->f_ids as $f_id) {
					if($f_id == $s_id){//お店のidとfavoriteが一致したら
						$stores[$s_id]['favorite'] = 1;
						break;
					}
					$stores[$s_id]['favorite'] = 0;
				}
			}
		}
		return view('public/search',compact('coupon','categories','stores','lists','query','request','datetime','apptdate','c_id','seo','category_name'));
	}
	// 店舗(公開)
	public function Store(Request $request){
		CommonController::AccessLog();
		$uid = Session::get('u_id'); 
		$user = User::find($uid);//購入者
		$store = Store::find($request->s_id);//選ばれたお店情報
		$file_name = '/store_image/'.$store->id.'-0.jpg';//店舗トップの写真
		// dd($file_name);
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$store->top_img = '/storage'.$file_name;
		}else{
			$store->top_img = '/img/store000.jpg';
		}
		$file_name = '/staff_image/'.$store->id.'.jpg';//店長の顔写真
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$store->staff_img = '/storage'.$file_name;
		}else{
			$store->staff_img = '/img/store00.jpg';
		}
		$store_categories = Custom::where('type',10)->where('s_id',$request->s_id)->orderByRaw('no asc')->get();//メニューカテゴリー

		$w_products = Product::where('s_id',$request->s_id)->where('p_status',1)->get();//選ばれたお店の商品全件取得
		$products = array();
		foreach($w_products as $key =>$product){
			$file_name = '/img_files/product/product'.$product->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$product->img = 'storage'.$file_name;
			}else{
				$product->img = '/img/product00.jpg';
			}
			$s_categories = explode(",", $product->sc_id);
			foreach ($s_categories as $s_category) {
				$products[$s_category][$product->id] = $product;
			}
		}
        $news = Custom::where('type',1)->where('s_id',$request->s_id)->orderByRaw('updated_at desc')->get();//店舗のお知らせ　新着順
		foreach($news as $key => $custom){
			$updated_at = $custom->updated_at;
			$news[$key]['updated_date'] =  date("Y/m/d", strtotime($updated_at));// ●●-●●-●●形式で保存されていたものを●●/●●/●●形式で表示させる為の処理
		}
       $reading_material = Custom::where('type',8)->where('s_id',$request->s_id)->orderByRaw('created_at desc')->orderByRaw('id desc')->get();//店舗のよみもの　新着順
		$cs = Custom::where('type',5)->get();//seo
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
			$week_schedules[$i]['d']    = $datetime->format('n/j');

			$week_schedules[$i]['week'] = $w;
			$week_schedules[$i]['day']  = $datetime->format('Y-m-d');
			$datetime->modify('+1 days');
		}
		//カレンダー(営業日)配列(calendars)を取得
	    $calendars = array();
		$work_calendars = Open_Close::where('s_id',$store->id)->get();
		foreach($work_calendars as $work_calendar){
			if(strpos($work_calendar->day,'w') !== false){// 'w'がなければ通らない
				$number = explode('w',$work_calendar->day)[1];//　'w'のあとの数字
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
				$mark = "ー";
			}
			$week_schedules[$key]['mark'] = $mark;
		}
		$delivery_time = Open_Close::where('s_id',$store->id)->where('day','w7')->first();
		$delivery_time->time = str_replace(',',' / ',$delivery_time->time);

		$date = date('Y-m-d');
		$coupons = Coupon::where('p_flag','1')->where('s_id',$store->id)->where('from_date','<=',$date)->where('to_date','>=',$date)->get();
		if(isset($user)){//ログイン済みのユーザーは入る
			$c_ids = explode(',',$user->coupon_stock);//持っているクーポン
			$o_c_ids = explode(',',$user->coupon_used);//使ったクーポン
			$all_c_ids = array_merge($c_ids,$o_c_ids);//持ってるクーポンと使ったクーポン一つの配列にする
		}
		foreach($coupons as $key => $coupon){//すべてのクーポンを回す
				if(isset($user)){//ログイン済みのユーザーは入る
					foreach ($all_c_ids as $key2 => $all_c_id) {//持っているクーポンと使ったクーポンを回す
					if($coupons[$key]->id == $all_c_id){//すべてのクーポンと持っているクーポンが一致したら入る
						unset($coupons[$key]);//使用済み・取得済みのクーポンを未定義の状態にする（表示させない）
						break;
						}
					}
				}
			}
		if(isset($user->favorite)){
			$user->f_ids = explode(',',$user->favorite);
		}
		if(isset($user->f_ids)){
			foreach ($user->f_ids as $f_id) {
				if($f_id == $store->id){
					$store->favorite = 1;
					break;
				}
				$store->favorite = 0;
			}
		}
		return view('public/store',compact('coupons','news','delivery_time','reading_material','store','products','store_categories','seo','store_name','datetime','week_schedules','work_calendars','calendars','mark'));
	}
// 商品詳細
	public function ProductDetail(Request $request) {
	// dd($request);
	CommonController::AccessLog();
	$uid = Session::get('u_id'); //u_idがあるのをgetする
	$user = User::find($uid);//購入者
	$cs = Custom::where('type',5)->get();//seo
	$seo = array();
	foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
	}
	// $o_title = '';//optionのタイトル
    // $o_text = '';//optionの選択肢のテキスト
	// dd($request->p_id);
	// if($request->p_id != ''){
    	$product = Product::where('id',$request->p_id)->first();//商品一つ
	// }else{
	// 	return;
	// }
	if(isset($product->id) == $request->p_id && $product->p_status == 1) {
		$store = Store::find($product->s_id);
		$file_name = '/staff_image/'.$store->id.'.jpg';//店長の顔写真
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$store->staff_img = '/storage'.$file_name;
		}else{
			$store->staff_img = '/img/store00.jpg';
		}
	}else{
		return;
	}
	$o_ids = explode(',', $product->o_ids);//オプションのidを分割
    $options = Option::where('s_id',$product->s_id)->whereIn('o_id',$o_ids)->orderBy('o_id', 'asc')->get();//商品のオプション
	$w_o_id = 0;
	foreach($options as $key => $option){
		$option->title = "";//二回目以降の為に空にする
		if($w_o_id != $option->o_id){
				$option->title = $option->o_name;
				$w_o_id = $option->o_id;
			}
		if($option->price == 0){//オプションで金額の変更がない場合
			$option->price = "";
		}else{//ある場合
			$option->price = $option->price."円";
		}
	}	
		return view('public/product_detail',compact('seo','product','options','store'));
	}
	// 時間を更新
	public function UpdateApptdate(Request $request){
			Session::put('apptdate',$request->apptdate);
			Session::put('date_flag',$request->apptdate);
		return back();
	}
	// お気に入り追加
	public function UpdateFavorite(Request $request){
		if(!is_numeric($request->s_id)){ return; }
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		$favorites = explode(',', $user->favorite);//お気に入り追加済みのお店を分割
		$the_s_id = $request->s_id;//今から追加するお店
		$index = array_search($the_s_id, $favorites);//二つの配列の値を検索する
		if($index === false){//値が一致しなかったら追加する
			$user->favorite = $user->favorite.','.$request->s_id;
			$user->favorite = ltrim($user->favorite,',');
		}else{//削除
			array_splice($favorites, $index, 1);//（削除する要素を含んだ対象の配列,最初の要素から何個目の要素から削除するか,削除する要素の数）
			$user->favorite = implode(',', $favorites);
		}
		$user->save();
		Session::put('favorite',$user->favorite);
		$url = url()->previous();//直前のページを取得
		return Redirect::to($url)->send();
	}
	// お問合せ
	public function Contact(Request $request) {
		CommonController::AccessLog();
		if($request->isMethod('GET')){
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('public/contact', compact('seo'));
		}else{//postの場合
			$title = $request->title;
			$email = $request->email;
			$tel = $request->tel;
			$name = $request->name;
			$information = $request->information;
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);
			$url	= url("/contact{$hash}");//メールで送った後に開かれるリンク
			$data	= [	   'url'          => $url,
			'name'         => $name,
			'email'        => $email,
			'tel'        => $tel,
			'title'       => $title,
			'information' => $information,
			];
		// お問い合わせ主
		$to		= $email;//もしもし
		$subject	= "【もしもしデリバリー】お問い合わせ受付完了メール";
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
		// admin
		$to		= 'mosimosi.delivery@gmail.com';// mosimosi.delivery@gmail.com
		$subject	= "【もしもしデリバリー】お問い合わせがありました";
		try{
			//メールを飛ばす処理
			Mail::send(['text'=>'emails.admin_contact'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
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
		// $message = 'お問い合わせ:'.$email;
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('public/contact_thanks', compact('email','seo','to'));
		}
	}
}/* EOF */

