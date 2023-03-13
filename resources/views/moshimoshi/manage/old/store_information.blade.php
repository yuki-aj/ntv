@include('manage/header')
<style>
    .contact_table th {
        font-weight: bold;
    }
    .contact_table td {
        text-align :left;
    }
    .contact-body img {
        width: 15%;
    }
    .flex {
        justify-content:left;
    }
    label {
        font-size: 0.5em;
        background: #000000;
    }
    @media  screen and (max-width: 1080px) {
        .update {
            width:30%;
            font-size: 0.8em;
        }
        .contact_table h2 {
            text-align:left;
        }
        .info_flex {
            display:block;
        }
        .info_table th,
        .info_table td{
            display: block;
            width:100%;
        }
        .contact-item {
            text-align:left;
        }
        .contact-body input {
            width: 100%;
            box-sizing:border-box;
        }
        .over {
            width:200px;
        }
    }
</style>

<h1 class="baskets">店舗管理画面</h1>
<div class="admin_page">
    @if(Session::get('kind')==0)
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/store_manage">
                <p>登録店舗一覧へ</p>
            </a>
        </div>
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/store_information">
                <p>店舗追加</p>
            </a>
        </div>
    @endif

    @if($store->id)
        <div class="admin_flex">
            <a class="update" href="/product_list/{{$store->id}}"><p>商品追加</p></a>
            <a class="update" href="/option_list/{{$store->id}}"><p>オプション管理</p></a>
            @if(Session::get('kind')==2)
            <a class="update" href="/shop_order_search/{{$store->id}}" rel="noopener noreferrer"><p>注文管理</p></a>
            @endif
            <a class="update" href="/logout"><p>ログアウト</p></a>

        </div>
        <div class="underline">
            <div class="t_center">
                <h1 class="bold">{{$store->name}}</h1>
                <!-- 通常の配送可能時間が設定されていたらURL表示 -->
                @if(isset($check_calendar[0]))
                <a target="_blank" href="/shop/{{$store->id}}">
                <p style="color:blue">店舗TOP　https://moshideli/shop/{{$store->id}}</p>
                </a>
                @endif
            </div>
        </div>

        <!-- 配送時間設定 -->
        <div class="under-line">
            <div class="admin_page">
                <table class="contact_table position">
                    <tr>
                        <td class="contact-body">
                        <h2 class="bold">・配送可能時間設定</h2>
                        </td>
                        <th>
                            <a href="/calendar/{{$store->id}}">
                                <div class="orange_btn">
                                    <p>追加</p>
                                </div>
                            </a>
                        </th>
                    </tr>
                    <?php $open = true; $close =true; ?>
                    @foreach($calendars as $key => $calendar)
                        @if($calendar->open == '営業')

                        <tr>
                            <td class="contact-body pa_6">
                                @if($open == true)
                                <p class="bold">営業</p>
                                <?php $open = false; ?>
                                @endif
                                <div class="m_left info_flex">
                                    <p>{{$calendar->day}}</p>
                                    <p>{{$calendar->time}}</p>
                                </div>
                            </td>
                            <th>
                                <div class="flex">
                                    <a href="/calendar_delete/{{$calendar->id}}"  onclick="return really_delete();"><p class="custom_btn_b">削除</p></a>
                                </div>
                            </th>
                        </tr>
                        @endif
                    @endforeach
                    @foreach($calendars as $key => $calendar)
                        @if($calendar->open == '休業')
                        <tr>
                            <td class="contact-body pa_6">
                                @if($close == true)
                                <p class="bold">休業</p>
                                <?php $close =false;?>
                                @endif
                                <div class="m_left info_flex">
                                    <p>{{$calendar->day}}</p>
                                    <p>{{$calendar->time}}</p>
                                </div>
                            </td>
                            <th>
                                <div class="flex">
                                    <a href="/calendar_delete/{{$calendar->id}}"  onclick="return really_delete();"><p class="custom_btn_b">削除</p></a>
                                </div>
                            </th>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div> 
        </div> 

        <!-- ショップ内カテゴリー -->
        <div class="under-line">
            <div class="admin_page">
                <table class="contact_table position">
                    <tr>
                        <td class="contact-body">
                        <h2 class="bold">・ショップ内カテゴリー</h2>
                        </td>
                        <th>
                            <a href="/product_category/{{$store->id}}">
                                <div class="orange_btn">
                                    <p>追加</p>
                                </div>
                            </a>
                        </th>
                    </tr>
                    @foreach($customs as $custom)
                    @if($custom->type == 10)
                    <tr>
                        <td class="contact-body pa_6">
                            <div class="flexs">
                                <p>{{$custom->no}}</p>
                                <p>{{$custom->title}}</p>
                            </div>
                        </td>
                        <th>
                            <div class="flex gap">
                                <a href="/product_category_delete/{{$custom->id}}"  onclick="return really_delete();"><p class="custom_btn_b">削除</p></a>
                            </div>
                        </th>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
        </div>

        <!-- 店舗からのお知らせ-->
        <div class="under-line">
            <div class="admin_page">
                <table class="contact_table position" style="table-layout: fixed;">
                    <tr>
                        <td class="contact-body admin_box">
                          <h2 class="bold">・店舗からのお知らせ</h2>
                        </td>
                        <th>
                            <a href="/admin_edit/1/{{$store->id}}/0">
                                <div class="orange_btn">
                                    <p>追加</p>
                                </div>
                            </a>
                        </th>
                    </tr>
                    @foreach($customs as $key => $custom)
                        @if($custom->type == 1)
                            <tr>
                                <td class="contact-body pa_6 admin_box">
                                    <div class="admin_gap">
                                        <p class="over">{{$custom->title}}</p>
                                        <p class="over">{{$custom->url}}</p>
                                    </div>
                                </td>
                                <th>
                                    <a href="/admin_edit/{{$custom->type}}/{{$store->id}}/{{$custom->id}}"> <p class="custom_btn_a m_btm">編集</p></a>
                                    <a href="/store_custom_delete/{{$custom->id}}" onclick="return really_delete();"><p class="custom_btn_b">削除</p></a>
                                </th>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>

        <!-- 店舗のよみものページ-->
        @if(Session::get('kind')==0)
        <div class="under-line">
            <div class="admin_page">
                <table class="contact_table position" style="table-layout: fixed;">
                    <tr>
                        <td class="contact-body admin_box">
                          <h2 class="bold">・店舗のよみものページ</h2>
                        </td>
                        <th>
                            <a href="/admin_edit/8/{{$store->id}}/0">
                                <div class="orange_btn">
                                    <p>追加</p>
                                </div>
                            </a>
                        </th>
                    </tr>
                    @foreach($customs as $key => $custom)
                    @if($custom->type == 8)
                    <tr>
                        <td class="contact-body pa_6 admin_box">
                            <div class="admin_gap">
                                <div class="flex gap">
                                        <img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}">
                                    <div>
                                        <p class="over">{{$custom->title}}</p>
                                        <p class="over">{{$custom->url}}</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <th>
                            <a href="/admin_edit/{{$custom->type}}/{{$store->id}}/{{$custom->id}}"> <p class="custom_btn_a m_btm">編集</p></a>
                            <a href="/store_custom_delete/{{$custom->id}}" onclick="return really_delete();"><p class="custom_btn_b">削除</p></a>
                        </th>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
        </div>
        @endif
    @endif

    <!-- 店舗基本情報 -->
    <form class="r_update" id="mainform" action="{{ url('store_update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="info_table">
            <tr>
                <th class="contact-item">店舗名</th>
                <td class="contact-body">
                    <input type="text" placeholder="例）　ナマステキッチン　多摩センター店" id="name" name="name" class="form-text" value="{{isset($store->name)? $store->name : ''}}" required>
                </td>
            </tr>
            <tr>
                <th class="contact-item">店主から一言</th>
                <td class="contact-body">
                    <label>
                        <input type="text" placeholder="例）　異国情緒あふれる世界にようこそ！  タイ料理も！　インド料理も!!    楽しめます♪" id="catch_copy" name="catch_copy" value="{{isset($store->catch_copy)? $store->catch_copy : ''}}" class="form-text" />
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item">宅配可能時間</th>
                <td class="contact-body">
                    <label>
                        <input type="text" placeholder="例）終日11:00 ~ 18:00" id="schedule_memo" name="schedule_memo" value="{{isset($store->schedule_memo)? $store->schedule_memo : ''}}" class="form-text" />
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item">店主よりみなさまへ</th>
                <td class="contact-body">
                    <textarea name="note" placeholder="" class="form-textarea">{{isset($store->note)? $store->note : ''}}</textarea>
                </td>
            </tr>
            <tr>
                <th class="contact-item">スライダー<br>店舗イメージ写真<br>※１枚目が店舗トップ写真</th>
                <td class="manage_img">
                    <label class="file_image">
                        @for ($i = 0; $i < 3; $i++)
                        <div class="flex" style="flex-wrap: wrap;">
                            <input type="file" name="store_image[]" class="form-text"/>
                            <p class="bold" style="font-size:0.7em;">※jpg形式のみ</p>
                            <img src="/storage/store_image/{{isset($store->id)? $store->id : '0'}}-{{$i}}.jpg">
                        </div>
                        @endfor
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item">店長の顔写真</th>
                <td class="manage_img">
                    <label class="file_image">
                        <div class="flex" style="flex-wrap: wrap;">
                            <input type="file" placeholder="" name="staff_image" class="form-text" />
                            <p class="bold" style="font-size:0.7em;">※jpg形式のみ</p>
                            <img src="/storage/staff_image/{{isset($store->id)? $store->id : '0'}}.jpg">
                        </div>
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-geo-fill"></i> 住所</th>
                <td class="contact-body">
                    <input type="text" placeholder="例）東京都多摩市落合1-45-1 丘の上パティオ205" id="address" name="address" class="form-text" value="{{isset($store->address)? $store->address : ''}}" />
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-geo-alt-fill"></i> アクセス</th>
                <td class="contact-body">
                    <input type="text" placeholder="例）多摩センター徒歩2分" id="access" name="access" class="form-text" value="{{isset($store->access)? $store->access : ''}}" />
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-telephone-fill"></i>   電話番号</th>
                <td class="contact-body">
                    <input name="tel" placeholder="0312345678" type="tel"
                        oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" 
                        value="{{isset($store->tel)? $store->tel : ''}}" maxlength="11" class="changeform">
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-globe"></i> WEBサイト</th>
                <td class="contact-body">
                    <input type="url" placeholder="ホームページ　例）https://mosideli.com" id="url" name="url" class="form-text"  value="{{isset($store->url)? $store->url : ''}}" />
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-chat"></i>　SNS</th>
                <td class="contact-body" style="height:auto;">
                    <input style="margin:3% 0;" type="text" placeholder="instagramのURL" id="instagram" name="instagram" class="form-text"  value="{{isset($store->instagram)? $store->instagram : ''}}" />
                    <!-- <input style="margin:3% 0;" type="text" placeholder="twitter" id="twitter" name="twitter" class="form-text"  value="{{isset($store->twitter)? $store->twitter : ''}}"  disabled/> -->
                    <!-- <input style="margin:3% 0;" type="text" placeholder="facebook" id="facebook"name="facebook" class="form-text"  value="{{isset($store->facebook)? $store->facebook : ''}}"  disabled/> -->
                </td>
            </tr>
            <tr>
                <th class="contact-item"><i class="bi bi-globe"></i> StripeID</th>
                <td class="contact-body">
                    <input type="text" placeholder="" name="stripe_user_id" class="form-text"  value="{{isset($store->stripe_user_id)? $store->stripe_user_id : ''}}" {{ $store->stripe_user_id ? 'readonly' : '' }} />
                </td>
            </tr>
        </table>
        <input type="hidden" name="s_id" value="{{isset($store->id)? $store->id : '0'}}">
        <input type="hidden" name="store_status" value="1">
        <input type="hidden" name="feature_status" value="0">
        <input type="hidden" name="email_status" value="1">
        <div class="t_center m_top3 m_btm">
            @if(isset($store->id) && $store->id != 0)
            <button class="addition"  onclick="return really_update();" type="submit">情報を更新する</button>
            @else
            <button class="addition" onclick="return check();" type="submit">店舗を追加する</button>
            @endif
        </div>
    </form>
