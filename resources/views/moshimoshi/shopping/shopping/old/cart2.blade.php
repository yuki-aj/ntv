@include('public/header')
@if(Session::get('msg'))
{{Session::get('msg')}}<br>
@endif

<br>
<div id="new_cart_text">
  <?php $count = 0?>
  @foreach ($new_carts as $stripe_id => $s_products)
  <div class="shop_box">
    <?php $summary = 0 ?>
    @foreach ($s_products as $key => $new_product)
    <div id="product_{{$key}}">
      店舗名{{$new_product['s_name']}}<br>
      商品名{{$new_product['name']}}<br>
      単価{{$new_product['price']}}円<br>
      @if($new_product['option_1'] != '')
      {{$new_product['option_1']}}
      @endif
      @if($new_product['option_2'] != '')
      {{$new_product['option_2']}}
      @endif
      @if($new_product['option_3'] != '')
      {{$new_product['option_3']}}
      @endif
      個数{{$new_product['quantity']}}個<br>
      @if(isset($new_product['o_name1']))
      {{$new_product['o_name1']}}　{{$new_product['o_price1']}}円<br>
      @endif
      @if(isset($new_product['o_name2']))
      {{$new_product['o_name2']}}　{{$new_product['o_price2']}}円<br>
      @endif
      @if(isset($new_product['o_name3']))
      {{$new_product['o_name3']}}　{{$new_product['o_price3']}}円<br>
      @endif
      合計{{$new_product['total']}}円
      <form class="dlc" method="POST">
          @csrf
          <input type="hidden" name="s_id" value="{{$new_product['s_id']}}">
          <input type="hidden" name="p_id" value="{{$new_product['p_id']}}">
          <input type="hidden" name="option_1" value="{{$new_product['option_1'] ?? ''}}">
          <input type="hidden" name="option_2" value="{{$new_product['option_2'] ?? ''}}">
          <input type="hidden" name="option_3" value="{{$new_product['option_3'] ?? ''}}">
          <input type="hidden" name="total" value="{{$new_product['total']}}">
          <input type="hidden" value="0" name="quantity">
          <div>
              <button class="delete_cart" type="button">削除する</button>
          </div>
      </form>
      <br><br>
      <?php $summary += $new_product['total'];?>
      <?php $count += $new_product['quantity']?>
    </div>
    @endforeach
    @if($summary < 1500)
    <div class="shop_total">店舗合計(送料込(300円)){{$summary + 300}}円</div><br><br>
    @elseif($summary > 3000)
    <div class="shop_total">店舗合計(送料込(600円)){{$summary + 600}}円</div><br><br>
    @else
    <div class="shop_total">店舗合計(送料込(20%)){{$summary + $summary*0.2}}円</div><br><br>
    @endif
    <form action="{{ url('/complete')}}" method="POST">
        @csrf
        <input type="hidden" name="s_id" value="{{$stripe_id}}">
        @if($summary < 1500)
        <input type="hidden" name="summary" value="{{$summary + 300}}">
        @elseif($summary > 3000)
        <input type="hidden" name="summary" value="{{$summary + 600}}">
        @else
        <input type="hidden" name="summary" value="{{$summary + $summary*0.2}}">
        @endif
        <!-- <label name="postcode">郵便番号</label>
        <input type="number" name="postcode" value="" placeholder="未入力の場合は登録先郵便番号になります"><br>
        <label name="address">お届け先住所</label>
        <input type="text" name="address" value="" placeholder="未入力の場合は登録先住所になります"><br> -->
        <!-- <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="{{ env('STRIPE_KEY') }}"
                data-amount="{{$all_summary}}"
                data-name=""
                data-label="購入する"
                data-email="{{!is_null(Session::get('u_mail'))? Session::get('u_mail') : ''}}"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="ja"
                data-allow-remember-me="true"
                data-currency="JPY">
        </script> -->
        <button>購入</button><!--これだけで購入できるかもしれない。明日見直す。上のJSを消すかどうか-->
    </form>
  </div>
  @endforeach
</div>
<br><br>
注文総数{{$count}}
<br><br>総合計金額<div id="total_price">{{$all_summary}}</div>円<br>
<a href="/top2">戻る</a>

<script>
$(".delete_cart").on('click',function(){//カート削除処理
    var form       = $(this).closest('.dlc').get(0);//親要素を取得
    var s_id       = form.elements['s_id'].value;//store_id
    var p_id       = form.elements['p_id'].value;//product_id
    var option_1   = form.elements['option_1'].value;//product_id
    var option_2   = form.elements['option_2'].value;//product_id
    var option_3   = form.elements['option_3'].value;//product_id
    var p_quantity = form.elements['quantity'].value;//product_id
    var total      = form.elements['total'].value;//product_id
    console.log(form);
    console.log(s_id);
    console.log(p_id);
    console.log(option_1);
    console.log(option_2);
    console.log(option_3);
    console.log(p_quantity);
    console.log(total);
    $.ajax({//商品削除のajax
        type: "POST",
        //ここでデータの送信先URLを指定します。
        url: "/deletecart",//deletecartに送る
        dataType: "json",
        scriptCharset: 'utf-8',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
          // form: form,
          s_id: s_id,//ショップid
          p_id: p_id,//商品id
          option_1: option_1,//オプション1
          option_2: option_2,//オプション1
          option_3: option_3,//オプション1
          quantity: p_quantity,//個数
          total:total,//金額
        },
      })
          //処理が成功したら
      .done(function(data) { 
        console.log('成功');
        // console.log(data);
        let result = JSON.parse(JSON.stringify(data));
        console.log(result);
        // let new_cart_text = document.getElementsByClassName('new_cart_text');
        let new_cart_text = document.getElementById('product_' + result['number']);
        $(new_cart_text).remove();
        let total_price = document.getElementById('total_price');
        all_price = total_price.innerHTML;
        total_price.innerHTML = parseInt(all_price) - result['total'];
        // new_cart_text.innerHTML = cart_text;
        // console.log(new_cart_text);
        // let cart_a = document.getElementById('cart_aj');
        // cart_a.innerHTML = cart_text;
        let form_csrf = document.getElementsByClassName('csrf');
        $(form_csrf).prepend('@csrf');//formの中に@csrfを追加
      })
      //処理がエラーであれば
      .fail(function(xhr) {  
        console.log('失敗');
        //通信失敗時の処理
        //失敗したときに実行したいスクリプトを記載
      })
      .always(function(xhr, msg) { 
        //通信完了時の処理
        //結果に関わらず実行したいスクリプトを記載
      });
  });
</script>

@include('public/footer')