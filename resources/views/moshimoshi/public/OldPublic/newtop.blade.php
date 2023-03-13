@include('public/header')

<!-- 初回ポップアップ -->
<!-- <section class="popup" id="js-popup">
    <div class="popup-inner">
        <div class="close-btn" id="js-close-btn"><i class="fas fa-times"></i></div>
        <img class="special_img"  id="js-close-logo"src="img/logo.jpg" alt="特集の記事、告知">
            <h2 style="font-family: 'Kosugi Maru', sans-serif;">～ 特集 ～</h2>
            <div class="special_logo" id="js-close-img">
                <a href="/#special_aria">
                    <div><img src="img/namasutelogo.jpg"></div>
                    <div><img src="img/namasute4.jpg"></div>
                    <h1 style="font-family: 'BIZ UDPGothic', sans-serif;">ナマステキッチン</h1>
                    <div><img src="img/namasutelogo.jpg"></div>
                    <div><img src="{{url($feature_shop->img)}}"></div>
                    <h1 style="font-family: 'BIZ UDPGothic', sans-serif;">{{$feature_shop->name}}</h1>
                </a>
            </div>
    </div>
    <div class="black-background" id="js-black-bg"></div>
</section> -->

<!-- 右下固定モーダル -->
<section class="bottom_right">
    <div class="js-modal__btn">
        <div id="menuopen"><i class="bi bi-list-ul"></i></div>
        <div id="menumask" class="menuhidden"></div>
    </div>
    <div class="js-modal__bg"></div>
    <div class="js-modal__main">
        <h1>料理ジャンル</h1>
        <div class="order">
            <div class="selects">
                <select name="upper limit" class="select_times" style="text-align:-webkit-center;">
                    <option value="1">上限なし</option>
                    <option value="3">¥800以下</option>
                    <option value="4">¥1000以下</option>
                    <option value="5">¥1500以下</option>
                    <option value="6">¥2000以上</option>
                </select>
            </div>
        </div>
        @foreach($categories as $key => $category)
        <li class="items">
            <form action="/searchproduct" method="post">
                @csrf
                <input type='hidden' name='category' value='{{$category->id}}'>
                <input class="images" type ="image" name="submit" src="img/category{{$category->id}}.jpg">
            </form>
            <a href="/searchproduct">
                <img src="img/category{{$category->id}}.jpg" alt="">
            </a>
            <h5>{{$category->name}}</h5>
        </li>
        @endforeach
        <li class="items">
            <a href="/search">
                <img src="img/curry1.jpg" alt="">
            </a>
            <h5>カレー</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/illast2.jpg" alt="">
            </a>
            <h5>和食</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/illast3.jpg" alt="">
            </a>
            <h5>洋食</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/salada.jpg" alt="">
            </a>
            <h5>サラダ</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/gyoza.jpg" alt="">
            </a>
            <h5>中華</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/suitu.jpg" alt="">
            </a>
            <h5>スイーツ</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/agemono.jpg" alt="">
            </a>
            <h5>揚げ物</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/don.jpg" alt="">
            </a>
            <h5>丼もの</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/takokuseki.jpg" alt="">
            </a>
            <h5>多国籍料理</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/ramen1.jpg" alt="">
            </a>
            <h5>麺類</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/itarian.jpg" alt="">
            </a>
            <h5>イタリアン</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/sushi1.jpg" alt="">
            </a>
            <h5>寿司・海鮮</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/pan.jpg" alt="">
            </a>
            <h5>パン</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/yakiniku.jpg" alt="">
            </a>
            <h5>肉</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/pazza.jpg" alt="">
            </a>
            <h5>ピザ</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/ham.jpg" alt="">
            </a>
            <h5>ハンバーガー</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/esunikku.jpg" alt="">
            </a>
            <h5>エスニック</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/drink1.jpg" alt="">
            </a>
            <h5>ドリンク</h5>
        </li>
        <p class="js-modal__btn--close">close</p>
        <p class="js-modal__btn--close--fix"></p>
        <!-- <div id="menuclose">
                    <p>閉じる</p>
                    </div> -->
    </div>
</section>

