<?php
namespace App\Http\Controllers;
use Session, DateTime, Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Invest_history;
use Illuminate\Pagination\LengthAwarePaginator;
class PublicController extends Controller	{
	//top
	public function Index(Request $request){
		if($request->isMethod('GET')){
			return view('public/index');
		}else{//postの場合
			$name = $request->name;
			$email = $request->email;
			$information = $request->information;
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);
			$url	= url("/contact{$hash}");//メールで送った後に開かれるリンク
			$data	= [	   'url'          => $url,
			'name'         => $name,
			'email'        => $email,
			'information'        => $information,
		];
		// お問い合わせ主
		// dd($request);
		$to		= $email;//admin
		$subject	= "お問い合わせ受付完了メール";
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
			Redirect::to('contact')->send();
			return redirect('/')->with('flash_message', 'メールが送信できませんでした。時間を空けてからお試しください。');

			return;
		}
		// admin
		$to		= 'shimizu.andjoey@gmail.com';// 変更箇所
		$subject	= "お問い合わせがありました";
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
			return redirect('/')->with('flash_message', 'メールが送信できませんでした。時間を空けてからお試しください。');
			// return;
		}
		return redirect('/#contact')->with('flash_message', 'お問い合わせ完了いたしました。');
	}
	}
	// login
	public function Login(Request $request){
		CommonController::AccessLog();
		if($request->isMethod('GET')){
			// if(!Session::has('user_id')){//セッション無し
			// 	$last_url = url()->previous();//ログインする前に居たページのURLを取得
			// 	if($last_url == 'logout' || $last_url == 'login'){//logout/loginの場合はルートにする
			// 		$last_url = '/';
			// 	}
			// 	Session::put('last_url',$last_url);//ログインする前に居たページのURLを記憶
			// 	$alert = Session::get('alert');//alertがある場合は退避
			// 	Session::forget('alert');
				return view('public/login');
		}else {
			if(!$request->has('user_name') || !$request->has('password')){
				return redirect('/login')->with('flash_message', '入力に誤りがあります。');
			}
			$user	 = User::where('user_name',$request->user_name)->first();
			if(is_null($user) || empty($user->user_name) ){
						return redirect('/login')->with('flash_message', 'アカウントが存在しません。');
					}
			if($request->password	!= $user->password){
				return redirect('/login')->with('flash_message', 'パスワードが違います。');
			}
			//ログイン処理へ(初回ログイン)
		}
		Session::put('user_id',$user->id);//ログインしているユーザー
		Session::put('kind',$user->kind);//kind
		Log::info("Logged in!:user_id={$user->id}");
		switch ($user->kind) {
			case '0'://管理者
				$url = 'user_list';
				break;
			case '1'://ユーザー
				$url = 'dashboard';
				break;
		}
		return redirect($url);
	}

	// logout
	public function Logout(Request $request){
		$user_id	= Session::get('user_id');//ログ出力
			Log::info("Logged out!：$user_id={$user_id}");
			Session::flush();//セッションクリア
		return redirect('/')->with('flash_message', 'ログアウトされました。');
	}

	// Registration
	public function Registration(Request $request){
		CommonController::AccessLog();
		// $u_id = AccountController::SessionCheck();
		// if(is_null($u_id)){ return Redirect::to('logout')->send(); }//SessionCheck NG
		if($request->isMethod('GET')){//GETのケース	
				$alert = Session::get('alert');//alertがある場合は退避
			return view('public/registration');
		}else{// POSTのケース
				$email = $request->email;
				$user_name = $request->user_name;
				$already_user = User::where('email',$email)->orWhere('user_name',$user_name)->first();
				if(isset($already_user)){
					return redirect('registration')->with('flash_message', '既にアカウントが登録されています。');
				}
			if($request->password){//パスワードチェック
				if(!preg_match("/\A([a-zA-Z0-9.?#$%&'()*+-.,:;<=>?@[\]\/^_`{|}~]){8,24}\z/", $request->password)){//半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上100文字以下
					return redirect('registration')->with('flash_message', 'パスワードの入力形式に誤りがあります。');
				}
				if($request->password !== $request->password2){//パスワード不一致チェック
					return redirect('registration')->with('flash_message', 'パスワードが不一致の為、再度入力してください。');
				}
			}
			$email = mb_convert_kana($request->email,"na");//全角記号と数字を半角記号と数字に変換
			$user = new User();
			$user->init();
			$user->kind   = 1;
			$user->user_status   = 1;
			$user->full_name   = $request->full_name;
			$user->user_name      = $request->user_name;
			$user->email      = $email;
			$user->referral_code      = $request->referral_code ? $request->referral_code: '';
			$user->wallet_address      = $request->wallet_address ? $request->wallet_address: '';
			$user->password      = $request->password;
			$date	            = date("Y-m-d H:i:s");
			$user->hash	        = md5($user->email.$date);
			$user->updated_at = date('Y-m-d H:i:s');
			$user->created_at = date('Y-m-d H:i:s');
			$user->save();
			Session::put('user_id',$user->id);
			return Redirect::to('/dashboard')->send();
		}
	}
	// ResetPassword
	public function ResetPassword(Request $request) {
		CommonController::AccessLog();
		if($request->isMethod('GET')){//Getのケース
			return view('public/reset_password');
		}else{//Postのケース
			$email = $request->email;
			$user  = User::where('email',$email)->first();
			if(!isset($user)){//ユーザーが存在するかどうか
				Log::error("ID未登録エラー email=({$email})");
				return redirect('reset_password')->with('flash_message', '指定されたメールアドレスは登録されておりません。');
			}
			$date	= date("Y-m-d H:i:s");
			$hash	= md5($email.$date);//ハッシュ生成
			$to		= $email;
			$url	= url("/new_password/{$hash}");//メールで送った後に開かれるリンク
			// $subject	= "【】パスワード変更";
			$subject	= "パスワード変更";
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
			$user->save();
			$message = 'パスワード変更:'.$email;
			return view('public/thanks_password_email', compact('email'));
		}
	}
		/** NewPassword (Both) **/
		public function NewPassword(Request $request){
			CommonController::AccessLog();
			if($request->isMethod('GET')){
				$hash = $request->hash;
				$user = User::where('hash',$hash)->first();//ハッシュの有無
				if(!isset($user)){
					Log::error("hashエラー");
					return redirect('reset_password')->with('flash_message', 'リンクが誤っています。');
					// return;
				}
				$past_date = date("Y-m-d H:i:s",strtotime("-1 hour"));//現在の時間より1時間前
				if($past_date > $user->updated_at){
					Log::error("セッション切れエラー u_id=({$user->id})");
					return redirect('reset_password')->with('flash_message', '期限切れの為、再度送信してください。');

				}
				Session::put('user_id',$user->id);
				Session::put('kind',$user->kind);
				Session::put('hash',$hash);
				return view('public/new_password');
			}else{
				$hash = Session::get('hash');
				$user_id = Session::get('user_id');
				if(!preg_match("/\A([a-zA-Z0-9.?#$%&'()*+-.,:;<=>?@[\]\/^_`{|}~]){8,24}\z/", $request->password)){//半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上24文字以下
					Log::error("パスワードの入力形式エラー user_id=({$user_id})");
					return redirect('new_password/'.$hash)->with('flash_message', 'パスワードの入力形式に誤りがあります。');

				}
				if($request->password !== $request->password2){
					Log::error("パスワードの不一致エラー user_id=({$user_id})");
					return redirect('new_password/'.$hash)->with('flash_message', 'パスワードが不一致の為、再度入力してください。');

				}
				$user = User::find($user_id);
				$user->password = $request->password;
				$user->updated_at = date("Y-m-d H:i:s");
				$user->save();
				Session::forget('hash');
				return redirect('/login')->with('flash_message', '新しいパスワードに変更しました。');

			}
		}
}