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
use App\Models\Option;
use App\Models\Product;
use App\Models\Time;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\OAuth;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Illuminate\Pagination\LengthAwarePaginator;
class HomeController extends Controller {
  
	public function Top2(Request $request){
		CommonController::AccessLog();
		// $categories  = Category::getList($locale);
		// $users      = User::where('t_user.user_status','=','1')->get();
		$categories = Category::get();//カテゴリー名
    $query = Product::join('t_store', 't_store.id', '=', 't_product.s_id');//$queryにストアのidとプロダクトの$s_idが同じものを入れる
		$query->where('t_store.store_status','=','1')->where('t_product.p_status','=','1');//お店も商品もstatusが有効な物
		if ($request->isMethod('POST') || $request->isMethod('GET')) {//(検索条件あり)のケース
			if ($request->has('name')) {//フリーワード検索(検索条件は商品名)
				$name = 't_product.name';
			 	$query->where($name, 'like', "%{$request->name}%");
			}
		}else if($request->has('name')){ //GET(検索条件あり)のケース。フリーワード検索(検索条件は商品名)
			$name = 't_product.name';
			$query->where($name, 'like', "%{$request->name}%");
			$category = $request->category;
		}
    $products = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','1')
    ->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
    ->get();
		date_default_timezone_set('Asia/Tokyo');//日本時間
		$now = Carbon::now("Asia/Tokyo");//現在時刻取得 $now->day(日にち),$now->dayOfWeek(曜日),$now->weekOfMonth(月の何週目か)
		$open_shops = Time::join('t_store','t_store.id','=','t_time.s_id')->where('t_store.store_status','=','1')->where('t_time.open_time','<=',$now)->where('t_time.end_time','>=',$now)->get();
		//営業時間内のお店
		$r_stores     = Time::join('t_store','t_store.id','=','t_time.s_id')->where('t_store.store_status','=','1')->where('t_time.r_time','<=',$now)->where('t_time.r_end_time','>=',$now)->get();
		//予約可能時間内のお店
		$stores     = Time::join('t_store','t_store.id','=','t_time.s_id')->where('t_store.store_status','=','1')->get();//statusが1のお店（有効）
		$compare_date  = (new DateTime())->modify('-1 week')->format('Y-m-d H:i:s');//1週間前の日付
		$new_shops      = Store::where('t_store.store_status','=','1')->latest()->get();//新着店舗(登録日が新しい順)
		// $new_shops      = Store::where('t_store.store_status','=','1')->where('t_store.created_at','>=',$compare_date)->get();//新着店舗(初回店舗情報が入力されてから１週間のみ表示)
    // foreach($shops as $key => $shop){
		// 	$file_name = '/img_files/store/store'.$shop->id.'.jpg';
		// 	if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
		// 		$shop->img = 'storage'.$file_name;
		// 	}else{
		// 		$shop->img = '/img/store00.jpg';
		// 	}
		// }
		foreach($stores  as $key1 =>$store){//$storesを回す
			$file_name = '/img_files/store/store'.$store->id.'.jpg';
			if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
				$store->img = 'storage'.$file_name;
			}else{
				$store->img = '/img/store00.jpg';
			}
    }
    foreach ($categories as $key2 => $category) {//$categoriesを回す
      if ($key2 == $store->c_id) {
        $c_name = $category->name;
        break;//c_idと$key2が同じだったら、名前を入れる。$c_name = $category->name
      }
    }
    foreach ($products as $key2 => $product) {//$productsを回す
      $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
      if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
        $product->img = 'storage'.$file_name;
      }else{
        $product->img = '/img/product00.jpg';
      }
    }
		//カート処理
    // Session::forget('carts');
    $carts     = Session::get('carts');//$cartsにsession('carts')を入れる
    $p_tables  = Product::where('t_product.p_status','1')->get();//productテーブルの有効なものを取得
    $cart_p    = array();//配列
    $cart_alls = array();//配列
    $s_id      = '';
    $total     = 0;//総額
    Session::put('carts',$carts);//決済データにする前のcartsを保存
    if(!empty($carts)){//カートが空じゃなかったら入る
      array_multisort(array_column($carts,'s_id'),SORT_ASC,$carts);//店舗のid順に昇順で並び替え
      // $cart_alls = $carts;//cart_allsに詰め替え
      foreach($carts as $key =>$cart){//cartsを回す
        foreach ($p_tables as $key2 => $p_table) {//productテーブルを回す
          $cart_p[$p_table->id] = $p_table;//$cart_pのidにプロダクトidの商品情報を入れる(商品idが3なら、$cart_p[3]にid3の商品情報が入る)
          if($carts[$key]['p_id'] == $cart_p[$p_table->id]->id){//カートの商品idとテーブルの商品idが一緒なら入る
            $carts[$key]['name']  =  $cart_p[$p_table->id]->name;//商品名を入れる
            $carts[$key]['price'] =  $cart_p[$p_table->id]->price;//商品金額
            $carts[$key]['note']  =  $cart_p[$p_table->id]->note;//商品説明
          }
        }
        //オプション名と金額入れる
        $sum = 0;//総額初期値0
        if(!empty($carts[$key]['option_1'])){//オプション1が空じゃなかったら
          $option_1 = Option::find($carts[$key]['option_1']);//idが一致するものを入れる
          $carts[$key]['o_name1']  = $option_1['note'].$option_1['name'];//一致したidのオプションとオプション名を入れる
          $carts[$key]['o_price1'] = $option_1['price'];//オプション金額を入れる
          $sum += $carts[$key]['o_price1'];//オプション合計に足していく
        }
        if(!empty($carts[$key]['option_2'])){//オプション2が空じゃなかったら
          $option_2 = Option::find($carts[$key]['option_2']);
          $carts[$key]['o_name2']  = $option_2['note'].$option_2['name'];
          $carts[$key]['o_price2'] = $option_2['price'];
          $sum += $carts[$key]['o_price2'];
        }
        if(!empty($carts[$key]['option_3'])){//オプション3が空じゃなかったら
          $option_3 = Option::find($carts[$key]['option_3']);
          $carts[$key]['o_name3']  = $option_3['note'].$option_3['name'];
          $carts[$key]['o_price3'] = $option_3['price'];
          $sum += $carts[$key]['o_price3'];
        }
        //オプションと商品ごとの総額計算
        // $carts[$key]['options'][0] = $option_1['price'] ?? 0;//1つ目のオプションを0に入れる
        // $carts[$key]['options'][1] = $option_2['price'] ?? 0;//2つ目のオプションを1に入れる
        // $carts[$key]['options'][2] = $option_3['price'] ?? 0;//3つ目のオプションを2に入れる
        $carts[$key]['total'] = ($carts[$key]['price'] + $sum) * $carts[$key]['quantity'];//(商品価格+オプション金額)×商品数＝商品総額
      }
    }
    Session::put('pay_carts',$carts);//決済データの入ったカート
		return view('public/top2',compact('s_id','carts','categories','stores','new_shops','products'));
	}

  public function Cart2(Request $request){
    // return redirect('/top2');
    $carts = Session::get('pay_carts');
    // dd($carts);
		$stores    = Store::where('t_store.store_status','1')->get();//statusが1のお店（有効）
    $products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
    $new_carts = array();//new_cartsは配列
    $new_products = array();//new_productsは配列
    if(is_null($carts) || $carts == []){
      return redirect('/top2');
    }
    foreach ($carts as $key => $cart) {
      foreach ($stores as $store_id => $store) {//storeを回す
        if($store->id == $cart['s_id']){//storeのidとカート内の商品のs_idが同じなら入る
          foreach ($products as $key2 => $product) {//Productテーブルを回す
            if($product->id == $cart['p_id']){//Productテーブルのidと、カート内の商品idが同じだったら
              $new_carts[$store->stripe_user_id][$key] = $cart;//$new_carts[stripe_id][商品id]に商品情報を入れる
              $s_name = Store::find($cart['s_id']);
              $new_carts[$store->stripe_user_id][$key]['s_name'] = $s_name->name;
              break;
            }
          }
        }
      }
    }
    // dd($new_carts);
    $all_summary = 0;//総合計金額（初期値0）
    $p_border    = 1500;//送料価格基準
    $p_upper     = 3000;//送料価格基準
    $d_fee300    = 300;//送料金額（1500円以下）
    $d_fee600    = 600;//送料金額（3000円以上）
    $d_fee       = 0.2;//送料金額（1500~3000円の間）
    // $count       = 0;//注文個数
    foreach ($new_carts as $stripe_id => $s_products) {//new_cartsを店舗ごとに回す($s_id = 商品id)
      $summary = 0;//店舗ごとの合計金額は（初期値0）
      foreach ($s_products as $product_id => $new_product) {//店舗の商品($s_product)ごとに回す
        $summary += $new_product['total'];//商品の数分足していく(商品×個数)
      }
			//店舗合計の送料計算
			// $postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
			if($summary < $p_border){//1500円以下
		  		$postage = Product::where('p_status','2')->where('price',300)->first();//DBから送料を取得
				$summary = $summary + $postage->price;
			}elseif($summary > $p_upper){//3000円以上
		  		$postage = Product::where('p_status','2')->where('price',600)->first();//DBから送料を取得
				$summary = $summary + $postage->price;
			}else{
		  		$postage = Product::where('p_status','2')->where('name','送料20%')->first();//DBから送料を取得
				$summary = $summary + $summary*$postage->price;//1000~3000円の時
			}
      $all_summary += $summary;//全店舗総額
    }
    Session::put('pay_carts',$carts);
    return view('public/cart2',compact('all_summary','stores','new_carts','carts'));
  }


    /**
     * Connectの子アカウント作成
     * @return \Illuminate\Contracts\Support\Renderable
     */ 
    // public function index() {
    //     return view('public/home');
    // }
    public function Stripe(Request $request){
        // Session::forget('status');
        // var_dump('hello');
        return view('public/stripe');
    }
    public function Complete(Request $request){
        // Session::forget('status');
        // var_dump('hello');
        return view('public/complete');
    }
    
    public function connect(){//子ユーザーコネクト作成
      define('CLIENT_ID', 'ca_LQ20m0JabCqBK68DfrvUy77ZrzJm0oMi');//connectの設置ページにある
      define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
      define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');
      if (isset($_GET['code'])) { // Redirect/ code
        $code = $_GET['code'];
        $token_request_body = array(
          'client_secret' => env('STRIPE_SECRET'),
          'grant_type' => 'authorization_code',
          'client_id' => CLIENT_ID,
          'code' => $code,
        );
        $req = curl_init(TOKEN_URI);
        curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($req, CURLOPT_POST, true );
        curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
        // TODO: Additional error handling
        $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
        $resp = json_decode(curl_exec($req), true);
        var_dump($resp);
        var_dump('<br>');
        curl_close($req);
        var_dump($req);
        // return;

        // $id = Auth::id();//user_id取得
        $id = User::where('t_user.id','=','44')->first();//user_id取得
        \App\Models\User::where('id', $id)->update(['stripe_user_id' => $resp['stripe_user_id']]);

        return redirect()->back();
      } else if (isset($_GET['error'])) { // Error
        echo $_GET['error_description'];
      } else { // Show OAuth link
        $authorize_request_body = array(
          'response_type' => 'code',
          'scope' => 'read_write',
          'client_id' => CLIENT_ID,
        );
        $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);

        return view('public/connect')->with('url',$url);
      }
}

    // public function Connect(Request $request)
    // {
    //     $code = $request->query('code');
    //     if (empty($code)) {
    //         return redirect('/home');
    //     }

    //     Stripe::setApiKey(config('app.stripe_secret'));

    //     // アクセストークン取得
    //     $params = [
    //         'grant_type' => 'authorization_code',
    //         'code' => $code,
    //     ];
    //     $response = OAuth::token($params);
    //     // echo($response);
        
    //     // StripeアカウントIDを保存
    //     $store = Store::where('t_store.store_status','=','1')->first();
    //     // var_dump("----------------------");
    //     // var_dump($store);
    //     // var_dump("----------------------");
    //     $store->stripe_s_id = $response->stripe_user_id;
    //     $store->save();
    //     echo("----------------------");
    //     echo($store);
    //     echo("----------------------");
    //     // return;
    //     $temp_session = Session::put('old_status',$store->stripe_s_id);
    //     var_dump($temp_session);
    //     // return;
    //     if(Session::has('old_status')){
    //         Session::put('status',$store->stripe_s_id);
    //         Session::forget('old_status');
    //     }
    //     return redirect('/home');
    // }
}