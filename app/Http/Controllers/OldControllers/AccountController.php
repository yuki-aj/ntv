<?php
namespace App\Http\Controllers;
use Session,Log;//Facades
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;//ストレージ保存用
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\LineMessengerController;
use App\Models\User;
use App\Models\Store;
use App\Models\Custom;
use App\Models\Product;
class AccountController extends Controller{
	/** SessionCheck (private) **/
	private function SessionCheck(){
		$u_id = Session::get('u_id');
		if(Session::has('temp_u_id')){
			$u_id = Session::get('temp_u_id');
		}
		$user = User::where('id',$u_id)->where('user_status','!=',9)->first();
		if(is_null($user)) {
			$u_id = NULL;
		}
		return $u_id;
	}
	
	/** EditUser (Both) **/
	public function EditUser(Request $request){
		// dd($request);
		CommonController::AccessLog();
		$u_id = AccountController::SessionCheck();
		if(is_null($u_id)){ return Redirect::to('logout')->send(); }//SessionCheck NG
		if($request->isMethod('GET')){//GETのケース
			
			$hash = Session::get('hash');
			$kind = Session::get('kind');
			if(Session::get('kind') != 0){//adminでない
				if($u_id != $request->u_id){//当該u_idでない
					return Redirect::to('logout')->send();
				}
			}
			if($request->u_id != 0){
				$user = User::find($request->u_id);
			}elseif($kind == 0 && $request->u_id == 0){
				$user   = '';
				$u_id   = $request->u_id;
				$stores = Store::where('store_status',1)->get();
				$cs = Custom::where('type',5)->get();
				$seo = array();
				foreach ($cs as  $c) {
					$seo [$c->no] = $c->title;
				}
				return view('account/edit_user', compact('user','hash','kind','u_id','stores','seo'));
			}else{
				return Redirect::to('logout')->send();
			}
			if(is_null($user)){ return Redirect::to('logout')->send(); }
			$alert = Session::get('alert');//リダイレクトで来た場合
			Session::forget('alert');
			$user->password	= CommonController::Bicrypt($this->PROJECT_NAME,$user->password, false);
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/edit_user', compact('alert','user','hash','seo'));
		}else{// POSTのケース
			if(Session::get('kind') != 0){//adminでない場合
				if($u_id != $request->id){//当該u_idでない
					return Redirect::to('logout')->send();
				}
			}
			if(empty($request->id)){//店舗ユーザー追加の場合
				$user = new User();
				$user->init();
			}else{//メール送信から自分でユーザー追加する場合
				$u_id = $request->id;
				$user = User::find($request->id);
			}
				$email = $request->email;
				$already_user = User::where('email',$email)->where('user_status',1)->first();//本登録済み。ユーザーテーブルから、emailがリクエストのmailで、user_statusが1の物を1つ取ってくる
				if(isset($already_user) && $user->s_id = 0){
					$alert['class'] = 'warning';
					$alert['text']  = '既にメールアドレスが登録されています。';
					Session::put('alert',$alert);
					Log::error("ID登録済みエラー address=({$email})");
					if(isset($request->id) && $request->id != 0){
						return Redirect::to('edit_user/'.$request->id)->send();
					}elseif($request->id == 0)
						return Redirect::to('edit_user/0')->send();
				}
			if($request->password){//パスワードチェック
				if(!preg_match("/\A([a-zA-Z0-9.?#$%&'()*+-.,:;<=>?@[\]\/^_`{|}~]){8,24}\z/", $request->password)){//半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上100文字以下
					$alert['class'] = 'warning';
					$alert['text'] = 'パスワードの入力形式に誤りがあります。';
					Session::put('alert',$alert);
					Log::error("パスワードの入力形式エラー u_id=({$u_id})");
					return Redirect::to('edit_user/'.$request->id)->send();
				}
				if($request->password !== $request->password2){//パスワード不一致チェック
					$alert['class'] = 'warning';
					$alert['text'] = 'パスワードが不一致の為、再度入力してください。';
					Session::put('alert',$alert);
					Log::error("パスワードの不一致エラー u_id=({$u_id})");
					return Redirect::to('edit_user/'.$request->id)->send();
				}
				$user->password = CommonController::Bicrypt($this->PROJECT_NAME,$request->password);
			}
			if($request->address){
				$address = mb_convert_kana($request->address,"na");//全角記号と数字を半角記号と数字に変換
			}
			if($request->postcode){
				$postcode = mb_convert_kana($request->postcode,"n");//全角記号と数字を半角記号と数字に変換
				switch ($postcode) {//多摩市以外の場合
					case '1920353':
						$d_name     = $request->name;
						$d_postcode = $postcode;
						$d_address = $address;
						$d_tel = $request->tel;
						break;
					case '1920354':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					case '1920363':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					case '1920362':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					case '1920355':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					case '1920361':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					case '1920352':
						$d_name     = $request->name;
						$d_address = $address;
						$d_postcode = $postcode;
						$d_tel = $request->tel;
						break;
					default:
						$d_name     = '';
						$d_address  = '';
						$d_postcode = '';
						$d_tel      = '';
					break;
				}
				if(preg_match('/206/',$postcode)){//多摩市の場合
						$d_name     = $request->name;
						$d_postcode = $postcode;
						$d_address = $address;
						$d_tel = $request->tel;
				}
			}
			$user->name      = $request->name;
			$user->kana      = $request->kana;
			//誕生日を月だけにして、最初の0を取る処理
			if($request->birthday){
				$birthday = explode('-',$request->birthday);
				unset($birthday[0],$birthday[2]);
				$birthday = array_merge($birthday);
				$birthday = $birthday[0];
				if(substr($birthday,0,1) == "0"){
					$birthday = ltrim($birthday,"0");
				}
				$user->birthday  = $birthday;
			}
			if($request->already_s_id){
				$user->s_id      = $request->already_s_id;
			}
			if($user->s_id == ''){
				$user->s_id      = 0;
			}
			if($user->kind == ''){
				$user->kind      = 2;
			}
			$user->corporation_flag = $request->c_flag ? $request->c_flag : 0;
			$user->email      = $request->email;
			$user->tel        = $request->tel;
			if($request->line_name){
				$user->line_name  = $request->line_name;
			}
			
			if($request->line_id){
				$user->line_id    = $request->line_id;
			}

			$user->postcode   = $postcode;
			$user->address    = $address;
			$user->d_name     = $d_name;
			$user->d_postcode = $d_postcode;
			$user->d_address  = $d_address;
			$user->d_tel      = $d_tel;
			$user->stripe_id  = '';
			if($user->kind == 3){//配達員の場合
				$user->line_id = LineMessengerController::LineLogin($user);
				if($user->line_id == false){
					$alert['class'] = 'warning';
					$alert['text'] = 'LINEユーザーが存在しません。';
					Session::put('alert',$alert);
					Log::error("LINEユーザーが存在しません。 u_id=({$u_id})");
					return Redirect::to('edit_user/'.$request->id)->send();
				}
			}elseif($request->kind == 0){//店舗ユーザー追加の場合
				if($request->s_id){
					$user->s_id         = $request->s_id;
				}
				$user->user_status  = 1;
				$date	            = date("Y-m-d H:i:s");
				$user->hash	        = md5($user->email.$date);
				$user->email_status = 1;
			}else{//一般ユーザーはline不要
				$user->line_id = '';
			}
			if($request->line_name){//任意項目
				$user->line_name = $request->line_name;
			}else{
				$user->line_name = '';
			}
			if(Session::has('hash')){
				$user->user_status = 1;
				Session::forget('hash');
			}
			$user->updated_by = $u_id;
			$user->updated_at = date("Y-m-d H:i:s");
			if(!$user->kind == 0) {
				Session::put('u_name',$user->name);//ユーザー名
			}
			$user->save();
			// dd($user);
			if(Session::has('temp_u_id')){
				Session::put('u_id',$u_id);
				Session::forget('temp_u_id');
				return redirect::to('login')->send();
			}else{
				// dd($request);
				return Redirect::to('/')->send();
			}
		}
	}

