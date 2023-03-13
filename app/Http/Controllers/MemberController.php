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
use App\Models\Invest_history;
use Illuminate\Pagination\LengthAwarePaginator;
class MemberController extends Controller	{

	// dashboard
	public function Dashboard(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		if(isset($user_id)){
		$user = User::where('id',$user_id)->first();
		$date = $user->created_at;
		$user->since = date('F-d-Y', strtotime($date));
		return view('member/dashboard', compact('user'));
		}
		else {
			return view('public/login');
		}
	}
	// invest
	public function Invest(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();

		return view('member/invest', compact('user'));
	}
	// withdraw
	public function Withdraw(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();

		return view('member/withdraw', compact('user'));
	}
	// invest_history
	public function InvestHistory(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();
		$invest_history = Invest_History::where('user_id',$user_id)->get();
		// dd($user_id);

		return view('member/invest_history', compact('user','invest_history'));
	}
	// earning_history
	public function EarningHistory(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();

		return view('member/earning_history', compact('user'));
	}
	// reference_history
	public function ReferenceHistory(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();

		return view('member/reference_history', compact('user'));
	}
	// withdraw_history
	public function WithdrawHistory(Request $request) {
		CommonController::AccessLog();
		$user_id = Session::get('user_id');
		$user = User::where('id',$user_id)->first();

		return view('member/withdraw_history', compact('user'));
	}
}