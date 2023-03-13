<?php
namespace App\Http\Controllers;
use Session, Log;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
// use App\Models\Nice;// お気に入り機能 
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
		$pick_up = Custom::where('type',4)->get();
		$stores = Store::where('store_status',1)->get();
		foreach($pick_up as $key => $pick) {
			foreach ($stores as $key2 => $store) {
				if($pick->title == $store->id){
					$pick->s_name = $store->name;
				}
			}
		}
		$categorys = array();
		return view('manage/admin_manage',compact('customs','pick_up','categorys'));
	}
	// 管理者情報編集
	public function AdminEdit(Request $request){
		if( !Session::has('kind') ){ return; }
		$kind = Session::get('kind');
		if( $kind != 0 && $kind != 2 ){ return; }// admin,store以外の場合
		$pickup_custom = Custom::where('type',4)->get();//pickup
		foreach ($pickup_custom as $custom) {
			$already_store[$custom->title] = $custom->title;
		}
		// $stores = Store::where('store_status',1)->whereNotIn('id',$already_store)->get();//PICKUP 有効な店舗かつ既に選択されていない店舗のみ表示
		// 編集復活なら
		if(isset($already_store)){
			$stores = Store::where('store_status',1)->whereNotIn('id',$already_store)->get();//有効な店舗かつ既に選択されていない店舗のみ表示
		}
		else{
			$stores = Store::where('store_status',1)->get();//有効な店舗かつ既に選択されていない店舗のみ表示
		}
		$customs = Custom::get();
		$store_id = $request->s_id;
		if($request->isMethod('GET')){
			if($request->id == 0){
				$custom = '';
				$type = $request->type;
			}elseif(isset($request->type)){
				$type = $request->type;
				if($type == 5){//seo
					$custom = Custom::where('type',$type)->where('no',$request->id)->first();
					if(is_null($custom)){
						$custom = new Custom();
						$custom->no = $request->id;
					}
				}else{
					$custom = Custom::where('id',$request->id)->first();
				}
			}
		}else{//更新の場合(post)
			$custom = Custom::find($request->c_id);
			$type = $request->type;
			if(is_null($custom)){
				if($type == 5){//seo
					$no = $request->no;
				}else{
					$custom = Custom::where('type',$type)->orderBy('no', 'desc')->first();
					if(isset($custom)){
						$no = $custom->no + 1;//noがあったら+1する
					}else{
						$no = 1;//最初は１
					}
				}
				$custom = new Custom();
				$custom->init();//初期化
				$custom->no = $no;
				$custom->created_at = date('Y/m/d');

			}
			//入力されたパラメータでオブジェクトを上書き
			if($request->title){
				$custom->title = $request->title;
			}
			if($store_id){
				$custom->s_id = $store_id;
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
			// 店舗のお知らせ
			if($type == 1 && $kind == 2){
				$custom->s_id = Session::get('s_id');
			}
			// 店舗のよみもの機能
			if($type == 8 && $kind == 2){
				$custom->s_id = Session::get('s_id');
			}
			$custom->updated_at = date('Y/m/d');
			$custom->save();
			if(isset($request->admin_img)){
				$file = $request->file('admin_img');
				$extension = $file->extension();
				switch ($request->type) {
					// カテゴリー
					case '0':
						$width_size = '500';
						$height_size = '800';
					break;
					// スライダー
					case '2':
						$width_size = '1600';
						$height_size = '900';

					break;
					// 広告
					case '3':
						$width_size = '800';
						$height_size = '450';

					break;
					// PICKUP
					case '4':
						$width_size = '800';
						$height_size = '450';

					break;
					//店舗のよみものページ
					case '8':
						$width_size = '800';
						$height_size = '800';

					break;
					default:
					break;
				}
				Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();//画像をリサイズ&トリミング&jpg変換
				$dir = 'public/admin_img';// 保管する場所
				$file_name = $custom->type."-".$custom->no.".".$extension;
				$file->storeAS($dir,$file_name);
				$custom->extension = $extension;
				$custom->save();
				if($type == 0){//カテゴリー
					if(isset($request->admin_img2)){//クリック後の画像
						$file = $request->file('admin_img2');
						$extension = $file->extension();
						$width_size = '500';
						$height_size = '800';
						Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();//画像をリサイズ&トリミング&jpg変換
						$dir = 'public/admin_img';// 保管する場所
						$file_name = $custom->type."-".$custom->no."-2.".$extension;
						$file->storeAS($dir,$file_name);
						$custom->extension = $extension;
						$custom->save();
					}
				}
			}
			// admin
			if($kind==0 && $type==0 || $type==1 || $type==2 || $type==3 || $type==4 || $type==5){
				if($type==1 && $store_id == 0){	// top　お知らせ
					return Redirect::to('admin_manage')->send();
				}
				elseif($type==1 || $store_id != 0){ //店舗のお知らせ
					return Redirect::to('store_information/'.$store_id)->send();
				}
				return Redirect::to('admin_manage')->send();
			}
			elseif($kind==0 && $type== 8){//店舗のよみもの
				return Redirect::to('store_information/'.$store_id)->send();
			}
			else{
				return Redirect::to('admin_manage')->send();
			}
			// 店舗ユーザー
			if($kind==2){
				return Redirect::to('store_infomation/'.$store_id)->send();
			}
		}
		$type_name_array = array("カテゴリー","お知らせ","スライダー","広告","もし推し店","SEO","","","よみもの");
		$type_name = $type_name_array[$request->type];
		return view('manage/admin_edit', compact('custom','customs','type','type_name','stores','store_id'));
	}

	public function CustomDelete(Request $request) {// カスタム削除
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
			$category = Custom::where('id',$request->c_id)->first();
			$products =Product::get();
			foreach($products as $key => $product){
				$custom_ids  = explode(',', $product->c_id);//カテゴリーを配列
				$category->no = (string) $category->no;
				$index = in_array($category->no,$custom_ids);
				if($index == true){
					$delete_c_id = str_replace($category->no, '', $custom_ids);
					$product->c_id  = implode(',', $delete_c_id);
					$product->save();
				}
			}
			Custom::where('id',$request->c_id)->delete();
			return Redirect::to('admin_manage')->send();
	}

	public function StoreCustomDelete(Request $request) {// 店舗カスタム削除
		if( !Session::has('kind') || Session::get('kind') == 1 || Session::get('kind') == 3 ){ return; }// admin以外の場合
			$custom = Custom::where('id',$request->c_id)->first();
			$s_id = $custom->s_id;
			Custom::where('id',$request->c_id)->delete();
			return Redirect::to('store_information/'.$s_id)->send();
	}

	public function CouponDelete(Request $request) {// クーポン削除
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		Coupon::where('id',$request->c_id)->delete();
		return Redirect::to('coupon_list')->send();
	}

	// クーポン一覧　
	public function CouponList(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$coupons = Coupon::orderBy('to_date', 'desc')->get();
		$stores = Store::where('store_status',1)->get();
		foreach ($coupons as $key => $coupon) {
			foreach ($stores as $key => $store) {
				if ($coupon->s_id == $store->id) {
					$coupon->s_name = $store->name;
				}
				elseif($coupon->s_id == 0){
					$coupon->s_name = '全店舗';
				}
			}
		}
		$date = date('Y-m-d');//日付で編集可能不可能の制御用
		return view('manage/coupon_list', compact('coupons','date','stores'));
	}
	// クーポン編集　
	public function CouponEdit(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		$products = array();
		$product  = '';
		$store    = '';
		if(isset($request->store_id)){
			if($kind != 0){// admin以外の場合
				return;
			}
			$coupon = new Coupon();
			$coupon->init();//初期化
			if($request->store_id == 0){//全店舗
				$coupon->s_id = 0;
			}elseif($request->store_id != 0){//店舗指定
				$coupon->s_id = $request->store_id;
				$store    = Store::find($request->store_id);
				$products = Product::where('s_id',$request->store_id)->get();
			}
		$coupon->p_id   = '';
		$coupon->p_flag = '';
		$stores = Store::orderBy('created_at', 'desc')->get();
		$date = date('Y-m-d');
		return view('manage/coupon_edit', compact('coupon','stores','date','products','product','store'));
		}
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
				$product = Product::find($coupon->p_id);
			}elseif($kind == 2){// 店舗ユーザの場合
				$coupon = Coupon::find($c_id);
				if(is_null($coupon)){// 割り当て店舗なしはダメ
					return;
				}
			}
			if($coupon->p_id == 16) {
				$product->name = '送料';
			}
		}else{//post
			$coupon = Coupon::find($request->c_id);
			if(is_null($coupon)){
				$coupon = new Coupon();
				$coupon->init();//初期化
			}
			//入力されたパラメータで上書き
			if($request->s_id){
				$coupon->s_id = $request->s_id;
			}
			if(isset($request->p_flag)){
				$coupon->p_flag = $request->p_flag;
			}
			if($request->p_id){
				$coupon->p_id = $request->p_id;
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
			// coupon画像トリミング
			if(isset($request->coupon_img)){
				$file = $request->file('coupon_img');
				$extension = $file->extension();
				$width_size = 800;
				$height_size = 450;
                Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
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
		return view('manage/coupon_edit', compact('coupon','stores','date','products','product','store'));
	}
	//店舗一覧
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
		$calendars = Open_Close::where('s_id',$s_id)->orderBy('day','desc')->get();//配送可能時間登録用
		$check_calendar = Open_Close::where('s_id',$s_id)->where('open',1)->where('day','w7')->get();//店舗URL表示用
		foreach ($calendars as $key => $calendar) {
			if($calendar->open){
				$calendars[$key]->open = '営業';
			}else{
				$calendars[$key]->open = '休業';
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
					$width_size = 1000;
					$height_size = 678;
					$files = $request->file('store_image');
					foreach($files as $key => $file){
						$extension = $file->extension();
						//画像をリサイズ&トリミング&jpg変換
						Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
						$dir = 'public/store_image';// 保管する場所
						$file_name = $store->id."-"."$key".".".$file->extension();
						$file->storeAS($dir,$file_name);
					}
				}
				
				// スタッフからのメッセージ欄の写真
				if($request->staff_image){
					$file = $request->file('staff_image');
					$extension = $file->extension();
					$width_size = 400;
					$height_size = 400;
					Image::make($file)->fit($width_size,$height_size,null,'top')->encode($extension)->save();
					$dir = 'staff_image';
					$file_name = $store->id.".".$request->staff_image->extension();
					$request->file('staff_image')->storeAs('public/' . $dir, $file_name);
				}
		$customs = Custom::where('s_id',$s_id)->orderByRaw('no asc')->get();
		return view('manage/store_information',compact('store','calendars','customs','check_calendar'));
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
			$store->name = $request->name ? $request->name: '';
			$store->catch_copy = $request->catch_copy ? $request->catch_copy: '';
			$store->schedule_memo = $request->schedule_memo ? $request->schedule_memo: '';
			$store->note = $request->note ? $request->note: '';
			$store->postcode = $request->postcode ? $request->postcode: '';
			$store->address = $request->address ? $request->address: '';
			$store->access = $request->access ? $request->access: '';
			$store->tel = $request->tel ? $request->tel: '';
			$store->email = $request->email ? $request->email: '';
			$store->url = $request->url ? $request->url: '';
			$store->instagram = $request->instagram ? $request->instagram: '';
			$store->twitter = $request->twitter ? $request->twitter: '';
			$store->facebook = $request->facebook ? $request->facebook: '';
			$store->stripe_user_id = $request->stripe_user_id ? $request->stripe_user_id: '';
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
			$width_size = 1000;
			$height_size = 678;
			$files = $request->file('store_image');
			foreach($files as $key => $file){
				$extension = $file->extension();
				//画像をリサイズ&トリミング&jpg変換
				Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
				$dir = 'public/store_image';// 保管する場所
				$file_name = $store->id."-"."$key".".".$file->extension();
				$file->storeAS($dir,$file_name);
			}
		}
		
		// スタッフからのメッセージ欄の写真
		if($request->staff_image){
			$file = $request->file('staff_image');
			$extension = $file->extension();
			$width_size = 400;
			$height_size = 400;
			Image::make($file)->fit($width_size,$height_size,null,'top')->encode($extension)->save();
			$dir = 'staff_image';
			$file_name = $store->id.".".$request->staff_image->extension();
			$request->file('staff_image')->storeAs('public/' . $dir, $file_name);
		}
		$store->save();

		if(isset($s_id) && $s_id != 0){
			return Redirect::to('store_information/'.$s_id)->send();
		}else{
			return Redirect::to('store_manage')->send();
		}
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
			if($request->date == 8){//日付
				$calendar->day = $request->day;
			}else{
				$calendar->day = 'w'.$request->date;
			}
			if($request->from1){
				$from_to1 = $request->from1.'-'.$request->to1;
			}else{
				$from_to1 = '';
			}
			if($request->from2){
				$from_to2 = $request->from2.'-'.$request->to2;
			}else{
				$from_to2 = '';
			}
			$from_to = $from_to1.','.$from_to2;
			if(isset($from_to)){
				$calendar->time = $from_to;
			}			
			$calendar->time = $from_to;
			$calendar->updated_at = date('Y-m-d H:i:s');
			$calendar->save();
			return Redirect::to('store_information/'.$s_id)->send();
		}
		return view('manage/calendar', compact('s_id'));
	}
	public function CalendarDelete(Request $request) {// カレンダー削除
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
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
			$custom->created_at = date('Y-m-d');
			$custom->updated_at = date('Y-m-d');
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
		$category = Custom::where('id',$request->c_id)->where('s_id',$s_id)->first();
		$products =Product::where('s_id',$s_id)->get();
		foreach($products as $key => $product){
			$custom_ids  = explode(',', $product->sc_id);//ショップ内カテゴリーを配列
			$category->no = (string) $category->no;
			$index = in_array($category->no,$custom_ids);
			if($index == true){
				$delete_c_id = str_replace($category->no, '', $custom_ids);
				$product->sc_id  = implode(',', $delete_c_id);
				$product->save();
			}
		}
		Custom::where('id',$request->c_id)->delete();
		return Redirect::to('store_information/'.$s_id)->send();
	}
	// 有料広告枠　
	public function PaidInventory(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$customs  = Custom::get();
		$w_stores = Store::get();
		foreach ($w_stores as $w_store) {
			$stores[$w_store->id] = $w_store;
		}
		// タイトルとリード文
		$ad = Custom::where('type',7)->first();
		$title_read  = explode(',', $ad->title);
		$ad->title = $title_read[0];
		$ad->read = $title_read[1] ;
		//背景画像　
		if(isset($request->paid_inventory_img)){
			$file = $request->file('paid_inventory_img');
			$extension = $file->extension();
			$width_size = 800;
			$height_size = 450;
			Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
			$dir = 'paid_inventory_img';
			$file_name = $ad->type."-".$ad->id.".".$file->extension();
			$request->file('paid_inventory_img')->storeAs('public/' . $dir, $file_name);
			$ad->extension = $extension;
			$ad->save();
		}//タイトル画像
		if(isset($request->paid_inventory_img2)){
			$file = $request->file('paid_inventory_img2');
			$extension = $file->extension();
			$width_size = 700;
			$height_size = 250;
			Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
			$dir = 'paid_inventory_img';
			$file_name = $ad->type."-"."2-".$ad->id.".".$file->extension();
			$request->file('paid_inventory_img')->storeAs('public/' . $dir, $file_name);
			$ad->extension = $extension;
			$ad->save();
		}
		// 有料広告一覧
		$paid_inventorys = Custom::where('type',6)->get();
		foreach($paid_inventorys as $paid_inventory){
			foreach ($stores as $key => $store) {
				if($paid_inventory->s_id == $store->id){
				$paid_inventory->name = $store->name;
				}
			}
		}
		return view('manage/paid_inventory', compact('customs','ad','paid_inventorys'));
	}

	// 有料広告枠　基本情報
	public function PaidInventoryUpdate(Request $request) {
		$kind = Session::get('kind');
		$custom = Custom::where('type',$request->type)->first();
		$title = $request->title ? $request->title: '';//タイトル
	    $read = $request->read ? $request->read: '';//リード
		$title_read = $title.','.$read;//カンマ区切りで
		$custom->title = $title_read;


		if($request->url){
			$custom->url = $request->url ? $request->url: '';
		}
		if(isset($request->display)){
			$custom->no = $request->display;
		}
		if($request->type){
			$custom->type = $request->type;
		}
		$custom->save();

		if(isset($request->paid_inventory_img)){
		$file = $request->file('paid_inventory_img');
		$extension = $file->extension();
		$width_size = 800;
		$height_size = 450;
		Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
		$dir = 'paid_inventory_img';
		$file_name = $custom->type."-".$custom->id.".".$file->extension();
		$request->file('paid_inventory_img')->storeAs('public/' . $dir, $file_name);
		$custom->extension = $extension;
		$custom->save();
		}
		if(isset($request->paid_inventory_img2)){
		$file = $request->file('paid_inventory_img2');
		// dd($file);2
		$extension = $file->extension();
		$width_size = 700;
		$height_size = 250;
		Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
		$dir = 'paid_inventory_img2';
		$file_name = $custom->type."-".$custom->id.".".$file->extension();
		$request->file('paid_inventory_img2')->storeAs('public/' . $dir, $file_name);
		$custom->extension = $extension;
		$custom->save();
		}
		return Redirect::to('paid_inventory')->send();
	}
	// 有料広告枠 詳細情報
	public function PaidInventoryDetail(Request $request) {
		if( !Session::has('kind') ){ return; }
		$kind = Session::get('kind');
		if( $kind != 0 && $kind != 2 ){ return; }// admin,store以外の場合
		if( $kind == 2 && $request->type != 1 ){ return; }// storeでtypeが1以外の場合
		$stores = Store::where('store_status',1)->get();
		$customs = Custom::get();
		if($request->isMethod('GET')){
			if($request->id == 0){
				$custom = '';
				$type = $request->type;
			}
			else{
				$custom = Custom::where('type',$request->type)->where('id',$request->id)->first();
			}
		}else{//更新の場合(post)
			$custom = Custom::where('type',$request->type)->where('id',$request->id)->first();
			$type = $request->type;
			if(is_null($custom)){//$customがnullだったら
				if($type == 6){
					$no = $request->no;
				}else{
					$custom = Custom::where('type',$type)->orderBy('no', 'desc')->first();
					if(isset($custom)){
						$no = $custom->no + 1;
					}else{
						$no = 1;
					}
				}
			 	$custom = new Custom();
				$custom->init();//初期化
			 	$custom->no = $no;
				$custom->created_at = date('Y-m-d H:i:s');
			}
			//入力されたパラメータでオブジェクトを上書き
			if($request->url){
				$custom->url = $request->url;
			}
			if($request->title){
				$custom->title = $request->title;
			}
			if($request->s_name){
				$custom->s_id = $request->s_name;
			}
			if($request->type){
				$custom->type = $request->type;
			}
			$custom->updated_at = date('Y-m-d H:i:s');

			$custom->save();

			// 有料広告枠掲載画像
			if(isset($request->paid_inventory_img)){
				$file = $request->file('paid_inventory_img');
				$extension = $file->extension();
				//画像をリサイズ&トリミング&jpg変換
				$width_size = 800;
				$height_size = 800;
				Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
				$dir = 'paid_inventory_img';// 保管する場所
				$file_name = $custom->type."-".$custom->id.".".$file->extension();
				$request->file('paid_inventory_img')->storeAs('public/' . $dir, $file_name);
				$custom->extension = $extension;
				$custom->save();
			}
				return Redirect::to('paid_inventory')->send();
		}
		return view('manage/paid_inventory_detail',compact('stores','custom'));
	}
	// 有料広告削除
	public function PaidInventoryDelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		Custom::where('id',$request->c_id)->delete();
		return Redirect::to('paid_inventory')->send();
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
		Session::put('s_id',$s_id);
		
		$products = Product::where('s_id',$s_id)->whereNotIn('p_status',[9])->orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		// dd($products);
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
		$store_id = $request->s_id;
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
		}elseif($kind == 0){//adminの場合
			$s_id = $request->s_id;
		}else{// 店舗ユーザ以外の場合
			return;
		}
		$categorys = Custom::where('type',0)->whereNotIn('no',[0])->orderBy('no', 'asc')->get();//すべてをみる以外のカテゴリー
		$customs = Custom::where('type',10)->where('s_id',$s_id)->orderBy('no', 'asc')->get();//メニューカテゴリー
		$w_options = Option::where('s_id',$s_id)->orderBy('o_id', 'desc')->get();//オプション
		$options = array();
		foreach ($w_options as $w_option) {
			$options[$w_option->o_id] = $w_option->o_name;
		}
		if($request->isMethod('GET')){
			$product = Product::find($request->p_id);
			if(isset($product)){
				//オプション複数選択可
				$product->o_ids = explode(',', $product->o_ids);
				if(isset($product->o_ids[0]) && $product->o_ids[0] != ''){
					$product->o_name1 = Option::where('s_id',$product->s_id)->where('o_id',$product->o_ids[0])->first()->o_name;
				}
				if(isset($product->o_ids[1]) && $product->o_ids[1] != ''){
					$product->o_name2 = Option::where('s_id',$product->s_id)->where('o_id',$product->o_ids[1])->first()->o_name;
				}
				if(isset($product->o_ids[2]) && $product->o_ids[2] != ''){
					$product->o_name3 = Option::where('s_id',$product->s_id)->where('o_id',$product->o_ids[2])->first()->o_name;
				}
				if(isset($product->o_ids[3]) && $product->o_ids[3] != ''){
					$product->o_name4 = Option::where('s_id',$product->s_id)->where('o_id',$product->o_ids[3])->first()->o_name;
				}
				// カテゴリー複数選択可
				$product->c_id = explode(',', $product->c_id);
				if(isset($product->c_id[0]) && $product->c_id[0] != ''){
					$product->title1 = Custom::where('type',0)->where('no',$product->c_id[0])->first()->title;
				}
				if(isset($product->c_id[1]) && $product->c_id[1] != ''){
					$product->title2 = Custom::where('type',0)->where('no',$product->c_id[1])->first()->title;
				}
				if(isset($product->c_id[2]) && $product->c_id[2] != ''){
					$product->title3 = Custom::where('type',0)->where('no',$product->c_id[2])->first()->title;
				}
				if(isset($product->c_id[3]) && $product->c_id[3] != ''){
					$product->title4 = Custom::where('type',0)->where('no',$product->c_id[3])->first()->title;
				}
				// ショップ内カテゴリー複数選択可
				$product->sc_id = explode(',', $product->sc_id);
				if(isset($product->sc_id[0]) && $product->sc_id[0] != ''){
					$product->s_category1 = Custom::where('type',10)->where('s_id',$product->s_id)->where('no',$product->sc_id[0])->first()->title;
				}
				if(isset($product->sc_id[1]) && $product->sc_id[1] != ''){
					$product->s_category2 = Custom::where('type',10)->where('s_id',$product->s_id)->where('no',$product->sc_id[1])->first()->title;
				}
				if(isset($product->sc_id[2]) && $product->sc_id[2] != ''){
					$product->s_category3 = Custom::where('type',10)->where('s_id',$product->s_id)->where('no',$product->sc_id[2])->first()->title;
				}
				if(isset($product->sc_id[3]) && $product->sc_id[3] != ''){
					$product->s_category4 = Custom::where('type',10)->where('s_id',$product->s_id)->where('no',$product->sc_id[3])->first()->title;
				}
			}
		}else{//更新の場合(post)
			$product = Product::find($request->p_id);
			if(is_null($product)){
				$product = new Product();
				$product->init();//初期化
				$product->created_at = date('Y-m-d H:i:s');
			}
			//入力されたパラメータでproductオブジェクトを上書き
			if($store_id){
				$product->s_id = $s_id;
			}
			if($request->no){
				$product->c_id = implode(',', $request->no);
			}
			if($request->sc_id){
				$product->sc_id = implode(',', $request->sc_id);
			}
			if($request->o_id){
				$product->o_ids = implode(',', $request->o_id);
			}
				$product->name = $request->name ?  $request->name: '';
				$product->price = $request->price ?  $request->price: '';
				$product->note = $request->note ?  $request->note: '';
				$product->hashtag = $request->hashtag ? $request->hashtag: '';

			$product->updated_at = date('Y-m-d H:i:s');
			$product->save();
			if(isset($request->product_image)){
				$file = $request->file('product_image');
				$extension = $file->extension();
				$dir = 'public/product_image';
				$file_name = $product->id."_original.".$file->extension();//トリミング前の画像
				$file->storeAs($dir, $file_name);//保存
				$width_size = 600;//サイズを正方形に
				$height_size = 600;
                Image::make($file)->fit($width_size,$height_size,)->encode($extension)->save();
				$file_name = $product->id.".".$file->extension();//トリミング後の画像
				$file->storeAs($dir, $file_name);//保存
				$product->extension = $file->extension();
				$product->save();
			}
			return Redirect::to('product_edit/'.$store_id.'/'.$product->id)->send();
			// return back();
		}
		return view('manage/product_edit', compact('product','categorys','customs','options','store_id'));
	}
	// 商品表示・非表示
	public function ProductHidden(Request $request) {
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$product = Product::find($request->p_id);
		if($product->p_status == 1){
			$product->p_status = 0;
		}else{
			$product->p_status = 1;
		}
		$product->updated_at =  date('Y-m-d H:i:s');
		$product->save();
		// return Redirect::to('product_list/'.$s_id)->send();
		return back();
	}
	// 商品削除
	public function Productdelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 2 && Session::get('kind') != 0){ return; }// adminとstore以外の場合
		$kind = Session::get('kind');
		$s_id = Session::get('s_id');
		if($kind == 2 || $kind == 0){// 店舗ユーザの場合
			if(!$s_id){// 割り当て店舗なしはダメ
				return;
			}
			$product = Product::where('id',$request->p_id)->where('s_id',$s_id)->first();
			// dd($product);

			if($product->p_status == 0 || $product->p_status == 1){
				$product->p_status = 9;
			}
			$product->updated_at =  date('Y-m-d H:i:s');

			$product->save();

			// if(!is_countable($product)){//対象店舗に当該商品が存在しない場合
			// 	return;
			// }
		}elseif($kind == 1){// 店舗ユーザとadmin以外の場合
			return;
		}
		// Product::where('id',$request->p_id)->delete();
		return Redirect::to('product_list/'.$s_id)->send();
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
		Session::put('s_id',$s_id);

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
			if(is_null($option)){
				$option = new Option();
				$option->init();//初期化
				$option->created_at = date('Y-m-d H:i:s');
				$option->o_name = $request->o_name;
				if(isset($request->require) && strpos($request->o_name,'(必須)') === false){
						$option->o_name = $request->o_name.'(必須)';
					}else{
						$option->o_name = $request->o_name;
					}
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
			//入力されたパラメータでoptionオブジェクトを上書き
			$option->s_id = $request->s_id;
			$option->name = $request->name;
			$option->price = $request->price;
			$option->updated_at = date('Y-m-d H:i:s');
			$option->save();
			return Redirect::to('option_list/'.$s_id)->send();
		}
	}
	// オプション削除
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
		$options = Option::where('o_id',$request->o_id)->where('s_id',$s_id)->get();
		$options = count($options);
		if($options == 1) {
			$option = Option::where('id',$request->oid)->where('s_id',$s_id)->first();
			$products =Product::where('s_id',$s_id)->get();
			foreach($products as $key => $product){
				$option_ids  = explode(',', $product->o_ids);
				$option->o_id = (string) $option->o_id;
				$index = in_array($option->o_id,$option_ids);
				if($index == true){
					$delete_o_id = str_replace($option->o_id, '', $option_ids);
					$product->o_ids  = implode(',', $delete_o_id);
					$product->save();
				}
			}
		}

		Option::where('id',$request->oid)->delete();
		return Redirect::to('option_list/'.$s_id)->send();
		
	}
	// 店舗削除
	public function Storedelete(Request $request) {
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
}/* EOF */
