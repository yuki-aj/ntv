<?php

namespace App\Http\Controllers;
use Session,Log;//Facades

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Stripe\Stripe;

class PaymentController extends Controller {
    public function CardForm(){
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = User::find(Session::get('u_id'));
        $default_card = '';//決済カード
        $sub_card     = array();//登録済みカード
        if(!empty($user->stripe_id)){//顧客の登録データがある場合
            $customer = \Stripe\Customer::retrieve($user->stripe_id);//ユーザーデータ呼び出し
            $card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//デフォルトのカード情報取得
            $all_card = \Stripe\Customer::allSources($user->stripe_id);//登録された1ユーザーのすべてのカード情報取得
            foreach($all_card['data'] as $key => $card_data){//登録された全てのカード情報を回す
                if($card_data['id'] == $customer['default_source']){//デフォルトのカード情報と一致したら、$default_cardに情報を入れる
                    $default_card = [
                        'number' => str_repeat('*', 8).$card->last4,
                        'brand' => $card->brand,
                        'exp_month' => $card->exp_month,
                        'exp_year' => $card->exp_year,
                        'name' => $card->name,
                        'id' => $card->id,
                    ];
                }else{//一致しなかったら$sub_cardに情報を入れる
                    $sub_card[$key] = [
                        'number' => str_repeat('*', 8).$card_data->last4,
                        'brand' => $card_data->brand,
                        'exp_month' => $card_data->exp_month,
                        'exp_year' => $card_data->exp_year,
                        'name' => $card_data->name,
                        'id' => $card_data->id,
                    ];
                }
            }
        }
        if(isset($user->stripe_id) && isset($customer)){
            Session::put('default_source',$customer->default_source);
            Session::put('default_card',$default_card);
            Session::put('all_card',$all_card);
        }
        return view('public/cardform',compact('stripe_pk','default_card','sub_card'));
    }
    public function AddNewCard(Request $request){//新規card追加
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $token = $request->stripeToken;
        $user  = User::find(Session::get('u_id'));
        $card  = '';
        try {
            if(!empty($user->stripe_id)){//顧客の登録データがある場合
                $customer = \Stripe\Customer::update($user->stripe_id,['card' => $token,]);//データ呼び出し
                $customer_db = Customer::find($user->id);//db呼び出し
                $user->save();
                $customer_db->save();
                $card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//カード情報
                if ($user->stripe_id) {
                    $customer = \Stripe\Customer::retrieve($user->stripe_id);
                    if (isset($customer['default_source'])) {
                        $default_card = [
                            'number' => str_repeat('*', 8).$card->last4,
                            'brand' => $card->brand,
                            'exp_month' => $card->exp_month,
                            'exp_year' => $card->exp_year,
                            'name' => $card->name,
                            'id' => $card->id,
                        ];
                    }
                }
                Session::put('default_card',$default_card);
                return redirect('/myprofile_payment');
            }else{//新規顧客登録
                $customer = \Stripe\Customer::create([//顧客作成
                    'card' => $token,
                    'name' => $user->name,
                    'description' => $user->id,
                ]);
                $user->stripe_id = $customer->id;
                $customer_db = new Customer();
                $customer_db->init();
                $customer_db->id = $user->id;
                $customer_db->stripe_id = $customer->id;
                $user->save();
                $customer_db->save();
                $card = \Stripe\Customer::retrieveSource($user->stripe_id,$customer->default_source);//カード情報
                if ($user->stripe_id) {
                    $customer = \Stripe\Customer::retrieve($user->stripe_id);
                    if (isset($customer['default_source'])) {
                        $default_card = [
                            'number' => str_repeat('*', 8).$card->last4,
                            'brand' => $card->brand,
                            'exp_month' => $card->exp_month,
                            'exp_year' => $card->exp_year,
                            'name' => $card->name,
                            'id' => $card->id,
                        ];
                    }
                }
                Session::put('default_card',$default_card);
                return redirect('/pay');
            }
        } catch(\Stripe\Exception\CardException $e) {
            /*
             * カード登録失敗時には現段階では一律で別の登録カードを入れていただくように
             * 促すメッセージで統一。
             * カードエラーの類としては以下があるとのこと
             * １、カードが決済に失敗しました
             * ２、セキュリティーコードが間違っています
             * ３、有効期限が間違っています
             * ４、処理中にエラーが発生しました
             *  */
            return false;
        }
    }
    public function AddCard(Request $request){//card追加(顧客データあり、他のカード情報もすでにある状態)
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $last_url = url()->previous();
        $token     = $request->stripeToken;
        $user      = User::find(Session::get('u_id'));
        $all_card  = \Stripe\Customer::allSources($user->stripe_id);//全てのカードデータ取得
        try {
            if(!empty($user->stripe_id)){//顧客の登録データがある場合
                $customer = \Stripe\Customer::createSource($user->stripe_id,['card' => $token,]);
                $customer_db = Customer::find($user->id);//db呼び出し
                // foreach ($all_card['data'] as $key => $card_data) {
                //     $card[$key] = $card_data;
                // }//古いカードが入っている
                $customer_db = Customer::find($user->id);//db呼び出し
            }else{//新規顧客登録は存在しないが処理を書く
                // $customer = \Stripe\Customer::create([
                //     'card' => $token,
                //     'name' => $user->name,
                //     'description' => $user->id,
                // ]);
                // $user->stripe_id = $customer->id;
                // $customer_db = new Customer();
                // $customer_db->init();
                // $customer_db->id = $user->id;
                // $customer_db->stripe_id = $customer->id;
            }
            $user->save();
            $customer_db->save();
            $all_card = \Stripe\Customer::allSources($user->stripe_id);//全カード情報
        } catch(\Stripe\Exception\CardException $e) {
            /*
             * カード登録失敗時には現段階では一律で別の登録カードを入れていただくように
             * 促すメッセージで統一。
             * カードエラーの類としては以下があるとのこと
             * １、カードが決済に失敗しました
             * ２、セキュリティーコードが間違っています
             * ３、有効期限が間違っています
             * ４、処理中にエラーが発生しました
             *  */
            return false;
        }
        if(session::get('carts') && !strpos($last_url,'myprofile_payment')){//strpos:第1引数の文字列に、第2引数の文字列が含まれているかをチェック
            return redirect('/pay');
        }
        return redirect('/myprofile_payment');
    }
    public function SwitchCard(Request $request){
        $stripe_pk = env('STRIPE_KEY');
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $user     = User::find(Session::get('u_id'));
        $customer = \Stripe\Customer::retrieve($user->stripe_id);
        $old_source = $customer->default_source;
        // dd($old_source);
        $all_card  = \Stripe\Customer::allSources($user->stripe_id);//全てのカードデータ取得
        // dd($all_card);
        foreach ($all_card as $key => $card) {
            if($card['id'] == $request->c_id){
                var_dump($card['id']);
                $customer->default_source = $request->c_id;
            }
        }
        $customer->save();
        return redirect('/myprofile_payment');
    }
    public function DeleteCard(Request $request){
        $user     = User::find(Session::get('u_id'));
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $customer = \Stripe\Customer::retrieve($user->stripe_id);
        $card =\Stripe\Customer::retrieveSource($user->stripe_id,$request->c_id);//カード情報
        /* card情報が存在していれば削除 */
        if ($card) {
            \Stripe\Customer::deleteSource(
                $user->stripe_id,
                $card->id
            );
        }
        return redirect('/myprofile_payment');
    }
    public function Store(Request $request){
        /**
         * フロントエンドから送信されてきたtokenを取得
         * これがないと一切のカード登録が不可
         **/
        $token = $request->stripeToken;
        // var_dump($token);
        // var_dump('<br>');
        // var_dump('<br>');
        // var_dump('<br>');
        // var_dump($token);//ok
        $user = User::find(Session::get('u_id'));
        // dd($user);
        // var_dump($user);
        // var_dump('<br>');
        // var_dump('<br>');
        // var_dump('<br>');
        // var_dump($user);//ok
        // $user = User::find($request->id);
        // $user = Auth::user(); //要するにUser情報を取得したい
        $ret = null;
        // var_dump($token);
        // return;
        /**
         * 当該ユーザーがtokenもっていない場合Stripe上でCustomer（顧客）を作る必要がある
         * これがないと一切のカード登録が不可
         **/
        if ($token) {

            /**
             *  Stripe上にCustomer（顧客）が存在しているかどうかによって処理内容が変わる。
             *
             * 「初めての登録」の場合は、Stripe上に「Customer（顧客」と呼ばれる単位の登録をして、その後に
             * クレジットカードの登録が必要なので、一連の処理を内包しているPaymentモデル内のsetCustomer関数を実行
             *
             * 「2回目以降」の登録（別のカードを登録など）の場合は、「Customer（顧客」を新しく登録してしまうと二重顧客登録になるため、
             *  既存のカード情報を取得→削除→新しいカード情報の登録という流れに。
             *
             **/

            if (!$user->stripe_id) {
                $result = Payment::setCustomer($token, $user);

                /* card error */
                if(!$result){
                    $errors = "カード登録に失敗しました。入力いただいた内容に相違がないかを確認いただき、問題ない場合は別のカードで登録を行ってみてください。";
                    return redirect('public/form')->with('errors', $errors);
                }

            } else {
                $defaultCard = Payment::getDefaultcard($user);
                if (isset($defaultCard['id'])) {
                    Payment::deleteCard($user);
                }
                // return;

                $result = Payment::updateCustomer($token, $user);

                /* card error */
                if(!$result){
                    $errors = "カード登録に失敗しました。入力いただいた内容に相違がないかを確認いただき、問題ない場合は別のカードで登録を行ってみてください。";
                    return redirect('public/form')->with('errors', $errors);
                }
                var_dump($defaultCard);
                var_dump('<br>');
                var_dump('<br>');
                var_dump('<br>');
            }
        } else {
            return redirect('public/form')->with('errors', '申し訳ありません、通信状況の良い場所で再度ご登録をしていただくか、しばらく立ってから再度登録を行ってみてください。');
        }


        return redirect('payment')->with("success", "カード情報の登録が完了しました。");
    }
}