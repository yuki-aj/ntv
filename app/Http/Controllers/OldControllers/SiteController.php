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
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
// use App\Models\Nice;// お気に入り機能 
// use App\Models\Prefecture;//地域で選択なら復活
use App\Models\Time;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\Category;
use App\Models\Custom;
use App\Models\Coupon;
use App\Models\Open_Close;
use Stripe\Stripe;
use Illuminate\Pagination\LengthAwarePaginator;
class SiteController extends Controller	{

// top
	public function Top(Request $request){
		CommonController::AccessLog();
		// $categories = Category::get();
		$categories = Custom::where('t_custom.type','=','0')->get();
		// $locale = Session::get('locale');
		// $categories  = Category::getList($locale);
		// $stores = Product::join('t_store','t_store.id','=','t_product.s_id')->where('store_status','=','1')->get();

		$stores = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','=','1')//storeのidとproductのs_idが同じ商品かつproductのp_statusが1の商品
		->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
		->get();//店舗と商品を紐づけ

		//特集ページに載せるお店　（storeテーブルのfeature_statusが1のもの）
		$feature_shop = Store::where('feature_status','=','1')->first();
		$file_name = '/img_files/store/store'.$feature_shop->id.'.jpg';
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$feature_shop->img = 'storage'.$file_name;
		}else{
			$feature_shop->img = '/img/store00.jpg';
		}
		foreach($stores as $key => $store){
			$file_name = '/img_files/store/store'.$store->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$store->img = 'storage'.$file_name;
			}else{
				$store->img = '/img/store00.jpg';
			}
		}

		//もしでり推し店をランダムで５件表示する(営業時間内なら条件追加)
		$push_shops = Store::where('store_status','=','1')->inRandomOrder()->take(5)->get();
		foreach($push_shops as $key => $push_shop){
			$file_name = '/img_files/store/store'.$push_shop->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$push_shop->img = 'storage'.$file_name;
			}else{
				$push_shop->img = '/img/store00.jpg';
			}
		}
		//クーポンをランダムで3件表示する
		$coupons = Coupon::get();
		// $coupons = Coupon::where('coupon_status','=','1')->inRandomOrder()->take(3)->get();
		foreach($coupons as $key => $coupon){
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
		
		$start = 0; //開始日（当日）
		$end = 7; //終了日（7日後）
		$datetime =array();// 空の配列
		for($i = $start; $i <= $end; $i++){
			$datetime[$i] = new DateTime('+'.$i.' day');
			$datetime[$i] = $datetime[$i]->format('m月d日');
		}
		// storeのnameを取得し、customのtitleと置き換える
		$customs = Custom::get();
		$stores = Store::where('store_status','=','1')->get();
		$file_name = '/img_files/store/store'.$feature_shop->id.'.jpg';
		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
			$feature_shop->img = 'storage'.$file_name;
		}else{
			$feature_shop->img = '/img/store00.jpg';
		}
		foreach($customs as $custom){
			// if($custom->type = 4){
				foreach ($stores as $key => $store) {
					if($custom->title == $store->id){
						$custom->title = $store->name;

					}
					$file_name = '/img_files/store/store'.$custom->id.'.jpg';
					if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
						$custom->img = 'storage'.$file_name;
					}else{
						$custom->img = '/img/store00.jpg';
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


		return view('public/top',compact('categories','stores','push_shops','coupons','feature_shop','request','datetime','customs'));
	}


	// 検索
	public function Search(Request $request){
		CommonController::AccessLog();
		$categories = Category::get();//カテゴリーをget
		// $locale = Session::get('locale');
		// $categories  = Category::getList($locale);

		// join =指定された配列内の要素を文字列として連結する 
		$query = Product::join('t_store', 't_store.id', '=', 't_product.s_id');
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');// store_statusとp_statusが1のもの
		if ($request->has('keyword')) { //キーワード検索　
			$query->where('t_product.name', 'like', "%{$request->keyword}%"); // あいまい検索　(productのnameのみ)
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

		$start = 0; //開始日（当日）
		$end = 7; //終了日（7日後）
		$datetime =array();// 空の配列
		for($i = $start; $i <= $end; $i++){
			$datetime[$i] = new DateTime('+'.$i.' day');
			$datetime[$i] = $datetime[$i]->format('m月d日');
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
		return view('public/search',compact('categories','stores','lists','query','request','datetime'));
	}

	// 店舗
	public function Shop(Request $request){
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
		$products = Product::where('s_id',$request->s_id)->where('p_status',1)->get();
		foreach($products as $key =>$product){
			$file_name = '/img_files/product/product'.$product->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$product->img = 'storage'.$file_name;
			}else{
				$product->img = '/img/product00.jpg';
			}
		}

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
		return view('public/shop',compact('store','time','products','options','store_message'));
	}
	
	// 商品詳細　今は使ってない
	public function Product(Request $request){
		return view('public/product');
	}
	// お買い物カゴ
	public function Cart(Request $request){
		return view('public/cart');
	}
	// ご注文手続き
	public function OnePay(Request $request){//未登録ユーザー購入の場合
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
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
		$user = "";
		$user = User::where('id',Session::get('u_id'))->first();
		// dd($user);
		$carts = Session::get('pay_carts');
		// dd($carts);
		$stores    = Store::where('t_store.store_status','1')->get();//statusが1のお店（有効）
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
		if(is_null($carts) || $carts == []){
		  return redirect('/');
		}
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
		$all_summary = 0;//総合計金額（初期値0）
		$p_border    = 1500;//送料価格基準
		$p_upper     = 3000;//送料価格基準
		$d_fee300    = 300;//送料金額（1500円以下）
		$d_fee600    = 600;//送料金額（3000円以上）
		$d_fee       = 0.2;//送料金額（1500~3000円の間）
		$pay_id    = '';
		foreach ($new_carts as $stripe_id => $pay_cart) {//new_cartsを店舗ごとに回す($s_id = 商品id)
		   if($stripe_id == $request->stripe_id){
			$pay_id = $request->stripe_id;
			$summary = 0;//店舗ごとの合計金額は（初期値0）
			foreach ($pay_cart as $product_id => $product) {//店舗の商品($s_product)ごとに回す
			  $summary += $product['total'];//商品の数分足していく(商品×個数)
			}
			//店舗合計の送料計算
			$postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
			if($summary < $p_border){//1500円以下
			  $summary = $summary + $postage[0]->price;
			}elseif($summary > $p_upper){//3000円以上
			  $summary = $summary + $postage[1]->price;
			}else{
			  $summary = $summary + $summary*$postage[2]->price;//1000~3000円の時
			}
			$all_summary += $summary;//全店舗総額

		   }
		}
		// dd($new_carts);
		Session::put('new_carts',$new_carts);//店舗ごとにstripe_id並んだカート内の商品
		Session::put('pay_carts',$carts);//全部が同列に並んだカート内の商品
		return view('public/one_pay',compact('stripe_pk','all_summary','stores','new_carts','carts','user','week','pay_id'));
		// return view('public/pay');
	}
	// ご注文手続き
	public function Pay(Request $request){
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
		$user = "";
		$user = User::where('id',Session::get('u_id'))->first();
		// dd($user);
		$carts = Session::get('pay_carts');
		// dd($carts);
		$stores    = Store::where('t_store.store_status','1')->get();//statusが1のお店（有効）
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
		if(is_null($carts) || $carts == []){
		  return redirect('/');
		}
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
		$all_summary = 0;//総合計金額（初期値0）
		$p_border    = 1500;//送料価格基準
		$p_upper     = 3000;//送料価格基準
		$d_fee300    = 300;//送料金額（1500円以下）
		$d_fee600    = 600;//送料金額（3000円以上）
		$d_fee       = 0.2;//送料金額（1500~3000円の間）
		$pay_id    = '';
		foreach ($new_carts as $stripe_id => $pay_cart) {//new_cartsを店舗ごとに回す($s_id = 商品id)
		   if($stripe_id == $request->stripe_id){
			$pay_id = $request->stripe_id;
			$summary = 0;//店舗ごとの合計金額は（初期値0）
			foreach ($pay_cart as $product_id => $product) {//店舗の商品($s_product)ごとに回す
			  $summary += $product['total'];//商品の数分足していく(商品×個数)
			}
			//店舗合計の送料計算
			$postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
			if($summary < $p_border){//1500円以下
			  $summary = $summary + $postage[0]->price;
			}elseif($summary > $p_upper){//3000円以上
			  $summary = $summary + $postage[1]->price;
			}else{
			  $summary = $summary + $summary*$postage[2]->price;//1000~3000円の時
			}
			$all_summary += $summary;//全店舗総額

		   }
		}
		// dd($new_carts);
		Session::put('new_carts',$new_carts);//店舗ごとにstripe_id並んだカート内の商品
		Session::put('pay_carts',$carts);//全部が同列に並んだカート内の商品
		return view('public/pay',compact('all_summary','stores','new_carts','carts','user','week','pay_id'));
		// return view('public/pay');
	}
	// マイページ　登録 
	public function Register(Request $request){
		return view('public/register');
	}
	// マイページ　
	public function Mypage(Request $request){
		return view('public/mypage');
	}
	//登録情報の確認　
	public function Registration(Request $request){
		return view('public/registration');
	}
	//登録情報の確認　
	public function Unregisteredpay(Request $request){
		return view('public/unregistered_pay');
	}
	//登録情報の確認　
	public function Unregisteredconfirm(Request $request){
		return view('public/unregistered_confirm');
	}
	// お気に入り　
	public function Favorite(Request $request){
		return view('public/favorite');
	}
	// 入力検索　今は使ってない
	public function Searchproduct(Request $request){
		return view('public/searchproduct');
	}
	// メールアドレス変更　
	public function Mail(Request $request){
		return view('public/mail');
	}
	// 住所変更　
	public function Address(Request $request){
		return view('public/address');
	}
	// 電話番号変更　
	public function Tel(Request $request){
		return view('public/tel');
	}
	// お支払い変更　
	public function Payments(Request $request){
		return view('public/payments');
	}
	// パスワード変更　
	public function Password(Request $request){
		return view('public/password');
	}
	// ユーザーアイコン変更　
	public function Usericon(Request $request){
		return view('public/usericon');
	}
	// お名前変更　
	public function Name(Request $request){
		return view('public/name');
	}
	// フリガナ変更　
	public function Subname(Request $request){
		return view('public/subname');
	}
	// 注文状況　
	public function Orderstatus(Request $request){
		return view('public/orderstatus');
	}
	// 注文履歴一覧　
	public function Orderhistory(Request $request){
		return view('public/orderhistory');
	}
	// 注文履歴詳細　
	public function Orderhistorydetails(Request $request){
		return view('public/orderhistorydetails');
	}
	// お客様情報一覧　
	public function Info(Request $request){
		return view('public/info');
	}
	// ログイン　
	public function Login(Request $request){
		return view('public/login');
	}
	// 店舗　管理ページ　
	public function Shopmanagement(Request $request){

		$calendar = Open_Close::get();
		$store = Store::find($request->s_id);

		if($request->isMethod('GET')){
			$store = Store::find($request->s_id);
		}else{//更新の場合(post)
			$store = Store::find($request->s_id);
			// $category = Category::find($request->c_id);
			if(is_null($store)){
				$store = new Store();
				$store->init();//初期化
				//$product->updated_by = $request->updated_by;
				$store->created_at = date('Y-m-d H:i:s');
			}
			//入力されたパラメータでproductオブジェクトを上書き
			//$product->p_status = $request->p_status;
			if($request->name){
				$store->name = $request->name;
			}
			if($request->catch_copy){
				$store->catch_copy = $request->catch_copy;
			}
			if($request->note){
				$store->note = $request->note;
			}
			if($request->postcode){
				$store->postcode = $request->postcode;
			}
			if($request->address){
				$store->address = $request->address;
			}
			if($request->access){
				$store->access = $request->access;
			}
			if($request->tel){
				$store->tel = $request->tel;
			}
			if($request->email){
				$store->email = $request->email;
			}
			if($request->url){
				$store->url = $request->url;
			}
			if($request->note){
				$store->line = $request->line;
			}
			if($request->instagram){
				$store->instagram = $request->instagram;
			}
			if($request->twitter){
				$store->twitter = $request->twitter;
			}
			if($request->facebook){
				$store->facebook = $request->facebook;
			}
			if($request->parking){
				$store->parking = $request->parking;
			}
		
			$store->updated_at = date('Y-m-d H:i:s');
			$store->save();
			// 店舗トップ写真
			if($request->store_back_image){
				$dir = 'store_back_image';
				$file_name = $store->id.".".$request->store_back_image->extension();
				$request->file('store_back_image')->storeAs('public/' . $dir, $file_name);
			}
			// 店長顔写真
			if($request->store_manager){
				$dir = 'store_manager';
				$file_name = $store->id.".".$request->store_manager->extension();
				$request->file('store_manager')->storeAs('public/' . $dir, $file_name);
			}
			// 店舗イメージ写真　　スライダー
			if(isset($request->store_image)){
				$files = $request->file('store_image');
				foreach($files as $key => $file){
					$dir = 'public/store_image';// 保管する場所
					$file_name = $store->id."-"."$key".".".$file->extension();
					$file->storeAS($dir,$file_name);
				}
				// for($i=0; $i < 3; $i++){
				// 	if(isset($request->store_image[$i])){
				// 		$dir = 'store_image';
				// 		$file_name = $store->id."."."$i".".".$request->store_image[$i]->extension();
				// 		$request->file('store_image['.$i.']')->storeAs('public/' . $dir, $file_name);
				// 	}
				// }
			}
			// スタッフからのメッセージ欄の写真
			if($request->staff_image){
				$dir = 'staff_image';
				$file_name = $store->id.".".$request->staff_image->extension();
				$request->file('staff_image')->storeAs('public/' . $dir, $file_name);
			}
		
			// if($request->product_image){
			// 	$dir = 'product_image';
			// 	$file_name = $product->id.".".$request->product_image->extension();
			// 	$request->file('product_image')->storeAs('public/' . $dir, $file_name);
			// }
			return Redirect::to('moshidelimanagement')->send();
		// $products = Product::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		// foreach($products as $key => $product){
		// 	$file_name = '/img_files/product/product'.$product->id.'.jpg';
		// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 		$product->img = 'storage'.$file_name;
		// 	}else{
		// 		$product->img = '/img/product00.jpg';
		// 	}
		// }
		// if($request->isMethod('GET')){//GETのケース
		// 	if(!$request->p_id){//新規の場合
		// 		if(Session::get('kind')==3){//adminの場合
		// 			$u_id = $request->u_id;
		// 		}
		// 		$store = new Store();
		// 		$store->init();
		// 		// $product->u_id = $u_id;
		// 	}else{//更新の場合
		// 		$store = Product::find($request->p_id);
		// 	}
		// 	$store->name  = $request->name;
		// 	$store->name  = $request->catch_copy;
		// 	$store->price  = $request->price;
		// 	$store->note  = $request->note;
		}
		return view('public/store_management',compact('store','calendar'));
	}
	// もしもし　店舗管理ページ　
	public function Moshidelimanagement(Request $request){
		$stores = Store::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		foreach($stores as $key => $store){
			$file_name = '/img_files/store/store'.$store->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$store->img = 'storage'.$file_name;
			}else{
				$store->img = '/img/store00.jpg';
			}
		}
		// // 登録店舗削除
		// if(isset($request->s_id)) {
		// 	$deletestore = store::where('t_store.id',$request->s_id)->first();
		// 	$deletestore->delete();
		// 	return redirect('moshidelimanagement');
		// }
		return view('public/moshidelimanagement',compact('stores'));
	}
		// もしもし　管理ページ　
		public function Management(Request $request){
			return view('public/management');
		}
		
	// 商品リスト　
	public function Productlist(Request $request){
		
		$products = Product::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		foreach($products as  $product){
			$file_name = '/img_files/product/product'.$product->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$product->img = 'storage'.$file_name;
			}else{
				$product->img = '/img/product00.jpg';
			}
		}
		// $category = Category::orederBy('created_at','desc') -> get();
		// $options = Option::orderBy('created_at', 'desc') ->get();
		$options = Option::orderBy('created_at', 'desc') ->paginate(3);

	if($request->isMethod('GET')){//GETのケース
		if(!$request->p_id){//新規の場合
			if(Session::get('kind')==0){//adminの場合
				// $u_id = $request->u_id;
			}
			$product = new Product();
			$product->init();
			$product->c_id = $request->c_id;
			$product->name  = $request->name;
			$product->price  = $request->price;
			$product->note  = $request->note;
		}else{//更新の場合
			$product = Product::find($request->p_id);
		}
	}

	// if($request->isMethod('GET')){//GETのケース
	// 	if(!$request->p_id){//新規の場合
	// 		// if(Session::get('kind')==3){//adminの場合
	// 		// 	$u_id = $request->u_id;
	// 		// }
	// 		$product = new Product();
	// 		$product->init();
	// 		$product->p_status = $request->p_status;
	// 		$product->o_id = $request->o_id;
	// 		$product->updated_by = $request->updated_by;
	// 		$product->s_id = $request->s_id;
	// 		$product->name = $request->name;
	// 		$product->price = $request->price;
	// 		$product->note = $request->note;
	// 		$product->created_at   = date('Y-m-d H:i:s');
	// 		$product->updated_at    = date('Y-m-d H:i:s');
	// 		$product->save();
	// 		// $product->u_id = $u_id;
	// 	}
	// 	else{//更新の場合
	// 		$product = Product::find($request->p_id);
	// 		$product->name  = $request->name;
	// 		$product->price  = $request->price;
	// 		$product->note  = $request->note;
	// 	}
	// }
		// return view('public/product_list',compact('product','products','options'));
		return view('public/product_list',compact('products','options','product'));

	}
	public function Productdelete(Request $request) {// 商品削除
		Product::where('id',$request->p_id)->delete();
		return Redirect::to('product_list')->send();
	}
	public function Storedelete(Request $request) {// 店舗削除
		Store::where('id',$request->s_id)->delete();
		return Redirect::to('moshidelimanagement')->send();
	}
	// 商品 編集　
	public function Shopedit(Request $request){

		return view('public/shop_edit');
	}
	// オプション 編集　
	public function Option(Request $request){
		// $u_id = 4;
		// $options = 0;
		$option = Product::find($request->p_id);
		$option = new Option();
        $option->o_name = $request->o_name;
        $option->name  = $request->name;
        $option->note  = $request->note;
        $option->price  = $request->price;
        $option->created_at   = date('Y-m-d H:i:s');
        $option->updated_at    = date('Y-m-d H:i:s');
        // $option->id = $request->id;
        $option->save();// データーベースに保存
		return view('public/option', compact('u_id','option'));
	}
	// 店舗スライダー　編集　
	public function Shopedit2(Request $request){
		return view('public/shop_edit2');
	}
	// スタッフからのメッセージ　編集　
	public function Shopedit3(Request $request){
		return view('public/shop_edit3');
	}
	// 店舗クーポン　編集　
	public function Shopedit4(Request $request){
		return view('public/shop_edit4');
	}
	// ヘッダー　編集　
	public function Edit0(Request $request){
		return view('public/edit0');
	}
	// カテゴリー　編集　
	public function Edit(Request $request){
		return view('public/edit');
	}

	// お知らせ　編集　
	public function Edit1(Request $request){
		return view('public/edit1');
	}
	// スライダーON/OFF　編集　
	public function Edit2(Request $request){
		return view('public/edit2');
	}
	// スライダー　画像　編集　
	public function Edit2_1(Request $request){
		return view('public/edit2_1');
	}
	// フリースペース　編集　
	public function Edit3(Request $request){
		return view('public/edit3');
	}
	// 広告　編集　
	public function Edit4(Request $request){
		return view('public/edit4');
	}
	// もしデリ推し店　編集　
	public function Edit5(Request $request){
		return view('public/edit5');
	}
	// 有料広告枠　編集　
	public function Edit6(Request $request){
		return view('public/edit6');
	}
	// 新メニュー　編集　
	public function Edit7(Request $request){
		return view('public/edit7');
	}
	// クーポン　編集　
	public function Edit8(Request $request){
		return view('public/edit8');
	}
	// どんなものが食べたい気分　編集　
	public function Edit9(Request $request){
		return view('public/edit9');
	}
	// タイトル　編集　
	public function Edit10(Request $request){
		return view('public/edit10');
	}
	// 店舗追加　
	public function Moshideliedit(Request $request){
		return view('public/moshideli_edit');
	}

	// もしもし管理画面　商品編集
	public function Productedit(Request $request){
		$categorys = Category::get();
		if($request->isMethod('GET')){
			$product = Product::find($request->p_id);
		}else{//更新の場合(post)
			$product = Product::find($request->p_id);
			// $category = Category::find($request->c_id);
			if(is_null($product)){
				$product = new Product();
				$product->init();//初期化
				//$product->updated_by = $request->updated_by;
				$product->created_at = date('Y-m-d H:i:s');
			}
			//入力されたパラメータでproductオブジェクトを上書き

			//$product->p_status = $request->p_status;
			if($request->s_id){
				$product->s_id = $request->s_id;
			}
			if($request->c_id){
				$product->c_id = $request->c_id;
			}
			if($request->o_id){
				$product->o_id = $request->o_id;
			}
			if($request->name){
				$product->name = $request->name;
			}
			if($request->price){
				$product->price = $request->price;
			}
			if($request->note){
				$product->note = $request->note;
			}
			$product->updated_at = date('Y-m-d H:i:s');
			$product->save();
			if($request->product_image){
				$dir = 'product_image';
				$file_name = $product->id.".".$request->product_image->extension();
				$request->file('product_image')->storeAs('public/' . $dir, $file_name);
			}
			return Redirect::to('product_list')->send();
		}
		return view('public/product_edit', compact('product','categorys'));
	}
	
	// ブログ
	public function Blog(Request $request){
		return view('public/blog');
	}
	// ブログリスト
	public function Bloglist(Request $request){
		return view('public/blog_list');
	}
	// フリースペース
	public function Freespace(Request $request){
		return view('public/freespace');
	}
	// フリースペースリスト
	public function Freespacelist(Request $request){
		return view('public/freespace_list');
	}
	// 決済　確認
	public function Confirm(Request $request){
		return view('public/confirm');
	}
	//　注文確定
	public function Ordercompletion(Request $request){
		return view('public/ordercompletion');
	}
	//　受注一覧リスト
	public function Orderlist(Request $request){
		return view('public/orderlist');
	}
	//　注文詳細
	public function Orderdetails(Request $request){
		return view('public/orderdetails');
	}
	//　注文詳細編集
	public function Orderedit(Request $request){
		return view('public/orderedit');
	}
	//　カレンダー機能
	public function Calendar(Request $request){
		$store = Store::find($request->s_id);
		if($request->isMethod('GET')){
			$calendar = Open_Close::find($request->s_id);
		}else{//更新の場合(post)
			// dd($request);
			$calendar = Open_Close::find($request->s_id);
			// $category = Category::find($request->c_id);
			if(is_null($calendar)){
				$calendar = new Open_Close();
				$calendar->init();//初期化
				$calendar->created_at = date('Y-m-d H:i:s');
			}
			//入力されたパラメータでproductオブジェクトを上書き
			if($request->open){
				$calendar->open = $request->open;
			}
			if($request->date){
				$calendar->day = $request->date;
			}
			if($request->day){
				$calendar->day = $request->day;
			}
			if($request->time1){
				// $calendar->time = $request->time1;
				$calendar->time = implode('-',$request->time1);
			}
			if($request->time2){
				// $calendar->time = $request->time2;
				$calendar->time = implode('-',$request->time2);
			}
			// if($request->time1 && $request->time2){
			// 	$calendar->time = implode(',',$request->$request->time);
			// }


			$calendar->updated_at = date('Y-m-d H:i:s');
			$calendar->save();
		
			// if($request->product_image){
			// 	$dir = 'product_image';
			// 	$file_name = $product->id.".".$request->product_image->extension();
			// 	$request->file('product_image')->calendarAs('public/' . $dir, $file_name);
			// }
			// return Redirect::to('store_management')->send();
		}
			return view('public/calendar', compact('calendar'));
			// return view('public/calendar');
		}


	

	/** SearchUser (private) **/
	// private function SearchUser($request,$kind,$url){
	// 	$grade_lists = array();//条件に指定されたgrade
	// 	$locale = Session::get('locale');
	// 	date_default_timezone_set('Asia/Tokyo');
	// 	$compare_date  = (new DateTime())->modify('-1 year')->format('Y-m-d H:i:s');//1年前
	// 	$query = Product::query()->join('t_user', 't_user.id', '=', 't_product.u_id')->where('kind', $kind); //1:buyer 2:Supplier
	// 	$query->where('t_product.updated_at','>=',$compare_date)->where('t_user.user_status','!=','0');
	// 	if ($request->isMethod('POST') || $request->isMethod('GET')) {//POST(検索条件あり)のケース
	// 		if ($request->prefecture) {
	// 			$query->where('t_product.origin', $request->prefecture);
	// 		}
	// 		if ($request->type) {
	// 			$query->where('t_product.type', $request->type);
	// 		}
	// 		if ($request->has('note')) {
	// 			$note = 't_product.note_'.str_replace('-', '', $locale);
	// 		 	$query->where($note, 'like', "%{$request->note}%");
	// 		}
	// 		if (isset($request->grade)) {
	// 			$grade_lists = $request->grade;
	// 			$query->where(function ($query) use ($grade_lists) {
	// 				foreach ($grade_lists as $key => $grade_list) {
	// 					$query->orWhereRaw('FIND_IN_SET(?, t_product.grade)', $grade_list);
	// 				}
	// 			});
	// 		}
	// 		$division    = $request->division;
	// 		$subdivision = $request->subdivision;
	// 		$category    = $division * 100 + $subdivision;
	// 		if ($division && $subdivision) { //division,subdivision共に指定
	// 			$query->where('t_product.category', $category);
	// 		}else if($division){ //divisionのみ
	// 			$lower = $division * 100;
	// 			$upper = ($division + 1) * 100;
	// 			$query->where('t_product.category', '>', $lower)->where('t_product.category', '<', $upper);
	// 		}else if($subdivision) { //subdivisionのみ
	// 			//今のところ存在しない
	// 		}
	// 	}else if($request->has('division')){ //GET(検索条件あり)のケース
	// 		$division = $request->division;
	// 		$lower    = $division * 100;
	// 		$upper    = ($division + 1) * 100;
	// 		$query->where('t_product.category', '>', $lower)->where('t_product.category', '<', $upper);
	// 	}
	// 	$query->orderByRaw('t_product.updated_at desc');
	// 	$note_as = 't_product.note_'.str_replace('-', '', $locale).' as note';
	// 	$query->select('t_product.type as type_name', 't_product.name as pname', 't_user.company as company', 't_user.id as u_id', 't_product.id as p_id', 't_product.category as pcategory', $note_as);
	// 	$lists = $query->get();
	// 	$result['prefectures'] = Prefecture::getList($locale);
	// 	$result['categories']  = Category::getList($locale);
	// 	$result['types']       = $types = Type::getList($locale);
	// 	$grades      = Grade::getList($locale);
	// 	$u_ids = array();//sort処理
	// 	$count = $list_count = count($lists);
	// 	$result['counts'] = $count;
	// 	foreach ($lists as $key1 => $list) {//edit lists
	// 		$lists[$key1]->type_name = $types[$list->type_name];
	// 		$category = Category::ReturnCategoryNames($list->pcategory,$locale);
	// 		$lists[$key1]->pdivision    = $category['division'];
	// 		$lists[$key1]->psubdivision = $category['subdivision'];
	// 		$file_name = '/img_files/product/product1_'.$list->p_id.'.jpg';
	// 		if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
	// 			$lists[$key1]->img = 'storage'.$file_name;
	// 		}else{
	// 			$lists[$key1]->img = '/img/product0.jpg';
	// 		}
	// 		if (in_array($list->u_id, $u_ids)) {
	// 			unset($lists[$key1]);
	// 			$lists[$count] = $list;
	// 			++$count;
	// 		}else{
	// 			array_push($u_ids,$list->u_id);
	// 		}
	// 	}
	// 	$lists = new LengthAwarePaginator( $lists->forPage($request->page, $this->COUNT_PAR_PAGE), $list_count, $this->COUNT_PAR_PAGE, $request->page, array('path'=>$url));
	// 	$result['lists']  = $lists;
	// 	foreach ($grade_lists as $key => $g_id) {//edit grades
	// 		$grades[$g_id]['checked'] = 'checked';
	// 	}
	// 	$result['grades'] = $grades;
	// 	return $result;
	// }
	// /** SearchSupplier (Both) **/
	// public function SearchSupplier(Request $request){
	// 	CommonController::AccessLog();
	// 	$kind = 2;
	// 	$result = PublicController::SearchUser($request,$kind,'search_supplier');
	// 	$lists        = $result['lists'];
	// 	$prefectures  = $result['prefectures'];
	// 	$categories   = $result['categories'];
	// 	$types        = $result['types'];
	// 	$grades       = $result['grades'];
	// 	$counts       = $result['counts'];
	// 	return view('public/search_user', compact('counts','lists', 'prefectures', 'categories', 'types', 'grades', 'request','kind'));
	// }
	// /** SearchBuyer (Both) **/
	// public function SearchBuyer(Request $request){
	// 	CommonController::AccessLog();
	// 	$kind = 1;
	// 	$result = PublicController::SearchUser($request,$kind,'search_buyer');
	// 	$lists       = $result['lists'];
	// 	$prefectures = $result['prefectures'];
	// 	$categories  = $result['categories'];
	// 	$types       = $result['types'];
	// 	$grades      = $result['grades'];
	// 	$counts      = $result['counts'];
	// 	return view('public/search_user', compact('counts','lists', 'prefectures', 'categories', 'types', 'grades', 'request','kind'));
	// }
	// /** UserIndividual (Get) **/
	// public function UserIndividual(Request $request){
	// 	CommonController::AccessLog();
	// 	$locale = Session::get('locale');
	// 	$p_id = $request->p_id;
	// 	$u_id        = Session::get('u_id'); //u_idがあるのをgetする
	// 	date_default_timezone_set('Asia/Tokyo');
	// 	$compare_date  = (new DateTime())->modify('-11 month')->format('Y-m-d H:i:s');//11か月前の日付
	// 	$today = (new DateTime())->format('Y-m-d H:i:s');//今日の日付
	// 	if($u_id == $request->u_id){
	// 		$myself = TRUE;
	// 	}else{
	// 		$myself = FALSE;
	// 	}
	// 	if(Session::get('kind')==3){
	// 		$myself = 'admin';
	// 	}
	// 	$post_user  = User::find($u_id);
	// 	$user       = User::find($request->u_id);
	// 	$address[1] = 'ja';
	// 	$address[2] = 'en';
	// 	$address[3] = 'cn';
	// 	$address[4] = 'tw';
	// 	$address[5] = 'ko';
	// 	$address[6] = 'fr';
	// 	$address[7] = 'other-la';
	// 	if(!empty($address[$user->address])){
	// 		$user->address  = $address[$user->address];
	// 	}else{
	// 		Redirect::to('/logout')->send();
	// 	}
	// 	$employee[1] = '～10';
	// 	$employee[11] = '11～100';
	// 	$employee[101] = '101～1000';
	// 	$employee[1001] = '1001～10000';
	// 	$employee[10001] = '10001～';
	// 	$user->employee  = $employee[$user->employee];
	// 	$note        = 'note_'.str_replace('-', '', $locale);
	// 	$user->note  = $user->$note;
	// 	$file_name = '/img_files/user/company'.$user->id.'.jpg';
	// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
	// 		$user->img = 'storage'.$file_name;
	// 	}else{
	// 		$user->img = '/img/company0.jpg';
	// 	}
	// 	$wechat_name = '/img_files/wechat/wechat'.$user->id.'.jpg';
	// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$wechat_name)){
	// 		$user->wechat = 'storage'.$wechat_name;
	// 	}else{
	// 		$user->wechat = '/img/wechat0.jpg';
	// 	}
	// 	$line_name = '/img_files/line/line'.$user->id.'.jpg';
	// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$line_name)){
	// 		$user->line = 'storage'.$line_name;
	// 	}else{
	// 		$user->line = '/img/line0.jpg';
	// 	}
	// 	$products    = Product::getListByUid($request->u_id);
	// 	$types       = Type::getList($locale);
	// 	$units       = Unit::getList($locale);
	// 	$grades      = Grade::getList($locale);
	// 	$prefectures = Prefecture::getList($locale);
	// 	foreach ($products as $key1 => $product) {
	// 		foreach ($types as $key2 => $type1) {
	// 			if ($key2 == $product->type) {
	// 				$type_name = $type1;
	// 				break;
	// 			}
	// 		}
	// 		foreach ($units as $key2 => $unit) {
	// 			if ($key2 == $product->p_unit) {
	// 				$p_unit_name = $unit;
	// 				break;
	// 			}
	// 		}
	// 		if($product->updated_at < $compare_date && $myself){
	// 			$products[$key1]->message      = ":$product->updated_at";
	// 		}
	// 		$products[$key1]->note         = $product->$note;
	// 		$products[$key1]->type_name    = $type_name;
	// 		$products[$key1]->p_unit_name  = $p_unit_name;
	// 		$array                         = Category::ReturnCategoryNames($product->category,$locale);
	// 		$products[$key1]->pdivision    = $array['division'];
	// 		$products[$key1]->psubdivision = $array['subdivision'];
	// 		$grade_lists = array();
	// 		if($product->grade){
	// 			$grade_lists = explode(',', $product->grade);
	// 			foreach($grade_lists as $key3 => $g_id){
	// 				$grade_lists[$key3] = $grades[$g_id]['name'];
	// 				//gradeの中のidが振られている名前を$grade_lists[$key3]に入れる（$g_idは番号。）
	// 			}
	// 		}
	// 		$products[$key1]->grade = $grade_lists;
	// 		if($product->unit){
	// 			$products[$key1]->unit = 'inch';
	// 		}else{
	// 			$products[$key1]->unit = 'mm';
	// 		}
	// 		if($product->origin){
	// 			$products[$key1]->origin = $prefectures[$product->origin];
	// 		}
	// 		for($i=1; $i < 4 ; $i++){
	// 			$file_name = '/img_files/product/product'.$i.'_'.$product->id.'.jpg';
	// 			$img = 'img'.$i;
	// 			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
	// 				$products[$key1]->$img = 'storage'.$file_name;
	// 			}else{
	// 				$products[$key1]->$img = '/img/product0.jpg';
	// 			}
	// 		}
	// 	}
	// 	if(count($products) && $p_id){
	// 		$x                        = array(); //空の配列$xを作る
	// 		$a                        = array_keys($products)[0]; //$aに$productsの0番目の物を入れる(退避)
	// 		$x                        = $products[$a]; //$xに$products[$a(0)]（0番目の物）を入れる
	// 		$products[$a]             = $products[$request->p_id]; //$products[$a]（先頭に表示）に$products[$request->p_id]を入れる（選んだもの）
	// 		$products[$request->p_id] = $x; //$products[$request->p_id]に､退避していた$x(0)を入れる。これで順番入れ替え
	// 	}
	// 	$i     = 0;
	// 	$kind  = $user->kind;
	// 	$alert = Session::get('alert');
	// 	Session::put('alert',False);
	// 	return view('public/user_individual', compact('locale','alert','post_user','user','products','p_id','i','myself','kind','compare_date'));
	// }
	// /** AboutUs (Get) **/
	// public function AboutUs(){
	// 	CommonController::AccessLog();
	// 	return view('public/about_us');
	// }
	// /** PrivacyPolicy (Get) **/
	// public function PrivacyPolicy(){
	// 	CommonController::AccessLog();
	// 	$locale = Session::get('locale');
	// 	return view('public/privacy_policy',compact('locale'));
	// }
	// /** ContactUs **/ 
	// public function ContactUs(Request $request){
	// 	CommonController::AccessLog();
	// 	$u_id         = session::get('u_id');
	// 	if($request->isMethod('GET')){
	// 		$send_flag = false;
	// 		if (session::get('send-flag')) {
	// 			$send_flag = true;
	// 			session::put('send-flag',false);
	// 		}
	// 		return view('public/contact_us',compact('u_id','send_flag'));
	// 	}else {
	// 		$company_name = $request->company_name;
	// 		$u_mail       = $request->u_mail;
	// 		$u_name       = $request->u_name;
	// 		$title        = $request->title;
	// 		$subject      = '【JWEL お問い合わせ】';
	// 		$information  = $request->information;
	// 		$to		      = env('J_WOOD_EMAIL','jwe@j-wood.org');
	// 		$data	      = [
	// 						'u_id'         => $u_id,
	// 						'company_name' => $company_name,
	// 						'u_mail'       => $u_mail,
	// 						'u_name'       => $u_name,
	// 						'title'        => $title,
	// 						'information'  => $information,
	// 						];
	// 		try{
	// 			Mail::send(['text'=>'emails.contact_thanks'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
	// 				function($send) use ($to,$subject){
	// 					$send->to($to);
	// 					$send->subject($subject);
	// 				}
	// 			);
	// 		}catch(Exception $e){
	// 			$get_message = $e->getMessage();
	// 			Log::error("メール送信エラー address=({$to}) message={$get_message}");
	// 			$alert['class'] = 'warning';
	// 			$alert['text']  = 'email-notsend';
	// 			Session::put('alert',$alert);
	// 			Redirect::to('initial_email')->send();
	// 			return;
	// 		}
	// 	session::put('send-flag',true);
	// 	Redirect::to('/contact_us')->send();
	// 	return;
	// 	}
	// }
}/* EOF */
