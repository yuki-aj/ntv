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
use App\Http\Controllers\LineMessengerController;
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
class AjaxController extends Controller {
  
	public function AddModal(Request $request){//モーダル表示
    $result  = [];//これに全部詰める
    $o_title = '';//optionのタイトル
    $o_text = '';//optionの選択肢のテキスト
    $option_box = '';//option全てを入れる箱
    log::info($request->s_id);
    $product = Product::where('s_id',$request->s_id)->where('id',$request->p_id)->first();//商品一つ
    $o_ids = explode(',', $product->o_ids);
    $options = Option::where('s_id',$request->s_id)->whereIn('o_id',$o_ids)->orderBy('o_id', 'asc')->get();//商品のオプション
    $w_o_id = 0;
    foreach($options as $key => $option){//optionを回す
      $option->title = "";//二回目以降の為に空にする
      $option->title = $option->o_name;
        if(!isset($option->title)){
          $o_title = "</div>";//タイトルがあれば閉じタグを追加
        }
        if($w_o_id != $option->o_id){
          $o_title .= "<div class='option-box'>
          <div class='o_title'>$option->title</div>";
          $w_o_id = $option->o_id;
        }
         
      // }//optionのタイトルを入れる
      if($option->price == 0){//オプションで金額の変更がない場合
        $option->price = "";
      }else{//ある場合
        $option->price = $option->price."円";
      }
      //optionの項目追加
      $o_text .= "<div class='option_single'><input class='radio_button' id='option_$option->id' type='radio' name='option_$option->o_id' value='$option->id'>
                    <label for='option_$option->id'>$option->name $option->price</label></div>";
      $option_box = $o_title .= $o_text;//optionの大きな箱に、タイトルと項目を詰めていく
      $o_text = "";//項目をいったん空にする
      if($option == end($options)){//項目が最後なら、閉じタグを追加
        $option_box .= "</div>";
      }
    }
    $file_name = '/product_image/'.$request->p_id.'.jpg';
    if(Storage::disk(env('STORAGE_ENV'))->exists('public'.$file_name)){//envのstorage_envにアクセスして、ファイルがあるかチェック(exists)
      $product->img = '/storage'.$file_name;//url + 画像のパス
    }else{
      $product->img = '/img/product00.jpg';
    }
    $result = [
      'p_name'     => $product->name,
      'p_price'    => $product->price,
      'p_note'     => $product->note,
      's_id'       => $request->s_id,
      'p_id'       => $request->p_id,
      'o_id'       => $product->o_id,
      'p_img'      => $product->img,
      'quantity'   => $request->quantity,
      'options'    => $options,
      'option_box' => $option_box,
    ];
    if(empty($result)){
      return false;
    }
    return $result;
  }
  public function MdlCart(Request $request){//商品追加
      $carts = Session::get('carts');//セッションを$cartsボックスに入れる
      $p_id = $request->p_id;//プロダクトのidを入れる
      $stores = Store::where('t_store.store_status','=','1')->get();
      if (is_null($carts)) {
          $carts = array();//空ならカートは配列
      }
      foreach ($stores as $key => $store) {//店舗を回して、idが一緒ならstripe_idを$cartsに入れる
          if($store->id == $request->s_id){
              $carts[$p_id]['stripe_id'] = $store->stripe_user_id;//店舗stripe_id
          }
      }
      $carts[$p_id]['s_id']      = $request->s_id;//店舗id
      $carts[$p_id]['p_id']      = $request->p_id;//商品id
      $carts[$p_id]['quantity']  = $request->p_quantity;//商品の個数
      Session::put('carts',$carts);//セッションに$cartsの中身を保存
      //カート処理
      $p_tables  = Product::where('t_product.p_status','1')->get();//productテーブルの有効なものを取得
      $cart_p    = array();//配列
      $cart_alls = array();//配列
      $total     = 0;//総額
      if(isset($carts)){//商品がカートにある場合
        foreach ($p_tables as $key => $p_table) {//productテーブルを回す
          $cart_p[$p_table->id] = $p_table;//$cart_pのプロダクトID番目にプロダクトIDの商品情報を入れる(商品idが3なら、$cart_p[3]にid3の商品情報が入る)
          foreach ($carts as $key2 => $cart) {//カートの中身を回す($key2には商品idが入る)
            $total     = 0;//総額
            if(is_array($cart) && $cart['p_id'] == $cart_p[$p_table->id]->id){//カートの中身のidと、productテーブルのidが同じ場合
            $cart_alls[$key2] = $cart_p[$p_table->id];//$cart_allsの[商品id番]の中身は、商品id番の商品データが入る
            $s_id = $cart_alls[$key2]['s_id'];
            $cart_alls[$key2]['quantity'] = $cart['quantity'];//$cart_allsの[商品id番]['購入個数']に、sessionのquantity（$cart['quantity']）を入れる
            $total += $cart_alls[$key2]['price'] * $cart_alls[$key2]['quantity'];//商品価格×商品数＝総額
            $cart_alls[$key2]['total'] = $total;
            }
          }
        }
      }
      Session::put('cart_alls',$cart_alls);
      $cart_text = "";
      foreach ($cart_alls as $key => $cart) {
        //ここでHTMLを作成する
        $cart_text .= "<div class='cart-box'>
                          <div class='cart-name'>商品名 $cart->name</div>
                          <!-- <div class='cart-note'>コメント $cart->note</div> -->
                          <div class='cart-price'>価格 $cart->price<span>円</span></div>
                          <div class='cart-quantity'>個数 $cart->quantity<span>個</span></div>
                          <div class='cart-total'>商品合計金額 $cart->total<span>円</span></div>
                          <form class='dlc csrf' method='POST' id='cart_$key'>
                          <input type='hidden' name='s_id' value=$cart->s_id>
                          <input type='hidden' name='p_id' value=$key>
                          <!-- <label>削除する数</label> -->
                          <input type='hidden' value='0' name='quantity'>
                          <button class='delete_cart' type=''>削除する</button>
                          </form>
                        </div>";
                        if($cart == end($cart_alls)){
                          $cart_text .= "<a href='/cart2'>カートへ進む</a>";
                        }
      }
      return response()->json($cart_text);
  }
  public function DeleteCart(Request $request){
    $carts     = Session::get('carts');//決済データの入ったカート
    $new_carts = Session::get('pay_carts');//決済データの入ったカート
    $result = [];
    $cart_number = '';
    $total = '';
    foreach ($carts as $key => $cart) {
      if($cart['p_id'] == $request->p_id){
        if($cart['option_1'] == $request->option_1 && $cart['option_2'] == $request->option_2 && $cart['option_3'] == $request->option_3){
          unset($carts[$key]);
        }
      }
    }
    foreach ($new_carts as $key => $new_cart) {
        if($new_cart['p_id'] == $request->p_id && $new_cart['option_1'] == $request->option_1 && $new_cart['option_2'] == $request->option_2 && $new_cart['option_3'] == $request->option_3){
          $cart_number = $key;
          $total = $new_cart['total'];
          unset($new_carts[$key]);
        }
        // else{
        //   //ここでHTMLを作成する
        //     $cart_text .= "<div class='cart-box'>
        //                       <div class='cart-s_name'>店舗名 {$new_cart['s_name']}</div>
        //                       <div class='cart-name'>商品名 {$new_cart['name']}</div>
        //                       <div class='cart-price'>単価 {$new_cart['price']}<span>円</span></div>
        //                       <div class='cart-quantity'>個数 {$new_cart['quantity']}<span>個</span></div>
        //                       <div class='cart-total'>商品合計金額 {$new_cart['total']}<span>円</span></div>
        //                       <form class='dlc csrf' method='POST' id='cart_$key'>
        //                       <input type='hidden' name='s_id' value={$new_cart['s_id']}>
        //                       <input type='hidden' name='p_id' value={$new_cart['p_id']}>
        //                       <!-- <label>削除する数</label> -->
        //                       <input type='hidden' value='0' name='quantity'>
        //                       <button class='delete_cart' type='button'>削除する</button>
        //                       </form>
        //                     </div>";
        // }
    }
    $result =[
      'number' => $cart_number,
      'total'  => $total,
    ];
    Session::put('carts',$carts);//決済データの入ったカート
    Session::put('pay_carts',$new_carts);//決済データの入ったカート
    return response()->json($result);
  }

  public function LineId(Request $request){
    $line_name = $request->line_user_name;
    $line_id   = array();
    $line_id   = LineMessengerController::LineName($line_name);
    // return  response()->json($line_id);
    $name ="";
    if($line_id == null){
      $name = "<div class='line_responce text-danger'>ユーザー名が存在しません</div>";
      return  response()->json($name);
    }else{
      foreach ($line_id as $key => $line) {
        if(reset($line_id) == $line) {
          $name .= "<form name='line' class='csrf flexbox space-between line_submit'><select id='line_u_id' name='line_u_id' class='padding10'>";
        }
        $name .= "<option name='l_name_select' value='{$line['userId']}'>{$line['displayName']}</option>";
        if(end($line_id) == $line){
          $name .= "</select><input id='line-data' type='button' onclick='line_send()' value='決定'></button>";
        }
      }
      return  response()->json($name);
    }
  }
}