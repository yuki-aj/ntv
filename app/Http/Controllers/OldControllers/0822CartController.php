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
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use App\Models\Time;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\OAuth;
use Stripe\Stripe;
use Illuminate\Pagination\LengthAwarePaginator;
class CartController extends Controller {
  
    public function ItemInfo($s_id,$p_id){
        $cart = Session::get('carts');
        //パラメータを一緒に送る
        //変数の初期化
        $u_id = '';//空
        // $product = array();//配列
        $p_category = array();//配列
        //urlパラメータから飛んできたユーザidを元にモデルからそれぞれ商品、カテゴリーを特定
		    // $stores     = Store::where('t_store.store_status','=','1')->findOrFail($s_id);//お店情報。店舗idをパラメータ（$s_id）で送る
        $product = Product::findOrFail($p_id);//商品情報をパラメータ（$p_id）で送る。
        $file_name = '/img_files/product/product'.$product->id.'.jpg';
        if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
            $product->img = 'storage'.$file_name;
        }else{
            $product->img = '/img/product00.jpg';
        }
        $p_category = Category::findOrFail($product->c_id);
        if(Session::has('u_id')){//ユーザーid
            $u_id = Session::get('u_id');
        }
        return view('public/iteminfo',compact('cart','product','p_category','u_id'));
    }
    public function AddCart(Request $request){//商品追加
      // if( Session::get('kind') != 1 || Session::get('kind') != 3){ return redirect()->to('/');}// ユーザーか配達員以外の場合
      $carts = Session::get('carts');//セッションを$cartsボックスに入れる
      $last_url = url()->previous();// カート追加する前に居たページのURLを取得
      // if($request->isMethod('get')){ //getの場合
      //   if(empty($carts)){
      //     return view('shopping/cart');
      //   }
      // }
      // $p_id = Session::get('p_id');//セッションを$p_idボックスに入れる
      $temp_cart = array();
      $p_id = $request->p_id;//プロダクトのidを入れる
      $stores = Store::where('store_status','=','1')->get();
      $categories = Category::get();
      $products = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','1')
      ->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
      ->get();
      foreach ($products as $key2 => $product) {//$productsを回す
        $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
        if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
          $product->img = 'storage'.$file_name;
        }else{
          $product->img = '/img/product00.jpg';
        }
      }
      $option_1 = Option::where('t_option.id',$request->option_1)->first();
      $option_2 = Option::where('t_option.id',$request->option_2)->first();
      $option_3 = Option::where('t_option.id',$request->option_3)->first();
      if (is_null($carts)) {
          $carts = array();//空ならカートは配列
      }
      if(isset($request->p_id)){
        foreach ($stores as $key => $store) {//店舗を回して、idが一緒ならstripe_idを$cartsに入れる
            if($store->id == $request->s_id){
                $temp_cart['stripe_id'] = $store->stripe_user_id;//店舗stripe_id
                $temp_cart['s_id']      = $request->s_id;//店舗id
                $temp_cart['p_id']      = $request->p_id;//商品id
                $temp_cart['quantity']  = $request->quantity;//商品の個数
                $temp_cart['option_1']  = $option_1->id ?? "";//option1があればいれて、なければ空
                $temp_cart['option_2']  = $option_2->id ?? "";//option2があればいれて、なければ空
                $temp_cart['option_3']  = $option_3->id ?? "";//option3があればいれて、なければ空
                $temp_cart['options']   = [$temp_cart['option_1'],$temp_cart['option_2'],$temp_cart['option_3']];//option3があればいれて、なければ空
            }
        }
        if(!empty($carts)){//商品がカートにある場合
            array_push($carts,$temp_cart);//まずtemp_cartを追加する
            foreach($carts as $key => $cart){
                if($carts[$key]['p_id'] == $temp_cart['p_id'] && $carts[$key]['options'] == $temp_cart['options']){
                    //p_id同じで、optionも同じ場合、個数のみ変更する
                    $carts[$key]['quantity'] = $temp_cart['quantity'];//同じオプションの商品の配列全てを同じ個数にする
                }
            }
            $carts = array_unique($carts,SORT_REGULAR);//まったく同じ値のものを、削除して並べる
        }else{//新規カート追加の場合
            array_push($carts,$temp_cart);
            $temp_cart = '';
        }
      }
      Session::put('carts',$carts);//セッションに$cartsの中身を保存
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
        $carts[$key]['total'] = ($carts[$key]['price'] + $sum) * $carts[$key]['quantity'];//(商品価格+オプション金額)×商品数＝商品総額
      }
    }
    Session::put('pay_carts',$carts);//決済データの入ったカート
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
    foreach ($products as $key2 => $product) {//$productsを回す
      $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
      if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
        $product->img = 'storage'.$file_name;
      }else{
        $product->img = '/img/product00.jpg';
      }
    }
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
		// if(is_null($carts) || $carts == []){
		//   return redirect('/top2');
		// }
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
		$all_summary = 0;//総合計金額（初期値0）
		$p_border    = 1500;//送料価格基準
		$p_upper     = 3000;//送料価格基準
		$d_fee300    = 300;//送料金額（1500円以下）
		$d_fee600    = 600;//送料金額（3000円以上）
		$d_fee       = 0.2;//送料金額（1500~3000円の間）
		foreach ($new_carts as $stripe_id => $s_products) {//new_cartsを店舗ごとに回す($s_id = 商品id)
		  $summary = 0;//店舗ごとの合計金額は（初期値0）
		  foreach ($s_products as $product_id => $new_product) {//店舗の商品($s_product)ごとに回す
			$summary += $new_product['total'];//商品の数分足していく(商品×個数)
		  }
		  //店舗合計の送料計算
		  $postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
		  if($summary < $p_border){//1500円以下
			$summary = $summary + $postage[0]->price;
		  }elseif($summary > $p_upper){//3000円以上
			$summary = $summary + $postage[1]->price;
		  }else{
			$summary = $summary + $summary*$postage[2]->price;//1000~3000円の時
		  }
		  $all_summary += $summary;//全店舗総額
		}
    // dd($new_carts);
		Session::put('new_carts',$new_carts);//storeのstripe_idで並んでいるカート
		Session::put('pay_carts',$carts);//商品が整列せずに入っているカート
		// dd(Session::get('pay_carts'));
    if($request->p_id){//postの場合
      return Redirect::to($last_url)->send();
    }
		return view('shopping/cart',compact('all_summary','stores','new_carts','carts','categories','products'));
    // return view('public/cart',compact('carts','categories','stores','products'));
    }
    public function CartView(Request $request){

    }
    
    public function ChangeQuantity(Request $request){
      $carts = Session::get('carts');//セッションを$cartsボックスに入れる
      if($request->isMethod('get')){ //getの場合
        if(empty($carts)){
          return view('public/cart');
        }
      }
      $temp_cart = array();
      $stores = Store::where('store_status','=','1')->get();
      $categories = Category::get();
      $products = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','1')
      ->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
      ->get();
      foreach ($products as $key2 => $product) {//$productsを回す
        $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
        if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
          $product->img = 'storage'.$file_name;
        }else{
          $product->img = '/img/product00.jpg';
        }
      }
      $option_1 = Option::where('t_option.id',$request->option_1)->first();
      $option_2 = Option::where('t_option.id',$request->option_2)->first();
      $option_3 = Option::where('t_option.id',$request->option_3)->first();
      if (is_null($carts)) {
          $carts = array();//空ならカートは配列
      }
      if(isset($request->p_id)){
        foreach ($stores as $key => $store) {//店舗を回して、idが一緒ならstripe_idを$cartsに入れる
            if($store->id == $request->s_id){
                $temp_cart['stripe_id'] = $store->stripe_user_id;//店舗stripe_id
                $temp_cart['s_id']      = $store->id;//店舗id
                $temp_cart['p_id']      = $request->p_id;//商品id
                $temp_cart['quantity']  = $request->quantity;//商品の個数
                $temp_cart['option_1']  = $request->option_1 ?? "";//option1があればいれて、なければ空
                $temp_cart['option_2']  = $request->option_2 ?? "";//option2があればいれて、なければ空
                $temp_cart['option_3']  = $request->option_3 ?? "";//option3があればいれて、なければ空
                $temp_cart['options']   = [$temp_cart['option_1'],$temp_cart['option_2'],$temp_cart['option_3']];//option3があればいれて、なければ空
            }
        }
        // dd($carts);
        if(!empty($carts)){//商品がカートにある場合
            array_push($carts,$temp_cart);//まずtemp_cartを追加する
            foreach($carts as $key => $cart){
                if($carts[$key]['p_id'] == $temp_cart['p_id'] && $carts[$key]['options'] == $temp_cart['options']){
                    //p_id同じで、optionも同じ場合、個数のみ変更する
                    $carts[$key]['quantity'] = $temp_cart['quantity'];//同じオプションの商品の配列全てを同じ個数にする
                }
            }
            $carts = array_unique($carts,SORT_REGULAR);//まったく同じ値のものを、削除して並べる
        }else{//新規カート追加の場合
            array_push($carts,$temp_cart);
            $temp_cart = '';
        }
      }
      // dd($carts);
      Session::put('carts',$carts);//セッションに$cartsの中身を保存
		//カート処理
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
        $carts[$key]['total'] = ($carts[$key]['price'] + $sum) * $carts[$key]['quantity'];//(商品価格+オプション金額)×商品数＝商品総額
      }
    }
    Session::put('pay_carts',$carts);//決済データの入ったカート
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
    foreach ($products as $key2 => $product) {//$productsを回す
      $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
      if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
        $product->img = 'storage'.$file_name;
      }else{
        $product->img = '/img/product00.jpg';
      }
    }
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
		$all_summary = 0;//総合計金額（初期値0）
		$p_border    = 1500;//送料価格基準
		$p_upper     = 3000;//送料価格基準
		$d_fee300    = 300;//送料金額（1500円以下）
		$d_fee600    = 600;//送料金額（3000円以上）
		$d_fee       = 0.2;//送料金額（1500~3000円の間）
		foreach ($new_carts as $stripe_id => $s_products) {//new_cartsを店舗ごとに回す($s_id = 商品id)
		  $summary = 0;//店舗ごとの合計金額は（初期値0）
		  foreach ($s_products as $product_id => $new_product) {//店舗の商品($s_product)ごとに回す
			$summary += $new_product['total'];//商品の数分足していく(商品×個数)
		  }
		  //店舗合計の送料計算
		  $postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
		  if($summary < $p_border){//1500円以下
			$summary = $summary + $postage[0]->price;
		  }elseif($summary > $p_upper){//3000円以上
			$summary = $summary + $postage[1]->price;
		  }else{
			$summary = $summary + $summary*$postage[2]->price;//1000~3000円の時
		  }
		  $all_summary += $summary;//全店舗総額
		}
      Session::put('new_carts',$new_carts);//storeのstripe_idで並んでいるカート
      Session::put('pay_carts',$carts);//商品が整列せずに入っているカート
      return redirect('add_cart');
      // return view('public/cart',compact('stores','products','categories','new_carts','all_summary'));
    }
    public function DeleteCart(Request $request){//商品削除
      $carts     = Session::get('carts');
      if($request->isMethod('get')){ //getの場合
        if(empty($carts)){
          return view('shopping/cart');
        }
      }
      $temp_cart = array();
      $stores = Store::where('store_status','=','1')->get();
      $categories = Category::get();
      $products = Product::join('t_store','t_store.id', '=','t_product.s_id')->where('t_product.p_status','1')
      ->select('t_product.id as p_id','t_product.s_id as s_id','t_product.name as p_name','t_product.price as p_price','t_product.note as p_note','t_store.name as s_name','t_store.address as s_address','t_store.stripe_user_id as s_uid')
      ->get();
      foreach ($products as $key2 => $product) {//$productsを回す
        $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
        if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
          $product->img = 'storage'.$file_name;
        }else{
          $product->img = '/img/product00.jpg';
        }
      }
      $option_1 = Option::where('t_option.id',$request->option_1)->first();
      $option_2 = Option::where('t_option.id',$request->option_2)->first();
      $option_3 = Option::where('t_option.id',$request->option_3)->first();
      if (is_null($carts)) {
          $carts = array();//空ならカートは配列
      }
      if(isset($request->p_id)){
        foreach ($stores as $key => $store) {//店舗を回して、idが一緒ならstripe_idを$cartsに入れる
            if($store->id == $request->s_id){
                $temp_cart['stripe_id'] = $store->stripe_user_id;//店舗stripe_id
                $temp_cart['s_id']      = $store->id;//店舗id
                $temp_cart['p_id']      = $request->p_id;//商品id
                $temp_cart['quantity']  = $request->quantity;//商品の個数
                $temp_cart['option_1']  = $request->option_1 ?? "";//option1があればいれて、なければ空
                $temp_cart['option_2']  = $request->option_2 ?? "";//option2があればいれて、なければ空
                $temp_cart['option_3']  = $request->option_3 ?? "";//option3があればいれて、なければ空
                $temp_cart['options']   = [$temp_cart['option_1'],$temp_cart['option_2'],$temp_cart['option_3']];//option3があればいれて、なければ空
            }
        }
        if(!empty($carts)){//商品がカートにある場合
            array_push($carts,$temp_cart);//まずtemp_cartを追加する
            // dd($carts);
            foreach($carts as $key => $cart){
                if($carts[$key]['p_id'] == $temp_cart['p_id'] && $carts[$key]['options'] == $temp_cart['options']){
                  unset($carts[$key]);
                  //p_id同じで、optionも同じ場合、個数のみ変更する
                    // $carts[$key]['quantity'] = $temp_cart['quantity'];//同じオプションの商品の配列全てを同じ個数にする
                }
            }
            $carts = array_unique($carts,SORT_REGULAR);//まったく同じ値のものを、削除して並べる
        // }else{//新規カート追加の場合
        //     array_push($carts,$temp_cart);
        //     $temp_cart = '';
        }
        // dd($carts);
      }
      // dd($carts);
      Session::put('carts',$carts);//セッションに$cartsの中身を保存
		//カート処理
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
        $carts[$key]['total'] = ($carts[$key]['price'] + $sum) * $carts[$key]['quantity'];//(商品価格+オプション金額)×商品数＝商品総額
      }
    }
    Session::put('pay_carts',$carts);//決済データの入ったカート
		$products  = Product::where('t_product.p_status','1')->get();//statusが有効な物を$productsに入れる
    foreach ($products as $key2 => $product) {//$productsを回す
      $file_name = '/img_files/product/product'.$product->p_id.'.jpg';
      if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){
        $product->img = 'storage'.$file_name;
      }else{
        $product->img = '/img/product00.jpg';
      }
    }
		$new_carts = array();//new_cartsは配列
		$new_products = array();//new_productsは配列
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
		$all_summary = 0;//総合計金額（初期値0）
		$p_border    = 1500;//送料価格基準
		$p_upper     = 3000;//送料価格基準
		$d_fee300    = 300;//送料金額（1500円以下）
		$d_fee600    = 600;//送料金額（3000円以上）
		$d_fee       = 0.2;//送料金額（1500~3000円の間）
		foreach ($new_carts as $stripe_id => $s_products) {//new_cartsを店舗ごとに回す($s_id = 商品id)
		  $summary = 0;//店舗ごとの合計金額は（初期値0）
		  foreach ($s_products as $product_id => $new_product) {//店舗の商品($s_product)ごとに回す
			$summary += $new_product['total'];//商品の数分足していく(商品×個数)
		  }
		  //店舗合計の送料計算
		  $postage = Product::where('t_product.p_status','2')->get();//DBから送料を取得
		  if($summary < $p_border){//1500円以下
			$summary = $summary + $postage[0]->price;
		  }elseif($summary > $p_upper){//3000円以上
			$summary = $summary + $postage[1]->price;
		  }else{
			$summary = $summary + $summary*$postage[2]->price;//1000~3000円の時
		  }
		  $all_summary += $summary;//全店舗総額
		}
      Session::put('new_carts',$new_carts);//storeのstripe_idで並んでいるカート
      Session::put('pay_carts',$carts);//商品が整列せずに入っているカート
      return redirect('add_cart');
      // $new_carts = Session::get('pay_carts');
      // dd($carts);
    }
}