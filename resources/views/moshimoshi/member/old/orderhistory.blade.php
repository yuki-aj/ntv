@include('public/header')
<!-- <section class="w_100 h_370">
    <div class="p_20 centers">
    <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>
    <i class="bi bi-bookmark-x-fill"></i>
        <h1>ご注文はありません</h1>
        <h2>注文時にこちらに表示されます</h2>
        <div class="changeok m_none">
                <a href="/search">
                    <button class="ok_button">
                        <h2>商品を探す</h2>
                    </button>
                </a>
        </div>
    </div>
</section> -->

<!-- モーダル -->
<div id="mask" class="hidden"></div>
<section id="modal" class="hidden">
    <div class="popup-inneres">
        <div class="p-20">
            <div class="close-btn" id="close"><i class="fas fa-times"></i></div>
            <div class="cancell">
                <h1>注文をキャンセルしますか？</h1>
                <input type="submit" class="" name="" value="キャンセルする">
            </div>
        </div>
    </div>
</section>


<section class="desc_w60">  
    <div class="b_gray">
    <div class="p_3">
    <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>
    <h1>注文履歴一覧</h1>
    <h3 class="gray">※反映には時間がかかることがあります。</h3>
        <div class="history">
            <h2 class="">2022/05/01に注文</h2>
            <div class="details_flex">
                <div class="orderstore">
                    <h1 class="bold">ナマステキッチン</h1>
                    <h2 class="gray">配送日:2022/05/04</h2>
                </div>
                <a href="/orderhistorydetails">
                    <p class="details_right">詳細を見る ></p>
                </a>
            </div>
            <div class="cartaccordion">
                <div class="option">
                <input type="checkbox" id="toggle1" class="toggle">
                <label class="title" for="toggle1">配送状況を確認する</label>
                <div class="contents m_0">
                    <h2>注文番号　12345678</h2>
                    <h2 class="gray">2022/05/04 19:00</h2>
                    <h1 class="orange">お届け完了</h1>
                </div>
                </div>
            </div>
            <div class="details_flex">
                <a href="/shop" src=""><h3>お店のページを見る</h3></a>
                <div id="open"><h3>キャンセルする</h3></div>
            </div>
        </div>

    <div class="history">
        <h2 class="">2022/05/01に注文</h2>
        <div class="details_flex">
            <div class="orderstore">
                <h1 class="bold">関西居酒屋　必死のパッチ!!</h1>
                <h2 class="gray">配送日:2022/05/04</h2>
            </div>
            <a href="/orderhistorydetails">
                <p class="details_right">詳細を見る   ></p>
            </a>
        </div>
        <div class="cartaccordion">
            <div class="option">
                <input type="checkbox" id="toggle1" class="toggle">
                <label class="title" for="toggle1">配送状況を確認する</label>
                <div class="contents m_0">
                    <h2>注文番号　12345678</h2>
                    <h2>2022/05/04 19:00</h2>
                    <h1 class="orange">配送完了</h1>
                </div>
            </div>
        </div>
        <div class="details_flex">
            <a href="/shop" src=""><h3>お店のページを見る</h3></a>
          <a href="/shop" src=""><h3>キャンセルする</h3></a>
        </div>
    </div>
    <!-- キャンセル -->
    <div class="cancel">
        <h2 class="">2022/05/01に注文</h2>
        <div class="details_flex">
            <div class="orderstore">
                <h1 class="bold">関西居酒屋　必死のパッチ!!</h1>
                <h2 class="gray">配送日:2022/05/04</h2>
            </div>
            <a href="/orderhistorydetails">
                <p class="details_right">詳細を見る ></p>
            </a>
        </div>
        <div class="cartaccordion">
            <div class="option">
                <input type="checkbox" id="toggle1" class="toggle">
                <label class="title" for="toggle1">配送状況を確認する</label>
                <div class="contents m_0">
                    <h2>注文番号　12345678</h2>
                    <h2>2022/05/04 19:00</h2>
                    <h1 class="orange">配送完了</h1>
                </div>
            </div>
        </div>
            <a href="/shop" src=""><h3>この商品はキャンセルになりました</h3></a>
    </div>
    </div>
</section>

<div class="m_b">
</div>

@include('public/footer')