<?php
namespace App\Http\Controllers;
use Session, DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;
use App\Models\User;
use App\Models\Invest_history;
use Illuminate\Pagination\LengthAwarePaginator;
class AdminController extends Controller	{

	// user_list
	public function UserList(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		$query  = User::query();//queryで取得した値は配列で返ってくる
		// 絞り込み検索
		if ($request->isMethod('GET')) {
			if (isset($request->full_name)) {//ユーザー名
				$query->where('full_name', 'like', "%{$request->full_name}%");
			}
			if (isset($request->invest_status)) {//ステータス
				$query->where('invest_status', $request->invest_status);
			}
		}
		$lists = $query->where('user_status',1)
					   ->orderByRaw('t_user.updated_at desc')
					   ->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
		foreach ($lists as $key => $list) {
			if($list->updated_at){
				$date = $list->updated_at;
				$list->date_updated = date('Y/m/d H:i', strtotime($date));
			}
			if($list->invest_status == 1){
				$list->invest_status = '承認';
			}elseif($list->invest_stastus == 0){
				$list->invest_status = '未承認';
			}
		}
		return view('admin/user_list', compact('lists'));
	}

	// user_detail
	public function UserDetail(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		if ($request->isMethod('GET')) {
			$user = User::where('id',$request->user_id)->first();
			$user_id = $user->id;
			$invest_historys = Invest_history::where('user_id',$user->id)->get();
		}
		if ($request->isMethod('POST')) {
			$user = User::where('id',$request->user_id)->first();
			$user_id = $user->id;
			$user->full_name = $request->full_name ? $request->full_name: '';
			$user->user_name = $request->user_name ? $request->user_name: '';
			$user->email = $request->email ? $request->email: '';
			$user->referral_code = $request->referral_code ? $request->referral_code: '';
			$user->wallet_address = $request->wallet_address ? $request->wallet_address: '';
			$user->invest_status = $request->invest_status ? $request->invest_status: 0;
			$user->invest = $request->invest ? $request->invest: '';
			$user->balance = $request->balance ? $request->balance: '';
			$user->earning = $request->earning ? $request->earning: '';
			$user->updated_at = date('Y-m-d H:i:s');
			$user->save();
			Session::put('u_id',$user->id);
			return Redirect::to('user_detail/'.$user_id)->send();
		}
		Session::put('u_id',$user->id);
		return view('admin/user_detail',compact('user','invest_historys'));
		
	}
	//インベストヒストリー
	public function InvestHistory(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		$user = User::where('id',$request->user_id)->first();
		$user_id = $user->id;
		$invest_historys = Invest_history::where('user_id',$user_id)->get();
		if ($request->isMethod('GET')) {
			return Redirect::to('user_detail/'.$user_id)->send();
		}
		if ($request->isMethod('POST')) {
			$invest = Invest_history::find($request->id);
			if(is_null($invest)){
				$invest = new Invest_history();
				$invest->init();
				$invest->created_at = date('Y-m-d H:i:s');
			}
			$invest->user_id = $request->user_id ? $request->user_id: '';
			$invest->user_status = 1;
			$invest->plan_name = $request->plan_name ? $request->plan_name: '';
			$invest->amount = $request->amount ? $request->amount: '';
			$invest->invest_date = $request->invest_date ? $request->invest_date: '';
			$invest->mature_date = $request->mature_date ? $request->mature_date: '';
			$invest->status = $request->status ? $request->status: '';
			$invest->updated_at = date('Y-m-d H:i:s');
			$invest->created_at = date('Y-m-d H:i:s');
			$invest->save();
		}
		return Redirect::to('user_detail/'.$user_id)->send();
	}	

	// インベスト削除
	public function InvestDelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		$user = User::where('id',$request->user_id)->first();
		$user_id = $user->id;
		Invest_history::where('id',$request->invest_id)->delete();
		return Redirect::to('user_detail/'.$user_id)->send();
	}

	// ユーザー削除
	public function UserDelete(Request $request) {
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		$user = User::where('id',$request->user_id)->first();
		$user->user_status = 0;
		$user->save();
		$invest_historys = Invest_history::where('user_id',$request->user_id)->get();
		foreach($invest_historys as $invest_history) {
			$invest_history->user_status = 0;
			$invest_history->save();
		}
		return Redirect::to('user_list')->send();
	}

	// パスワード変更　
	public function Password(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// adminのみ
		$u_id = Session::get('u_id');
		$user = User::find($u_id);
		if ($request->isMethod('POST')) {//POSTの場合
			if($request->new_password !== $request->new_password2){
				return redirect('user_detail/'.$u_id)->with('flash_message', '新しいパスワードが不一致の為、再度入力してください。');
			}
				$user->password = $request->new_password;
				$user->save();
				return redirect('user_detail/'.$u_id)->with('flash_message', 'パスワードを変更しました');
		}
		return Redirect::to('user_detail/'.$u_id)->send();
	}

}