</div>

<script>
     function really_delete(){
        var result = confirm('本当に削除しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
    function really_update(){//更新
        var name = document.getElementById('name');
        var name_count = name.value.length;
        console.log(name_count);
        if(name_count > 255){
            alert('店舗名の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var catch_copy = document.getElementById('catch_copy');
        var catch_copy_count = catch_copy.value.length;
        console.log(catch_copy_count);
        if(catch_copy_count > 255){
            alert('キャッチコピーの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var schedule_memo = document.getElementById('schedule_memo');
        var schedule_memo_count = schedule_memo.value.length;
        console.log(schedule_memo_count);
        if(schedule_memo_count > 255){
            alert('宅配可能時間の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var address = document.getElementById('address');
        var address_count = address.value.length;
        console.log(address_count);
        if(address_count > 255){
            alert('住所の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var access = document.getElementById('access');
        var access_count = access.value.length;
        console.log(access_count);
        if(access_count > 255){
            alert('アクセスの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var url = document.getElementById('url');
        var url_count = url.value.length;
        console.log(url_count);
        if(url_count > 255){
            alert('Webサイトの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var instagram = document.getElementById('instagram');
        var instagram_count = instagram.value.length;
        console.log(instagram_count);
        if(instagram_count > 255){
            alert('instagramの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var twitter = document.getElementById('twitter');
        var twitter_count = twitter.value.length;
        console.log(twitter_count);
        if(twitter_count > 255){
            alert('twitterの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var facebook = document.getElementById('facebook');
        var facebook_count = facebook.value.length;
        console.log(facebook_count);
        if(facebook_count > 255){
            alert('facebookの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var result = confirm('本当に更新しますか？');
        if(result) {
            document.querySelector('r_update').submit();
        } else {
            return false;
        }
    }

    function check(){//追加
        var name = document.getElementById('name');
        var name_count = name.value.length;
        console.log(name_count);
        if(name_count > 255){
            alert('店舗名の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var catch_copy = document.getElementById('catch_copy');
        var catch_copy_count = catch_copy.value.length;
        console.log(catch_copy_count);
        if(catch_copy_count > 255){
            alert('キャッチコピーの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var schedule_memo = document.getElementById('schedule_memo');
        var schedule_memo_count = schedule_memo.value.length;
        console.log(schedule_memo_count);
        if(schedule_memo_count > 255){
            alert('宅配可能時間の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var address = document.getElementById('address');
        var address_count = address.value.length;
        console.log(address_count);
        if(address_count > 255){
            alert('住所の文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var access = document.getElementById('access');
        var access_count = access.value.length;
        console.log(access_count);
        if(access_count > 255){
            alert('アクセスの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var url = document.getElementById('url');
        var url_count = url.value.length;
        console.log(url_count);
        if(url_count > 255){
            alert('Webサイトの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var instagram = document.getElementById('instagram');
        var instagram_count = instagram.value.length;
        console.log(instagram_count);
        if(instagram_count > 255){
            alert('instagramの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var twitter = document.getElementById('twitter');
        var twitter_count = twitter.value.length;
        console.log(twitter_count);
        if(twitter_count > 255){
            alert('twitterの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        var facebook = document.getElementById('facebook');
        var facebook_count = facebook.value.length;
        console.log(facebook_count);
        if(facebook_count > 255){
            alert('facebookの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
    }
</script>

</body>
</html>