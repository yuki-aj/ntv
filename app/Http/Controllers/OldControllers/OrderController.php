<?php
namespace App\Http\Controllers;
use Session,App,Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use App\Http\Controllers\Controller;
use App\Http\Controllers\LineMessengerController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Option;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Coupon;
use App\Models\Custom;
use App\Models\Category;
class OrderController extends Controller{
	private function SessionCheck(){
		$kind = Session::get('kind');
		if ($kind==0) {
			return true;
		 }
		return false;
	}
//もしもし管理
	public function SearchUser(Request $request){
		$result = OrderController::SessionCheck();
		if(!$result){
			return Redirect::to('/logout')->send();
		}
		$stores = Store::get();
		$query  = User::query();//queryで取得した値は配列で返ってくる
		if ($request->isMethod('POST')) {//POSTの場合
			if ($request->has('u_id') && $request->has('u_status')) {//有効無効切替
				$user = User::where('id',$request->u_id)->first();
				if($request->u_status == 0){
					$user->user_status = 1;
				}else{
					$user->user_status = 0;
				}
				$user->save();
			}
		}
		if ($request->isMethod('GET')) {//GET(検索条件あり)のケース
			if (isset($request->name)) {//ユーザー名
				$query->where('kana', 'like', "%{$request->name}%")
					  ->orWhere('name', 'like', "%{$request->name}%");
			}
			if (isset($request->email)) {
				$query->where('email', 'like', "%{$request->email}%");
			}
			if (isset($request->kind)) {
				$query->where('kind', $request->kind);
			}
			if (isset($request->birthday)) {
				$query->where('birthday', $request->birthday);
			}
			if (isset($request->from)) {
				$from = $request->from.' 00:00:00';
				$query->where('created_at','>=', $from);
			}
			if (isset($request->to)) {
				$to = $request->to.' 23:59:59';
				$query->where('created_at','<=', $to);
			}
			if (isset($request->user_status)) {
				$query->where('user_status', $request->user_status);
			}
		}
		$lists = $query->where('kind','!=',0)
					   ->orderByRaw('t_user.updated_at desc')
					   ->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
		foreach ($lists as $key => $list) {
			if($list->s_id != 0){
				$list->s_name = Store::where('id',$list->s_id)->first()->name;
			}
			if($list->updated_at){
				$date = $list->updated_at;
				$list->date_updated = date('Y/m/d H:i', strtotime($date));
			}
			// if($list->kind == 1){
			// 	if($list->corporation_flag == 1){
			// 		$list->kind = '個';
			// 	}elseif($list->corporation_flag == 2){
			// 		$list->kind = '業';
			// 	}elseif($list->corporation_flag == 3){
			// 		$list->kind = 'も';
			// 	}
			// }elseif($list->kind == 2){
			// 	$list->kind = '店';
			// }elseif($list->kind == 3){
			// 	$list->kind = '配';	
			// }	
		}
		
		// csv
		// $lists[$i] = array(
			// 	'email' => $email,
			// 	'name' => $name,
			// 	'kana' => $kana,
			// 	'tel' => $tel,
			// 	'birthday' => $birthday,
			// 	'postcode' => $postcode,
			// 	'address' => $address,
			// );
			// ++$i;

			// $fileName = 'label_'.'.csv';
			// // HTTPヘッダ
			// header("Content-Type: application/octet-stream");
			// header("Content-Disposition: attachment; filename=\"{$fileName}\"");
			// // 書き込み用ファイルを開く
			// $f = fopen('php://output', 'w');
			// if ($f) {
			// 	// データの書き込み
			// 	foreach ($lists as $list) {
			// 		mb_convert_variables('sjis-win', 'UTF-8', $list);
			// 		fputcsv($f, $list);
			// 	}
			// }
			// // ファイルを閉じる
			// fclose($f);
			// 	return;
		// }
		return view('admin/search_user', compact('lists','request','stores'));
	}

