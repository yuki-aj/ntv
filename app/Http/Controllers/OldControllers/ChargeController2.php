<?php

namespace App\Http\Controllers;//コントローラーを使うための記述
use Session,App,Log;//Facades
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;//ストレージ存在チェック
use App\Models\User;
use App\Models\Product;
use App\Models\Store;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;

class ChargeController extends Controller
{
    //connect用の決済コード。親に振り込んで、子に入金。
    public function onecharge(Request $request){//connect charge
        $carts = Session::get('carts');//セッションを$cartsボックスに入れる
        $user	= User::find(Session::get('u_id'));// userデータを取得
        $products = Product::join('t_store', 't_store.id', '=', 't_product.s_id')->where('t_product.p_status','=','1')
        ->select('t_product.id as p_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.stripe_user_id as s_uid')->get();
        $stores = Store::where('t_store.store_status','1')->get();
        $new_carts = Session::get('new_carts');
        $s_name    = array();//店舗名
        $stripe_id = array();//店舗のstripe_id
        $uri = "/cart2";//失敗時のリダイレクト先
        //決済成功時の処理
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            //購入者情報
            $customer = Customer::create(array(
                // 'email' => $request->stripeEmail,
                // 'source' => $request->stripeToken,
                // 'name' => $user->name??$request->name,
                // // 'postcode' => $user->postcode,
                // // 'address' => $user->address,
                // 'address' => array(
                //     "city" => null,
                //     "country" => '日本',
                //     "line1" => $user->address??Session::get('address'),
                //     "line2" => null,
                //     "postal_code" => $user->postcode??Session::get('postcode'),
                //     "state" => null
                // ),
                // 'card' => $token,
                'email'   => $request->stripeEmail,
                'source'  => $request->stripeToken,
                'name'    => $user->name??$request->name,
                'phone'   => $user->tel??$request->tel,
                // 'shipping' => $user->address??$request->address,
                // 'postcode' => $user->postcode,
                // 'address' => $user->address,
                'address' => array(
                    "city" => null,
                    "country" => '日本',
                    "line1" => $user->address??$request->address,
                    "line2" => null,
                    "postal_code" => $user->postcode??$request->postcode,
                    "state" => null
                ),//住所を入れる
            ));
            var_dump($customer);
            //まとめて決済
            $all_summary = 0;//総合計金額（初期値0）
            if(is_null($new_carts)){
                return redirect('/');
            }
            foreach ($new_carts as $stripe_id => $s_products) {//カート内の店舗で回す
                //配送料金計算
                $amount    = 0;//手数料引いた後の入金額（初期値0）
                $p_border  = 1500;//送料価格基準
                $p_upper   = 3000;//送料価格基準
                $d_fee300  = 300;//送料金額（1500円以下）
                $d_fee600  = 600;//送料金額（3000円以上）
                $d_fee     = 0.2;//送料金額（1500~3000円の間）
                $s_amount  = 0.036;//stripe手数料
                $s_summary = 0;//店舗ごとの合計金額は（初期値0）
                foreach($stores as $key => $store){//商品テーブルを回す
                    if($store->stripe_user_id == $stripe_id){//商品のストアidとリクエストのs_idが一致したら入る
                        $s_name = $store->name;//ストア名を$s_nameに入れる
                    }
                }
                foreach($s_products as $product_id => $s_product){//1店舗のカート内の商品を回す
                    $s_summary += $s_product['quantity']*$s_product['price'];//商品の数分足して$s_summaryに入れていく(商品×個数)
                }

                //クーポンが必要ならこの辺りに入れる？

                //送料計算
                if($s_summary < $p_border){//1500円以下の場合入る
                  $s_summary = $s_summary + $d_fee300;//送料300円
                }elseif($s_summary > $p_upper){//3000円以上の場合入る
                  $s_summary = $s_summary + $d_fee600;//送料600円
                }else{//1500~3000円の場合入る
                  $s_summary = $s_summary + $s_summary*$d_fee;//送料は、合計金額の20%
                }
                $s_commission   = floor($s_summary * $s_amount);//stripe手数料の金額を$s_commissionに入れる(店舗合計金額 × stripe手数料)
                //店舗入金金額計算
                if($s_summary < $p_border){//店舗合計金額が1500円以下の場合
                    $amount = floor(($s_summary - $d_fee300) - $s_commission);//(総額から送料を引いて) - stripe手数料 したものを$amountに入れる
                }elseif($p_upper < $s_summary){//店舗合計金額が3000円以上の場合
                    $amount = floor(($s_summary- $d_fee600) - $s_commission );//(総額から送料を引いて) - stripe手数料 したものを$amountに入れる
                }else{//店舗合計金額が1500円～3000円の間の場合
                    $amount = floor(($s_summary - $s_summary * $d_fee) - $s_commission);//(総額から送料を引いて) - stripe手数料 したものを$amountに入れる
                }
                //決済情報
                $charge = Charge::create(array(
                    'customer' => $customer->id,//購入者のid
                    'amount' => $s_summary,//合計金額
                    'currency' => 'jpy',//通貨単位
                    "transfer_group" => $s_name,//送金先の名前
                    "metadata" => array("order_id" => "6735"),//メダデータ作成(入金金額の配列)
                    "destination" => array(//これを付けると子アカウントへ入金する
                    "amount" => $amount,//手数料と送料を引いた店舗への入金額
                    "account" => $stripe_id,//入金先のユーザーのstripe_id
                    ),
                ));
                $all_summary += $s_summary;//全店舗総額
            }


            $result = 1;//①成功時の処理
            // return view('public/complete',compact('all_summary','new_carts','user','customer','charge','products','stores'));
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
        //決済後に表示する画面の切り替え
        if($result == 1){//成功時
            //後でコメントアウトを消す
            Session::forget('carts');
            Session::forget('new_carts');
            //後でコメントアウトを消す
            $msg = 'お支払いが完了しました。';
            return view('public/complete',compact('all_summary','new_carts','user','customer','charge','products','stores','msg'));
            // return redirect($uri)->with('msg');
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