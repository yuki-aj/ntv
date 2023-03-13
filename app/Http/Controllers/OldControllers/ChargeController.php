<?php

namespace App\Http\Controllers;//コントローラーを使うための記述
use Session,App,Log,DateTime;//Facades
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use App\Models\Customer;//酒匂さんに名前変えるか、auと振込は保存しなくていいか確認
use App\Models\Order;
use App\Models\Custom;
use App\Models\Coupon;
use App\Models\Option;
use App\Models\Order_detail;
use Stripe\Stripe;
// use Stripe\Customer;
use Stripe\Charge;
use Stripe\PaymentIntent;
use App\Http\Controllers\LineMessengerController;

class ChargeController extends Controller
{
    //connect用の決済コード。親に振り込んで、子に入金。
    public function onecharge(Request $request){//connect charge
        if($request->isMethod('GET')){//GETのケース
            return redirect('/');
        }
        $customer   = '';
        $stripe = Session::get('stripe');
        $temp_carts = Session::get('carts');//セッションを$cartsボックスに入れる
        $user	    = User::find(Session::get('u_id'));// userデータを取得
        $products   = Product::where('p_status','1')->get();//statusが有効な物を$productsに入れる
        $store      = Store::where('store_status','1')->where('id',$request->s_id)->first();//決済店舗
        $carts      = Session::get('pay_carts');
        $new_carts  = array();//new_cartsは配列
        $stripe_id  = array();//店舗のstripe_id
        $uri        = "/add_cart";//失敗時のリダイレクト先
        //まとめて決済
        $all_summary = 0;//総合計金額（初期値0）
        $p_carts     = [];//購入済みの物を入れる
        if($carts == []){
            return redirect('/add_cart');
        }
		$pst_id = '16';//送料のp_id
		$one_coupon = '';
        session::forget('flash_message');
		if(isset($request->coupon)){//クーポンがある場合
			if($request->coupon == 0){//クーポンなしの場合
			}else{//ある場合
				$one_coupon = Coupon::find($request->coupon);
				$msg = $one_coupon->title.'を使用しました。';
				session::flash('flash_message',$msg);
				if($one_coupon->p_id == 0){//総額から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id != 0 && $one_coupon->p_id != $pst_id){//商品から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_p_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_p_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_p_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}
				}
				if($one_coupon->p_id == $pst_id){//送料から引く場合
					if(strpos($one_coupon->discount,'円') == true){
						//$one_coupon->discountのなかに'円'が含まれている場合
						$fix_post_discount = strstr($one_coupon->discount,'円',true);
					}elseif(strpos($one_coupon->discount,'%') == true){
						$pct_post_discount = strstr($one_coupon->discount,'%',true) * 0.01;
					}elseif(strpos($one_coupon->discount,'％') == true){
						$pct_post_discount = strstr($one_coupon->discount,'％',true) * 0.01;
					}

				}
			}
		}
		if(!Session::get('kind')){return redirect()->to('/');};
        foreach ($carts as $key => $cart) {
            if($store->id == $cart['s_id']){//storeのidとカート内の商品のs_idが同じなら入る
              foreach ($products as $key2 => $product) {//Productテーブルを回す
                if($product->id == $cart['p_id']){//Productテーブルのidと、カート内の商品idが同じだったら
                  $new_carts[$store->stripe_user_id][$key] = $cart;//$new_carts[stripe_id][商品id]に商品情報を入れる
                  $s_id   = $store->id;
                  $new_carts[$store->stripe_user_id][$key]['s_name'] = $store->name;
                  break;
                }
              }
            }
        }
        $postage_price = '';
		$temp_summary = 0;//送料計算の為の仮合計
		$used_coupon_flag = false;
        $summary     = 0;//商品合計（初期値0）
        $all_summary = 0;//店舗ごとの合計金額
        $all_postage = Product::where('p_status','2')->get();
        foreach ($new_carts as $stripe_id => $s_products) {//カート内の店舗で回す
            if($stripe_id == $stripe){//選んだ店舗だけ決済
                //配送料金計算
                $amount      = 0;//手数料引いた後の入金額（初期値0）
                $p_border    = 1500;//送料価格基準
                $p_upper     = 3000;//送料価格基準
                $d_fee300    = 375;//送料金額（1500円以下）
                $d_fee600    = 750;//送料金額（3000円以上）
                $d_fee       = 0.25;//送料金額（1500~3000円の間）
                $m_fee       = 0.2;//もしもし手数料20%
                $o_m_fee     = 0.1;//もしもし旧手数料10%
                $s_amount    = 0.036;//stripe手数料
                $o_post      = '';
		        $parcent_amount = '';
                foreach($s_products as $key => $s_product){//店舗のカート内の商品を回す
                    $new_carts[$stripe_id][$key]['post_total'] = $s_product['total'];
					if($s_product['s_id'] == $store->id){
                        if(isset($one_coupon->p_id) && $s_product['p_id'] == $one_coupon->p_id && isset($fix_p_discount) && !$used_coupon_flag){
                        //商品から割引(クーポンがあって、商品id一致、割引固定値の場合)
                            $new_carts[$stripe_id][$key]['total'] = $s_product['total'] - ($d_post = (float)$fix_p_discount);//商品総額から固定値を引く
                            $used_coupon_flag = true;
                        }
                        $summary += $new_carts[$stripe_id][$key]['total'];//商品の数分足していく(商品×個数)
                    }
                }
				if(isset($fix_discount)){//店舗総額から固定値割引の場合
					$temp_summary = $summary = $all_summary += ($summary - ($d_post = $fix_discount));
				}elseif(isset($pct_discount)){//店舗総額から％引き
					$temp_summary = $summary = $all_summary += $summary - ($d_post = ($summary * $pct_discount));
				}else{//店舗総額割引なし
					$temp_summary = $summary = $all_summary += $summary;
				}
				//店舗合計の送料計算
				if($all_summary < $p_border){//1500円以下
                    foreach ($all_postage as $key => $post) {
                        if($post->price == $d_fee300){
                            $postage = $post;
                        }
                    }
                    $o_post  = floor($postage->price);
					if(isset($fix_post_discount)){//送料値引きあり(金額割引)
						$postage->price = $postage_price = $postage->price - ($d_post = $fix_post_discount);
						$all_summary = $summary + $postage->price;
					}elseif(isset($pct_post_discount)){//送料値引きあり(％割引)
						$postage->price =  $postage_price = ($d_post = $postage->price * $pct_post_discount);
						$all_summary = $summary + $postage->price;
					}else{//割引なし
                        $postage_price = $postage->price;
						$all_summary = $summary + $postage->price;
					}
				}elseif($all_summary > $p_upper){//3000円以上
                    foreach ($all_postage as $key => $post) {
                        if($post->price == $d_fee600){
                            $postage = $post;
                        }
                    }
                    $o_post  = floor($postage->price);
					if(isset($fix_post_discount)){
						$postage->price = $postage_price = $postage->price - ($d_post = $fix_post_discount);
						$all_summary = $summary + $postage->price;
					}elseif(isset($pct_post_discount)){//％割引
						$postage->price = $postage_price = ($d_post = $postage->price * $pct_post_discount);
						$all_summary = $summary + $postage->price;
					}else{//割引なし
                        $postage_price = $postage->price;
						$all_summary = $summary + $postage->price;
					}
				}else{
                    foreach ($all_postage as $key => $post) {
                        if($post->price == $d_fee){
                            $postage = $post;
                        }
                    }
                        $o_post  = floor($summary * $postage->price);
					if(isset($fix_post_discount)){//金額割引
						$postage->price = $postage_price = $summary * $postage->price - ($d_post = $fix_post_discount);
						//総額×元の送料-新送料（固定値）
						$all_summary = $summary + $postage->price;
					}elseif(isset($pct_post_discount)){//％割引
                        $d_post = floor(($summary * $postage->price) * $pct_post_discount);
						$postage->price = $postage_price = ($summary * $postage->price) - $d_post;
						//総額×元の送料-(総額×元の送料)×新送料の割引率
						$all_summary = $summary + $postage->price;
					}else{//割引なし
						$postage_price = $parcent_amount = $summary * $postage->price;
						$all_summary   = $summary + $summary * $postage->price;
					}
				}
                $postage_price = floor($postage_price);
                //送料計算
                if($summary <= $p_border){//1500円以下の場合入る
                    $p_fee         = $summary*$s_amount;//商品に対するstripe手数料(1000円の場合36円)
                    $post_fee      = $postage_price*$s_amount;//送料に対するstripe手数料(1000円の場合送料375円(300×0.036=13.5))
                    $o_p_summary   = $summary;//送料を足す前の合計金額
                    $summary     = $summary + $postage_price;//1000+375=1375
                    $amount        = floor($summary - ($p_fee+$post_fee) - ($postage_price - $post_fee) - ($o_p_summary * $m_fee));//小数点切り捨て
                }elseif($summary >= $p_upper){//3000円以上の場合入る
                    $p_fee         = $summary*$s_amount;//商品に対するstripe手数料(5000円の場合180円)
                    $post_fee      = $postage_price*$s_amount;//送料に対するstripe手数料(5000円の場合送料600円(600×0.036=21.6))
                    $o_p_summary   = $summary;//送料を足す前の合計金額
                    $summary     = $summary + $postage_price;//送料600円
                    $amount        = floor($summary - ($p_fee+$post_fee) - ($postage_price - $post_fee) - ($o_p_summary * $m_fee));
                }else{//1500~3000円の場合入る
                    $p_fee       = $summary*$s_amount;//商品に対するstripe手数料(2000円の場合72円)
                    $o_p_fee     = $postage_price;//送料計算(2000円の場合500円)
                    $post_fee    = $o_p_fee*$s_amount;//送料に対するstripe手数料(400円の場合(400×0.036=14.4))
                    $o_p_summary = $summary;//送料を足す前の合計金額(2000円の場合は2000円)
                    $summary     = floor($summary + $postage_price);//送料を足した金額(2000円の場合2400円になる)
                    $amount = floor($summary - ($p_fee+$post_fee) - ($o_p_fee - $post_fee) - ($o_p_summary * $m_fee));
                }
                $summary = floor($summary);
                $amount = floor($amount);
                if($request->c_flag == 01){//カード決済の場合
                    //決済情報
                    Stripe::setApiKey(env('STRIPE_SECRET'));
                    // dd($stripe_id);
                    $customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ取得
                       $charge = Charge::create(array(
                        'customer' => $customer->id,//購入者のid
                        'amount' => $summary,//店舗合計と送料を足したもの
                        // "payment_method" => $customer->default_source,
                        'currency' => 'jpy',//通貨単位
                        "transfer_group" => $store->name,//送金先の名前
                        "metadata" => array("order_id" => "6735"),//メダデータ作成(入金金額の配列)
                        "destination" => array(//これを付けると子アカウントへ入金する
                        "amount" => $amount,//手数料と送料を引いた店舗への入金額
                        "account" => $stripe_id,//入金先のユーザーのstripe_id
                        ),
                    ));
                }
                $all_summary = $summary;//店舗総額
            }
        }
        //代引追加
        $cod = 0;
        if($request->c_flag == 20 && $user->corporation_flag == 3){
            $cod = 300;
            $all_summary += $cod;
        }
        if($request->c_flag == 01){//カード決済の場合
            //決済成功時の処理
            try {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                //購入者情報
                $customer = \Stripe\Customer::retrieve($user->stripe_id);
                // $customer = Customer::retrieve($user->stripe_id);
                $result = 1;//①成功時の処理
            }//決済失敗時の処理
            catch (\Stripe\Exception\CardException $e) {// ②カード情報不備などで支払いを拒否された
                $result = 2;
                $error =  $e->getError()->msg;
            } catch (\Stripe\Exception\RateLimitException $e) {// ③APIへのリクエストが早く、多すぎる
                $result = 3;
                $error =  $e->getError()->msg;
            } catch (\Stripe\Exception\InvalidRequestException $e) {// ④パラメータが無効
                $result = 4;
                $error =  $e->getError()->msg;
            } catch (\Stripe\Exception\AuthenticationException $e) {// ⑤STRIPE APIの認証に失敗（最近APIキーを変更した場合など）
                $result = 5;
                $error =  $e->getError()->msg;
            } catch (\Stripe\Exception\ApiConnectionException $e) {// ⑥Stripeとのネットワークコミュニケーションに失敗
                $result = 6;
                $error =  $e->getError()->msg;
            } catch (\Stripe\Exception\ApiErrorException $e) {// ⑦一般的なエラー
                $result = 7;
                $error =  $e->getError()->msg;
            } catch (Exception $e) {// ⑧Stripeと関係のないエラー
                $result = 8;
                $error =  $e->getError()->msg;
            }
        }
        if($request->c_flag == 10 || $request->c_flag == 20){
            $result    = 1;
        }
        //決済後に表示する画面の切り替え
        if($result == 1){//成功時
            if($request->check_address == 1){
                $user->d_name = $d_name = $request->name1;
                $user->d_postcode = $post_code = $request->postcode1;
                $user->d_address  = $d_address = $request->address1;
                $user->d_tel  = $d_tel = $request->d_tel1;
            }elseif($request->check_address == 2){
                $user->d_name2 = $d_name = $request->name2;
                $user->d_postcode2 = $post_code = $request->postcode2;
                $user->d_address2  = $d_address= $request->address2;
                $user->d_tel2  = $d_tel = $request->d_tel2;
            }
            if($request->coupon){
                $c_ids   = explode(',', $user->coupon_stock);
                $u_c_ids = explode(',', $user->coupon_used);
                foreach ($c_ids as $key => $c_id) {
                    if($c_id == $request->coupon){
                        unset($c_ids[$key]);
                        array_push($u_c_ids,$request->coupon);//$u_c_idsに使用済クーポン追加
                    }
                }
                $user->coupon_stock = implode(',', $c_ids);//詰め直し
                $user->coupon_used  = implode(',', $u_c_ids);//詰め直し
                if(substr($user->coupon_stock,0,1) == ','){
                    $user->coupon_stock = substr_replace($user->coupon_stock,'',0,1);//先頭の,を消す処理
                }elseif (substr($user->coupon_stock,-1) == ',') {
                    $user->coupon_stock = rtrim($user->coupon_stock,',');//末尾の,を消す処理
                }
                if(substr($user->coupon_used,0,1) == ','){
                    $user->coupon_used = substr_replace($user->coupon_used,'',0,1);//先頭の,を消す処理
                }elseif (substr($user->coupon_used,-1) == ',') {
                    $user->coupon_used = rtrim($user->coupon_used,',');//末尾の,を消す処理
                }
            }
            $user->save();
		    $week = array( "日", "月", "火", "水", "木", "金", "土" );
		    $datetime = new DateTime();
            if(12 < $datetime->format('H')){//13時過ぎかチェック
                $datetime->modify('+1 days');//過ぎていたら一日プラス
            }
            $datetime->modify('+'.$request->delivery_date. 'day');
            $w = (int)$datetime->format('w');
			$delivery_date = $datetime->format('m月d日('.$week[$w].')');
            $temp_time     = strstr($request->delivery_time,'-',true);//受取時間
            $catch_time    = date("H:i",strtotime($temp_time."-30 minute"));//受取時間指定30分前の時刻
            $message       = '';
            if($request->cutlery == 1){
                $message .= "あり,";
            }else{
                $message .= "なし,";
            }
            if($request->gift != ''){
                $message .= "$request->gift,";
            }
            elseif($request->gift == ''){
                $message .= ",";
            }
            if($request->note != ''){
                $message .= "$request->note,";
            }
            elseif($request->note == ''){
                $message .= ",";
            }

		    $status_time = [];
            $order = new Order();
            $order->init();
			$status_time = explode(',', $order->status_time);
            $today = date('Y-m-d H:i');
            $status_time[0] = $today;
            $order->o_id             = isset($charge->id) ? $charge->id : '';
            $order->c_flag           = $request->c_flag;
            $order->u_id             = $user->id;
            $order->s_id             = $store->id;
            $order->nominate_list    = 0;
            $order->d_staff_id       = 0;
            $order->u_name           = $user->name;
            $order->order_flag       = 1;
            $order->corporation_flag = $user->corporation_flag;
            $order->delivery_date    = $delivery_date;
            $order->delivery_time    = $request->delivery_time;
            $order->catch_time       = $catch_time;
            $order->d_postcode       = $post_code;
            $order->d_address        = $d_address;
            $order->d_tel            = $d_tel;
            $order->d_name           = $d_name;
            $order->o_postcode       = $user->postcode;
            $order->o_address        = $user->address;
            $order->o_tel            = $user->tel;
            $order->note             = $message;
            $order->memo             = '';
            $order->status_time      = implode(',', $status_time);
            $order->created_at       = date('Y-m-d H:i:s');
            $order->updated_at       = date('Y-m-d H:i:s');
            $order->save();
            if($request->c_flag == 10){
                $order->o_id = 'au_'.$order->id;
                $order->save();
            }elseif($request->c_flag == 20) {
                $order->o_id = 'tf_'.$order->id;
                $order->save();
            }
            foreach ($new_carts as $stripe_id => $s_products) {//カート内の店舗で回す
                if($stripe_id == $stripe){//選んだ店舗だけ決済
                    foreach($s_products as $product_id => $s_product){//1店舗のカート内の商品を回す
                        $order_detail = new Order_detail();
                        $order_detail->init();
                        $order_detail->order_id      = $order->o_id;
                        $order_detail->s_id          = $s_product['s_id'];
                        $order_detail->product_id    = $s_product['p_id'];
                        $order_detail->option_1      = !$s_product['option_1'] == '' ? $s_product['option_1'] : '0';
                        $order_detail->option_2      = !$s_product['option_2'] == '' ? $s_product['option_2'] : '0';
                        $order_detail->option_3      = !$s_product['option_3'] == '' ? $s_product['option_3'] : '0';
                        $order_detail->option_4      = !$s_product['option_4'] == '' ? $s_product['option_4'] : '0';
                        $order_detail->price         = $s_product['post_total'];
                        $order_detail->quantity      = $s_product['quantity'];
                        $order_detail->created_at    = date('Y-m-d H:i:s');
                        $order_detail->updated_at    = date('Y-m-d H:i:s');
                        $order_detail->save();
                    }
                }
            }
            $coupon = '';
            if($request->coupon){//クーポン計算。quantityが0の場合はクーポン
                $coupon = Coupon::find($request->coupon);
                $order_detail = new Order_detail();
                $order_detail->init();
                $order_detail->order_id   = $order->o_id;
                if($coupon->p_id == 0){//総額引き
                    $order_detail->product_id = 'A';
                }elseif($coupon->p_id == 16){//送料引き
                    $order_detail->product_id = 'S';
                }else{//商品引き
                    $order_detail->product_id = 'P';
                }
                $order_detail->s_id       = $coupon->s_id;
                $order_detail->option_1   = $coupon->id;
                $order_detail->option_2   = '0';
                $order_detail->option_3   = '0';
                $order_detail->option_4   = '0';
                $order_detail->price      = '-'.$d_post;
                $order_detail->quantity   = 1;
                $order_detail->created_at = date('Y-m-d H:i:s');
                $order_detail->updated_at = date('Y-m-d H:i:s');
                $order_detail->save();
            }
            if($request->c_flag == 20 && $cod != 0){
                $order_detail = new Order_detail();
                $order_detail->init();
                $order_detail->order_id   = $order->o_id;
                $order_detail->product_id = 'D';
                $order_detail->s_id       = 0;
                $order_detail->option_1   = '0';
                $order_detail->option_2   = '0';
                $order_detail->option_3   = '0';
                $order_detail->option_4   = '0';
                $order_detail->price      = 300;
                $order_detail->quantity   = 1;
                $order_detail->created_at = date('Y-m-d H:i:s');
                $order_detail->updated_at = date('Y-m-d H:i:s');
                $order_detail->save();
            }
            $order_detail = new Order_detail();//送料を計算してDBに保存
            $order_detail->init();
            $order_detail->order_id   = $order->o_id;
            $order_detail->product_id = 16;
            $order_detail->s_id       = 0;
            $order_detail->option_1   = '0';
            $order_detail->option_2   = '0';
            $order_detail->option_3   = '0';
            $order_detail->option_4   = '0';
            $order_detail->price      = $o_post;
            $order_detail->quantity    = 1;
            $order_detail->created_at  = date('Y-m-d H:i:s');
            $order_detail->updated_at  = date('Y-m-d H:i:s');
            $order_detail->save();
            foreach ($temp_carts as $p_id => $temp_cart) {//決済したものをカートから削除
                if($temp_cart['stripe_id'] == $stripe && $temp_cart['s_id'] == $store->id){//stripe_idが一致したら
                    unset($temp_carts[$p_id]);//削除
                }
            }
            // dd($new_carts);
            LineMessengerController::ShopPost($order);//決済された店舗にlineを送る
            $temp_carts = array_merge($temp_carts);//配列を詰める
            Session::put('carts',$temp_carts);//カートを保存（購入してない商品）
            $p_carts = $new_carts[$stripe];//購入品をp_cartに入れる
            Session::put('p_carts',$p_carts);//購入品を保存
            Session::forget('pay_carts');
            //支払い方法で分岐
            if($order->c_flag == '01'){
                $order->p_kind = 'クレジットカード';
                $msg = 'お支払いが完了いたしました。';
            }elseif ($order->c_flag == '10') {
                $order->p_kind = 'au PAY';
                $msg = 'au PAYの受付が完了いたしました。';
            }else{
                if($order->corporation_flag == 2){
                    $order->p_kind = '代引(無料)';
                }else{
                    $order->p_kind = '代引';
                }
                $msg = '代引支払いの受付が完了いたしました。';
            }
            if($temp_carts == []){
                $temp_carts = '';
            }
            //注文確認メール
		    $t_quantity = 0;
		    $total     = 0;
            $p_o_id = '';
            $order->note = explode(',', $order->note);
            // dd($order->note[1]);

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
            $email  = $request->email ? $request->email : $user->email;
            $url	= "もしもしお客様センターのアドレスを入れる";
			$to		= $email;
			$subject	= "【もしもしデリバリー】注文完了確認メール";
			$data	= ['url'          => $url,
                       'user'         => $user,
                       's_name'       => $store->name,
                       'order'        => $order,
                       'order_details'=> $order_details,
                       'p_carts'      => $p_carts,
                       'all_summary'  => $all_summary,
                       'cod'          => $cod,
                       'coupon'       => $coupon,
                       't_quantity'   => $t_quantity,
                       'total'        => $total,
                      ];
			try{
				Mail::send(['text'=>'emails.order_thanks'], $data,// 第1引数:テンプレート 第2引数:データ 第3引数:コールバック関数
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
            $cs = Custom::where('type',5)->get();
            $seo = array();
            foreach ($cs as  $c) {
                $seo [$c->no] = $c->title;
            }
            return view('shopping/ordercompletion',compact('order','email','cod','parcent_amount','temp_summary','temp_carts','p_carts','all_summary','new_carts','user','customer','products','store','msg','seo'));
            }elseif($result == 2){
                return redirect($uri)->with('msg','入力いただいたカードでは、お支払いができませんでした。再度お試しいただくか、または他のカードでお試しください。');
            }elseif($result == 3){
                return redirect($uri)->with('msg','APIエラーです。');
            }elseif($result == 4){
                return redirect($uri)->with('msg','パラメータが無効です。');//ok
            }elseif($result == 5){
                return redirect($uri)->with('msg','認証に失敗しました。');
            }elseif($result == 6){
                return redirect($uri)->with('msg','通信エラーです。');
            }elseif($result == 7){
                return redirect($uri)->with('msg','エラーが起こりました。7');
            }elseif($result == 8){
                return redirect($uri)->with('msg','エラーが起こりました。8');
            }
    }
}