	/** AddStoreUser (Both) **/
	public function AddStoreUser(Request $request){
		CommonController::AccessLog();
		// dd($request);
		if($request->isMethod('GET')){
			$kind = $request->kind;
			if(is_null($kind)){
				return Redirect::to('logout')->send();
			}
			if( $kind !='0'){
				return Redirect::to('logout')->send();
			}
			$last_url = url()->previous();// 会員登録する前に居たページのURLを取得
			if(strpos($last_url,'initial_email')){//strpos:第1引数の文字列に、第2引数の文字列が含まれているかをチェック
				$last_url = '/';
			}
			Session::put('last_url',$last_url);// 会員登録する前に居たページのURLを記憶
			$alert = Session::get('alert');
			Session::forget('alert');
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/initial_email', compact('alert','kind','seo'));
		}else{//POSTの場合(店舗管理者追加)
			$email = $request->email;
			$user = User::where('email',$email)->where('user_status',1)->first();//本登録済み。ユーザーテーブルから、emailがリクエストのmailで、user_statusが1の物を1つ取ってくる
			if(isset($user)){
				$alert['class'] = 'warning';
				$alert['text']  = '既にIDが登録されています。';
				Session::put('alert',$alert);
				Log::error("ID登録済みエラー address=({$email})");
				Redirect::to('search_user')->send();
				return;
			}
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);
			$user = User::where('email',$email)->first();//新規登録中のユーザの場合
			if(is_null($user)){
				$user = new User();
				$user->init();
			}
			$store = Store::find($request->s_id);//一致する店舗を取得
			$user->kind         = $request->kind;
			$user->s_id         = $store->id;
			$user->user_status  = 1;
			$user->password     = CommonController::Bicrypt($this->PROJECT_NAME,$request->password);
			$user->hash         = $hash;
			$user->name         = $request->name;
			$user->email        = $email;
			$user->line_id      = $request->line_id;
			$user->email_status = 1;
			$user->tel          = $request->tel;
			$user->postcode     = $store->postcode;
			$user->address      = $store->address;
			$user->stripe_id    = '';
			$user->updated_at   = date("Y-m-d H:i:s");
			$user->created_at   = date("Y-m-d H:i:s");
			$user->save();//insertまたはupdate
			Redirect::to('search_user')->send();
		}
	}

	/** login (Both) **/
	public function Login(Request $request){
		CommonController::AccessLog();
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		if($request->isMethod('GET')){
			if(!Session::has('u_id')){//セッション無し
				$last_url = url()->previous();//ログインする前に居たページのURLを取得
				if($last_url == 'logout' || $last_url == 'login'){//logout/loginの場合はルートにする
					$last_url = '/';
				}
				Session::put('last_url',$last_url);//ログインする前に居たページのURLを記憶
				$alert = Session::get('alert');//alertがある場合は退避
				Session::forget('alert');
				return view('account/login', compact('alert','seo'));//compactはリダイレクトのalert用
			}
			$user	= User::find(Session::get('u_id'));// userデータを取得
			if(is_null($user)) {// Userデータが存在しない場合
				return Redirect::to('logout')->send();
			}
			//ログイン処理へ(セッション残)
		}else if($request->isMethod('POST')){
			if(!$request->has('email') || !$request->has('password')){//name属性指定なし
				$alert['class'] = 'warning';
				$alert['text']  = '入力に誤りがあります。';
				return view('account/login', compact('alert','seo'));
			}
			$user	 = User::where('email',$request->email)->first();
			if(is_null($user) || $user->user_status == 0){// 指定したemailのアカウントなし
				$alert['class'] = 'warning';
				$alert['text']  = 'アカウントが存在しません。';
				return view('account/login', compact('alert','seo'));
			}
			$password	= CommonController::Bicrypt($this->PROJECT_NAME,$user->password, false);
			if($request->password	!= $password){//パスワードが違います
				$alert['class'] = 'warning';
				$alert['text']  = 'パスワードが違います。';
				return view('account/login', compact('alert','seo'));
			}
			//ログイン処理へ(初回ログイン)
		}
		Session::put('u_id',$user->id);//ログイン処理(セッション設定)
		Session::put('kind',$user->kind);//分類（バイヤー,サプライヤー,admin）
		Session::put('s_id',$user->s_id);//担当者id
		Session::put('u_mail',$user->email);//メール
		Session::put('u_name',$user->name);//担当者名
		Session::put('address',$user->address);//住所
		Log::info("Logged in!:u_id={$user->id}");
		switch ($user->kind) {
			case '0'://もしもし
				$uri = 'admin_manage';
				break;
			case '1'://ユーザー
				if(Session::get('carts')){//カートの中身がある場合
					$uri = 'add_cart';
				}else{//ない場合
					$uri =  'mypage';
				}
				if(is_null($uri)){
					$uri = '/';
				}
				break;
			case '2'://事業者
				$uri = "/store_information";
				break;
			case '3'://配達員
				$uri =  'mypage';
				break;
		}
		Session::forget('last_url');
		return redirect($uri);
	}
	/** Logout (Get) **/
	public function Logout(Request $request){
		$u_id	= Session::get('u_id');//ログ出力
		if(is_null($u_id)){
			$u_id = 'none';
		}
		Log::info("Logged out!：u_id={$u_id}");
		$alert = Session::get('alert');//アラート設定
		if(is_null($alert)){
			$alert['class'] = 'warning';
			$alert['text']  = 'ログアウトされました。';
		}
		Session::flush();//セッションクリア
		$alert['class'] = 'success';
		$alert['text']  = 'ログアウトされました。';
		Session::put('alert',$alert);
		return redirect('login');
	}
	/** InitialEmail (Both) **/
	public function InitialEmail(Request $request){
		CommonController::AccessLog();
		if($request->isMethod('GET')){
			$kind = $request->kind;
			if(is_null($kind)){
				return Redirect::to('logout')->send();
			}
			if( $kind !='1' && $kind !='3'){
				return Redirect::to('logout')->send();
			}
			$last_url = url()->previous();// 会員登録する前に居たページのURLを取得
			if(strpos($last_url,'initial_email')){//strpos:第1引数の文字列に、第2引数の文字列が含まれているかをチェック
				$last_url = '/';
			}
			Session::put('last_url',$last_url);// 会員登録する前に居たページのURLを記憶
			$alert = Session::get('alert');
			Session::forget('alert');
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/initial_email', compact('alert','kind','seo'));
		}else{//postの場合
			$email = $request->email;
			$user = User::where('email',$email)->where('user_status',1)->first();//本登録済み。ユーザーテーブルから、emailがリクエストのmailで、user_statusが1の物を1つ取ってくる
			if(isset($user)){
				$alert['class'] = 'warning';
				$alert['text']  = '既にIDが登録されています。';
				Session::put('alert',$alert);
				Log::error("ID登録済みエラー address=({$email})");
				Redirect::to('login')->send();
				return;
			}
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);
			$url	= url("/initial_registration/{$hash}");
			$to		= $email;
			$subject	= "【もしもしデリバリー】会員登録確認メール";
			$data	= ['url' => $url];
			try{
				Mail::send(['text'=>'emails.initial_email_thanks'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
					function($send) use ($to,$subject){
						$send->to($to);
						$send->subject($subject);
					}
				);
			}catch(Exception $e){
				$get_message = $e->getMessage();
				Log::error("メール送信エラー address=({$to}) message={$get_message}");
				$alert['class'] = 'warning';
				$alert['text']  = 'メールが送信できませんでした。時間を空けてからお試しください。';
				Session::put('alert',$alert);
				Redirect::to('initial_email')->send();
				return;
			}
			$user = User::where('email',$email)->first();//新規登録中のユーザの場合
			if(is_null($user)){
				$user = new User();
				$user->init();
			}
			$user->hash  = $hash;
			$user->email = $email;
			$user->kind =  $request->kind;
			$user->updated_at = date("Y-m-d H:i:s");
			$user->save();//insertまたはupdate
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/initial_email_thanks', compact('email','seo'));
		}
	}
	/** InitialRegistration (Get) **/
	public function InitialRegistration(Request $request){
		CommonController::AccessLog();
		// $locale = Session::get('locale');//ロケール退避
		$carts = Session::get('carts');//カートの商品退避
		Session::flush();//セッションクリア
		$hash = $request->hash;
		$user = User::where('hash',$hash)->where('user_status',1)->first();//本登録済み
		// dd($user);
		if(isset($user)){
			$alert['class'] = 'warning';
			$alert['text'] = '既にIDが登録されています。';
			Session::put('alert',$alert);
			Log::error("ID登録済みエラー u_id=({$user->id})");
			return Redirect::to('logout')->send();
		}
		$user = User::where('hash',$hash)->first();
		if(is_null($user)){//ハッシュの有無
			$alert['class'] = 'warning';
			$alert['text'] = 'リンクが誤っています。';
			Session::put('alert',$alert);
			Log::error("hashエラー");
			return Redirect::to('logout')->send();
		}
		$past_date = date("Y-m-d H:i:s",strtotime("-1 hour"));//ハッシュ期限チェック
		if($past_date > $user->updated_at){
			User::find($user->id)->delete();
			$alert['class'] = 'warning';
			$alert['text'] = '期限切れの為、再度登録してください。';
			Session::put('alert',$alert);
			// dd($request);
			Log::error("セッション切れエラー u_id=({$user->id})");
			return Redirect::to('initial_email/'.$user->kind)->send();
		}
		$user->email_status = 1;//メール疎通OK
		$user->updated_at = date("Y-m-d H:i:s");
		$user->save();//update
		Session::put('temp_u_id',$user->id);
		Session::put('kind',$user->kind);
		Session::put('hash',$hash);
		// Session::put('locale',$locale);//最初に退避していたロケールをput
		Session::put('carts',$carts);//最初に退避していたカートの商品をput
		return Redirect::to('edit_user/'.$user->id)->send();
	}
	/** ResetPassword (Get) **/
	public function ResetPassword(Request $request){
		CommonController::AccessLog();
		if($request->isMethod('GET')){//Getのケース
			$alert = Session::get('alert');
			Session::forget('alert');
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/reset_password', compact('alert','seo'));
		}else{//Postのケース
			$email = $request->email;
			$user  = User::where('email',$email)
			->where('user_status',1)
			->where('email_status',1)
			->first();
			if(!isset($user)){//ユーザーが存在するかどうか
				$alert['class'] = 'warning';
				$alert['text']  = '指定されたIDは登録されておりません。';
				Session::put('alert',$alert);
				Log::error("ID未登録エラー email=({$email})");
				Redirect::to('reset_password')->send();
				return;
			}
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);//ハッシュ生成
			$to		= $email;
			$url	= url("/new_password/{$hash}");//メールで送った後に開かれるリンク
			$subject	= "【もしもしデリバリー】パスワード変更";
			$data	= [ 'url'	=> $url ];
			try{
				//メールを飛ばす処理
				Mail::send(['text'=>'emails.thanks_password_email'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
					function($message) use ($to,$subject){
						$message->to($to);
						$message->subject($subject);
					}
				);
			}catch(Exception $e){
				$message = $e->getMessage();
				Log::error("メール送信エラー address=({$to}) message={$message}");
				$alert['class'] = 'warning';
				$alert['text'] = 'メールが送信できませんでした。時間を空けてからお試しください。';
				Session::put('alert',$alert);
				Redirect::to('reset_password')->send();
				return;
			}
			$user->hash = $hash;
			$user->updated_at = date("Y-m-d H:i:s");
			$user->updated_by = $user->id;
			$user->save();
			$message = 'パスワード変更:'.$email;
			
			$cs = Custom::where('type',5)->get();
			$seo = array();
			foreach ($cs as  $c) {
				$seo [$c->no] = $c->title;
			}
			return view('account/thanks_password_email', compact('email','seo'));
		}
	}
	/** NewPassword (Both) **/
	public function NewPassword(Request $request){
		CommonController::AccessLog();
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		if($request->isMethod('GET')){
			$alert = Session::get('alert');
			Session::forget('alert');
			$hash = $request->hash;
			$user = User::where('hash',$hash)->first();//ハッシュの有無
			if(!isset($user)){
				$alert['class'] = 'warning';//class
				$alert['text'] = 'リンクが誤っています。';
				Session::put('alert',$alert);
				Log::error("hashエラー");
				Redirect::to('reset_password')->send();
				return;
			}
			$past_date = date("Y-m-d H:i:s",strtotime("-1 hour"));//現在の時間より1時間前
			if($past_date > $user->updated_at){
				$alert['class'] = 'warning';//class
				$alert['text'] = '期限切れの為、再度送信してください。';
				Session::put('alert',$alert);
				Log::error("セッション切れエラー u_id=({$user->id})");
				Redirect::to('reset_password')->send();
				return;
			}
			Session::put('u_id',$user->id);
			Session::put('kind',$user->kind);
			Session::put('hash',$hash);
			return view('account/new_password', compact('alert','seo'));
		}else{
			$hash = Session::get('hash');
			$u_id = Session::get('u_id');
			if(!preg_match("/\A([a-zA-Z0-9.?#$%&'()*+-.,:;<=>?@[\]\/^_`{|}~]){8,24}\z/", $request->password)){//半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上24文字以下
				$alert['class'] = 'warning';
				$alert['text'] = 'パスワードの入力形式に誤りがあります。';
				Session::put('alert',$alert);
				Log::error("パスワードの入力形式エラー u_id=({$u_id})");
				Redirect::to('new_password/'.$hash)->send();
				return;
			}
			if($request->password !== $request->password2){
				$alert['class'] = 'warning';
				$alert['text'] = 'パスワードが不一致の為、再度入力してください。';
				Session::put('alert',$alert);
				Log::error("パスワードの不一致エラー u_id=({$u_id})");
				Redirect::to('new_password/'.$hash)->send();
				return;
			}
			$user = User::find($u_id);
			$user->password = CommonController::Bicrypt($this->PROJECT_NAME,$request->password);
			$user->updated_at = date("Y-m-d H:i:s");
			$user->updated_by = $user->id;
			$user->save();
			$alert['class'] = 'success';
			$alert['text'] = '新しいパスワードに変更しました。';
			Session::put('change_info',$alert);
			Session::forget('hash');
			Redirect::to('/')->send();
			return;
		}
	}
}/* EOF */