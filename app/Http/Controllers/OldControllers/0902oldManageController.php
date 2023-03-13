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
// use App\Models\Nice;// お気に入り機能 
// use App\Models\Prefecture;//地域で選択なら復活
use App\Models\Time;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\Category;
use App\Models\Custom;
use App\Models\Open_Close;
use App\Models\Coupon;
use Illuminate\Pagination\LengthAwarePaginator;
class ManageController extends Controller	{
	// 管理者ページ　
	public function AdminManage(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$customs  = Custom::get();
		$w_stores = Store::get();
		foreach ($w_stores as $w_store) {
			$stores[$w_store->id] = $w_store;
		}

		$categorys = array();
		return view('manage/admin_manage',compact('customs','stores','categorys'));
	}
	// 管理者情報編集
	public function AdminEdit(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$stores = Store::get();
		$customs = Custom::get();
		if($request->isMethod('GET')){
			if($request->id == 0){
				$custom = '';
				$type = $request->type;
			}elseif(isset($request->type)){
				$custom = Custom::where('id',$request->id)->first();
				$type = $request->type;
			}
		}else{//更新の場合(post)
			$custom = Custom::find($request->c_id);
			if(is_null($custom)){
				$type = $request->type;
				$custom = Custom::where('type',$type)->orderBy('no', 'desc')->first();
				if(isset($custom)){
					$no = $custom->no + 1;
				}else{
					$no = 1;
				}
				$custom = new Custom();
				$custom->init();//初期化
				$custom->no = $no;
			}
			//入力されたパラメータでcategoryオブジェクトを上書き
			if($request->title){
				$custom->title = $request->title;
			}
			if($request->url){
				$custom->url = $request->url;
			}
			if($request->from){
				$custom->from = $request->from;
			}
			if($request->type){
				$custom->type = $request->type;
			}
			if($request->from_date && $request->to_date){
				$custom->from_date = $request->from_date;
				$custom->to_date = $request->to_date;
			}
			if(isset($type) == 4){
				// $custom->type = $request->type;
				//$store->feature_status =   $store->feature_status == 1;
			}
			$custom->save();

			// 店舗イメージ写真　　スライダー
			if(isset($request->admin_img)){
				$file = $request->file('admin_img');
				$dir = 'public/admin_img';// 保管する場所
				$file_name = $custom->type."-".$custom->no.".".$file->extension();
				$file->storeAS($dir,$file_name);

				$custom->extension = $file->extension();
				$custom->save();
			}
			// if(isset($request->custom)){
			// 	$dir = 'custom';
			// 	$file_name = $custom->type.".".$request->custom->extension();
			// 	$request->file('custom')->storeAs('public/' . $dir, $file_name);
			// }
			return Redirect::to('admin_manage')->send();
		}
		$type_name_array = array("カテゴリー","お知らせ","スライダー","広告","もし推し店");
		$type_name = $type_name_array[$request->type];

		return view('manage/admin_edit', compact('custom','customs','type','type_name','stores'));
	}
	public function CustomDelete(Request $request) {// カスタム削除
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		Custom::where('id',$request->c_id)->delete();
		return Redirect::to('admin_manage')->send();
	}
	public function CouponDelete(Request $request) {// クーポン削除
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		Coupon::where('id',$request->c_id)->delete();
		return Redirect::to('coupon_list')->send();
	}

	// クーポン一覧　
	public function CouponList(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$coupons = Coupon::get();
		$stores = Store::get();
		foreach ($coupons as $key => $coupon) {
			foreach ($stores as $key => $store) {
				if ($coupon->s_id == $store->id) {
					$coupon->s_name = $store->name;
				}
			}
		}
		$date = date('Y-m-d');
		return view('manage/coupon_list', compact('coupons','date'));
	}
	// クーポン編集　
	public function CouponEdit(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($request->isMethod('GET')){
			if($kind == 1){// 店舗ユーザ以外の場合
				return;
			}elseif($request->c_id == 0){//
				$coupon = new Coupon();
				$coupon->init();//初期化
				$coupon->s_id = $s_id;
			}elseif($kind == 0){// adminの場合
				$c_id = $request->c_id;
				$coupon = Coupon::find($c_id);
			}elseif($kind == 2){// 店舗ユーザの場合
				$coupon = Coupon::find($c_id);
				if(is_null($coupon)){// 割り当て店舗なしはダメ
					return;
				}
			}
		}else{//更新の場合(post)
			$coupon = Coupon::find($request->c_id);
			if(is_null($coupon)){
				$coupon = new Coupon();
				$coupon->init();//初期化
			}
			//入力されたパラメータで上書き
			if($request->s_id){
				$coupon->s_id = $request->s_id;
			}
			if($request->title){
				$coupon->title = $request->title;
			}
			if($request->discount){
				$coupon->discount = $request->discount;
			}
			if($request->from_date){
				$coupon->from_date = $request->from_date;
			}
			if($request->to_date){
				$coupon->to_date = $request->to_date;
			}
			$coupon->save();
			// 店舗イメージ写真　　スライダー
			if(isset($request->coupon_img)){
				$file = $request->file('coupon_img');
				$dir = 'public/coupon_img';// 保管する場所
				$file_name = $coupon->id.".".$file->extension();
				$file->storeAS($dir,$file_name);

				$coupon->extension = $file->extension();
				$coupon->save();
			}
			return Redirect::to('coupon_list')->send();
		}
		$stores = Store::orderBy('created_at', 'desc')->get();
		$date = date('Y-m-d');
		return view('manage/coupon_edit', compact('coupon','stores','date'));
	}

