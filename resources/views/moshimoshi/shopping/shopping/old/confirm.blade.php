@include('public/header')

<div class="header_img">
        <a href="/">
        <img src="{{('img/header.jpg')}}">
        </a>
</div>
<h1 class="baskets">確認画面</h1>
<h2 class="orange confirm bold">注文はまだ確定していません。</h2>
<div class="desc_w60">  
    <div class="w_90">
        <h3 class="gray">
            <a href="/">ホーム</a>   >   
            <a href="/cart">カート</a>  >
            <a href="/pay">支払い</a> >
            <a href="/confirm">確認</a>
        </h3>
   
        <div class="m_tb">
            
            <div class="priceBox">
                <h1 class="line_gray">[ 請求情報 ]</h1>
                    <dl class="price cf">
                        <dt>お名前</dt>
                        <dd>
                            
                            <span class="basePrice basePrice1">もしもし</span>
                            <span class="basePrice basePrice1">花子</span>

                        </dd>
                    </dl>
                    <dl class="price cf">

                        <dt>お届け先</dt>
                        <dd>
                            <span class="basePrice basePrice1">多摩市落合2丁目38番地</span>
                        </dd>
                    </dl>
                    <dl class="price cf">
                        <dt>TEL</dt>
                        <dd>
                            <span class="basePrice basePrice1">03-1234-5678</span>
                        </dd>
                    </dl>
                    <dl class="price cf">
                        <dt>mail</dt>
                        <dd>
                            <span class="basePrice basePrice1">moshimoshi@delivery.com</span>

                        </dd>
                    </dl>
            </div>
            <div class="priceBox">
            <h1 class="line_gray">[ 配送日時 ]</h1>
                <dl class="price cf">
                    <dt>配送日</dt>
                    <dd>
                        <span class="basePrice basePrice1">2022年5月25日（水）</span>
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>配送時間</dt>
                    <dd>
                        <span class="basePrice basePrice1">11:30 ~ 12:00</span>
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>メモ</dt>
                    <dd>
                        <span class="basePrice basePrice1">特になし</span>
                    </dd>
                </dl>
            </div>
            <!-- <div class="priceBox">
            <h1 class="line_gray">[ クーポン ]</h1>
                <dl class="price cf">
                    <dt>クーポン</dt>
                    <dd>
                        <span class="basePrice basePrice1">10% OFF クーポン</span>
                    </dd>
                </dl>
            </div> -->
            <div class="priceBox">
            <h1 class="line_gray">[ ご注文商品 ]</h1>
                <dl class="price cf">
                    <dt>商品名</dt>
                    <dd>
                        <span class="basePrice basePrice1">カレー</span>
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>数量</dt>
                    <dd>
                        <span class="basePrice basePrice1">2</span>
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>オプション：大盛り/福神漬け</dt>
                    <dd>
                        <span class="optionTotal optionTotal1">0</span>
                        円
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>小計</dt>
                    <dd>
                        <span class="basePrice basePrice1">1,900</span>
                        円
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>配送料</dt>
                    <dd>
                        <span class="deriveryPrice">600</span>
                        円
                    </dd>
                </dl>
                <dl class="price cf">
                    <dt>合計</dt>
                    <dd>
                        <span class="total total1">2,500</span>
                        円
                    </dd>
                </dl>
            </div>
            <div class="priceBox">
                <h1 class="line_gray">[ 支払い ]</h1>
                <h2 class="">クレジットカード</h2>
                    <dl class="price cf">
                        <dt>カード番号</dt>
                        <dd>
                            <span class="basePrice basePrice1">12345678</span>
                        </dd>
                    </dl>
                    <dl class="price cf">
                        <dt>有効期限</dt>
                        <dd>
                            <span class="basePrice basePrice1">2022/05/31</span>
                        </dd>
                    </dl>
                    <dl class="price cf">
                        <dt>セキュリティ番号</dt>
                        <dd>
                            <span class="basePrice basePrice1">123</span>
                        </dd>
                    </dl>
            </div>

            <div class="confirm m_tb">
                <a href="/ordercompletion">
                    <input class="contact_submit" type="" value="確定" />
                </a>
            </div>
            <div class="confirm">
                <a href="/pay">
                    <input class="return" type="" value="戻る" />
                </a>
            </div>
            
        </div>
    </div>
</div>

<div class="m_b">
</div>

<!-- @include('public/footer') -->