	public function OrderSearch(Request $request){//もしもし注文管理画面
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$result = OrderController::SessionCheck();//セッションチェック
		if(!$result){
			return Redirect::to('/logout')->send();
		}//管理者じゃなかったらログアウト
		$query = Order::query();
		$order_details = Order_detail::get();
		$query = Order::join('t_store', 't_store.id', '=', 't_order.s_id');
		$query->select('t_store.name as name','t_order.id as id','o_id','c_flag','s_id','u_name','nominate_list','d_staff_id','s_hash','hash','u_id','order_flag','corporation_flag','delivery_date','delivery_time','catch_time','d_postcode','d_address','d_tel','d_name','o_address','o_tel','t_order.note as note','memo','status_time','t_order.updated_at as updated_at','t_order.created_at as created_at');

		if ($request->isMethod('GET')) {//GET(検索条件あり)のケース
			if (isset($request->u_name)) {
				$query->where('u_name', 'like', "%{$request->u_name}%");
			}
			if (isset($request->name)) {//店舗名
				$query->where('name', 'like', "%{$request->name}%");
			}
			if (isset($request->o_id)) {
				$query->where('o_id', 'like', "%{$request->o_id}%");
			}
			if (isset($request->from)) {
					$from = $request->from.' 00:00:00';
					$query->where('t_order.created_at','>=', $from);
			}
			if (isset($request->to)) {
					$to = $request->to.' 23:59:59';
					$query->where('t_order.created_at','<=', $to);
			}
			if (isset($request->corporation_flag)) {
					$query->where('corporation_flag', $request->corporation_flag);
			}
			if (isset($request->order_flag)) {
					$query->where('order_flag', $request->order_flag);
			}
			if ($request->has('c_flag')) {
				if($request->c_flag == 1){
					$query->whereIn('c_flag', [01,11,21]);
				}
				if($request->c_flag == 2){
					$query->whereIn('c_flag', [10,20]);
				}
			}
			if ($request->has('pay_kind')) {
				if($request->pay_kind == 1){
					$query->where('c_flag', 01);
				}
				if($request->pay_kind == 2){
					$query->whereIn('c_flag', [10,21]);
				}
				if($request->pay_kind == 3){
					$query->whereIn('c_flag', [20,21]);
				}
			}
			if ($request->has('delivery_date')) {
				if($request->delivery_date == 1){
					$lists = $query->orderByRaw('delivery_date desc')->paginate($this->COUNT_PAR_PAGE);
				}elseif($request->delivery_date == 2){
						$lists = $query->orderByRaw('delivery_date asc')->paginate($this->COUNT_PAR_PAGE);
				}else{
						$lists = $query->orderByRaw('updated_at desc')->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
				}
			}
		}
		
		if ($request->isMethod('POST')) {//POST
			$order = Order::where('o_id',$request->o_id)->first();
			if($request->has('c_flag') && $request->c_flag == 1){
				if($order->c_flag == 10){//支払い未了から変更の場合
					$order->c_flag = 11;
				}elseif($order->c_flag == 20){
					$order->c_flag = 21;
				}
				$order->save();
			}elseif($request->has('c_flag') && $request->c_flag == 2){
				if($order->c_flag == 11){//支払い完了から変更の場合
					$order->c_flag = 10;
				}elseif($order->c_flag == 21){
					$order->c_flag = 20;
				}
				$order->save();
			}
			if (isset($request->order_flag)) {
				$order->order_flag = $request->order_flag;
			}
			if (isset($request->catch_time)) {
				$order = Order::where('o_id',$request->order_id)->first();
				$order->catch_time = $request->catch_time;
				$order->save();
			}
			$today = date('Y-m-d H:i');
			$status_time = explode(',', $order->status_time);
			if($request->has('order_flag')){
				if($request->order_flag == 5){
					$status_time[1] = $today;
				}elseif($request->order_flag == 6){
					$status_time[2] = $today;
				}
				if($request->order_flag == 6 && $order->c_flag == 10){
					$order->c_flag = 11;
				}elseif($request->order_flag == 6 && $order->c_flag == 20){
					$order->c_flag = 21;
				}
				$order->status_time = implode(',', $status_time);
				$order->save();
			}
			if($request->has('order_flag')){
				$details = Order_detail::where('order_id',$request->o_id)->get();
				if($request->order_flag == 7){//キャンセルの場合
					foreach ($details as $key => $detail) {
						$details[$key]->order_flag = 2;//キャンセルにする
						$details[$key]->save();
					}
				}else{
					foreach ($details as $key => $detail) {
						$details[$key]->order_flag = 1;
						$details[$key]->save();
					}
				}
			}
		}
		$d_staff  		 = User::where('kind',3)->get();
		$products      = Product::get();
		$orders = Order::get();
		$w_coupon = array('A','P','S');
		$work_order_details = Order_detail::whereIn('product_id',$w_coupon)->get();

		$order_detail_s = array();
		foreach ($work_order_details as $key => $work_order_detail) {
				$order_detail_s[$work_order_detail->order_id] = $work_order_detail;
		}
		$w_coupons = Coupon::get();
		$coupons = array();
		foreach ($w_coupons as $key => $w_coupon){
			$coupons[$w_coupon->id] = $w_coupon;
		}
		foreach ($orders as $key => $order) {
			if(isset($order_detail_s[$order->o_id])){
				$orders[$key]->title = $coupons[$order_detail_s[$order->o_id]->option_1]->title;
				$orders[$key]->discount = $order_detail_s[$order->o_id]->price;
			}else{
				$orders[$key]->title = '';
				$orders[$key]->discount = '';
			}
		}
		$lists = $query->orderByRaw('t_order.updated_at desc')->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
		foreach ($lists as $key => $list) {
			$store         = Store::find($list->s_id);
			$list->status_time = explode(',', $list->status_time);
			$list->new_o_id = substr($list->o_id, -5);
			$list->note = explode(',', $list->note);
			$total = 0;
			$t_quantity = 0;
			if(isset($list->d_staff_id) && $list->d_staff_id != 0){
				foreach($d_staff as $staff){
					if($list->d_staff_id == $staff->id){
						$list->d_staff_id = $staff->name;
					}
				}
			}else{
				$list->d_staff_id = '';
			}
			foreach ($order_details as $key2 => $order_detail) {
				if ($list->o_id == $order_detail->order_id) {
					$lists[$key]['p_detail'] = $order_detail;
					foreach ($products as $key4 => $product) {
						if($product->id == $order_detail->product_id && $order_detail->quantity != 0){
							$order_detail->p_name = $product->name;
							$order_detail->p_price = $product->price;
						}
					}
					$p_o_id = Product::where('id',$order_detail->product_id)->first();
					if($p_o_id != ''){
						$o_ids  = explode(',', $p_o_id->o_ids);
						$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
						foreach ($options as $option) {
							if($option->id == $order_detail->option_1){
								$order_detail->o_1_name  = $option->o_name;
								$order_detail->o_1_note  = $option->name;

								$order_detail->o_1_price = $option->price;
							}
							if($option->id == $order_detail->option_2){
								$order_detail->o_2_name  = $option->o_name;
								$order_detail->o_2_note  = $option->name;
								$order_detail->o_2_price = $option->price;
							}
							if($option->id == $order_detail->option_3){
								$order_detail->o_3_name  = $option->o_name;
								$order_detail->o_3_note  = $option->name;
								$order_detail->o_3_price = $option->price;
							}
							if($option->id == $order_detail->option_4){
								$order_detail->o_4_name  = $option->o_name;
								$order_detail->o_4_note  = $option->name;
								$order_detail->o_4_price = $option->price;
							}
						}
					}

					$lists[$key]['p_detail']['subtotal'] = (($order_detail->p_price + $order_detail->o_1_price + $order_detail->o_2_price + $order_detail->o_3_price + $order_detail->o_4_price) * $order_detail->quantity);
					if($order_detail->product_id == 16){//送料
						$lists[$key]['p_detail']['postage'] = $order_detail->price;
					}
					$list->subtotal == $lists[$key]['p_detail']['subtotal'];
					$list->postage == $lists[$key]['p_detail']['postage'];
				}
			}	
			$list->d_address = str_replace('東京都','',$list->d_address);
			if($list->c_flag == 01){
				$list->charge = '完　クレカ';
			}elseif($list->c_flag == 10){
				$list->charge = '未　auPAY';
			}elseif($list->c_flag == 11){
				$list->charge = '完　auPAY';
			}elseif($list->c_flag == 20){
				$list->charge = '未　代引';
			}elseif($list->c_flag == 21){
				$list->charge = '完　代引';
			}
			if($list->corporation_flag == 1){
				$list->kind = '個';
			}elseif($list->corporation_flag == 2){
				$list->kind = '業';
			}elseif($list->corporation_flag == 3){
				$list->kind = 'も';
			}
			if($list->order_flag == 1){
					$list->detail = '注文完了';
			}elseif($list->order_flag == 2){
					$list->detail = '注文確定';
			}elseif($list->order_flag == 3){
					$list->detail = '配達員選定中';
			}elseif($list->order_flag == 4){
					$list->detail = '配達員決定';
			}elseif($list->order_flag == 5){
					$list->detail = '引取完了';
			}elseif($list->order_flag == 6){
					$list->detail = '配送完了';
			}elseif($list->order_flag == 7){
					$list->detail = 'キャンセル';
			}
			if($list->s_id){
				$list->s_id = $store->name;
			}
			if($list->created_at){
				$date = $list->created_at;
				$list->created_a_t = date('Y/m/d H:i', strtotime($date));
			}
			foreach ($orders as $key => $order) {
				if($list->o_id == $order->o_id){
					$list->coupon_title = $order->title;
					$list->coupon_discount = $order->discount;
				}
			}
		}

        return view('order/order_search',compact('lists','request','order_details','d_staff','coupons','orders','work_order_details'));
    }
	public function AdminMemo(Request $request){//管理メモ
		$kind = Session::get('kind');
		$order = Order::where('o_id',$request->o_id)->first();
		$order->memo = $request->memo ? $request->memo: '';
		$order->updated_at = date('Y-m-d H:i:s');
		$order->save();
		return Redirect::to('order_search')->send();
	}

