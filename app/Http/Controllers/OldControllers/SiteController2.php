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
use App\Models\Product;
use App\Models\Option;// オプション追加
use App\Models\Prefecture;
use App\Models\Type;
use App\Models\Unit;
use App\Models\Grade;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
class SiteController extends Controller	{
	/** Top page (Get) **/
	public function Top(Request $request){
		return view('public/top');
	}
	//　トップサブ　今は使ってない
	public function Topsub(Request $request){
		return view('public/topsub');
	}
	// 検索
	public function Search(Request $request){
		return view('public/search');
	}
	// 店舗
	public function Shop(Request $request){
		return view('public/shop');
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
	public function Pay(Request $request){
		return view('public/pay');
	}
	// マイページ　登録 
	public function Register(Request $request){
		return view('public/register');
	}
	// マイページ　
	public function Mypage(Request $request){
		return view('public/mypage');
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
	public function Payment(Request $request){
		return view('public/payment');
	}
	// パスワード変更　
	public function Password(Request $request){
		return view('public/password');
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
	// 注文履歴　
	public function Orderhistory(Request $request){
		return view('public/orderhistory');
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
		$datas = Product::orderBy('created_at', 'desc')->get();// 新着順に取り出す
		return view('public/store_management',compact('datas'));
	}
	// もしもし　管理ページ　
	public function Management(Request $request){
		return view('public/management');
	}
	// もしもし　店舗管理ページ　
	public function Moshidelimanagement(Request $request){
		return view('public/moshidelimanagement');
	}
	// 商品 編集　
	public function Shopedit(Request $request){
		return view('public/shop_edit');
	}
	// オプション 編集　
	public function Shopedit1(Request $request){
		$u_id = 4;
		// return view('public/product_edit', compact('u_id'));
		return view('public/shop_edit1', compact('u_id'));

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
	// もしもし商品編集
	public function Productedit(Request $request){
		// return view('public/product_edit');
		$u_id = 4;
		// $datas = Option::orderBy('created_at', 'desc') ->get();// 全投稿を新着順に取り出す
		$datas = Option::orderBy('created_at', 'desc') ->paginate(3);// 最新の投稿３つを表示
		return view('public/product_edit', compact('u_id','datas'));
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
