@include('public/header')

<h1 class="product_baskets">マイページ</h1>
<div class="mypagetop">
    <img class="mypage_img" src="{{url('img/mypage_icon.png')}}">
    <div class="mypage_thanks"><h1>{{$user->name}}</h1></div>
    <div class="mypage_thanks" style="color:#717571"><h2>ID:{{$user->email}}</h2>
</div>

<div class="desc_w60">
    <!-- クーポン -->
    <ul class="mypage_category">
        <li class="mypage_list" style="border-bottom: none">
            <a href="/user_coupon">
                <div class="acflex">
                    <div class="content1_2">
                    <p><i class="bi bi-wallet2"></i>　クーポン</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <!-- お気に入り -->
    <ul class="mypage_category">
        <li class="mypage_list" style="border-bottom: none">
            <a href="/favorite">
                <div class="acflex">
                    <div class="content1_2">
                    <p><i class="bi bi-heart"></i>　お気に入り</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <!-- ご利用履歴 -->
    <ul class="mypage_category">
        <li class="mypage_list" style="border-bottom: none">
            <a href="/myorder/{{$user->id}}">
                <div class="acflex">
                    <div class="content1_2">
                    <p><i class="bi bi-card-list"></i>　ご注文履歴</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <!-- お客様情報 -->
    <ul class="mypage_category">
        <h2 class="bold user_data">お客様情報</h2>
        <li class="mypage_list">
            <a href="/name">
                <div class="acflex" style="border-bottom: 1px solid #ccc;">
                    <div class="content1_2">
                        <p><i class="bi bi-card-list"></i>　基本情報</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
        <li class="mypage_list">
            <a href="/password">
                <div class="acflex" style="border-bottom: 1px solid #ccc;">
                    <div class="content1_2">
                        <p><i class="bi bi-card-list"></i>　パスワード</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
        <li class="mypage_list" style="border-bottom: none">
            <a href="/myprofile_payment">
                <div class="acflex" style="border-bottom: 1px solid #ccc;">
                    <div class="content1_2">
                        <p><i class="bi bi-card-list"></i>　お支払方法</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
    </ul>

    <!-- ログアウト -->
    <ul class="mypage_category">
        <li class="mypage_list" style="border-bottom: none">
            <a href="/logout">
                <div class="acflex">
                    <div class="content1_2">
                        <p>ログアウト</p>
                    </div>
                    <div class="content1_2_0">
                        <h3>＞</h3>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</div>

@include('public/footer')