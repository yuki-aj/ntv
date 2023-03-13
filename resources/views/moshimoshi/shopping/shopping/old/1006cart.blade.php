@include('public/header')
<!-- <div class="header_img">
    <a href="/">
        <img src="{{url('/img/header.jpg')}}">
    </a>
</div> -->
<!-- <section class=""> -->
<h1 class="baskets">お買い物カゴ</h1>
@if($new_carts)
<?php $count = 0?>
@foreach ($new_carts as $stripe_id => $s_products)
<?php $summary = 0 ?>
<div class="cartbox" style="margin-bottom:0;">
    @foreach ($s_products as $key => $new_product)
    @if(reset($s_products) == $new_product)
    <!-- <h2 class="left"><a href="/search" class="btn-circle-3d-emboss"><</a></h2> -->
    <!-- <button onclick="location.href='/shop/{{$new_product['s_id']}}'" class="return_shop_button">戻る</button> -->
    <!-- <button onclick="location.href='/shop/{{$new_product['s_id']}}'" class="btn-circle m_btm2"><</button> -->
    <div class="border" style="margin-bottom: 5%;">    
        <div class="b_color">
            <div class="storebox" style="padding:0 3%;">
                <div class="flexs" style="align-items:center">
                    <button onclick="location.href='/shop/{{$new_product['s_id']}}'" class="btn-circle"><</button>
                    <h2>{{$new_product['s_name']}}</h2>
                </div>
                @foreach($stores as $store)
                @if($store->stripe_user_id == $stripe_id)
                <!-- <div class="flexbox space-between">
                    <h3 class="">{{$store->address}}</h3>
                </div> -->
                @endif
                @endforeach
            </div>
        </div>
        @endif
        <div class="under-line">
            <div class="flexbox space-between padding3 cart-box" style="flex-wrap:wrap;">
                <p class="bold" style="width:100%;">{{$new_product['name']}}</p>
                @foreach($products as $product)
                @if($new_product['p_id'] == $product->id)
                <!-- <img class="productimg" src="{{URL($product->img)}}"> -->
                <img class="productimg" src="/storage/product_image/{{$product->id}}.jpg">
                @endif
                @endforeach
                <div class="">
                    <div class="flexbox space-between">
                        <p>商品単価</p>
                        <p>{{$new_product['price']}}円</p>
                    </div>
                    <div>
                        @if(isset($new_product['o_name1']) || isset($new_product['o_name2']) || isset($new_product['o_name3']) || isset($new_product['o_name4']))
                        <p class="bold b_color_gray">オプション</p>
                        @endif
                        @if(isset($new_product['o_name1']))
                        <div class="flexbox space-between">
                            <p>・{{$new_product['o_name1']}}</p>
                            <p>{{$new_product['o_price1'].'円'}}</p>
                        </div>
                        @endif
                        @if(isset($new_product['o_name2']))
                        <div class="flexbox space-between">
                            <p>・{{$new_product['o_name2']}}</p>
                            <p>{{$new_product['o_price2'].'円'}}</p>
                        </div>
                        @endif
                        @if(isset($new_product['o_name3']))
                        <div class="flexbox space-between">
                            <p>・{{$new_product['o_name3']}}</p>
                            <p>{{$new_product['o_price3'].'円'}}</p>
                        </div>
                        @endif
                        @if(isset($new_product['o_name4']))
                        <div class="flexbox space-between">
                            <p>・{{$new_product['o_name4']}}</p>
                            <p>{{$new_product['o_price4'].'円'}}</p>
                        </div>
                        @endif
                    </div>
                    <div class="flexbox space-between">
                        <p>個数</p>
                        <p>{{$new_product['quantity']}}個</p>
                    </div>
                    <div class="flexbox space-between">
                        <p>商品合計</p>
                        <p class="f_size24"><span>{{$new_product['total']}}</span>円</p>
                    </div>
                </div>
            </div>
            <div class="flexbox flex-right padding3">
                <div class="flexbox padding3">
                    <form method="POST" action="{{url('/change_quantity')}}" id="change_quantity_{{$key}}">
                        @csrf
                        <input type="hidden" value="{{$key}}" name="p_key">
                        <input type="hidden" value="{{$new_product['s_id']}}" name="s_id">
                        <input type="hidden" value="{{$new_product['p_id']}}" name="p_id">
                        @if(isset($new_carts[$stripe_id][$key]['option_1']))
                        <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_1']}}" name="option_1">
                        @endif
                        @if(isset($new_carts[$stripe_id][$key]['option_2']))
                        <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_2']}}" name="option_2">
                        @endif
                        @if(isset($new_carts[$stripe_id][$key]['option_3']))
                        <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_3']}}" name="option_3">
                        @endif
                        @if(isset($new_carts[$stripe_id][$key]['option_4']))
                        <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_4']}}" name="option_4">
                        @endif
                        <label>個数
                            <select onchange="submit(this.form)" class="" name="quantity">
                                <option disabled selected>変更</option>
                                <?php for($i = 1; $i <= 100; $i++){?>
                                <option value="{{$i}}">{{$i}}</option>
                                <?php } ?>
                            </select>
                        </label>
                    </form>
                </div>
                <form method="POST" action="{{url('/delete_cart')}}">
                    @csrf
                    <input type="hidden" name="s_id" value="{{$new_product['s_id']}}">
                    <input type="hidden" name="p_id" value="{{$new_product['p_id']}}">
                    <input type="hidden" name="option_1" value="{{$new_product['option_1'] ?? ''}}">
                    <input type="hidden" name="option_2" value="{{$new_product['option_2'] ?? ''}}">
                    <input type="hidden" name="option_3" value="{{$new_product['option_3'] ?? ''}}">
                    <input type="hidden" name="total" value="{{$new_product['total']}}">
                    <input type="hidden" value="0" name="quantity">
                    <div><input class="delete-btn" onclick="return really_delete();" type="submit" value="削除"></div>
                </form>
            </div>
        </div>
        <?php $summary += $new_product['total'];?>
        <?php $count += $new_product['quantity']?>
        @endforeach
        <section class="cf">
            <div class="priceBox p_0 total-price">
                <div class="padding3">
                    @if($summary <= 1500)
                    <div class="text-right" >店舗合計(送料375円込)<span>{{floor($summary + 375)}}</span>円</div>
                    @elseif($summary >= 3000)
                    <div class="text-right">店舗合計(送料750円込)<span>{{floor($summary + 750)}}</span>円</div>
                    @else
                    <div class="text-right">店舗合計(送料25%込)<span>{{floor($summary + $summary*0.25)}}</span>円</div>
                    @endif
                    <div class="">
                        @if(session('flash_message') && session('stripe_id') == $stripe_id)
                        <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
                        @endif
                        <!-- 直近一週間の日付表示 -->
                        <div class="flexbox margin10" style="justify-content:center;">
                            <select name="upper limit flexbox" class="select_times" onChange="location.href=value;">
                                <option value="#" disabled>配達希望日</option>
                                @foreach($datetime as $key =>$date)
                                <option value="/update_apptdate/{{$date['value']}}" {{isset($apptdate)&&($apptdate==$date['value']) ? 'selected' : ''}}>{{$date['display']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- @if(Session::has('flash_message'))
                            <div>{{Session::get('flash_message')}}</div>
                        @endif -->
                        @if(Session::has('u_id'))
                        <form action="/pay" method="POST">
                            @csrf
                            <div class="t_center m_top5 m_btm">
                                <input type="hidden" value="{{$stripe_id}}" name="stripe_id">
                                <button class="m_top5 addition" type="submit">決済へ進む</button>
                            </div>
                        </form>
                        @else
                            <div class="t_center m_top5 m_btm2">
                                <button class="addition" onclick="location.href='/initial_email/1'">決済へ進む</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        </div>
    </div>
<!-- </div> -->
@endforeach


@else
<div class="cartbox b_color_gray">
    <p>カートの中に商品はありません</p>
</div>
@endif
<!-- </section> -->
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script>


function really_delete(){
    var result = confirm('本当に削除しますか？');
    if(result) {
    //はいを選んだときの処理
        document.querySelector('#r_delete').submit();
    } else {
    //いいえを選んだときの処理
        return false;
    }
}

 /*-------------------------------
 カウントアップ
 -------------------------------*/
$(function(){
 /* 初期値の設定 */
 var priceBase = removeFigure($(".basePrice1").text()); //基本価格を取得
 var priceOptions = removeFigure($(".optionTotal").text()); //オプション合計を取得
 var priceTotal = priceBase + priceOptions; //基本価格とオプション合計から総額を計算
 var optionsPrice = 0; //加算するオプション価格の初期設定
 var basePrice = priceBase; //数量変更後の基本価格を変更

 $(".priceTotal").text(addFigure(priceTotal)); //総額を反映

 $(".options1 :checkbox").click(function(){
     optionsPrice = 0; //加算するオプション価格を初期化
     $(".options1 :checkbox:checked").each(function(){
         //指定された範囲の中にある、すべてのチェックされたチェックボックスと同じラベル内にある、.optionPriceのテキストを取得
         optionsPrice = optionsPrice + removeFigure($(this).parent("label").find(".optionPrice").text());
        //  find()はタグやclass,idを検索   parent()1つ上の階層である親要素を取得→labelの親要素
        // 「parent()」メソッドと「find()」メソッドを組み合わせることで、親要素内にある別のHTML要素を取得
        // →　labelの親要素のoptions1の中の.optionPrice要素を取得したことになる

     });
     var timerPrice = setInterval(function(){
         if(priceOptions != optionsPrice){ //計算前と計算後の値が同じになるまで実行する
             if(priceOptions < optionsPrice){//元の数が計算後の数より大きいか小さいかを判定
                 priceOptions = priceOptions + Math.round((optionsPrice - priceOptions)/2); //値を反比例して加減する
             }else{
                 priceOptions = priceOptions - Math.round((priceOptions - optionsPrice)/2);
             }

             //算出されたオプション合計と総額をHTMLに反映
             $(".optionTotal1").text(addFigure(priceOptions));
             $(".total1").text(addFigure(priceBase + priceOptions));
         }else{
             clearInterval(timerPrice);//  setInterval()の動作停止
         }
     }, 30);//  数字が変わるスピード
 });

 $("select.num").change(function(){
     //セレクトボックス内の選択されているoptionのdata-price属性を取得
     basePrice = removeFigure($(this).find("option:selected").attr("data-price"));

     var timerPrice = setInterval(function(){
         if(priceBase != basePrice){
             if(priceBase < basePrice){
                 priceBase = priceBase + Math.round((basePrice - priceBase)/2);
             }else{
                 priceBase = priceBase - Math.round((priceBase - basePrice)/2);
             }

             //算出された基本価格と総額をHTMLに反映
             $(".basePrice1").text(addFigure(priceBase + priceOptions));
             $(".total1").text(addFigure(priceBase + priceOptions));
         }else{
             clearInterval(timerPrice);
         }

     }, 30);
 });
 
 /*-------------------------------
 カンマ処理
 -------------------------------*/
 function addFigure(str) {
     var num = new String(str).replace(/,/g, "");
     while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1,$2")));
     return num;
 }
    //  str === priceTotal, priceOptions, priceBase + priceOptions
    //  str文字列のカンマ(,)をすべて(“”)で置き換え、その結果をnumに代入。
    // str文字列の全てのカンマ（,）を取り除くには、gオプションをつける 通常はreplace(/,/, "") 
    //  ^(-?/d+)でnumの前からの数字を取得 後で$1として参照
    // (\d{3})でnumの後ろから3桁分の数字を取得 後で$2として参照
    // ^(-?/d+)の部分は、numの後ろから3桁分として(\d{3})で切り出された部分を除いた数字
    // 、/^(-?\d+)(\d{3})/は元のnumが抽出

    // 例えばnum="123456789"の場合、$1には"123456"、$2には"789"が入る。/^(-?\d+)(\d{3})/は"123456789"
    // /^(-?\d+)(\d{3})/の値と"$1,$2"が交換されることで、num.replace(/^(-?\d+)(\d{3})/, "$1,$2") = "123456,789"となる
    // while(num != (num = ... ))の部分で、numが"123,456,789"となるまで繰り返します。
    //  関数の呼び出し元に3桁区切りされた文字列が戻り値として返されます。

 function removeFigure(str) {
     var num = new String(str).replace(/,/g, "");
     num = Number(num);
     return num;
 }

});
</script>
@include('public/footer')