<!-- モーダル　配送希望日 --->
<div id="mask" class="hidden"></div>
<section id="modal" class="hidden">
    <div class="popup-inneres">
        <div class="p-20">
            <div class="close-btn" id="close"><i class="fas fa-times"></i></div>
            <div class="choise">
                <h1>配送希望日はお決まりですか？</h1>
                <p>※原則13時までのご注文で当日17時以降配送・19時までのご注文で翌日11時以降配送</p>
                <div class="selects">
                    <select name="upper limit" class="set">
                        <option value="1">4月11日</option>
                        <option value="2">4月12日</option>
                        <option value="3">4月13日</option>
                        <option value="4">4月14日</option>
                        <option value="5">4月15日</option>
                        <option value="6">4月16日</option>
                        <option value="7">4月17日</option>
                    </select>
                </div>
                <div class="selects">
                    <select name="upper limit" class="set">
                        <option value="1100">11:00</option>
                        <option value="1130">11:30</option>
                        <option value="1200">12:00</option>
                        <option value="1230">12:30</option>
                        <option value="1300">13:00</option>
                        <option value="1330">13:30</option>
                        <option value="1400">14:00</option>
                        <option value="1430">14:30</option>
                        <option value="1500">15:00</option>
                        <option value="1530">15:30</option>
                        <option value="1600">16:00</option>
                        <option value="1630">16:30</option>
                        <option value="1700">17:00</option>
                        <option value="1730">17:30</option>
                        <option value="1800">18:00</option>
                        <option value="1830">18:30</option>
                        <option value="1900">19:00</option>
                        <option value="1930">19:30</option>
                        <option value="2000">20:00</option>
                        <option value="2030">20:30</option>
                        <option value="2100">21:00</option>
                        <option value="2130">21:30</option>
                        <option value="2200">22:00</option>
                        <option value="2230">22:30</option>
                        <option value="2300">23:00</option>
                        <option value="2330">23:30</option>
                        <option value="2400">24:00</option>
                    </select>
                </div>
                <div class="seek">
                    <a href="/">
                        <h3>この配送日時で探す</h3>
                    </a>
                </div>
                <div class="nochoise">
                    <a href="/">
                        <h3>今は指定しない</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 　ヘッダー部分 -->
<section class="topsearch">
    <div class="newregistar">
        <button type="button" class="registar_btn">
            <span class="registar" id="open">
                配送希望日登録
            </span>
        </button>
    </div>
    <!-- <form class="" id="form5" action="/search">
        <input id="sbox5" type="text"  autocomplete="on" list="keyword" placeholder="キーワードを入力" />
        <datalist id="keyword">
            <option value="カレー">
            <option value="ピザ">
            <option value="肉料理">
            <option value="ラーメン">
            <option value="中華料理">
        </datalist>
        
        <a href="/search">
            <button type="submit" id="sbtn2"><i class="fas fa-search"></i></button>
        </a>
    </form> -->
    <form class="flex" id="form5" action="/search" method="post">
        @csrf
        <input id="sbox5" type="search" name="name" autocomplete="on" list="keyword" placeholder="キーワード検索" value="{{$request->name}}">
        <!-- <datalist id="keyword">
            <option value="カレー">
            <option value="ピザ">
            <option value="肉料理">
            <option value="ラーメン">
            <option value="中華料理">
        </datalist> -->
        
        <!-- <a href="/searchproduct">
            <button type="submit" id="sbtn2"><i class="fas fa-search"></i></button>
        </a> -->
        <button type="submit" id="sbtn2"><i class="fas fa-search"></i></button>
        <!-- <input id="sbtn5" type="submit" value="検索"> -->
    </form>
    
</section>

<!-- メニュー　横スクロール -->
<section class="menu_icon">
    <ul class="horizontal-list">
        @foreach($categories as $key => $category)
            <li class="items">
                <form action="/search" method="post">
                    @csrf
                    <input type='hidden' name='category' value='{{$category->id}}'>
                    <input class="images" type ="image" name="submit" src="img/category{{$category->id}}.jpg">
                </form>
                <!-- <a href="/searchproduct">
                    <img src="img/category{{$category->id}}.jpg" alt="">
                </a> -->
                <h5>{{$category->name}}</h5>
            </li>
        @endforeach

        <li class="items">
            <a href="/search">
                <img src="img/s.curry.jpg" alt="">
            </a>
            <h5>カレー</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/s.japanesefood.jpg" alt="">
            </a>
            <h5>和食</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/s.westernfood.jpg" alt="">
            </a>
            <h5>洋食</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/salada.jpg" alt="">
            </a>
            <h5>サラダ</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/gyoza.jpg" alt="">
            </a>
            <h5>中華</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/suitu.jpg" alt="">
            </a>
            <h5>スイーツ</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/agemono.jpg" alt="">
            </a>
            <h5>揚げ物</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/don.jpg" alt="">
            </a>
            <h5>丼もの</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/takokuseki.jpg" alt="">
            </a>
            <h5>多国籍料理</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/ramen1.jpg" alt="">
            </a>
            <h5>麺類</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/itarian.jpg" alt="">
            </a>
            <h5>イタリアン</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/sushi1.jpg" alt="">
            </a>
            <h5>寿司・海鮮</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/pan.jpg" alt="">
            </a>
            <h5>パン</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/yakiniku.jpg" alt="">
            </a>
            <h5>肉</h5>
            </a>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/pazza.jpg" alt="">
            </a>
            <h5>ピザ</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/ham.jpg" alt="">
            </a>
            <h5>ハンバーガー</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/esunikku.jpg" alt="">
            </a>
            <h5>エスニック</h5>
        </li>
        <li class="items">
            <a href="/search">
                <img src="img/drink1.jpg" alt="">
            </a>
            <h5>ドリンク</h5>
        </li>
    </ul>
