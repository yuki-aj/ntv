@include('public/header')
<style>
    /* スライダーの丸ボタン*/
    .bx-wrapper .bx-pager {
        bottom: 15px;
    }
    .says:after {
        border:10px solid transparent;
        border-right: 16px solid #e8e8e8;
        top:10px;
    }
    .selects {
        margin: 3em 0 1em;
        text-align: center;
    }
    @media  screen and (max-width: 600px) {
        .select_time {
            font-size:0.9em;
        }
        .selects {
            margin: 1em 0;
            text-align: center;
        }
    }
</style>

<section class="moshideli_btm">
    <div class="desc_w60" style="padding-top:3%">
        @if(Session::has('u_id'))
        <input  style="font-weight: bold;" type="text"  class="address" placeholder="{{Session::get('address')}}" value="" disabled>
        @else
            <div class="newregister"style="margin:0 auto 0.5em;">
                <a href="/initial_email/1">
                    <p>【新規会員登録・ログインはこちら】</p>
                </a>
            </div>
        @endif
        <div class="flex_keyword">
            <select  name="upper limit" class="select_appttime" onChange="location.href=value;">
                <option value="" {{Session::get('date_flag') == null ? 'selected' : ''}}>【はじめに】配送希望日を選ぶ</option>
                @foreach($datetime as $key =>$date)
                <option style="color:#000; background:#fff;" value="/update_apptdate/{{$date['value']}}" {{isset($apptdate) && Session::get('date_flag') != null &&  ($apptdate == $date['value']) ? 'selected' : ''}}>{{$date['display']}}</option>
                @endforeach
            </select>

            <form id="form" action="/search" method="post">
                @csrf
                <input id="keywords" type="search" name="keyword" autocomplete="on" placeholder="例）店名、商品名、キーワード等を入力"
                    value="{{$request->keyword}}">
                <button type="submit" id="sbtn2" class=""><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
    <!-- カテゴリー　横スクロール -->
    <section class="menu_icon">
        <ul class="horizontal-list">
            @foreach($categories as $key => $category)
                @if($category->no == $c_id)
                    <a href="/search/{{$category->no}}">
                        <li class="items">
                            <img src="/storage/admin_img/0-{{isset($category->no)? $category->no : '0'}}-2.{{isset($category->extension)? $category->extension : '0'}}">
                        </li>
                    </a>
                @else
                    <a href="/search/{{$category->no}}">
                        <li class="items">
                            <img src="/storage/admin_img/0-{{isset($category->no)? $category->no : '0'}}.{{isset($category->extension)? $category->extension : '0'}}">
                        </li>
                    </a>
                @endif
            @endforeach
        </ul>
    </section>

    <!-- お店・商品　タブメニュー -->
        <div class="tab-buttons w_76">
            <span class="content1"><p id="product">商品({{count($lists)}})</p></span>
            <span class="content2"><p id="store">お店({{count($stores)}})</p></span>
            <div id="lamp" class="content1"></div>
        </div>
        <!-- お店 -->
        <div class="tab-content">
            <div class="content2" id="none">
                <!-- キーワード検索 -->
                @if(isset($request->keyword))
                <div class="results">
                <p class="m_5"><span style="font-size:1.2em;">{{$request->keyword}}</span> の検索結果</p>
                </div>
                @endif
                <!-- 一件もお店がなかったら -->
                @if(empty($stores))
                <div class="results">
                <h2 class="t_center">該当するお店はございません。<br>他の検索キーワードをお試しください。</h2>
                </div>
                @endif
                <ul>
                    @foreach($stores as $s_id => $store)
                    <li>
                        <div class="surround">
                            <div class="allshop">
                                <a href="/shop/{{$s_id}}">
                                    <div class="balloon w_90">
                                        <div class="faceicon">
                                            <img src="{{$store['staff_img']}}">
                                        </div>
                                        @if($store['s_catch_copy'] != '')
                                        <div class="says">
                                            <p>{{$store['s_catch_copy']}}</p>
                                        </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="slider_width w_90">
                                    <div class="slider">
                                        @for($i=0; $i < 3; $i++)
                                        <div class="favorite" style="width:100%;">
                                            <a class="item_img" href="/shop/{{$s_id}}">
                                                <img src="{{$store[$i]['store_img']}}">
                                            </a>
                                            @if(Session::has('u_id'))
                                            @if($store['favorite'] == 1)
                                            <a href="/update_favorite/{{$s_id}}" class="search_heart_posi" onclick="return favo_remove();">
                                                <div class="big_favo_icon">
                                                    <img src ="{{url('img/favorite_hover.png')}}">
                                                </div>
                                            </a>
                                            @else
                                            <a href="/update_favorite/{{$s_id}}" class="search_heart_posi" onclick="return favo_addition();">
                                                <div class="big_favo_icon">
                                                    <img src ="{{url('img/favorite.png')}}">
                                                </div>
                                            </a>
                                            @endif
                                            @endif
                                        </div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="shop_name w_90m">
                                    <div class="flexspace">
                                        <div class="p_r5">
                                            <a href="/shop/{{$s_id}}">
                                                <h2 class="bold">{{$store['s_name']}}</h2>
                                            </a>
                                        </div>
                                        <!-- 該当するお店のクーポンがあったら -->
                                        @if(Session::has('u_id'))
                                        @if($store['coupon_flag'] == 1)
                                        <a class="t_c" href="/shop_coupon/{{$s_id}}">
                                            <img src ="{{url('img/coupon.png')}}">
                                        </a>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                                <!-- お店毎の該当商品 -->
                                <div class="shop_menu">
                                    <ul class="horizontal-lists">
                                        @foreach($store['product'] as $p_id => $product)
                                        <li class="item textoverflow prod_item">
                                            <a href="/product_detail/{{$p_id}}">
                                                <div class="favorite">
                                                    <img style="height: auto;" src="/storage/product_image/{{$p_id}}.{{$product['p_extension']}}">
                                                </div>
                                                <div class="menus">
                                                    <div class="menus_big bold">{{$product['p_name']}}</div>
                                                    <div class="menus_small">{{number_format($product['p_price'])}}円</div>
                                                </div>
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <!-- 配送メモ -->
                                @if($store['s_schedule_memo'] != '')
                                <div class="w_90">
                                    <p class="time_color">{{$store['s_schedule_memo']}}</p>
                                </div>
                                @endif

                                <!-- 一週間のカレンダー -->
                                <div class="calendar w_90m">
                                    <div class="calendarwrap">
                                        <div class="calendar_container">
                                            <table class="calendar_table" summary="デリバリーカレンダー">
                                                <thead>
                                                    <tr>
                                                    @foreach($store['week_schedules'] as $s_id => $week_schedule)
                                                        <td aria-label="" class="calendar_daywrap">
                                                            <p>{{$week_schedule['w']}}</p>
                                                        </td>
                                                    @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                    @foreach($store['week_schedules'] as $key => $week_schedule)
                                                        <td class="calendar_cell">
                                                            @if($week_schedule['mark'] == 'ー')
                                                            <div class="store_reserva" rel="nofollow">
                                                                <span class="calendar_date" aria-label="">
                                                                    {{$week_schedule['d']}}
                                                                </span> 
                                                                <p class="bold orange">
                                                                    {{$week_schedule['mark']}}
                                                                </p>
                                                            </div>
                                                            @else
                                                            <a class="store_reserva" href="/update_apptdate/{{$key}}" rel="nofollow">
                                                                <span class="calendar_date" aria-label="">
                                                                    {{$week_schedule['d']}}
                                                                </span> 
                                                                <p class="bold orange">
                                                                @if($week_schedule['mark'] == 'L')
                                                                <span class="material-symbols-outlined">clear_day</span>
                                                                @elseif($week_schedule['mark'] == 'D')
                                                                <span class="material-symbols-outlined">bedtime</span>
                                                                @elseif($week_schedule['mark'] == '●')
                                                                <span>●</span>
                                                                @endif
                                                                </p>
                                                            </a>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="lanch_dinner">
                                    <p class="google_icons"><span>●</span>営　<span class="material-symbols-outlined">clear_day</span>ランチ　<span class="material-symbols-outlined">bedtime</span>ディナー</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        <!-- </div> -->
        <!-- 商品 -->
        <!-- <div class="tab-content"> -->
            <div class="content1" id="none2">
                <!-- キーワード検索 -->
                @if(isset($request->keyword))
                <div class="results">
                 <p class="m_5"><span style="font-size:1.2em;">{{$request->keyword}}</span> の検索結果</p>
                </div>
                @endif
                @if(empty($lists))
                <!-- 一件も商品がなかったら -->
                <div class="results">
                    <h2 class="t_center">該当する商品はございません。<br>他の検索キーワードをお試しください。</h2>
                </div>
                @endif

                @if(empty($lists))
                @else
                <!-- ソート -->
                <div class="selects">
                    @if($c_id == '')
                    <form action="/search" method="POST">
                    @else
                    <form action="/search/{{$c_id}}" method="POST">
                    @endif
                    @csrf
                    <select onchange="submit(this.form)" name="price" class="select_time" style="text-align:-webkit-center;">
                        <option disabled selected>並び替え</option>
                        <option value="1">料金が安い順</option>
                        <option value="2">料金が高い順</option>
                    </select>
                    <input id="keywords" type="hidden" name="keyword" autocomplete="on" value="{{$request->keyword}}">
                    </form>
                </div>
                @endif
                <!-- 商品一覧 -->
                <div class="productcontainer">
                    <ul class="searchflex">
                        @foreach($lists as $p_id => $list)
                        <li class="search_li">
                            <a href="/product_detail/{{$list['p_id']}}">
                                <div class="searchproduct">
                                    <div class="favorite">
                                        <div class="modal-open mdl">
                                            @if(isset($list['p_id']))
                                            <img src="/storage/product_image/{{$list['p_id']}}.{{$list['p_extension']}}">
                                            @else
                                            <img src="/storage/product_image/{{$p_id}}.{{$list['p_extension']}}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p_center">
                                        <p>{{$list['s_name']}}</p>
                                        <h2 class="bold">{{$list['p_name']}}</h2>
                                        <h3>{{number_format($list['p_price'])}}円</h3>
                                    </div>
                                    <input class="s_id" type="hidden" name="s_id" value="{{$list['s_id']}}">
                                    <input class="quantity" type="hidden" pattern="^[0-9]+$" name="quantity" value="1" min="1">
                                    @if(isset($list['p_id']))
                                    <input class="p_id" type="hidden" name="p_id" value="{{$list['p_id']}}">
                                    @else
                                    <input class="p_id" type="hidden" name="p_id" value="{{$p_id}}">
                                    @endif
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
</section>

