@include('public/header')

<style>
  .news .bx-wrapper .bx-prev,.news .bx-wrapper .bx-next  {
    display: none;
  }
  select option {
    text-align:center;
  }
</style>

<!-- 　ヘッダー部分 -->
<section class="w_100">
    <div class="header_img">
        <a href="/">
            <img src="{{('img/headerlogo.png')}}">
        </a>
    </div>
    <div class="desc_w60">
        <!-- ログイン -->
        @if(Session::has('u_id'))
            <input id="" style="font-weight: bold;" type="text" name="" class="address" value="{{Session::get('address')}}" disabled>
        @else
        <div class="newregister">
            <a href="/initial_email/1">
                <p>【新規会員登録・ログインはこちら】</p>
            </a>
        </div>
        @endif
        <div class="flex_keyword m_top3">
            <!-- 直近一週間の日付表示 -->
            <select  name="upper limit" class="select_appttime" onChange="location.href=value;">
                <option value="" {{Session::get('date_flag') == null ? 'selected' : ''}}>【はじめに】配送希望日を選ぶ</option>
                @foreach($datetime as $key =>$date)
                <option style="text-align:webkit-center; color:#000; background:#fff;" value="/update_apptdate/{{$date['value']}}" {{isset($apptdate) && Session::get('date_flag') != null &&  ($apptdate == $date['value']) ? 'selected' : ''}}>{{$date['display']}}</option>
                @endforeach
            </select>
            <form id="form" action="/search" method="post">
                @csrf
                <input class="keyword" id="keywords" type="search" name="keyword" autocomplete="on" placeholder="例）店名、商品名、キーワード等を入力" value="{{$request->keyword}}">
                <button type="submit" id="sbtn2"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</section>

<!-- カテゴリー　横スクロール -->
<section class="menu_icon" style="margin-left:5%;">
    <ul class="horizontal-list">
        @foreach($categories as $key => $category)
            <a href="/search/{{$category->no}}">
                <li class="items">
                    <img src="/storage/admin_img/0-{{isset($category->no)? $category->no : '0'}}.{{isset($category->extension)? $category->extension : '0'}}">
                </li>
            </a>
        @endforeach
    </ul>
</section>

<!-- お知らせ ・スライダー-->
<section class="news">
    @foreach($customs as $custom)
    @if($custom->type == 1 && $custom->s_id == 0)
        <a href ="{{$custom->url}}">
            <p class="guid">{{$custom->title}}</p>
        </a>
    @endif
    @endforeach
        <ul class="slider">
            @foreach($customs as $custom)
            @if($custom->type == 2)
                <li>
                    <a href="{{$custom->url}}">
                        <div class="banner">
                                <img src="/storage/admin_img/2-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : 'jpg'}}">
                        </div>
                    </a>
                </li>
            @endif
            @endforeach
        </ul>
</section>

<!-- プロモーション-->
@if(isset($custom_ad[0]))
<section class="favoshop" style="background: #ededec;">
    <li class="title_list">
      <p class="promotion bold">プロモーション</p>
    </li>
    <ul class="horizontal-list">
        @foreach($custom_ad as $ad)
        <li class="item textoverflow">
            <div class="favorite">
                <a class="item_img" href="{{$custom->url}}">
                    <img src="/storage/admin_img/3-{{isset($ad->no)? $ad->no : '0'}}.{{isset($ad->extension)? $ad->extension : '0'}}">
                </a>
            </div>
            <div class="title_name">
                <p style="font-size:0.81em;">{{$ad->title}}</p>
            </div>
        </li>
        @endforeach
    </ul>
</section>
@endif

<!-- 特集枠 -->
@if($add->no == 0)
@else
<section class="special_photo" style="background:#{{$add->url}};" id="special_aria">
    <div class="bg_photo" style="background-image:url(../storage/paid_inventory_img/{{isset($add->type)? $add->type : ''}}-{{isset($add->id)? $add->id : ''}}.{{isset($add->extension)? $add->extension : ''}})"></div>
    <div class="img_float">
        <img src="/storage/paid_inventory_img2/{{isset($add->type)? $add->type : ''}}-{{isset($add->id)? $add->id : ''}}.{{isset($add->extension)? $add->extension : ''}}">
    </div>
    <div class="special_box">
        <div class="cp_box">
            <p class="t_center bold">{{$add->title}}</p>
            <div class="catch_f">{{$add->read}}</div>
            <div class="feature_box">
                @foreach($paid_inventorys as $key => $paid_inventory)
                    @if($paid_inventorys[0] == $paid_inventory || $paid_inventorys[1] == $paid_inventory)
                    <div class="smallbox">
                        <a href="{{$paid_inventory->url}}">
                            <div class="modal-open mdl">
                                <img src="/storage/paid_inventory_img/6-{{isset($paid_inventory->id)? $paid_inventory->id : '0'}}.{{isset($paid_inventory->extension)? $paid_inventory->extension : '0'}}">
                            </div>
                            <p class="paid_title">{{$paid_inventory->title}}</p>
                            <p class="paid_name">{{$paid_inventory->name}}</p>
                        </a>
                    </div>
                    @else
                    <!-- ３つ目以降 -->
                    <div class="other_container">
                        <a href="{{$paid_inventory->url}}">
                            <div class="center" style="margin-top:1em;">
                                <img src="/storage/paid_inventory_img/6-{{isset($paid_inventory->id)? $paid_inventory->id : '0'}}.{{isset($paid_inventory->extension)? $paid_inventory->extension : '0'}}">
                            </div>
                            <p class="paid_title">{{$paid_inventory->title}}</p>
                            <p class="paid_name">{{$paid_inventory->name}}</p>
                        </a>
                    </div>                    
                    @endif
                @endforeach
                <!-- 続きを見る閉じるボタン -->
                @foreach($paid_inventorys as $key => $paid_inventory)
                @if(isset($paid_inventorys[2]) && $paid_inventorys[2] == $paid_inventory)
                        <input id="cp00" type="checkbox" onchange="change()">
                        <label class="w_55 move" for="cp00"></label>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