	public function OrderDetail(Request $request){//もしもし注文個別管理画面
		if( !Session::has('kind') || Session::get('kind') != 0){ return; }// admin以外の場合
		$d_staffs = [];
		$coupon = '';
		$order = Order::where('o_id',$request->o_id)->first();
		$status_time = explode(',', $order->status_time);
		$today = date('Y-m-d H:i');
		$order_details = Order_detail::where('order_id',$request->o_id)->get();
		$delivery_staff = User::where('kind',3)->get();//ユーザーテーブルから配達員を取得
		if($request->has('order_flag')){
			$order->order_flag = $request->order_flag;
			if($request->order_flag == 5){
				$status_time[1] = $today;
			}elseif($request->order_flag == 6){
				$status_time[2] = $today;
			}
			if($request->order_flag == 6 && $order->c_flag == 10){
				$order->c_flag = 11;
			}elseif($request->order_flag == 6 && $order->c_flag == 20){
				$order->c_flag = 21;
			}
			$order->status_time = implode(',', $status_time);
			$order->save();
		}
		if(isset($request->o_id)){
			$order->new_o_id = substr($order->o_id, -5);
		}
		if($order->order_flag == 1){
			$order->detail = '注文完了';
		}elseif($order->order_flag == 2){
			$order->detail = '注文確定';
		}elseif($order->order_flag == 3){
			$order->detail = '配達員選定';
		}elseif($order->order_flag == 4){
			$order->detail = '配達員決定';
		}elseif($order->order_flag == 5){
			$order->detail = '受取完了';
		}elseif($order->order_flag == 6){
			$order->detail = '配送完了';
		}elseif($order->order_flag == 7){
			$order->detail = 'キャンセル';
		}
		if($order->corporation_flag == 1){
			$order->kind = '個人';
		}elseif($order->corporation_flag == 2){
			$order->kind = '法人';
		}elseif($order->corporation_flag == 3){
			$order->kind = 'もしもし';
		}
		$total = 0;
		$t_quantity = 0;
		$coupon = '';
		$store         = Store::find($order->s_id);
		$products      = Product::where('p_status','1')->get();
		if($request->has('order_flag') && $request->order_flag == 7){//キャンセルの場合
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 2;//キャンセルにする
				$order_details[$key]->save();
			}
		}elseif($request->has('order_flag') && $request->order_flag != 7){
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 1;//有効にする
				$order_details[$key]->save();
			}
		}
		foreach($order_details as $key => $order_detail){
			if($order_detail->product_id == 'S' || $order_detail->product_id == 'P' || $order_detail->product_id == 'A'){
				$coupon = Coupon::find($order_detail->option_1);
			}
			if($order_detail->order_flag == 1){
					$order_detail->detail = '注文完了';
			}elseif($order_detail->order_flag == 2){
					$order_detail->detail = 'キャンセル';
			}
			$order_detail->s_name = $store->name;
			$order_detail->address = $store->address;
			$order_detail->tel = $store->tel;
			foreach ($products as $key => $product) {
				if($product->id == $order_detail->product_id && $order_detail->quantity != 0){
					$order_detail->p_name = $product->name;
					$order_detail->p_price = $product->price;
				}
			}
			$p_o_id = Product::where('id',$order_detail->product_id)->first();
			if($p_o_id != ''){
				$o_ids  = explode(',', $p_o_id->o_ids);
				$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
				foreach ($options as $key => $option) {
					if($option->id == $order_detail->option_1){
						$order_detail->o_1_name  = $option->o_name;
						$order_detail->o_1_note  = $option->name;
						$order_detail->o_1_price = $option->price;
					}
					if($option->id == $order_detail->option_2){
						$order_detail->o_2_name  = $option->o_name;
						$order_detail->o_2_note  = $option->name;
						$order_detail->o_2_price = $option->price;
					}
					if($option->id == $order_detail->option_3){
						$order_detail->o_3_name  = $option->o_name;
						$order_detail->o_3_note  = $option->name;
						$order_detail->o_3_price = $option->price;
					}
					if($option->id == $order_detail->option_4){
						$order_detail->o_4_name  = $option->o_name;
						$order_detail->o_4_note  = $option->name;
						$order_detail->o_4_price = $option->price;
					}
				}
			}
			if($coupon != ''&& $coupon->id == $order_detail->option_1){//クーポンがある場合
				if($order_detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
					$total += $order_detail->price;
				}
			}else{
				$total += $order_detail->price;
			}
			if($order_detail->s_id != 0 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S'){//個数計算(商品のみ)
				$t_quantity += $order_detail->quantity;
			}
		}
		if(isset($request->order_id)){
			$detail = Order_detail::where('id',$request->order_id)->first();
			$detail->order_flag = $request->status;
			$detail->save();
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('order/order_detail',compact('status_time','coupon','order','order_details','total','t_quantity','delivery_staff','d_staffs','seo'));
	}
	public function StaffList(Request $request,$id){//配達員選択
		$staff_lists = User::where('kind',3)->where('user_status',1)->get();//ユーザーテーブルから配達員を取得
		$order = Order::where('id',$id)->first();//オーダー1件
		$msg = '';
		if($request->isMethod('POST')){//POSTの場合
			if(isset($request->staff_id)){//チェックがついた配達員リストが送られてきた場合
				$order->nominate_list = implode(',',$request->staff_id);//カンマ区切りで$order->nominate_listに保存
				$order->order_flag = 3;//配達員選定中
				// dd($order->order_flag);
				$order->save();//セーブ
				LineMessengerController::DeliveryList($order);//選んだ配達員にLine送信
				$msg = "配達員からの返信をお待ちください。";
				if(isset($order->d_staff_id)){//配達者が決まっていてキャンセルする場合
					$nominate_list = explode(',',$order->nominate_list);//配列に直す
					if(in_array($order->d_staff_id,$nominate_list) == false){//$nominate_listの中に配達指定者がいなかったら
						$order->d_staff_id = '0';//未設定状態に戻す
						$order->order_flag = 3;//配達員選定中
						$order->save();//セーブ
					}
				}
			}
			if(isset($request->d_staff_id)){//配達者が選択されて送信された場合
				// dd($request->d_staff_id);
				$nominate_list = explode(',',$order->nominate_list);//配列に直す
				foreach ($staff_lists as $key => $staff) {//配達員リストを回す
					foreach($nominate_list as $key2 => $list){
						if($staff->id == $list){//ノミネートリストと一致したら入る
							$staff_lists[$key]['checked'] = 'checked';//チェックを付ける
						}
					}
					if($staff->id == $request->d_staff_id){//ラジオボタンの番号と、配達員idが一致したら
						$order->d_staff_id = $request->d_staff_id;//$order->d_staff_idにラジオボタンの番号を入れる
						$order->order_flag = 4;//オーダーフラグを配達員決定状態にする
						$order->save();//セーブ
						$staff_lists[$key]['radiochecked'] = 'checked';//チェックを付ける
						$msg = "配達員が.$staff->name.さんに決定しました。";
						break;
					}
				}
				$order->save();
				LineMessengerController::DeliveryPost($order);//選んだ配達員にLine送信
			}
		}
		if(isset($order->nominate_list)){//$order->nominate_listがあったら入る
			$nominate_list = explode(',',$order->nominate_list);//配列に直す
			foreach ($staff_lists as $key => $staff) {
				foreach($nominate_list as $key2 => $list){
					if($staff->id == $list){
						$staff_lists[$key]['checked'] = 'checked';
					}
				}
				if(isset($order->d_staff_id) && $staff->id == $order->d_staff_id){
					$staff_lists[$key]['radiochecked'] = 'checked';//チェックを付ける
					$msg = "配達員が{$staff->name}さんに決定しました";
				}
			}
		}
		if($request->isMethod('GET')){//GETの場合
			if($order->order_flag != 3 && $order->order_flag != 4){
				foreach ($staff_lists as $key => $staff) {//配達員リストを回す
						unset($staff_lists[$key]['checked']);//チェックを外す
						unset($staff_lists[$key]['radiochecked']);//チェックを外す
				}
				$msg = "";
				$order->d_staff_id = 0;
			}
		}
		$order->save();
		return view('order/staff_list',compact('staff_lists','id','msg','order'));
	}
	public function AddCoupon(Request $request){//クーポン追加
		$msg     = '';
		$date = date('Y-m-d');
		$coupons = Coupon::where('from_date','<=',$date)->where('to_date','>=',$date)->get();
		if($request->isMethod('GET') && $request->c_hash){//GETの場合
			$u_id = Session::get('u_id');
			if($u_id == ''){return redirect()->to('/');};
			$user        = User::find($u_id);
			$new_copupon = '';
			foreach($coupons as $key => $coupon){
				$coupons[$key]->coupon_hash = md5('mdl-'.$user->id.$coupon->id);
				if($coupons[$key]->coupon_hash == $request->c_hash){
					$new_copupon = $coupons[$key]->id;
					$c_ids = explode(',', $user->coupon_stock);
					if(in_array($coupon->id,$c_ids,true)){//追加するクーポンを既に持っていないかチェック
					}else{//持っていなければ追加
						array_unshift($c_ids,$coupon->id);//先頭に追加
						$user->coupon_stock = implode(',', $c_ids);
						if(substr($user->coupon_stock,0,1) == ','){
							$user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
						}elseif (substr($user->coupon_stock,-1) == ',') {
							$user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
						}
						$user->save();
					}
					$msg = $coupon->title.'を追加しました。';
					session::flash('flash_message',$msg);
					return redirect()->to('/user_coupon');
				}
			}
		}
		if($request->isMethod('GET') || $request->u_ids == ''){//GETの場合
			return redirect()->to('/search_user');
		}
		$msg     = '';
		$coupons = Coupon::where('from_date','<=',$date)->where('to_date','>=',$date)->get();
		$u_ids   = $request->u_ids;
		$users   = array();
		foreach ($u_ids as $key => $u_id) {
			$users[$key]   = User::where('id',$u_id)->first();
		}
		if($request->coupon_id){//クーポンIDがあったら入る
			$c_title = Coupon::where('id',$request->coupon_id)->first()->title;


			foreach ($users as $key => $user) {//チェックされたユーザーを回す
			// 使っているクーポンを見た後に持っているクーポンを見て両方ともなければ追加する
				$used = explode(',', $user->coupon_used);//既に使っているクーポンを配列に
				if(in_array($request->coupon_id,$used,true)){//追加するクーポンを既に使っていないかチェック
				}else{//既に使っていなければこっち
					$stock = explode(',', $user->coupon_stock);//既に持っているクーポンを配列に
					if(in_array($request->coupon_id,$stock,true)){//追加するクーポンを既に持っていないかチェック
					}else{//使っているクーポンも持っているクーポンもなければこっち
						array_push($stock,$request->coupon_id);
						$user->coupon_stock = implode(',', $stock);
						if(substr($user->coupon_stock,0,1) == ','){
							$user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
						}elseif (substr($user->coupon_stock,-1) == ',') {
							$user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
						}
					}
				}
				$user->save();
			}
			$msg = $c_title.'を付与しました。';
    		session::flash('flash_message',$msg);
			return redirect()->to('/search_user');
		}
		return view('order/add_coupon',compact('coupons','u_ids','users','msg'));
	}

	
	// // CSVダウンロード
	// public function Csv(Request $request){
	// 	$uid  = OrderController::SessionCheck();
	// 	// $s_id = $request->s_id;
	// 	$users = User::orderByRaw('id asc')->get();
	// 	$datas = array();
	// 	$i = 0;
	// 	foreach ($users as $key => $user) {//usersを配列にする
	// 		$datas[$i] = array(
	// 			'name'        => $user->name,
	// 			'kana'        => $user->kana,
	// 			'email'       => $user->email,
	// 			'tel'         => $user->tel,
	// 			'birthday'    => $user->birthday,
	// 			'postcode'    => $user->postcode,
	// 			'address'      => $user->address,
	// 		);
	// 		++$i;
	// 	}
	// 	// $datas = array_values($datas);//歯抜け配列を詰める
	// 	$date	= date("Y-m-d");// 今日の日付
	// 	$fileName = $date.'.csv';//保存するファイル名

	// 	//HTTPヘッダ
	// 	header("Content-Type: application/octet-stream");//バイナリファイル
	// 	header("Content-Disposition: attachment; filename=\"{$fileName}\"");//csvファイル
	// 	//Content-Disposition: attachment;でダウンロードするものと表記

	// 	$head = ['name','kana','email','tel','birthday','postcode','address'];//カラム作成
	// 	$f = fopen('php://output', 'w');//書き込み用ファイルを開く
	// 	if ($f) {
	// 		mb_convert_variables('sjis-win', 'UTF-8', $head);//先に項目を出力する
	// 		fputcsv($f, $head);
	// 		foreach ($datas as $data) {//データの書き込み
	// 			mb_convert_variables('sjis-win', 'UTF-8', $data);
	// 			fputcsv($f, $data);
	// 		}
	// 	}
	// 	fclose($f);//ファイルを閉じる
	// 	return;//header("Content-Disposition: attachment;);の部分によりページ遷移はしない
	// }

	//店舗管理
	public function ReceiveShop(Request $request){//店舗側注文受付完了画面
		$s_hash = $request->s_hash;//リンクをクリックしたらs_hash発行
		if(isset($s_hash)){
			$order = Order::where('s_hash',$s_hash)->first();
			return view('order/receive_shop',compact('order'));
		}
		if($request->id){
			$order = Order::find($request->id);
			if($request->order_flag && $request->order_flag == 1){
				$order->order_flag = 1;
			}elseif($request->order_flag && $request->order_flag == 2){
				$order->order_flag = 2;
			}
			$order->save();
			return view('order/receive_shop',compact('order'));
		}
	}
	//配達員の商品配達管理画面
	public function ManageProducts(Request $request){
		$msg         = '';
		$status_time = [];
		if($request->isMethod('GET')){//GETの場合(リンククリック時)
			$hash    = $request->hash;
			$order   = Order::where('hash',$hash)->first();
			$status_time = explode(',', $order->status_time);
			$details = Order_detail::where('order_id',$order->o_id)->get();
			if($order->order_flag == 5){
				$msg = '受取が完了しています。配送を開始してください。';
			}elseif($order->order_flag == 6) {
				$msg = '配送が完了しています。配送ありがとうございました。';
			}
		}
		if($request->isMethod('POST')){//POSTの場合(ステータス変更時)
			$order = Order::where('o_id',$request->o_id)->first();
			$details = Order_detail::where('order_id',$order->o_id)->get();
			$order->order_flag = $request->order_flag;
			$status_time = explode(',', $order->status_time);
			$today = date('Y-m-d H:i');
			if($order->order_flag == 5){
				$msg = '受取が完了しています。配送を開始してください。';
				$status_time[1] = $today;
			}elseif($order->order_flag == 6) {
				$msg = '配送が完了しています。配送ありがとうございました。';
				$status_time[2] = $today;
			}
			$order->status_time = implode(',', $status_time);
			$order->save();
		}
		$store      = Store::find($order->s_id);
		$products   = Product::where('p_status','1')->get();
		$coupon     = '';
		$t_quantity = 0;
		$total      = 0;
		$p_o_id     = '';
		foreach ($details as $key => $detail) {//注文された商品を回す
			if($detail->product_id == 'S' || $detail->product_id == 'P' || $detail->product_id == 'A'){
				$coupon = Coupon::find($detail->option_1);
				$coupon->d_price = $detail->price;
			}
			$detail->s_name = $store->name;
			$detail->address = $store->address;
			$detail->tel = $store->tel;
			foreach ($products as $key => $product) {
				if($product->id == $detail->product_id){
					$detail->p_name = $product->name;
					$detail->p_price = $product->price;
				}
			}
			$p_o_id = Product::where('id',$detail->product_id)->first();
			if($p_o_id != ''){
				$o_ids  = explode(',', $p_o_id->o_ids);
				$options = Option::whereIn('o_id',$o_ids)->where('s_id',$detail->s_id)->orderBy('o_id','asc')->get();//商品のオプション
				foreach ($options as $key => $option) {
					if($option->id == $detail->option_1){
						$detail->o_1_name  = $option->o_name;
						$detail->o_1_note  = $option->name;
						$detail->o_1_price = $option->price;
					}
					if($option->id == $detail->option_2){
						$detail->o_2_name  = $option->o_name;
						$detail->o_2_note  = $option->name;
						$detail->o_2_price = $option->price;
					}
					if($option->id == $detail->option_3){
						$detail->o_3_name  = $option->o_name;
						$detail->o_3_note  = $option->name;
						$detail->o_3_price = $option->price;
					}
					if($option->id == $detail->option_4){
						$detail->o_4_name  = $option->o_name;
						$detail->o_4_note  = $option->name;
						$detail->o_4_price = $option->price;
					}
				}
			}
			$detail->subtotal = (($detail->p_price + $detail->o_1_price + $detail->o_2_price + $detail->o_3_price + $detail->o_4_price) * $detail->quantity);
// dd($order->subtotal);
			if($coupon != '' && $coupon->id == $detail->option_1){//クーポンがある場合
				if($detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
					$total += $detail->price;
				}
			}else{
				$total += $detail->price;
			}
			if($detail->s_id != 0 && $detail->product_id != 'P' && $detail->product_id != 'A' && $detail->product_id != 'S'){//個数計算(商品のみ)
				$t_quantity += $detail->quantity;
			}
		}
		if($request->isMethod('POST') && $order->order_flag == 6){//配達完了時のみ
			$user      = User::where('id',$order->u_id)->first();//注文者
			$s_name    = Store::where('id',$order->s_id)->first()->name;//店舗名
				//注文確認メール
		    $t_quantity = 0;
		    $total     = 0;
			// $order->note = explode(',', $order->note);
				$p_o_id = '';
				$order_details = Order_detail::where('order_id',$order->o_id)->get();
				foreach($order_details as $key => $order_detail){
						$order_detail->s_name = $store->name;
						$order_detail->address = $store->address;
						$order_detail->tel = $store->tel;
						if($order_detail->product_id == 'S' || $order_detail->product_id == 'P' || $order_detail->product_id == 'A'){
								$coupon = Coupon::find($order_detail->option_1);
								$coupon->d_price = $order_detail->price;
						}
						$p_o_id = '';
						foreach ($products as $key => $product) {
								if($product->id == $order_detail->product_id && $order_detail->quantity != 0){
										$order_detail->p_name = $product->name;
										$order_detail->p_price = $product->price;
								}
						}
						$p_o_id = Product::where('id',$order_detail->product_id)->first();
						if($p_o_id != ''){
								$o_ids  = explode(',', $p_o_id->o_ids);
								$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
								foreach ($options as $key => $option) {
										if($option->id == $order_detail->option_1){
												$order_detail->o_1_name  = $option->o_name;
												$order_detail->o_1_note  = $option->name;
												$order_detail->o_1_price = $option->price;
										}
										if($option->id == $order_detail->option_2){
												$order_detail->o_2_name  = $option->o_name;
												$order_detail->o_2_note  = $option->name;
												$order_detail->o_2_price = $option->price;
										}
										if($option->id == $order_detail->option_3){
												$order_detail->o_3_name  = $option->o_name;
												$order_detail->o_3_note  = $option->name;
												$order_detail->o_3_price = $option->price;
										}
										if($option->id == $order_detail->option_4){
												$order_detail->o_4_name  = $option->o_name;
												$order_detail->o_4_note  = $option->name;
												$order_detail->o_4_price = $option->price;
										}
								}
						}
						if($coupon != ''&& $coupon->id == $order_detail->option_1){//クーポンがある場合
								if($order_detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
										$total += $order_detail->price;
								}
						}else{
								$total += $order_detail->price;
						}
						if($order_detail->s_id != 0 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S'){//個数計算(商品のみ)
								$t_quantity += $order_detail->quantity;
						}
				}
				
			if($order->c_flag == 10){
				$order->c_flag = 11;
			}elseif($order->c_flag == 20){
				$order->c_flag = 21;
			}
			$p_date  = date("Y-m-d H:i:s");//配達完了時間
			$order->updated_at = $p_date;
			$order->save();
			//支払い方法で分岐
			if($order->c_flag == '01'){
					$order->p_kind = 'クレジット';
			}elseif ($order->c_flag == '11') {
					$order->p_kind = 'au PAY';
			}else{
					if($order->corporation_flag == 2){
							$order->p_kind = '代引(無料)';
					}else{
							$order->p_kind = '代引';
					}
			}
			$url	 = "mosimosi.delivery@gmail.com";
			$to		 = $user->email;
			$subject = "【もしもしデリバリー】配送完了メール";
			$data	 = ['url'          => $url,
								'user'         => $user,
								's_name'       => $store->name,
								'order'        => $order,
								'order_details'=> $order_details,
								'coupon'       => $coupon,
								't_quantity'   => $t_quantity,
								'total'        => $total,
								];
			try{
				Mail::send(['text'=>'emails.order_complete'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
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
		}
		if($order->order_flag == 1){
			$order->detail = '注文完了';
		}elseif($order->order_flag == 2){
			$order->detail = '注文確定';
		}elseif($order->order_flag == 3){
			$order->detail = '配達員選定中';
		}elseif($order->order_flag == 4){
			$order->detail = '配達員決定';
		}elseif($order->order_flag == 5){
			$order->detail = '受取完了';
		}elseif($order->order_flag == 6){
			$order->detail = '配送完了';
		}elseif($order->order_flag == 7){
			$order->detail = 'キャンセル';
		}
		
		$order->status_time = explode(',', $order->status_time);
		$d_staff  		 = User::where('kind',3)->get();
		if(isset($order->d_staff_id) && $order->d_staff_id != 0){
			foreach($d_staff as $staff){
				if($order->d_staff_id == $staff->id){
					$order->d_staff_id = $staff->name;
				}
			}
		}else{
			$order->d_staff_id = '';
		}
		$order->new_o_id = substr($order->o_id, -5);
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('order/manage_products',compact('order','details','msg','seo','store','coupon','total','status_time'));
	}
	public function ShopOrderSearch(Request $request){
		if( !Session::has('kind') || Session::get('kind') != 2){ return;}// store以外の場合
		if(Session::get('s_id') != $request->s_id){//セッションのs_idと送られてきたs_idが一致しなかったら
			Log::error("店舗idとユーザーid不一致エラー");
			return Redirect::to('logout')->send();
		}else{//セッションのs_idと送られてきたs_idが一致したら
			$query = Order::query()->where('s_id',$request->s_id);
			$order_details = Order_detail::get();
			// $query = Order::join('t_store', 't_store.id', '=', 't_order.s_id');//storeのidとproductのs_idが同じものを結合
			// $query->select('t_store.name as name','t_order.id as id','o_id','c_flag','s_id','u_name','nominate_list','d_staff_id','s_hash','hash','u_id','order_flag','corporation_flag','delivery_date','delivery_time','catch_time','d_postcode','d_address','d_tel','d_name','o_address','o_tel','t_order.note as note','memo','status_time','t_order.updated_at as updated_at','t_order.created_at as created_at');
			if ($request->isMethod('GET')) {//GET(検索条件あり)のケース
				if (isset($request->u_name)) {
					$query->where('u_name', 'like', "%{$request->u_name}%");
				}
				if (isset($request->name)) {
					$query->where('name', 'like', "%{$request->name}%");
				}
				if (isset($request->o_id)) {
					$query->where('o_id', 'like', "%{$request->o_id}%");
				}
				if (isset($request->from)) {
						$from = $request->from.' 00:00:00';
						$query->where('t_order.created_at','>=', $from);
				}
				if (isset($request->to)) {
						$to = $request->to.' 23:59:59';
						$query->where('t_order.created_at','<=', $to);
				}
				if (isset($request->corporation_flag)) {
					$query->where('corporation_flag', $request->corporation_flag);
				}
				if (isset($request->order_flag)) {
						$query->where('order_flag', $request->order_flag);
				}
				if ($request->has('c_flag')) {
					if($request->c_flag == 1){
						$query->whereIn('c_flag', [01,11,21]);
					}
					if($request->c_flag == 2){
						$query->whereIn('c_flag', [10,20]);
					}
				}
				if ($request->has('pay_kind')) {
					if($request->pay_kind == 1){
						$query->where('c_flag', 01);
					}
					if($request->pay_kind == 2){
						$query->whereIn('c_flag', [10,21]);
					}
					if($request->pay_kind == 3){
						$query->whereIn('c_flag', [20,21]);
					}
				}
				if ($request->has('delivery_date')) {
					if($request->delivery_date == 1){
						$lists = $query->orderByRaw('delivery_date desc')->paginate($this->COUNT_PAR_PAGE);
					}elseif($request->delivery_date == 2){
							$lists = $query->orderByRaw('delivery_date asc')->paginate($this->COUNT_PAR_PAGE);
					}else{
							$lists = $query->orderByRaw('updated_at desc')->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
					}
				}
			}
			if($request->isMethod('POST')){//POSTの場合
				$order = Order::where('o_id',$request->o_id)->first();
			if($request->has('c_flag') && $request->c_flag == 1){
				if($order->c_flag == 10){//支払い未了から変更の場合
					$order->c_flag = 11;
				}elseif($order->c_flag == 20){
					$order->c_flag = 21;
				}
			}elseif($request->has('c_flag') && $request->c_flag == 2){
				if($order->c_flag == 11){//支払い完了から変更の場合
					$order->c_flag = 10;
				}elseif($order->c_flag == 21){
					$order->c_flag = 20;
				}
			}
			if (isset($request->order_flag)) {
				$order->order_flag = $request->order_flag;
			}
			if (isset($request->catch_time)) {
				$order = Order::where('o_id',$request->order_id)->first();
				$order->catch_time = $request->catch_time;
			}
			// $today = date('Y-m-d H:i');
			// $status_time = explode(',', $order->status_time);
			// if($request->has('order_flag')){
			// 	if($request->order_flag == 5){
			// 		$status_time[1] = $today;
			// 	}elseif($request->order_flag == 6){
			// 		$status_time[2] = $today;
			// 	}
			// 	if($request->order_flag == 6 && $order->c_flag == 10){
			// 		$order->c_flag = 11;
			// 	}elseif($request->order_flag == 6 && $order->c_flag == 20){
			// 		$order->c_flag = 21;
			// 	}
			// 	$order->status_time = implode(',', $status_time);
				$order->save();
			}
			if($request->has('order_flag')){
				$details = Order_detail::where('order_id',$request->o_id)->get();
				if($request->order_flag == 7){//キャンセルの場合
					foreach ($details as $key => $detail) {
						$details[$key]->order_flag = 2;//キャンセルにする
						$details[$key]->save();
					}
				}else{
					foreach ($details as $key => $detail) {
						$details[$key]->order_flag = 1;//キャンセルにする
						$details[$key]->save();
					}
				}
			}
			$d_staff  		 = User::where('kind',3)->get();
			$products      = Product::get();
			$store         = Store::find($request->s_id);
			// dd($request);
			$orders = Order::get();
			$w_coupon = array('A','P','S');
			$work_order_details = Order_detail::whereIn('product_id',$w_coupon)->get();
	
			$order_detail_s = array();
			foreach ($work_order_details as $key => $work_order_detail) {
					$order_detail_s[$work_order_detail->order_id] = $work_order_detail;
			}
			$w_coupons = Coupon::get();
			$coupons = array();
			foreach ($w_coupons as $key => $w_coupon){
				$coupons[$w_coupon->id] = $w_coupon;
			}
			foreach ($orders as $key => $order) {
				if(isset($order_detail_s[$order->o_id])){
					$orders[$key]->title = $coupons[$order_detail_s[$order->o_id]->option_1]->title;
					$orders[$key]->discount = $order_detail_s[$order->o_id]->price;
				}else{
					$orders[$key]->title = '';
					$orders[$key]->discount = '';
				}
			}
			$lists = $query->orderByRaw('t_order.updated_at desc')->paginate($this->COUNT_PAR_PAGE);//更新日順に並び替える
			foreach ($lists as $key => $list) {
				$store         = Store::find($list->s_id);
				$list->status_time = explode(',', $list->status_time);
				$list->new_o_id = substr($list->o_id, -5);
				$list->note = explode(',', $list->note);
				$total = 0;
				$t_quantity = 0;
				if(isset($list->d_staff_id) && $list->d_staff_id != 0){
					foreach($d_staff as $staff){
						if($list->d_staff_id == $staff->id){
							$list->d_staff_id = $staff->name;
						}
					}
				}else{
					$list->d_staff_id = '';
				}
				foreach ($order_details as $key2 => $order_detail) {
					if ($list->o_id == $order_detail->order_id) {
						$lists[$key]['p_detail'] = $order_detail;
						foreach ($products as $key4 => $product) {
							if($product->id == $order_detail->product_id && $order_detail->quantity != 0){
								$lists[$key]['p_detail']['p_name'] = $product->name;
								$lists[$key]['p_detail']['p_price'] = $product->price;
							}
						}
						$p_o_id = Product::where('id',$order_detail->product_id)->first();
						if($p_o_id != ''){
							$o_ids  = explode(',', $p_o_id->o_ids);
							$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
							foreach ($options as $option) {
								if($option->id == $order_detail->option_1){
									$order_detail->o_1_name  = $option->o_name;
									$order_detail->o_1_note  = $option->name;
	
									$order_detail->o_1_price = $option->price;
								}
								if($option->id == $order_detail->option_2){
									$order_detail->o_2_name  = $option->o_name;
									$order_detail->o_2_note  = $option->name;
									$order_detail->o_2_price = $option->price;
								}
								if($option->id == $order_detail->option_3){
									$order_detail->o_3_name  = $option->o_name;
									$order_detail->o_3_note  = $option->name;
									$order_detail->o_3_price = $option->price;
								}
								if($option->id == $order_detail->option_4){
									$order_detail->o_4_name  = $option->o_name;
									$order_detail->o_4_note  = $option->name;
									$order_detail->o_4_price = $option->price;
								}
							}
						}
	
						$lists[$key]['p_detail']['subtotal'] = (($order_detail->p_price + $order_detail->o_1_price + $order_detail->o_2_price + $order_detail->o_3_price + $order_detail->o_4_price) * $order_detail->quantity);
						// dd($order_detail->price);

						if($order_detail->product_id == 16){//送料
							$lists[$key]['p_detail']['postage'] = $order_detail->price;
						}
						// $list->subtotal += $order[$key]['p_detail']['subtotal'];
						// $list->postage += $order[$key]['p_detail']['postage'];
						// $list->total = $list->subtotal + $list->postage;
	
					}
				}
				
	
				$list->d_address = str_replace('東京都','',$list->d_address);
				if($list->c_flag == 01){
					$list->charge = '完　クレカ';
				}elseif($list->c_flag == 10){
					$list->charge = '未　auPAY';
				}elseif($list->c_flag == 11){
					$list->charge = '完　auPAY';
				}elseif($list->c_flag == 20){
					$list->charge = '未　代引';
				}elseif($list->c_flag == 21){
					$list->charge = '完　代引';
				}
				if($list->corporation_flag == 1){
					$list->kind = '個';
				}elseif($list->corporation_flag == 2){
					$list->kind = '業';
				}elseif($list->corporation_flag == 3){
					$list->kind = 'も';
				}
				if($list->order_flag == 1){
						$list->detail = '注文完了';
				}elseif($list->order_flag == 2){
						$list->detail = '注文確定';
				}elseif($list->order_flag == 3){
						$list->detail = '配達員選定';
				}elseif($list->order_flag == 4){
						$list->detail = '配達員決定';
				}elseif($list->order_flag == 5){
						$list->detail = '受取完了';
				}elseif($list->order_flag == 6){
						$list->detail = '配送完了';
				}elseif($list->order_flag == 7){
						$list->detail = 'キャンセル';
				}
				if($list->s_id){
					$list->s_id = $store->name;
				}
				if($list->created_at){
					$date = $list->created_at;
					$list->created_a_t = date('Y/m/d H:i', strtotime($date));
				}
				foreach ($orders as $key => $order) {
					if($list->o_id == $order->o_id){
						$list->coupon_title = $order->title;
						$list->coupon_discount = $order->discount;
					}
					// dd($list);
				}
				
			}
		}
		return view('order/shop_order_search',compact('lists','order_details','request','store'));
	}
	public function ShopOrderDetail(Request $request){//店舗オーダー詳細
		$order = Order::where('t_order.o_id',$request->o_id)->first();
		$order_details = Order_detail::where('order_id',$request->o_id)->get();
		if($request->has('order_flag')){
			$order->order_flag = $request->order_flag;
			if($request->order_flag == 6 && $order->c_flag == 10){
				$order->c_flag = 11;
			}elseif($request->order_flag == 6 && $order->c_flag == 20){
				$order->c_flag = 21;
			}
			$order->save();
		}
		$order->status_time = explode(',', $order->status_time);
		$order->new_o_id = substr($order->o_id, -5);
		if($order->order_flag == 1){
			$order->detail = '注文完了';
		}elseif($order->order_flag == 2){
			$order->detail = '注文確定';
		}elseif($order->order_flag == 3){
			$order->detail = '配達員選定';
		}elseif($order->order_flag == 4){
			$order->detail = '配達員決定';
		}elseif($order->order_flag == 5){
			$order->detail = '受取完了';
		}elseif($order->order_flag == 6){
			$order->detail = '配送完了';
		}elseif($order->order_flag == 7){
			$order->detail = 'キャンセル';
		}
		if($order->corporation_flag == 1){
			$order->kind = '個人';
		}elseif($order->corporation_flag == 2){
			$order->kind = '法人';
		}elseif($order->corporation_flag == 3){
			$order->kind = 'もしもし';
		}
		$total = 0;
		$t_quantity = 0;
		$coupon = '';
		$store         = Store::find($order->s_id);
		$products      = Product::where('p_status','1')->get();
		if($request->has('order_flag') && $request->order_flag == 7){//キャンセルの場合
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 2;//キャンセルにする
				$order_details[$key]->save();
			}
		}elseif($request->has('order_flag') && $request->order_flag != 7){
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 1;//有効にする
				$order_details[$key]->save();
			}
		}
		foreach($order_details as $key => $order_detail){
			if($order_detail->product_id == 'S' || $order_detail->product_id == 'P' || $order_detail->product_id == 'A'){
				$coupon = Coupon::find($order_detail->option_1);
			}
			if($order_detail->order_flag == 1){
					$order_detail->detail = '注文完了';
			}elseif($order_detail->order_flag == 2){
					$order_detail->detail = 'キャンセル';
			}
			$order_detail->s_name = $store->name;
			$order_detail->address = $store->address;
			$order_detail->tel = $store->tel;
			foreach ($products as $key => $product) {
				if($product->id == $order_detail->product_id){
					$order_detail->p_name = $product->name;
					$order_detail->p_price = $product->price;
				}
			}
			$p_o_id = Product::where('id',$order_detail->product_id)->first();
			if($p_o_id != ''){
				$o_ids  = explode(',', $p_o_id->o_ids);
				$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id','asc')->get();//商品のオプション
				foreach ($options as $key => $option) {
					if($option->id == $order_detail->option_1){
						$order_detail->o_1_name  = $option->o_name;
						$order_detail->o_1_note  = $option->name;
						$order_detail->o_1_price = $option->price;
					}
					if($option->id == $order_detail->option_2){
						$order_detail->o_2_name  = $option->o_name;
						$order_detail->o_2_note  = $option->name;
						$order_detail->o_2_price = $option->price;
					}
					if($option->id == $order_detail->option_3){
						$order_detail->o_3_name  = $option->o_name;
						$order_detail->o_3_note  = $option->name;
						$order_detail->o_3_price = $option->price;
					}
					if($option->id == $order_detail->option_4){
						$order_detail->o_4_name  = $option->o_name;
						$order_detail->o_4_note  = $option->name;
						$order_detail->o_4_price = $option->price;
					}
				}
			}
			if($coupon != ''&& $coupon->id == $order_detail->option_1){//クーポンがある場合
				if($order_detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
					$total += $order_detail->price;
				}
			}else{
				$total += $order_detail->price;
			}
			if($order_detail->s_id != 0 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S'){//個数計算(商品のみ)
				$t_quantity += $order_detail->quantity;
			}
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('order/shop_order_detail',compact('coupon','order','order_details','total','t_quantity','seo','store'));
	}
	/** ユーザー管理 **/
	public function MyOrder(Request $request,$id){
		$user = User::find($id);
		if(Session::get('u_id') != $id){
			return Redirect::to('/logout')->send();
		}
		$order = '';
		$order = Order::where('u_id',$user->id)->orderByRaw('created_at desc')->paginate($this->COUNT_PAR_PAGE);
		foreach ($order as $key => $detail) {
			$s_id              = $detail->s_id;
			$detail->name      = Store::where('id',$s_id)->first()->name;
			$detail->status_time = explode(',',$detail->status_time);
			$detail->last_o_id = substr($detail->o_id, -5);//注文idの表示を5桁にする
			switch($detail->order_flag){
				case 1:
					$detail->status = '注文受付中';
					break;
				case 5:
					$detail->status = '配送中';
					break;
				case 6:
					$detail->status = '配送完了';
					break;
				case 7:
					$detail->status = 'キャンセル';
					break;
				default:
					$detail->status = '注文完了';
					break;
			}
			if($detail->created_at){
				$date = $detail->created_at;
				$detail->date_created = date('Y年m月d日 H:i', strtotime($date));
			}

			if($detail->status_time[2]){
				$date = $detail->status_time[2];
				$detail->date_status_time = date('Y年m月d日 H時i分', strtotime($date));
			}
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('order/myorder',compact('order','seo'));
	}
	public function MyOrderDetail(Request $request){
		if($request->isMethod('GET')){//GETのケース
			return Redirect::to('/logout')->send();
		}
		$order = Order::where('o_id',$request->o_id)->first();
		if($order->created_at){
			$date = $order->created_at;
			$order->date_created = date('Y/m/d H:i', strtotime($date));
		}
		$order->status_time = explode(',', $order->status_time);
		if($order->status_time[2]){
			$date = $order->status_time[2];
			$order->date_status_time = date('Y/m/d H:i', strtotime($date));
		}
		$order->note = explode(',', $order->note);
		$s_id             = Order_detail::where('s_id','!=',0)->where('order_id',$request->o_id)->first()->s_id;
		$order->name      = Store::where('id',$s_id)->first()->name;
		$order->last_o_id = substr($order->o_id, -5);//注文idの表示を5桁にする
		switch($order->order_flag){
			case 1:
				$order->status = '注文受付中';
				break;
			case 4:
				$order->status = '配送中';
				break;
			case 5:
				$order->status = '配送完了';
				break;
			case 6:
				$order->status = 'キャンセル';
				break;
			default:
				$order->status = '注文完了';
				break;
		}
		if($order->corporation_flag == 1){
			$order->kind = '個人';
		}elseif($order->corporation_flag == 2){
			$order->kind = '法人';
		}elseif($order->corporation_flag == 3){
			$order->kind = 'もしもし';
		}
		$order_details = Order_detail::where('order_id',$request->o_id)->get();
		$total      = 0;
		$t_quantity = 0;
		$coupon = '';
		$store         = Store::find($order->s_id);
		$products      = Product::get();
		if($request->has('order_flag') && $request->order_flag == 7){//キャンセルの場合
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 2;//キャンセルにする
				$order_details[$key]->save();
			}
		}elseif($request->has('order_flag') && $request->order_flag != 7){
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 1;//有効にする
				$order_details[$key]->save();
			}
		}
		foreach($order_details as $key => $order_detail){
			if($order_detail->product_id == 'S' || $order_detail->product_id == 'P' || $order_detail->product_id == 'A'){
				$coupon = Coupon::find($order_detail->option_1);
			}
			if($order_detail->order_flag == 1){
					$order_detail->detail = '注文完了';
			}elseif($order_detail->order_flag == 2){
					$order_detail->detail = 'キャンセル';
			}
			$order_detail->s_name = $store->name;
			$order_detail->address = $store->address;
			$order_detail->tel = $store->tel;
			foreach ($products as $key => $product) {
				if($product->id == $order_detail->product_id){
					$order_detail->p_name  = $product->name;
					$order_detail->d_price = $product->price;
					$extension = $product->extension;
					$file_name = '/product_image/'.$product->id.'.'.$extension;
					if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
						$order_detail->img = 'storage'.$file_name;
					}else{
						$order_detail->img = '/img/product00.jpg';
					}
				}
			}
			$p_o_id = Product::where('id',$order_detail->product_id)->first();
			if($p_o_id != ''){
				$o_ids  = explode(',', $p_o_id->o_ids);
				$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
				foreach ($options as $key => $option) {
					if($option->id == $order_detail->option_1){
						$order_detail->o_1_name  = $option->o_name;
						$order_detail->o_1_note  = $option->name;
						$order_detail->o_1_price = $option->price;
					}
					if($option->id == $order_detail->option_2){
						$order_detail->o_2_name  = $option->o_name;
						$order_detail->o_2_note  = $option->name;
						$order_detail->o_2_price = $option->price;
					}
					if($option->id == $order_detail->option_3){
						$order_detail->o_3_name  = $option->o_name;
						$order_detail->o_3_note  = $option->name;
						$order_detail->o_3_price = $option->price;
					}
					if($option->id == $order_detail->option_4){
						$order_detail->o_4_name  = $option->o_name;
						$order_detail->o_4_note  = $option->name;
						$order_detail->o_4_price = $option->price;
					}
				}
			}
			// if($coupon != ''&& $coupon->id == $order_detail->option_1){//クーポンがある場合
			// 	if($order_detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
			// 		$total += $order_detail->price;
			// 	}
			// }else{
				$total += $order_detail->price;
			// }
			if($order_detail->s_id != 0 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S'){//個数計算(商品のみ)
				$t_quantity += $order_detail->quantity;
			}
		}
		if(isset($request->order_id)){
			$detail = Order_detail::where('id',$request->order_id)->first();
			$detail->order_flag = $request->status;
			$detail->save();
		}
		
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		return view('order/myorder_detail',compact('coupon','order','order_details','total','t_quantity','seo'));
	}
	// 領収書
	public function Receipt(Request $request){
		if($request->isMethod('GET')){//GETのケース
			return Redirect::to('/logout')->send();
		}
		$u_id = Session::get('u_id');
		$user     = User::where('id',$u_id)->first();
		$order            = Order::where('o_id',$request->o_id)->first();
		$order->status_time = explode(',', $order->status_time);
		$s_id             = Order_detail::where('s_id','!=',0)->where('order_id',$request->o_id)->first()->s_id;
		$order->name      = Store::where('id',$s_id)->first()->name;
		$order->last_o_id = substr($order->o_id, -5);//注文idの表示を5桁にする
		$order_details = Order_detail::where('order_id',$request->o_id)->get();

		// product_idが16以外のものを全部足す。
		// $all_sum = $order_details->price;
		// dd($all_sum);
		$total      = 0;
		$t_quantity = 0;
		$coupon = '';
		$store         = Store::find($order->s_id);
		$products      = Product::where('p_status','1')->get();
		if($request->has('order_flag') && $request->order_flag == 7){//キャンセルの場合
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 2;//キャンセルにする
				$order_details[$key]->save();
			}
		}elseif($request->has('order_flag') && $request->order_flag != 7){
			foreach ($order_details as $key => $detail) {
				$order_details[$key]->order_flag = 1;//有効にする
				$order_details[$key]->save();
			}
		}
		foreach($order_details as $key => $order_detail){
			if($order_detail->product_id == 'S' || $order_detail->product_id == 'P' || $order_detail->product_id == 'A'){
				$coupon = Coupon::find($order_detail->option_1);
			}

			$order_detail->s_name = $store->name;
			$order_detail->address = $store->address;
			$order_detail->tel = $store->tel;
			foreach ($products as $key => $product) {
				if($product->id == $order_detail->product_id){
					$order_detail->p_name  = $product->name;
					$order_detail->d_price = $product->price;
					$file_name = '/product_image/'.$product->id.'.jpg';
					if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
						$order_detail->img = 'storage'.$file_name;
					}else{
						$order_detail->img = '/img/product00.jpg';
					}
				}
			}
			$p_o_id = Product::where('id',$order_detail->product_id)->first();
			if($p_o_id != ''){
				$o_ids  = explode(',', $p_o_id->o_ids);
				$options = Option::whereIn('o_id',$o_ids)->where('s_id',$order_detail->s_id)->orderBy('o_id', 'asc')->get();//商品のオプション
				foreach ($options as $key => $option) {
					if($option->id == $order_detail->option_1){
						$order_detail->o_1_name  = $option->o_name;
						$order_detail->o_1_note  = $option->name;
						$order_detail->o_1_price = $option->price;
					}
					if($option->id == $order_detail->option_2){
						$order_detail->o_2_name  = $option->o_name;
						$order_detail->o_2_note  = $option->name;
						$order_detail->o_2_price = $option->price;
					}
					if($option->id == $order_detail->option_3){
						$order_detail->o_3_name  = $option->o_name;
						$order_detail->o_3_note  = $option->name;
						$order_detail->o_3_price = $option->price;
					}
					if($option->id == $order_detail->option_4){
						$order_detail->o_4_name  = $option->o_name;
						$order_detail->o_4_note  = $option->name;
						$order_detail->o_4_price = $option->price;
					}
				}
			}
			if($coupon != ''&& $coupon->id == $order_detail->option_1){//クーポンがある場合
				if($order_detail->product_id != 'P'){//加算しない時の条件(商品クーポンの場合)
					$total += $order_detail->price;
				}
			}else{
				$total += $order_detail->price;
			}
			if($order_detail->s_id != 0 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S'){//個数計算(商品のみ)
				$t_quantity += $order_detail->quantity;
			}
		}

		if(isset($request->order_id)){
			$detail = Order_detail::where('id',$request->order_id)->first();
			$detail->order_flag = $request->status;
			$detail->save();
		}
		$cs = Custom::where('type',5)->get();
		$seo = array();
		foreach ($cs as  $c) {
			$seo [$c->no] = $c->title;
		}
		$created_at =  date("Y年m月d日", strtotime($order->created_at));
		$created_date =  date("Y/m/d H:i", strtotime($order->created_at));
		$c_time = $order->status_time[2];
		$completion_time =  date("Y/m/d H:i", strtotime($c_time));
		$receipt_time =  date("Y年m月d日", strtotime($c_time));
		//郵便番号
		$zipcode = $order->o_postcode;
		//最初から3文字分を取得する
		$zip1    = substr($zipcode ,0,3);
		//4文字目から最後まで取得する
		$zip2    = substr($zipcode ,3);
		//ハイフンで結合する
		$zipcode = $zip1 . "-" . $zip2;
// dd($order);
		return view('receipt/receipt',compact('zipcode','receipt_time','created_at','completion_time','created_date','coupon','order','order_details','total','t_quantity','seo','user'));
	}
	/** SwitchStatus (GET) **/
	public function SwitchStatus(Request $request){
		$result = AdminController::SessionCheck();
		if(!$result){
			return Redirect::to('/logout')->send();
		}
		$user = User::find($request->u_id);
		if($user->user_status){
			$user->user_status = 0;
		}else{
			$user->user_status = 1;
		}
		$user->updated_by = Session::get('u_id');
		$user->updated_at = date("Y-m-d H:i:s");
		$user->save();
		$alert['class'] = 'success';
		$alert['text'] = 'update';
		Session::put('alert',$alert);
		return Redirect::to('search_user')->send();
	}
}/* EOF */
