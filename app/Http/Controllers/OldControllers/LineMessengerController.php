<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session,App;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use App\Models\User;
use App\Models\Store;
use App\Models\Order;
use App\Models\Option;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Order_detail;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
// use LINE\LINEBot\Event\FollowEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CommonController;

class LineMessengerController extends Controller{
	/** SessionCheck (private) **/
	private function SessionCheck($request){
		if ($request->ajax()) {
			$u_id = Session::get('u_id');
			if(Session::has('temp_u_id')){
				$u_id = Session::get('temp_u_id');
			}
			$user = User::where('id',$u_id)->where('user_status','!=',9)->first();
			if(isset($user)) {
				return $u_id;
			}
		}
		return NULL;
	}
    /** LineLogin**/
    public static function LineLogin($user){
		$line_id = '';
		$profiles = array();
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot        = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$friends    = $bot->getAllFollowerIds();//友達リストの全idを取得
		$line_name  = $user->line_name;
		foreach ($friends as $key => $friend) {//friendには各user_idの暗号が入る
			$user = $bot->getProfile($friend);//ユーザーのプロフィールを$userに入れる
			$profile = $user->getJSONDecodedBody();//profileにプロフィール情報を入れる
			if($profile['displayName'] == $line_name){
				$line_id = $profile['userId'];
			}
		}
		if($line_id == ''){
			$line_id = false;
		}
		return $line_id;
	}
    /** LineLogin**/
    public static function LineName($line_name){
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot        = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$friends    = $bot->getAllFollowerIds();//友達リストの全idを取得
		// dd($friends);
		$line_id    = array();
		foreach ($friends as $key => $friend) {//friendには各user_idの暗号が入る
			$user = $bot->getProfile($friend);//ユーザーのプロフィールを$userに入れる
			$profile = $user->getJSONDecodedBody();//profileにプロフィール情報を入れる
			if(strpos($profile['displayName'],$line_name) !== false){
				$line_id[$key]['displayName'] = $profile['displayName'];
				$line_id[$key]['userId'] = $profile['userId'];
			}
		}
		if($line_id == array()){
			$line_id = null;
			return $line_id;
		}
		return $line_id;
	}
    /** Line **************************************************************/
    public function Line(Request $request){
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$response = $bot->getAllFollowerIds();//友達リストの全idを取得
		$profiles = array();
		foreach ($response as $key => $user_id) {//user_idには各user_idの暗号が入る
			$user = $bot->getProfile($user_id);//ユーザーのプロフィールを$userに入れる
			var_dump($user);
			$profile = $user->getJSONDecodedBody();//profileにプロフィール情報を入れる
			var_dump($profile);
			var_dump('<br>');
			var_dump('<br>');
			var_dump('<br>');
		}
		return;
		// dd($response);
		// API.getProfile { result in
		// 	switch result {
		// 	case .success(let profile):
		// 		print("User ID: \(profile.userID)")
		// 		print("User Display Name: \(profile.displayName)")
		// 		print("User Status Message: \(profile.statusMessage)")
		// 		print("User Icon: \(String(describing: profile.pictureURL))")
		// 	case .failure(let error):
		// 		print(error)
		// 	}
		// }
		// $response = $bot->getProfile('U3d0b7aa65cd0d409bf889229a235b649');//
		// $response = $bot->getProfile('U8f8b907b2a4ac4e0f48f057253c68dfa');//配達者本人のid情報入れた場合は情報が表示される
		// dd($response);
        // $result['o_id'] = $request->o_id;
        // $result['u_id'] = $request->u_id;
        // return dd($result);
    }
    public static function ShopPost($order){
		$httpClient    = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot           = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$friends       = $bot->getAllFollowerIds();//友達リストの全idを取得
		$profile = [];
		foreach ($friends as $key => $friend) {//friendには各user_idの暗号が入る
			$user = $bot->getProfile($friend);//ユーザーのプロフィールを$userに入れる
			$profile[$key] = $user->getJSONDecodedBody();//profileにプロフィール情報を入れる
			// if($profile[$key]['displayName'] == 'shizuyo'){
			// 	dd($profile);
			// }
			// if($profile['displayName'] == $line_name){
			// 	$line_id = $profile['userId'];
			// }
		}
		// dd($profile);
		$details       = Order_detail::where('t_order_detail.order_id',$order->o_id)->get();
		$store         = Store::where('t_store.id',$order->s_id)->first();
		$store_user    = User::where('s_id',$order->s_id)->first();
		$store_users   = User::where('s_id',$order->s_id)->get();
		$user          = User::find($order->u_id);
		$date	         = date("Y-m-d H:i:s");
		$s_hash	       = md5($store_user->line_id.$date);
		$order->s_hash = $s_hash;
		$order->save();
		//支払い方法
		if($order->c_flag == '01'){
				$order->p_kind = 'クレジットカード';
		}elseif ($order->c_flag == '10') {
				$order->p_kind = 'au PAY';
		}else{
				if($order->corporation_flag == 2){
						$order->p_kind = '代引(無料)';
				}else{
						$order->p_kind = '代引';
				}
		}
		$d_address = '';
		if(strpos($order->d_address,'-') !== false){//ハイフンが含まれていたらハイフン以降を削除
			$d_address = strstr($order->d_address,'-',true);
		}elseif(strpos($order->d_address,'‐') !== false ){
			$d_address = strstr($order->d_address,'‐',true);
		}else{//無ければ末尾2文字を削除
			$d_address = $order->d_address;
		}
		$shop_url = url("/receive_shop/{$s_hash}");
		$p_text   = "";
		$c_text   = "";
		$s_text   = "";
		$total    = (int)"";
		$dis_price  = 0;//商品か総額の割引額
		$s_dis_price  = 0;//送料の割引額
		$direct_price = 0;//代引
		$s_temp_price  = 0;
		$p_id      = 0;//クーポンの商品id
		$o_text = "";
		if($user->name != $order->d_name){
			$o_text .= "注文者:$user->name\n";
		}
		if($user->tel != $order->d_tel){
			$o_text .= "注文者電話番号:$user->tel\n";
		}
		$all_coupons = Coupon::get();
		foreach ($details as $key => $detail) {
			if($detail->product_id == 'P' || $detail->product_id == 'A'){//商品か総額から割引の場合
				foreach ($all_coupons as $key => $coupon1) {
					if($coupon1->id == $detail->option_1){
						$coupon = $coupon1;
					}
				}
				$dis_price = ltrim($detail->price,'-');
				$p_id = $coupon->p_id;
			}elseif($detail->product_id == 'S'){//送料から割引の場合
				foreach ($all_coupons as $key => $coupon1) {
					if($coupon1->id == $detail->option_1){
						$coupon = $coupon1;
					}
				}
				$s_dis_price = ltrim($detail->price,'-');
			}elseif($detail->product_id == 'D'){//代引
				$direct_price = $detail->price;
			}
		}
		$all_products = Product::where('p_status','1')->get();
		$all_options = option::get();
		foreach ($details as $key => $detail) {
			if($detail->product_id == 16){//送料の場合
				if($s_dis_price != 0){//送料クーポンの場合
					$s_temp_price = number_format($detail->price - $s_dis_price);
					$c_text .= "$coupon->title を使用しました。\n$s_dis_price 円割引しました。";
					$s_text = "割引前送料は、$detail->price 円です。\n割引後送料は、$s_temp_price 円です。";
				}else{
					$s_temp_price = number_format($detail->price);
					$s_text = "送料は、$s_temp_price 円です。";
				}
				$total  += $s_temp_price;
			}else{//商品の場合
				if($detail->product_id == 'P'){//商品
					$c_text .= "$coupon->title を使用しました。\n $dis_price 円割引しました。";//$c_textに文字列を追加する
					$total  -= $dis_price;//$totalに$dis_priceを引く
				}elseif($detail->product_id == 'A'){//総額
					$format_price = number_format($dis_price);
					$c_text .= "$coupon->title を使用しました。\n $format_price 円割引しました。";
					// Log::info($dis_price);
					$total  -= $dis_price;
					// Log::info('総額');
					// Log::info($total);

				}elseif($detail->product_id == 'D'){//代引
					$c_text .= "代引き手数料は$detail->price 円です。";
					$total  += $detail->price;
				}elseif($detail->product_id == 'S'){
				}else{
					$o_1 = "";
					$o_2 = "";
					$o_3 = "";
					$o_4 = "";
					$o_p = 0;
					foreach ($all_products as $all_product) {
						if($all_product->id == $detail->product_id){
							$product = $all_product;
						}
					}
					// $product = Product::where('t_product.id',$detail->product_id)->first();
					$product->p_price = number_format($product->price);
					if($detail->option_1 != ''){
						foreach ($all_options as $all_option) {
							if($all_option->id == $detail->option_1){
								$option_1 = $all_option;
							}
						}
						// $option_1 = option::where('t_option.id',$detail->option_1)->first();
						$o_1 = $option_1->o_name.$option_1->note.$option_1->name.number_format($option_1->price).'円';
						$o_p += $option_1->price;
					}
					if($detail->option_2 != ''){
						foreach ($all_options as $all_option) {
							if($all_option->id == $detail->option_2){
								$option_2 = $all_option;
							}
						}
						// $option_2 = option::where('t_option.id',$detail->option_2)->first();
						$o_2 = $option_2->o_name.$option_2->note.$option_2->name.number_format($option_2->price).'円';
						$o_p += $option_2->price;
					}
					if($detail->option_3 != ''){
						foreach ($all_options as $all_option) {
							if($all_option->id == $detail->option_3){
								$option_3 = $all_option;
							}
						}
						// $option_3 = option::where('t_option.id',$detail->option_3)->first();
						$o_3 = $option_3->o_name.$option_3->note.$option_3->name.number_format($option_3->price).'円';
						$o_p += $option_3->price;
					}
					if($detail->option_4 != ''){
						foreach ($all_options as $all_option) {
							if($all_option->id == $detail->option_4){
								$option_4 = $all_option;
							}
						}
						// $option_4 = option::where('t_option.id',$detail->option_4)->first();
						$o_4 = $option_4->note.$option_4->name.number_format($option_4->price).'円';
						$o_p += $option_4->price;
					}
					$order_note = '';
					$a = explode(',',$order->note);
					if($a[0] != ''){
						$order_note .= "注文メモ:\n【カトラリー】$a[0]\n";
					}
					if($a[1] != ''){	
						$order_note .= "【ギフトメッセージ】$a[1]\n";
					}
					if($a[2] != ''){
						$order_note .= '【備考欄】'.$a[2];
					}
				$p_total = ($product->price+$o_p)*$detail->quantity;
				$p_all = number_format($p_total);
				$p_text .="商品名: $product->name\n単価:$product->p_price 円\nオプション1:$o_1\nオプション2:$o_2\nオプション3:$o_3\nオプション4:$o_4\n個数:$detail->quantity 点\n商品総額:$p_all 円\n";
				$total  += (int)$detail->price;
				}
			}
		}
		$total = number_format($total);
		$msg    = "$store->name 様\n\n貴店へのご注文をいただきました！\n\n内容をご確認の上、\n文末の「注文確定URL」にアクセスし「注文確定」の設定を行ってください。\n\n注文ID: $order->o_id\n注文日時: $order->created_at\n配送先名: $order->d_name\n配送希望日: $order->delivery_date\n配送希望時間: $order->delivery_time\n引取予定時間:$order->catch_time\n支払い方法:$order->p_kind\n配送先住所: $d_address\n配送先電話番号: $order->d_tel\n注文商品一覧:\n$p_text\n$c_text\n$s_text\n総額:$total 円\n$order_note\n\下記のURLにアクセスし「注文確定」の設定を行ってください。\n注文確定URL:$shop_url\n";
		// dd($msg);
		$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
		foreach($store_users as $key => $s_user){
			$response = $bot->pushMessage($s_user->line_id,$textMessageBuilder);
		}
	}
    public static function DeliveryList($order){
		// U3d0b7aa65cd0d409bf889229a235b649//送信元
		// U8f8b907b2a4ac4e0f48f057253c68dfa//送信先
		$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$detail = Order::where('t_order.o_id',$order->o_id)->first();
		if(strpos($order->d_address,'-') !== false){//ハイフンが含まれていたらハイフン以降を削除
			$d_address = strstr($order->d_address,'-',true);
		}elseif(strpos($order->d_address,'‐') !== false ){
			$d_address = strstr($order->d_address,'‐',true);
		}else{//無ければ末尾2文字を削除
			$d_address = $order->d_address;
		}
		//支払い方法
		if($order->c_flag == '01'){
				$p_kind = 'クレジット';
		}elseif ($order->c_flag == '10') {
				$p_kind = 'au PAY';
		}else{
				if($order->corporation_flag == 2){
						$p_kind = '代引(無料)';
				}else{
						$p_kind = '代引';
				}
		}
		// dd($d_address);
		$store  = Store::where('t_store.id',$detail->s_id)->first();
		$msg = "『もしもしデリバリー』です。\n下記ご注文分の配送依頼です！\n
引取店舗:$store->name
注文ID:$order->o_id
店舗住所:$store->address
店舗電話番号:$store->tel
配送希望日:$order->delivery_date
配送希望時間:$order->delivery_time
引取希望時間:$order->catch_time
支払い方法:$p_kind
配送先住所:$d_address\n
＊配送担当してくださる方は、この文章の全文コピーの上「配送担当します！」と返信ください。";
		// dd($msg);
		$nominate_lists = explode(',',$order->nominate_list);//配列に直す
		// foreach ($nominate_lists as $key => $nominate_list) {
			$staff_lists = User::whereIn('id',$nominate_lists)->get();
		// }
		// dd($staff_lists);
		foreach ($staff_lists as $key => $staff) {
			$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
			$response = $bot->pushMessage($staff->line_id,$textMessageBuilder);
		}
	}
    public static function DeliveryPost($order){
		// U3d0b7aa65cd0d409bf889229a235b649//送信元
		// U8f8b907b2a4ac4e0f48f057253c68dfa//送信先
		$httpClient  = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(config('services.line.channel_token'));
		$bot         = new \LINE\LINEBot($httpClient, ['channelSecret' => config('services.line.messenger_secret')]);
		$details     = Order_detail::where('order_id',$order->o_id)->get();
		$store       = Store::where('id',$order->s_id)->first();
		$staff       = User::where('id',$order->d_staff_id)->first();
		$user          = User::find($order->u_id);
		$date	     = date("Y-m-d H:i:s");
		$hash	     = md5($staff->line_id.$date);
		$order->hash = $hash;
		$order->save();

	
		// $order->note =  implode(',',$order->nominate_list);
		// $order->note[0] = 'カトラリー'.$order->note[0];
		// $order->note[1] = 'ギフトメッセージ'.$order->note[1];
		// $order->note[2] = '備考欄'.$order->note[2];

		//支払い方法
		if($order->c_flag == '01'){
				$p_kind = 'クレジットカード';
		}elseif ($order->c_flag == '10') {
				$p_kind = 'au PAY';
		}else{
				if($order->corporation_flag == 2){
						$p_kind = '代引(無料)';
				}else{
						$p_kind = '代引';
				}
		}
		$manage_url  = url("/manage_products/{$hash}");		$p_text   = "";
		$c_text   = "";
		$s_text   = "";
		$total    = (int)"";
		$dis_price  = 0;//商品か総額の割引額
		$s_dis_price  = 0;//送料の割引額
		$direct_price = 0;//代引
		$s_temp_price  = 0;
		$p_id      = 0;//クーポンの商品id
		$o_text = "";
		if($user->name != $order->d_name){
			$o_text .= "注文者名:$user->name\n";
		}
		if($user->tel != $order->d_tel){
			$o_text .= "注文者電話番号:$user->tel\n";
		}
		foreach ($details as $key => $detail) {
			if($detail->product_id == 'P' || $detail->product_id == 'A'){//商品か総額から割引の場合
				$coupon  = Coupon::find($detail->option_1);
				$dis_price = ltrim($detail->price,'-');
				$p_id = $coupon->p_id;
			}elseif($detail->product_id == 'S'){//送料から割引の場合
				$coupon  = Coupon::find($detail->option_1);
				$s_dis_price = ltrim($detail->price,'-');
			}elseif($detail->product_id == 'D'){//代引
				$direct_price = $detail->price;
			}
		}
		foreach ($details as $key => $detail) {
			if($detail->product_id == 16){//送料の場合
				if($s_dis_price != 0){//送料クーポンの場合
					$s_temp_price = number_format($detail->price - $s_dis_price);
					$c_text .= "$coupon->title を使用しました。\n $s_dis_price 円割引しました。";
					$s_text = "元の送料は、$detail->price 円です。\n 割引後送料は、$s_temp_price 円です。";
				}else{
					$s_temp_price = number_format($detail->price);
					$s_text = "送料は、$s_temp_price 円です。";
				}
				$total  += $s_temp_price;
			}else{//商品の場合
				if($detail->product_id == 'P'){//商品（商品から引くときは引かない）
					$c_text .= "$coupon->title を使用しました。\n $dis_price 円割引しました。";
					$total  -= (int)$dis_price;
				}elseif( $detail->product_id == 'A'){//総額クーポン
					$c_text .= "$coupon->title を使用しました。\n $dis_price 円割引しました。";
					$total  -= (int)$dis_price;
				}elseif($detail->product_id == 'D'){//代引
					$c_text .= "代引き手数料は、$detail->price 円です。";
					$total  += $detail->price;
				}elseif($detail->product_id == 'S'){
				}else{
					$o_1 = "";
					$o_2 = "";
					$o_3 = "";
					$o_4 = "";
					$o_p = 0;
					$product = Product::where('t_product.id',$detail->product_id)->first();
					if($detail->option_1 != ''){
						$option_1 = option::where('t_option.id',$detail->option_1)->first();
						$o_1 = $option_1->note.$option_1->name.$option_1->price.'円';
						$o_p += $option_1->price;
					}
					if($detail->option_2 != ''){
						$option_2 = option::where('t_option.id',$detail->option_2)->first();
						$o_2 = $option_2->note.$option_2->name.$option_2->price.'円';
						$o_p += $option_2->price;
					}
					if($detail->option_3 != ''){
						$option_3 = option::where('t_option.id',$detail->option_3)->first();
						$o_3 = $option_3->note.$option_3->name.$option_3->price.'円';
						$o_p += $option_3->price;
					}
					if($detail->option_4 != ''){
						$option_4 = option::where('t_option.id',$detail->option_4)->first();
						$o_4 = $option_4->note.$option_4->name.$option_4->price.'円';
						$o_p += $option_4->price;
					}
					$order_note = '';
					$a = explode(',',$order->note);
					if($a[0] != ''){
						$order_note .= "注文メモ:\n【カトラリー】$a[0]\n";
					}
					if($a[1] != ''){	
						$order_note .= "【ギフトメッセージ】$a[1]\n";
					}
					if($a[2] != ''){
						$order_note .= '【備考欄】'.$a[2];
					}

				$p_total = ($product->price+$o_p)*$detail->quantity;
				$p_text .="商品名: $product->name\n単価:$product->price 円\nオプション1:$o_1\nオプション2:$o_2\nオプション3:$o_3\nオプション4:$o_4\n個数:$detail->quantity 点\n商品総額:$p_total 円";
				$total  += (int)$detail->price;
				}
			}
		}
		$msg    = "《配達者確定のお知らせ》\n『もしもしデリバリー』です。\n返信ありがとうございます。\n\n下記の配送をお願いいたします。\n指定日時に、下記店舗で注文商品を受け取ってください。\n\n無事故の配送をお願いいたします。\n
ー－－－－ー
引取店舗:$store->name
引取店舗住所:$store->address
引取店舗電話番号:$store->tel
注文ID: $order->o_id
$o_text
配送希望日: $order->delivery_date
配送希望時間: $order->delivery_time
引取予定時間:$order->catch_time
支払い方法:$p_kind
配送先名: $order->d_name
配送先住所:$order->d_address
注文商品一覧:
$p_text
$c_text
$s_text
総額:$total 円
$order_note\n
＊下記URLをタップして、配送状況を更新してください。
配送管理URL:$manage_url";
$textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($msg);
$response = $bot->pushMessage($staff->line_id,$textMessageBuilder);
    }

    // public function webhook(Request $request) {
    //     // LINEから送られた内容を$inputsに代入
    //     $inputs=$request->all();
		// log::info($inputs);
    //     // そこからtypeをとりだし、$message_typeに代入
    //     $message_type=$inputs['events'][0]['type'];
 
    //     // メッセージが送られた場合、$message_typeは'message'となる。その場合処理実行。
    //     if($message_type=='message') {
            
    //         // replyTokenを取得
    //         $reply_token=$inputs['events'][0]['replyToken'];
 
    //         // LINEBOTSDKの設定
    //         $http_client = new CurlHTTPClient(config('services.line.channel_token'));
    //         $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
 
    //         // 送信するメッセージの設定
    //         $reply_message='どうも、メッセージありがとうございます！！';
 
    //         // ユーザーにメッセージを返す
    //         $reply=$bot->replyText($reply_token, $reply_message);
            
    //         return 'ok';
    //     }
    // }
}/* EOF */