</section>

<!-- 緊急時のお知らせ記事 -->
<section class="news">
    <a href="">
        <p class="day">2022/05/10</p>
        <p class="titles textoverflow">タイトルタイトルタイトルタイトルタイトルタイトルタイトル</p>
    </a>
</section>

<!-- スライドショー -->
<section class="no_icon">
    <div class="slider">
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="banner">
            <img src="img/logo.jpg" alt="">
        </div>
       
    </div>
</section>

<!-- フリースペース枠 -->
<section class="container">
    <p>フリースペース枠</p>
</section>
<!-- 広告バナー枠 -->
<!-- <section class="advertisement">
    <div class="flex">
        <div class="ad">
            <img src="" alt="広告">
        </div>
        <div class="ad">
            <img src="" alt="広告">
        </div>
        <div class="ad">
            <img src="" alt="広告">
        </div>
        <div class="ad">
            <img src="" alt="広告">
        </div>
      
    </div>

</section> -->

<!-- もしデリ推し店-->
<section class="favoshop">
    <li class="title_list">
        <a href="/search">
            <div class="acflex">
                    <div class="content1_2"><h1>もしデリ推し店</h1></div>
                    <div class="content1_2_1"><h1 class="gray">＞</h1></div>
            </div>
        </a>
    </li>
    <ul class="horizontal-list">
        @foreach($push_shops as $push_shop)
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="{{$push_shop->img}}" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3>{{$push_shop->name}}</h3>
        </li>
        @endforeach
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/seafood.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3 class="textoverflow">海鮮 家庭居酒屋</h3>
        </li>
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/meat.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3 class="textoverflow">Steak＆Lounge JB</h3>
        </li>
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/kansai2.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3 class="textoverflow">関西居酒屋　必死のパッチ!!</h3>
        </li>
    </ul>
</section>

<!-- 特集枠 -->
<section class="special_photo" id="special_aria">
    <img class="bg_photo" src="img/others.jpg" />
    <div class="logo_img">
        <img src="img/namasutelogo.jpg">
    </div>
    <div class="special_box">
        <div class="cp_box">
                <h1>ナマステキッチン</h1>
                <div class="feature_box">
                    <div class="smallbox">
                    <a href="/shop">
                        <img src="img/namasute5.jpg">
                        <h3>ガパオライス</h3>
                        <h3 class="orange">¥1,050</h3>
                    </a>
                    </div>
                    <div class="smallbox">
                    <a href="/shop">
                        <img src="img/namasute6.jpg">
                        <h3>チーズナン</h3>
                        <h3 class="orange">¥550</h3>
                    </a>
                    </div>
                </div>
            </a>
            <input id="cp00" type="checkbox">
            <label for="cp00">続きをみる</label>
            <div class="other_container">
                <div class="feature_box">
                    <div class="smallbox">
                    <a href="/shop">
                        <img src="img/namasute7.jpg">
                        <h3>タンドリーミックス</h3>
                        <h3 class="orange">¥1,300</h3>
                    </a>
                    </div>
                    <div class="smallbox">
                    <a href="/shop">
                        <img src="img/namasute11.jpg">
                        <h3>カオパットクン</h3>
                        <h3 class="orange">¥1,150</h3>
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- フリースペース枠 -->
<!-- <section class="container">
    <p>フリースペース枠</p>
</section> -->

