<?php
namespace App\Http\Controllers;
use Session,Log;//Facades
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;//ストレージ保存用
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Store;
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
		CommonController::AccessLog();
		$u_id = AccountController::SessionCheck();
		if(is_null($u_id)){ return Redirect::to('logout')->send(); }//SessionCheck NG
		if($request->isMethod('GET')){//GETのケース
			$hash = Session::get('hash');
			if(Session::get('kind') != 0){//adminでない
				if($u_id != $request->u_id){//当該u_idでない
					return Redirect::to('logout')->send();
				}
			}
			$user = User::find($request->u_id);
			if(is_null($user)){ return Redirect::to('logout')->send(); }
			$alert = Session::get('alert');//リダイレクトで来た場合
			Session::forget('alert');
			$user->password	= CommonController::Bicrypt($this->PROJECT_NAME,$user->password, false);
			return view('edit/edit_user', compact('alert','user','hash'));
		}else{// POSTのケース
			if(Session::get('kind') != 0){//adminでない場合
				if($u_id != $request->id){//当該u_idでない
					return Redirect::to('logout')->send();
				}
			}
			$u_id = $request->id;
			$user = User::find($request->id);
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
			if($request->postcode){
				$postcode = mb_convert_kana($request->postcode,"n");//全角記号と数字を半角記号と数字に変換
			}
			if($request->address){
				$address = mb_convert_kana($request->address,"na");//全角記号と数字を半角記号と数字に変換
			}
			$user->name      = $request->name;
			$user->email     = $request->email;
			$user->tel       = $request->tel;
			$user->postcode  = $postcode;
			$user->address   = $address;
			if($request->line_id){//任意項目
				$user->line_id = $request->line_id;
			}else{
				$user->line_id = '';
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
			if(Session::has('temp_u_id')){
				Session::put('u_id',$u_id);
				Session::forget('temp_u_id');
				return redirect::to('login')->send();
			}else{
				// return Redirect::to('user_individual/'.$user->id.'/0')->send();
				return Redirect::to('top2')->send();
			}
		}
	}

	/** AddStoreUser (Both) **/
	public function AddStoreUser(Request $request){
		CommonController::AccessLog();
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
			// return view('account/initial_email', compact('alert','kind'));
			return view('account/initial_email', compact('alert','kind'));
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
			$store = Store::where('id',$request->s_id)->first();//一致する店舗を取得
			$user->kind         = $request->kind;
			$user->s_id         = $request->s_id;
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
			// $user->updated_by   = $user->id;
			$user->save();//insertまたはupdate
			Redirect::to('search_user')->send();
			// return view('/search_user', compact('email'));
		}
		return 'hello';
	}

	


















	/** login (Both) **/
	public function Login(Request $request){
		CommonController::AccessLog();
		if($request->isMethod('GET')){
			if(!Session::has('u_id')){//セッション無し
				$last_url = url()->previous();//ログインする前に居たページのURLを取得
				if($last_url == 'logout' || $last_url == 'login'){//logout/loginの場合はルートにする
					$last_url = '/';
				}
				Session::put('last_url',$last_url);//ログインする前に居たページのURLを記憶
				$alert = Session::get('alert');//alertがある場合は退避
				Session::forget('alert');
				return view('account/login', compact('alert'));//compactはリダイレクトのalert用
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
				return view('account/login', compact('alert'));
			}
			$user	 = User::where('email',$request->email)->first();
			if(is_null($user)){// 指定したemailのアカウントなし
				$alert['class'] = 'warning';
				$alert['text']  = 'アカウントが存在しません。';
				return view('account/login', compact('alert'));
			}
			$password	= CommonController::Bicrypt($this->PROJECT_NAME,$user->password, false);
			if($request->password	!= $password){//パスワードが違います
				$alert['class'] = 'warning';
				$alert['text']  = 'パスワードが違います。';
				return view('account/login', compact('alert'));
			}
			//ログイン処理へ(初回ログイン)
		}
		Session::put('u_id',$user->id);//ログイン処理(セッション設定)
		Session::put('kind',$user->kind);//分類（バイヤー,サプライヤー,admin）
		Session::put('s_id',$user->s_id);//担当者id
		Session::put('u_mail',$user->email);//メール
		Session::put('u_name',$user->name);//担当者名
		Log::info("Logged in!:u_id={$user->id}");
		switch ($user->kind) {
			case '0'://もしもし
				// $uri = 'search_user';
				$uri = '/';
				break;
			case '1'://ユーザー
				$uri = Session::get('last_url');
				if(is_null($uri)){
					$uri = '/';
				}
				break;
			case '2'://事業者
				$store = Store::where('id',$user->s_id)->first();//商品登録が行われているかチェック
				if(is_null($store)){// 店舗登録なし
					// $uri = "/user_individual/{$user->s_id}";
					$uri = "/";
				}else{
					$uri = "/";
				}
				break;
			case '3'://配達員
				$uri = Session::get('last_url');
				if(is_null($uri)){
					$uri = '/';
				}
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
			return view('account/initial_email', compact('alert','kind'));
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
			return view('account/initial_email_thanks', compact('email'));
		}
	}
	/** InitialRegistration (Get) **/
	public function InitialRegistration(Request $request){
		CommonController::AccessLog();
		// $locale = Session::get('locale');//ロケール退避
		Session::flush();//セッションクリア
		$hash = $request->hash;
		$user = User::where('hash',$hash)->where('user_status',1)->first();//本登録済み
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
		return Redirect::to('edit_user/'.$user->id)->send();
	}
	/** ResetPassword (Get) **/
	public function ResetPassword(Request $request){
		CommonController::AccessLog();
		if($request->isMethod('GET')){//Getのケース
			$alert = Session::get('alert');
			Session::forget('alert');
			return view('account/reset_password', compact('alert'));
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
			return view('account/thanks_password_email', compact('email'));
		}
	}
	/** NewPassword (Both) **/
	public function NewPassword(Request $request){
		CommonController::AccessLog();
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
			return view('account/new_password', compact('alert'));
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