	// // カテゴリー編集　
	// public function CategoryEdit(Request $request){
	// 	$categorys = Category::get();
	// 	if($request->isMethod('GET')){
	// 		$category = Category::find($request->c_id);
	// 	}else{//更新の場合(post)
	// 		$category = Category::find($request->c_id);
	// 		// $category = Category::find($request->c_id);
	// 		if(is_null($category)){
	// 			$category = new Category();
	// 			$category->init();//初期化
	// 		}
	// 		//入力されたパラメータでcategoryオブジェクトを上書き
	// 		if($request->name){
	// 			$category->name = $request->name;
	// 		}
	// 		$category->save();
	// 		if(isset($request->category)){
	// 			$dir = 'category';
	// 			$file_name = $category->id.".".$request->category->extension();
	// 			$request->file('category')->storeAs('public/' . $dir, $file_name);
	// 		}
			
	// 		return Redirect::to('admin_manage')->send();
	// 	}
	// 	return view('manage/category_edit', compact('category','categorys'));
	// }
	public function StoreManage(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$stores = Store::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		foreach($stores as $key => $store){
			$file_name = '/img_files/store/store'.$store->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$store->img = 'storage'.$file_name;
			}else{
				$store->img = '/img/store00.jpg';
			}
		}

		return view('manage/store_manage',compact('stores'));
	}
	// 店舗情報　
	public function StoreInformation(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		// if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){// adminの場合
			$s_id = $request->s_id;
		}elseif($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
		}elseif($kind != 2){// 店舗ユーザ以外の場合
			return;
		}
		if(!$s_id){// 新規の場合(adminのみ)
			$store = new Store();
			$store->init();//初期化
		}else{
			$store = Store::find($s_id);
		}
		$calendars = Open_Close::where('s_id',$s_id)->orderBy('day','desc')->get();
		foreach ($calendars as $key => $calendar) {
			if($calendar->open){
				$calendars[$key]->open = '営業：';
			}else{
				$calendars[$key]->open = '休業：';
				$calendars[$key]->time = '';
			}
			switch ($calendar->day) {
				case 'w0':
					$calendars[$key]->day = '日曜';
				break;
				case 'w1':
					$calendars[$key]->day = '月曜';
				break;
				case 'w2':
					$calendars[$key]->day = '火曜';
				break;
				case 'w3':
					$calendars[$key]->day = '水曜';
				break;
				case 'w4':
					$calendars[$key]->day = '木曜';
				break;
				case 'w5':
					$calendars[$key]->day = '金曜';
				break;
				case 'w6':
					$calendars[$key]->day = '土曜';
				break;
				case 'w7':
					$calendars[$key]->day = '通常';
				break;
				default:
				$calendars[$key]->day = '不定期('.$calendar->day.')';
				break;
			}
		}
		$customs = Custom::where('s_id',$s_id)->get();
		return view('manage/store_information',compact('store','calendars','customs'));
	}
	// 店舗情報更新　
	public function StoreUpdate(Request $request){
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){//adminの場合
			$s_id = $request->s_id;
		}elseif($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
			if($s_id != $request->s_id){// 他の店舗を指定してきた場合
				return;
			}
		}elseif($kind != 2){// 店舗ユーザ以外の場合
			return;
		}
		if(!$s_id){// 新規の場合(adminのみ)
			$store = new Store();
			$store->init();//初期化
			$store->created_at = date('Y-m-d H:i:s');
		}else{
			$store = Store::find($s_id);
			if(is_null($store)){// 指定されたs_idが存在しない場合
				return;
			}
		}
		// 設定されたパラメータを代入
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
		if($request->stripe_user_id){
			$store->stripe_user_id = $request->stripe_user_id;
		}
		// 更新日付をいれる
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
		}
		// スタッフからのメッセージ欄の写真
		if($request->staff_image){
			$dir = 'staff_image';
			$file_name = $store->id.".".$request->staff_image->extension();
			$request->file('staff_image')->storeAs('public/' . $dir, $file_name);
		}
		return Redirect::to('store_information/'.$s_id)->send();
	}
	//　カレンダー機能
	public function Calendar(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){//adminの場合
			$s_id = $request->s_id;
		}
		if($request->isMethod('POST')){//更新の場合(post)
			$calendar = new Open_Close();
			$calendar->init();//初期化
			$calendar->created_at = date('Y-m-d H:i:s');
			$calendar->s_id = $s_id;
			//入力されたパラメータでproductオブジェクトを上書き
			if($request->open){
				$calendar->open = $request->open;
			}
			if($request->date == 8){
				$calendar->day = $request->day;
			}else{
				$calendar->day = 'w'.$request->date;

			}
			if($request->from && $request->to){
				$calendar->time = $request->from.'-'.$request->to;
			}
			$calendar->updated_at = date('Y-m-d H:i:s');
			$calendar->save();
			return Redirect::to('store_information/'.$s_id)->send();
		}
		return view('manage/calendar', compact('s_id'));
	}
	public function CalendarDelete(Request $request) {// カテゴリー削除
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		// if( !Session::has('kind') || Session::get('kind') == 1){ return; }//一般ユーザの場合
		$kind = Session::get('kind');
		if($kind == 2){
			$s_id   = Session::get('s_id');
			$calendar = Open_Close::where('id',$request->c_id)->where('s_id',$s_id)->first();
			if(is_null($calendar)){//誤ったc_idが指定されてきた場合
				return;
			}
		}else{
			$calendar = Open_Close::where('id',$request->c_id)->first();
			$s_id = $calendar->s_id;
		}
		Open_Close::where('id',$request->c_id)->delete();
		return Redirect::to('store_information/'.$s_id)->send();
	}
	//
	public function ProductCategory(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){//adminの場合
			$s_id = $request->s_id;
		}
		if($request->isMethod('POST')){//更新の場合(post)
			$no = $request->no;
			if(!$no){
				$no = 1;
			}
			$custom = Custom::where('s_id',$s_id)->where('no',$no)->first();
			if(is_null($custom)){
				$custom = new Custom();
				$custom->init();//初期化
			}
			//入力されたパラメータでproductオブジェクトを上書き
			$custom->s_id = $s_id;
			$custom->no   = $no;
			$custom->type = 10;
			$custom->title = $request->title;
			$custom->save();
			return Redirect::to('store_information/'.$s_id)->send();
		}
		return view('manage/product_category', compact('s_id'));
	}
	public function ProductCategoryDelete(Request $request) {// カスタム削除
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		// if( !Session::has('kind') || Session::get('kind') == 1){ return; }//一般ユーザの場合
		$kind = Session::get('kind');
		if($kind == 2){
			$s_id   = Session::get('s_id');
			$custom = Custom::where('id',$request->c_id)->where('s_id',$s_id)->first();
			if(is_null($custom)){//誤ったc_idが指定されてきた場合
				return;
			}
		}else{
			$custom = Custom::where('id',$request->c_id)->first();
			$s_id = $custom->s_id;
		}
		Custom::where('id',$request->c_id)->delete();
		return Redirect::to('store_information/'.$s_id)->send();
	}


	// 商品リスト　
	public function ProductList(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){// adminの場合
			$s_id = $request->s_id;
		}elseif($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
		}elseif($kind != 2){// 店舗ユーザ以外の場合
			return;
		}
		$products = Product::where('s_id',$s_id)->where('p_status',1)->orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		foreach($products as  $product){
			$file_name = '/img_files/product/product'.$product->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$product->img = 'storage'.$file_name;
			}else{
				$product->img = '/img/product00.jpg';
			}
		}
		return view('manage/product_list',compact('s_id','products'));
	}
	// もしもし管理画面　商品編集
	public function ProductEdit(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
			if($request->p_id){
				$product = Product::where('id',$request->p_id)->where('s_id',$s_id)->get();
				if(!count($product)){//対象店舗に当該商品が存在しない場合
					return;
				}
			}
		}elseif($kind == 0){
			$product = Product::find($request->p_id);
			$s_id = $product->s_id;
		}else{// 店舗ユーザ以外の場合
			return;
		}
		//$categorys = Category::get();
		$categorys = Custom::where('type',0)->orderBy('no', 'asc')->get();
		$customs = Custom::where('type',10)->where('s_id',$s_id)->orderBy('no', 'asc')->get();
		$w_options = Option::where('s_id',$s_id)->orderBy('o_id', 'desc')->get();
		$options = array();
		foreach ($w_options as $w_option) {
			$options[$w_option->o_id] = $w_option->o_name;
		}
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
			if($s_id){
				$product->s_id = $s_id;
			}
			if($request->c_id){
				$product->c_id = $request->c_id;
			}
			if($request->sc_id){
				$product->sc_id = $request->sc_id;
			}
			if($request->o_id){
				$product->o_ids = implode(',', $request->o_id);
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
			return Redirect::to('product_list/'.$s_id)->send();
		}
		return view('manage/product_edit', compact('product','categorys','customs','options'));
	}
	public function Productdelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
			$product = Product::where('id',$request->p_id)->where('s_id',$s_id)->get();
			if(!count($product)){//対象店舗に当該商品が存在しない場合
				return;
			}
		}elseif($kind == 1){// 店舗ユーザ以外の場合
			return;
		}
		$product = Product::find($request->p_id);
		if($product->p_status){
			$product->p_status = 0;
		}else{
			$product->p_status = 1;
		}
		$product->updated_at =  date('Y-m-d H:i:s');
		$product->save();
		return Redirect::to('product_list')->send();
	}
	// オプション一覧　
	public function OptionList(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 0){// adminの場合
			$s_id = $request->s_id;
		}elseif($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
		}elseif($kind != 2){// 店舗ユーザ以外の場合
			return;
		}
		$options = Option::where('s_id',$s_id)->orderBy('o_id', 'asc')->get();
		$o_names = array();
		foreach ($options as $key => $option) {
				$o_names[$option->o_id] = $option->o_name;
		}
		return view('manage/option_list', compact('s_id','options','o_names'));
	}
	// オプション 編集　
	public function OptionEdit(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		if($kind == 1){ return; }
		$s_id = Session::get('s_id');
		if($kind == 2){// 店舗ユーザの場合
			if($request->id){
				$option = Option::where('id',$request->id)->where('s_id',$s_id)->get();
				if(!count($option)){//対象店舗に当該商品が存在しない場合
					return;
				}
			}
		}else{
			$s_id = $request->s_id;
		}
		if($request->isMethod('POST')){
			$option = Option::find($request->id);
			// $category = Category::find($request->c_id);
			if(is_null($option)){
				$option = new Option();
				$option->init();//初期化
				$option->created_at = date('Y-m-d H:i:s');
				$option->o_name = $request->o_name;
				$w_option = Option::where('o_name',$request->o_name)->first();
				if(isset($w_option)){
					$option->o_id = $w_option->o_id;
					}else{
					$w_option = Option::where('s_id',$s_id)->orderBy('o_id', 'desc')->first();
					if(isset($w_option->o_id)){
						$option->o_id = $w_option->o_id + 1;
					}else{
						$option->o_id = 1;
					}
				}
			}
			//入力されたパラメータでproductオブジェクトを上書き
			$option->s_id = $request->s_id;
			$option->name = $request->name;
			$option->price = $request->price;
			$option->updated_at = date('Y-m-d H:i:s');
			$option->save();
			return Redirect::to('option_list/'.$s_id)->send();
		}
	}
	public function Optiondelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 2){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
			$option = Option::where('id',$request->o_id)->where('s_id',$s_id)->get();
			if(!count($option)){//対象店舗に当該商品が存在しない場合
				return;
			}
		}elseif($kind == 1){// 店舗ユーザ以外の場合
			return;
		}
		Option::where('id',$request->o_id)->delete();
		return Redirect::to('option_list')->send();
	}
	public function Storedelete(Request $request) {// 店舗削除
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$store = Store::find($request->s_id);
		if($store->store_status){
			$store->store_status = 0;
		}else{
			$store->store_status = 1;
		}
		$store->updated_at =  date('Y-m-d H:i:s');
		$store->save();
		return Redirect::to('store_manage')->send();
	}
		// 商品 編集　
	public function Shopedit(Request $request){

		return view('manage/shop_edit');
	}
}/* EOF */
