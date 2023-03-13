@include('public/header')

<section class="desc_w60">  
    <div class="b_gray">
    <div class="p_3">
        <h2 class="left"><a href="/orderhistory" class="btn-circle-3d-emboss"><</a></h2>
        <h1>注文履歴詳細</h1>
        <h3 class="gray ">※反映には時間がかかることがあります。</h3>
        <div class="">
            <div class="history">
                <div class="details_flex">
                    <h2 class="">店名：</h2>
                    <p class="details_right bold">ナマステキッチン</p>
                </div>
                <div class="details_flex">
                    <h2 class="">注文日時：</h2>
                    <p class="details_right bold">2022/3/11</p>
                </div>
                <div class="details_flex">
                    <h2 class="">注文番号：</h2>
                    <p class="details_right bold">000001</p>
                </div>
                <div class="details_flex">
                    <h2 class="">配送日：</h2>
                    <p class="details_right bold">2022/04/10</p>
                </div>
                <div class="details_flex">
                    <h2 class="">決済方法：</h2>
                    <p class="details_right bold">クレジットカード</p>
                </div>
                <div class="details_flex">
                    <h2 class="">配送先：</h2>
                    <p class="details_right bold">多摩市豊ヶ丘1-1-1</p>
                </div>
                <div class="cartaccordion">
                    <div class="option">
                        <input type="checkbox" id="toggle1" class="toggle">
                        <label class="title" for="toggle1">領収書の発行</label>
                        <div class="contents m_0">
                            <h2>宛名</h2>
                            <input type="text" placeholder="" name="電話" class="receipt" required />
                            <div class="issue">
                                <input type="button" class="" value="発行">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="orederproduct">
                    <h2>ご注文商品</h2>
                    <div class="m_tb3">
                        <div class="flexspace">
                            <img src="img/namasute7.jpg">
                            <div class="">
                                <p class="bold">商品名商品名</p>
                                <p class="gray">数量：1点</p>
                            </div>
                            <p class="">¥900</p>
                        </div>
                    </div>
                    <div class="m_tb3">
                        <div class="flexspace">
                            <img src="img/namasute6.jpg">
                            <div class="">
                                <p class="bold">商品名商品名商品名</p>
                                <p class="gray">数量：2点</p>
                            </div>
                            <p class="">¥900</p>
                        </div>
                    </div>
                </div>
                <div class="mypagecoupon details_flex">
                    <h2>クーポン</h2>
                    <h2>送料200円引き</h2>
                </div>
                <div class="details_flex">
                    <h2 class="">小計（2点）</h2>
                    <p class="details_right">¥1750</p>
                </div>
                <div class="details_flex">
                    <h2 class="">送料</h2>
                    <p class="details_right">¥500</p>
                </div>
                <div class="details_flex">
                    <h2 class="">割引</h2>
                    <p class="details_right">¥-200</p>
                </div>
                <div class="details_flex">
                    <h1 class="">合計</h1>
                    <p class="details_right bold f_30">¥2,050</p>
                </div>
            </div>
       </div>
    </div>
    </div>
</section>

<div class="m_b">
</div>
@include('public/footer')