<!-- 新メニュー -->
<!-- <section class="newmenu">
    <div class="flex">
        <div class="menu">
            <img src="" alt="新メニュー">
        </div>
        <div class="menu">
            <img src="" alt="新メニュー">
        </div>
        <div class="menu">
            <img src="" alt="新メニュー">
        </div>
    </div>
</section> -->
<!-- <section class="favoshop">
    <li class="title_list">
        <a href="/search">
            <div class="acflex">
                    <div class="content1_2"><h1>新メニュー</h1></div>
                    <div class="content1_2_1"><h1 class="gray">＞</h1></div>
            </div>
        </a>
    </li>
    <ul class="horizontal-list">
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/seafood.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3>海鮮 家庭居酒屋</h3>
        </li>
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/meat.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3>Steak＆Lounge JB</h3>
        </li>
        <li class="item">
            <div class="favorite">
                <a class="item_img" href="/search"><img src="img/kansai2.jpg" alt=""></a>
                <a class="favo_icon" href="/favorite"><i class="bi bi-heart"></i></a>
            </div>
            <h3>関西居酒屋　必死のパッチ!!</h3>
        </li>
    </ul>
</section> -->

<!-- クーポン情報 -->
<!-- <section class="newmenu">
    <div class="flex">
        <div class="menu">
            <img src="" alt="クーポン情報">
        </div>
        <div class="menu">
            <img src="" alt="クーポン情報">
        </div>
        <div class="menu">
            <img src="" alt="クーポン情報">
        </div>
    </div>
</section> -->

<!-- フリースペース枠 -->
<!-- <section class="container">
    <p>フリースペース枠</p>
</section> -->

<!-- どんなものが食べたい気分？ -->
<section class="product">
    <h1>どんなものが食べたい気分？</h1>
    <ul class="genre">
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/sappari.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>さっぱりヘルシー</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/karaimono.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>辛いもの</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/amaimono.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>甘いもの</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/gatturi.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>ガッツリしたもの</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/mennmono.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>麵もの</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/otumami.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>単品・おつまみ</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/koukyu.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>ちょっぴり高級</h2>
                    </div>
                </div>
            </a>
        </li>
        <li class="genres">
            <a class="genre_target" href="/search">
                <div class="genre_img">
                    <img src="img/ramen.jpg" alt="" class="genre_images">
                    <div class="genre_name">
                        <h2>なんでもいい</h2>
                    </div>
                </div>
            </a>
        </li>
    </ul>
</section>

<!-- フリースペース枠 -->
<!-- <section class="container">
    <p>フリースペース枠</p>
</section> -->

<!-- 最近人気のストーリー -->
<!--<section class="movies">
    <h1>最近人気のストーリー</h1>
    <ul class="horizontal-list">
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/namasute4.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>ナマステキッチン</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/Diet - 26731.mp4"></video></a>
        </li>
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/omusubi1.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>おむすびカフェくさびや</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/Pizza - 79635.mp4"></video></a>
        </li>
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/kansai1.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>関西居酒屋　必死のパッチ!!</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/pasta_-_33256 (Original).mp4"></video></a>
        </li>
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/namasute4.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>ナマステキッチン</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/Diet - 26731.mp4"></video></a>
        </li>
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/omusubi1.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>おむすびカフェくさびや</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/Pizza - 79635.mp4"></video></a>
        </li>
        <li class="story">
            <div class="miniballoon">
                <div class="faceicon">
                    <img src="img/kansai1.jpg" alt="">
                </div>
                <div class="shopname">
                    <h4>関西居酒屋　必死のパッチ!!</h4>
                </div>
            </div>
            <a href="/search"><video playsinline muted autoplay src="img/pasta_-_33256 (Original).mp4"></video></a>
        </li>
    </ul>
</section> -->

<!-- 右下固定モーダル -->
<script>
    const menuopen = document.getElementById('menuopen');
    const menuclose = document.getElementById('menuclose');
    const menumodal = document.getElementById('menumodal');
    const menumask = document.getElementById('menumask');

    menuopen.addEventListener('click', () => {
        menumodal.classList.remove('menuhidden');
        menumask.classList.remove('menuhidden');
    });

    menuclose.addEventListener('click', () => {
        menumodal.classList.add('menuhidden');
        menumask.classList.add('menuhidden');
    });

    menumask.addEventListener('click', () => {
        // modal.classList.add('hidden');
        // mask.classList.add('hidden');
        menuclose.click();
    });
</script>

@include('public/footer')