</section>
@endif

<!-- もしデリ推し店-->
@if(isset($c_pickup[0]))
<section class="favoshop">
    <li class="title_list">
      <p class="bold">PICKUP</p>
    </li>
    <ul class="horizontal-list">
        @foreach($c_pickup as $pickup)
        <li class="item textoverflow">
            <div class="favorite textoverflow">
                <a class="item_img" href="shop/{{$pickup->title}}">
                    <!-- <img src="/storage/store_image/{{isset($pickup->title)? $pickup->title : '0'}}-0.jpg"> -->
                    <img src ="{{$pickup->img}}">
                </a>
                @if(Session::has('u_id'))
                    <div class="favo_icons">
                        @if($pickup->favorite == 1)
                        <a href="/update_favorite/{{$pickup->title}}"  onclick="return favo_remove();">
                            <img style="height:1.5em; width:1.5em;" src ="{{url('img/favorite_hover.png')}}">
                        </a>
                        @else
                        <a href="/update_favorite/{{$pickup->title}}"  onclick="return favo_addition();">
                            <img style="height:1.5em; width:1.5em;" src ="{{url('img/favorite.png')}}">
                        </a>
                        @endif
                    </div>
                @endif
            </div>
            <div class="title_name">
                <p class="" style="font-size:11px;">{{$pickup->name}}</p>
            </div>
        </li>
        @endforeach
    </ul>
</section>
@endif

<!-- クーポン-->
@if($coupons != '')
<section class="favoshop">
    <li class="title_list">
        <p class="bold">クーポン</p>
    </li>
    <ul class="horizontal-list">
        @foreach($coupons as $coupon)
            <li class="item textoverflow">
                <div class="favorite">
                    @if(Session::has('u_id'))
                    <a class="item_img" href="add_coupon/{{$coupon->coupon_hash}}" onclick="return really_addition();">
                        <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                        <div class="title_name">
                            <p class="bold" style="font-size:14px;">{{$coupon->title}}</p>
                            <p style="font-size:11px;">{{$coupon->s_name}}</p>
                        </div>
                    </a>
                    @else
                    <a class="item_img" href="initial_email/1" onclick="return really_addition();">
                        <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                        <div class="title_name">
                            <p class="bold" style="font-size:14px;">{{$coupon->title}}</p>
                            <p style="font-size:11px;">{{$coupon->s_name}}</p>
                        </div>
                    </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</section>
@endif

<!-- フッター ------------->
<footer class="footers">
    <p class="oreder_form"><span style="font-size:1.1em;">ご注文に関するお問い合わせ</span></p>
    <div class="moshideli_form">
        <div class="weekday">
            <p style="font-size: 1.3em;">平日</p>
        </div>
        <div class="moshideli_btm">
            <p>[受付時間] 9:00～18:00</p>
            <div class="">
                <p style="font-size:0.9em;">TEL.
                <span style="font-size:1.6em">042-337-1888</span>
                </p>
            </div>
        </div>
    </div>
    <a href="/contact">
        <p class="inquiry_form"><span style="font-size: 1.1em;">お問い合わせフォームへ</span></p>
    </a>
</footer>

<!-- 有料広告枠の続きを見ると閉じる -->
<script>
    function change() {
         let checkbox = document.getElementById('cp00');
         let other_containers = document.querySelectorAll('.other_container');//3つ目以降
         if (checkbox.checked) {//チェックされたら
            for (let i = 0; i < other_containers.length; i++) {
                other_containers[i].style.height = 'auto';
            }
        } else {
             for (let i = 0; i < other_containers.length; i++) {
                 other_containers[i].style.height = '0';
             }
         }       
    }
</script>

<!-- 広告バナー枠　　　スライダー -->
<script type="text/javascript">
    $(document).ready(function(){
        $('.slider').bxSlider({
            auto: true,
            pause: 4000,
            touchEnabled: false
        });
    });
    function really_addition(){
        var result = confirm('クーポンを追加しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
    function favo_addition(){
        var result = confirm('お気に入りに追加しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
    function favo_remove(){
        var result = confirm('お気に入りを外しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
</script>
@include('public/footer')