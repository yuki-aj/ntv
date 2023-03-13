
      <div class="order_container">

        <div class="order_pic">
           <div id="opens">
            <div class="order_pic_inner">
              <img src="img/namasute5.jpg" alt="ナマステキッチン多摩センター" class="">
            </div>
           </div>
        </div>

      <div id="masks" class="hidden"></div>

      <section id="food" class="hidden">
            <img src="img/namasute5.jpg">
            <div id="closes">
              <h2>閉じる</h2>
            </div>
       </section>


      <div class="order_name">
        <a href="" class="merchandise">ガパオライス</a>
        <p class="red">¥1,050</p>
        <div class="item_description">
          <p>鶏ひき肉のバジル炒めをライスと一緒にどうぞ。目玉焼き付き。</p>
        </div>
        <div class="order_detail">
          <p>※パクチー、辛みの追加をご希望の方は、お支払い画面の「注文メモ」にご入力ください。</p>
        </div>
        <div class="menu_back">
          <a href="/shop#a">
            <p>メニュー一覧に戻る</p>
          </a>
        </div>
      </div>
      <div class="order_cart">
        <div class="order_quantity_control">

          <div class="order_quantity">
            <div class="order_control">
              <p class="order_tel">
              <div class="p-qty js-qty">
                <div class="__arrow __up js-qty_up"></div>
                <div class="__arrow __down js-qty_down"></div>
                <input type="number" id="quantity" name="quantity" value="1" maxlength="6"
                  class="p-qty__input js-qty_target order_tels">
              </div>
              </p>
            </div>
          </div>

          <div class="orderright">
            <button type="button" class="order_rights">
              <div id="open"><span class="order_addition">カートに追加</span></div>
              <span class="">　¥
                <span class="order_number">1.050</span>
              </span>
            </button>
          </div>

          <div id="mask" class="hidden"></div>

          <section id="modal" class="hidden">
            <i class="bi bi-cart-check"></i>
            <p>カートに商品を追加しました。</p>
            <div class="cartcheck">
              <a href="/cart">
                <p>カートを確認する</p>
              </a>
            </div>
            <div class="cartcheck">
              <a href="/shop#a">
                <p>他の商品を見る</p>
              </a>
            </div>
            <div id="close">
              <p>閉じる</p>
            </div>
          </section>

          </div>
        </div>
      </div>
    </div>
@include('public/footer')