<script>
        jQuery(document).ready(function(){
            $('.slider').bxSlider({
                auto: false,
                touchEnabled: false,
            });
        });
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
    // jquery
    // hide 　　　　 　特定のHTML要素を非表示にすることが可能
    // first 　　　　　指定要素の最初の要素を対象
    // slideDown　 　 隠れている要素を縦方向に拡大して表示
    // attr　　　　　 属性(class)を取得
    // addClass  　　 任意の要素にclass属性を追加する
    // removeClass　　HTML要素に付与されたクラスを削除することができる ()　＝すべてのクラスを削除
    // each 　　　　　 html要素・配列・オブジェクトなどに対して繰り返し処理を行う
    // hasClass 　　　対象のHTML要素にそのクラスがあるかを確認できる

    // お店・商品　のタブメニュー部分
        $(document).ready(function(){
            $('#none').addClass('none');
        });
    // $('.tab-content>div').hide();
    $('.tab-content>div').first();
    $('.tab-buttons span').click(function () {
        var thisclass = $(this).attr('class');
        $('#lamp').removeClass().addClass('#lamp').addClass(thisclass);
        if(thisclass == 'content2'){
            $('#none').removeClass('none');
            $('#none2').addClass('none');
        }else{
            $('#none').removeClass('none');
            $('#none2').addClass('none');
        }
        $('.tab-content>div').each(function () {
            if ($(this).hasClass(thisclass)) {
                $(this).fadeIn();
            }
            else {
                $(this).hide();
            }
        });
    });

    // $(document).ready(function(){
    //         $('#none').addClass('none');
    //     });
    // // $('.tab-content>div').hide();
    // $('.tab-content>div').first();
    // $('.tab-buttons span').click(function () {
    //     var thisclass = $(this).attr('class');
    //     // $('#lamp').addClass('#lamp').addClass(thisclass);
    //     $('#lamp').removeClass().addClass('#lamp').addClass(thisclass);
    //     // if(thisclass == 'content2'){
    //     //     // document.getElementById("none").classList.remove("none")
    //     //      $('#none').removeClass('none');
    //     //     $('#none2').addClass('none');
    //     // }else{
    //     //     // document.getElementById("none").classList.remove("none")
    //     //     $('#none').addClass('none');
    //     //     $('#none2').removeClass('none');
    //     // }
    //     $('.tab-content>div').each(function () {
    //         if ($(this).hasClass(thisclass)) {
    //             $(this).fadeIn();
    //         }
    //         else {
    //             $(this).hide();
    //         }
    //     });
    // });
    

    // タブメニュー切替装飾
    document.getElementById('store').addEventListener('click',function(){
    document.getElementById('store').style.color = '#da551a';
    document.getElementById('store').style.background = 'white';
    document.getElementById('store').style.border = 'thin solid #f4a125';
    document.getElementById('store').style.borderRadius = '50px';
    document.getElementById('product').style.background = '#f4a125';
    document.getElementById('product').style.color = 'white';
    document.getElementById('product').style.borderRadius = '50px';
    document.getElementById('product').style.border = 'none';
    })
    document.getElementById('product').addEventListener('click',function(){
    document.getElementById('product').style.color = '#da551a';
    document.getElementById('product').style.background = 'white';
    document.getElementById('product').style.border = 'thin solid #f4a125';
    document.getElementById('product').style.borderRadius = '50px';
    document.getElementById('store').style.color = 'white';
    document.getElementById('store').style.background = '#f4a125';
    document.getElementById('store').style.borderRadius = '50px';
    document.getElementById('store').style.border = 'none';
    })

</script>

@include('public/footer')