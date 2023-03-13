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
use Illuminate\Pagination\LengthAwarePaginator;
class UserController extends Controller	{

	// マイページ　登録 
	public function Register(Request $request){
		return view('public/register');
	}
	// マイページ　
	public function Mypage(Request $request){
		$kind = Session::get('kind');
		$u_id = Session::get('u_id');
		$user = User::find($u_id);

		return view('public/mypage',compact('user'));
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
	// // ユーザーアイコン変更　
	// public function Usericon(Request $request){
	// 	return view('public/usericon');
	// }


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
			// if($request->subname){
			// 	$user->subname = $request->subname;
			// }
			if($request->tel){
				$user->tel = $request->tel;
			}
			if($request->email){
				$user->email = $request->email;
			}
			if($request->postcode){
				$user->postcode = $request->postcode;
			}
			if($request->address){
				$user->address = $request->address;
			}
			$user->save();
		return Redirect::to('mypage')->send();
		}
		return view('public/name', compact('user'));
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
	// お客様情報一覧　
	public function UserCoupon(Request $request){
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		$coupons = explode(',', $user->coupon_stock);
		$coupons = Coupon::WhereIn('id',$coupons)->get();
		return view('public/user_coupon',compact('coupons'));
	}
	// ログイン　
	public function Login(Request $request){
		return view('public/login');
	}
}/* EOF */
