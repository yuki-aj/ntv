@include('public/header')
<script>
    var stripe_pk = "{{$stripe_pk}}";
</script>
<div class="header_img">
    <a href="/"><img src="{{('img/header.jpg')}}"></a>
</div>
<h1 class="baskets">決済</h1>
<h2 class="orange confirm bold">注文はまだ確定していません。</h2>
<div class="desc_w60">  
    <div class="w_90">
        <h3 class="gray">
            <a href="/">ホーム</a> >
            <a href="/cart">カート</a> >
            <a href="/pay">支払い</a>
        </h3>
    </div>
    <div class="p_3">
        <form class="h-adr" action="{{url('ordercompletion')}}" method="POST" id="user_data">
            @csrf
            <p class="line_gray">[ 配送先情報 ]</p>
            <div class="acflex m_tb">
                <div class="content1_2">
                    <p class="line_gray"><label for="name">お名前</label></p>
                    <p><input id="name" class="padding10" name="name" type="text" value="{{isset($user->name) ? $user->name: ''}}"placeholder="名前を入力してください" required></p>
                </div>
            </div>
            <div class="acflex m_tb">
                <div class="content1_2">
                    <p class="line_gray"><label for="postcode">お届け先</label></p>
                    <p>
                        <span class="p-country-name" style="display:none;">Japan</span>
                        <input class="p-postal-code padding10" size="8" maxlength="8" id="postcode" name="postcode" type="text" type="text" class="padding10 p-postal-code" size="8" maxlength="8" placeholder="郵便番号" value="{{isset($user->postcode) ? $user->postcode :''}}" required>
                        <input class="p-region p-locality p-street-address p-extended-address padding10" name="address" type="text" placeholder="住所" class="padding10" value="{{isset($user->address) ? $user->address: ''}}" required>
                    </p>
                </div>
            </div>
            <div class="acflex m_tb">
                <div class="content1_2">
                    <p class="line_gray"><label for="tel">電話番号</label></p>
                    <input id="tel" name="tel" type="tel" placeholder="電話番号" class="padding10" value="{{isset($user->tel) ? $user->tel: ''}}" required>
                </div>
            </div>
            <div class="acflex m_tb">
                <div class="content1_2">
                    <p class="line_gray"><label for="email">メールアドレス</label></p>
                    <input class="padding10" name="email" type="email" placeholder="メールアドレス" value="{{isset($user->email) ? $user->email : ''}}" required>
                </div>
            </div>
            <div class="m_tb acflex">
                <p class="line_gray"><label for="corporation_flag">利用種別</label></p>
                <select name="corporation_flag" id="corporation_flag" class="numes">
                    <option value="1">個人利用</option>
                    <option value="2">法人利用</option>
                </select>
            </div>
            <div class="m_tb acflex">
                <p class="line_gray"><label for="delivery_date">配送希望日</label></p>
                <select name="delivery_date" id="delivery_date" class="numes">
                    @foreach($week as $day)
                    <option value="{{$day}}">{{$day}}</option>
                    @endforeach
                </select>
            </div>
            <div class="m_tb acflex">
                <p class="line_gray"><label for="delivery_time">配送希望時間</label></p>
                <select name="delivery_time" id="delivery_time" class="numes">
                    <option value="11:30">11:30 - 12:00</option>
                </select>
            </div>
            <div class="m_tops">
                <th class="contact-item"><p class="line_gray">注文メモ</p></th>
                <td class="contact-body">
                    <textarea name="note" placeholder="ご要望がございましたら、こちらにご記入下さい。" class="form-textareas"></textarea>
                </td>
            </div>
            <div class="m_tops">
                <th class="contact-item"><p class="line_gray">カード情報入力</p></th>
                <td class="card-body">
                    <!-- <form action="{{url('addnewcard')}}" class="card-form" id="form_payment" method="POST">
                        @csrf --><!-- ここの情報をaddnewcardに送る -->
                        <div class="form-group">
                            <label for="cardNumber">カード番号</label>
                            <div id="cardNumber"></div>
                        </div>

                        <div class="form-group">
                            <label for="securityCode">セキュリティコード</label>
                            <div id="securityCode"></div>
                        </div>

                        <div class="form-group">
                            <label for="expiration">有効期限</label>
                            <!-- <div id="expiration"></div> -->
                            <input id="expiration" value="" name="expiration" type="text">
                        </div>

                        <div class="form-group">
                            <label for="cardName">カード名義</label>
                            <input type="text" name="cardName" id="cardName" class="form-control" value="" placeholder="カード名義を入力">
                        </div>
                        <!-- <div class="form-group">
                            <button type="submit" id="create_token" class="btn btn-primary">カードを登録する</button>
                        </div> -->
                    <!-- </form> -->
                </td>
            </div>
            <div class="priceBox">
                <?php $count = 0?>
                @foreach($new_carts as $stripe_id => $s_products)
                <?php $summary = 0 ?>
                @if($stripe_id == $pay_id)
                @foreach ($s_products as $key => $product)
                <dl class="price cf">
                    <dt>商品名</dt>
                    <dd><span class="basePrice basePrice1">{{$product['name']}}</span></dd>
                </dl>
                <dl class="price cf">
                    <dt>基本価格</dt>
                    <dd><span class="basePrice basePrice1">{{$product['price']}}</span>円</dd>
                </dl>
                @if($product['option_1'] != '')
                <dl class="price cf">
                    <dt>{{$product['o_name1']}}</dt>
                    <dd><span class="optionTotal optionTotal1">{{$product['o_price1'].'円'}}</span></dd>
                </dl>
                @endif
                @if($product['option_2'] != '')
                <dl class="price cf">
                    <dt>{{$product['o_name2']}}</dt>
                    <dd><span class="optionTotal optionTotal1">{{$product['o_price2'].'円'}}</span></dd>
                </dl>
                @endif
                @if($product['option_3'] != '')
                <dl class="price cf">
                    <dt>{{$product['o_name3']}}</dt>
                    <dd><span class="optionTotal optionTotal1">{{$product['o_price3'].'円'}}</span></dd>
                </dl>
                @endif
                <dl class="price cf">
                    <dt>数量</dt>
                    <dd><span class="basePrice basePrice1">{{$product['quantity']}}</span></dd>
                </dl>
                <dl class="price cf">
                    <dt>商品合計</dt>
                    <dd><span class="basePrice basePrice1">{{$product['total']}}円</span></dd>
                </dl>
                <?php $summary += $product['total'];?>
                <?php $count += $product['quantity']?>
                @endforeach
                <dl class="price cf">
                    <dt>送料</dt>
                    @if($summary < 1500)
                    <dd><span class="deriveryPrice">300円</span></dd>
                    <input type="hidden" name="summary" value="{{$summary + 300}}">
                    @elseif($summary > 3000)
                    <dd><span class="deriveryPrice">600円</span></dd>
                    <input type="hidden" name="summary" value="{{$summary + 600}}">
                    @else
                    <dd><span class="deriveryPrice">20%({{$summary*0.2}}円)</span></dd>
                    <input type="hidden" name="summary" value="{{$summary + $summary*0.2}}">
                    @endif
                </dl>
                <dl class="price cf">
                    <dt>お支払金額</dt>
                    <dd><span class="total total1">{{$all_summary}}円</span></dd>
                </dl>
                <input type="hidden" name="s_id" value="{{$stripe_id}}">
                <div class="flexbox"><button>購入</button></div>
                @endif
                @endforeach
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>
<div class="m_b"></div>
<div style="margin-bottom:300px;" class="m_b"></div><!-- 後で消す -->
<!-- <div class="" style="margin-bottom:300px;"></div> -->
<script src="https://js.stripe.com/v3/"></script>
<script src="js/payment.js"></script>
@include('public/footer')