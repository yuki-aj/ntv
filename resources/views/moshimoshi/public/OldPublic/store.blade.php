@include('public/header')

<style>
    .store_table tr{
        padding: 3% 0;
    }
    .say {
        margin-left: 0;
        margin-top:5%;
    }
    .say:after {
        bottom:-1.2em;
    }
    @media  screen and (max-width: 420px) {
        #menu,#info,#product,#store {
            line-height:23px;
        }
    }
</style>

<!-- お店トップ -->
<section class="store_top">
    <div class="">
        <div class="b_btn">
        <!-- <img src="/storage/store_image/{{$store->id}}-0.jpg"> -->
        <img src="{{$store->top_img}}">

            <a href="/search" class="back_posi">
            <div class="back_btn"><span class="left material-symbols-outlined">arrow_circle_left</span></div>
            </a>
        @if(Session::has('u_id'))
            @if($store->favorite == 1)
            <a href="/update_favorite/{{$store->id}}" class="heart_posi" onclick="return favo_remove();">
                <img src ="{{url('img/favorite_hover.png')}}">
            </a>
            @else
            <a href="/update_favorite/{{$store->id}}"  class="heart_posi" onclick="return favo_addition();">
                <img  src ="{{url('img/favorite.png')}}">
            </a>
            @endif    
        @endif
            <div id="open_sns"class="url_posi">
                <img class="" src ="{{url('img/popup.png')}}">
            </div>
        </div>
    </div>
    <div id="mask_sns" class="hiddens"></div>
    <section id="modal_sns" class="hiddens">
        <div class="popup-inneres">
            <div class="p-20">
                <div class="close-btn" id="close_sns"><i class="fas fa-times"></i></div>
                <div class="modal_p10">
                    <h2>このページをシェアする</h2>
                    @if($store->instagram != '')
                    <a href="{{$store->instagram}}" rel="nofollow" target="_blank"><i class="bi bi-instagram"></i></a>
                    @endif
                    <a href="https://twitter.com/share?url=<?php echo url()->current(); ?>" target="_blank"><i class="bi bi-twitter"></i></a>
                    <a href="https://www.facebook.com/share.php?u=<?php echo url()->current(); ?>" target="_blank"><i class="bi bi-facebook"></i></a>
                </div>
            </div>
        </div>
    </section>

    <div class="store_box w_90m">
        <div class="store_name">
            <div class="flexspace">
                <div class="p_r5">
                    <h2 class="bold">{{$store['name']}}</h2>
                </div>
                @if(Session::has('u_id'))
                    @if(isset($coupons[0]))
                    <a class="t_c" href="/shop_coupon/{{$store['id']}}">
                      <img src ="{{url('img/coupon.png')}}">
                    </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- スケジュールメモ -->
    @if($store['schedule_memo'] != '')
    <div class="w_90">
        <p class="time_color">{{$store['schedule_memo']}}</p>
    </div>
    @endif

    <!-- カレンダー -->
    <div class="calendar w_90m">
        <div class="calendarwrap">
            <div class="calendar_container">
                <table class="calendar_table" summary="デリバリーカレンダー">
                    <tbody>
                        <thead>
                            <tr>
                                @foreach($week_schedules as $key => $week_schedule)
                                    <td aria-label="" class="calendar_daywrap">
                                        <p>{{$week_schedule['w']}}</p>
                                    </td>
                                @endforeach
                            </tr>
                        </thead>
                        <tr>
                        @foreach($week_schedules as $key => $week_schedule)
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

    <!-- お知らせ -->
    @if(isset($news[0]))
    <div class="store_news">
        <p class="bold">お知らせ</p>
        @foreach($news as $key => $custom)
            @if($custom->type == 1 && $custom->s_id == $store->id)
            <div class="w_76">
                <a  href="{{$custom->url}}">
                    <div class="flexs " style="border-bottom:1px solid #ccc;">
                        <span>{{$news[$key]['updated_date']}}</span>
                        <p class="s_news">{{$custom->title}}</p>
                    </div>
                </a>
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <!-- よみもの-->
    @if(isset($reading_material[0]))
    <section class="favoshop" style="background: #fce2bb; margin:1em 0 2.5em;">
        <li>
            <div class="reading_material">
                <div class="content1_2">
                    <p style="font-size:0.835em;"class="left">よみもの</p>
                </div>
            </div>
        </li>
        <ul class="horizontal-list">
        @foreach($reading_material as $key => $custom)
            <li class="item textoverflow">
                <div class="favorite">
                    <a class="item_img" href="{{$custom->url}}">
                    <p><img style="height:auto;"src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}"></p>
                    </a>
                </div>
                <div class="custom_tilte">
                    <p style="font-size:11px;">{{$custom->title}}</p>
                </div>
            </li>
        @endforeach
        </ul>
    </section>
    @endif

</section>
<!-- <div class='tabs'> -->
    <div class="tab-buttons w_76">
        <span class="content1"><p id="info">店舗情報</p></span>
        <span class="content2"><p id="menu">商品</p></span>
        <div id="lamp" class="content1"></div>
    </div>
    <!-- 店舗詳細 -->
    <div class="tab-content">
        <div class="content1">
            <section class="w_90">
                <table class="store_table">
                    <div class="speach">
                        <div class="itembox">
                            @if(isset($store->catch_copy) && $store->catch_copy != '')
                            <div class="say">
                                <h2>{{$store->catch_copy}}</h2>
                            </div>
                            @endif
                            <div class="balloons m_top3">
                                <div class="faceicon">
                                    <!-- <img src="/storage/staff_image/{{isset($store->id)? $store->id : '0'}}.jpg"> -->
                                    <img src="{{$store->staff_img}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2>店舗詳細</h2>
                    <tbody>
                        <tr>
                            <th><i class="bi bi-clock"></i></th>
                            <td>
                                <div class="weekcalenders">
                                    <p>宅配対応時間</p>
                                    <span>{{$store->schedule_memo}}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-telephone-fill"></i></th>
                            <td>{{$store->tel}}<br>※ご注文方法に関するお問い合わせは (有)もしもし 042-337-1888 へお願いいたします</td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-geo-alt-fill"></i></th>
                            <td><a href="{{url($store->url)}}" target="_blank">{{$store->address}}</a><br>
                            {{$store->access}}
                            </td>
                        </tr>
                        <tr>
                        <th><i class="bi bi-globe"></i></th>
                            <td style="word-break: break-all;">ホームページ<br><a href="{{url($store->url)}}" target="_blank" style="text-decoration: underline">{{$store->url}}</a></td>
                        </tr>
                        <tr>
                            <th><i class="bi bi-credit-card"></i></th>
                            <td>お支払方法<br>クレジットカードでのオンライン決済のみ。<br>対応しているカードは次の通りです。<br>
                                Visa、Mastercard、American Express、JCB、Diners Club、Discover Card<br>電子マネー「au PAY」にも対応しております。</td>
                        </tr>
                        <tr>
                    </tbody>
                </table>
                @if(empty($store->note))
                @else
                <div class="store_message">
                    <h2>メッセージ</h2>
                    <div class="store_message_inner">
                        <div class="store_message_img">
                            <img src="/storage/staff_image/{{isset($store->id)? $store->id : '0'}}.jpg">
                        </div>
                        <div class="store_message_text">
                            <p>{{$store->note}}</p>
                        </div>
                    </div>
                </div>
                @endif
                <div class="moshideli_btm">
                </div>
            </section>
        </div>
    <!-- </div> -->
    <!-- 商品 -->
    <!-- <div class="tab-content"> -->
        <div class="content2 none">
            <section class="store_tabs active" id="service">
                @foreach($store_categories as $store_categorie)
                @if(isset($products[$store_categorie->no]))
                    <div class="favoshop" id="items_column" style="text-align:left;">
                        <li>
                            <div class="menu_flex">
                                @if(isset($products[$store_categorie->no]) != '')
                                 <p>{{$store_categorie->title}}</p>
                                @endif
                            </div>
                        </li>
                        <ul class="horizontal-list">
                            @foreach($products[$store_categorie->no] as $product)
                            <li class="item textoverflow" >
                                <a class="item_img" href="/product_detail/{{$product->id}}">
                                    <div class="favorite">
                                        <img style="height:auto;"  src="/storage/product_image/{{$product->id}}.{{$product->extension}}">
                                    </div>
                                    <div class="menus">
                                        <div class="menus_big">{{$product->name}}</div>
                                        <div class="menus_small">{{number_format($product->price)}}円</div>
                                    </div>
                                    <input class="s_id" type="hidden" name="s_id" value="{{$product->s_id}}">
                                    <input class="p_id" type="hidden" name="p_id" value="{{$product->id}}">
                                    <input class="quantity" type="hidden" pattern="^[0-9]+$" name="quantity" value="1" min="1">
                                    <div class="w_40 m_left">
                                    </div> 
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @endforeach
            </section>
        </div>
    </div>

<!-- </div> -->

<script src="https://js.stripe.com/v3/"></script>
<script src="js/payment.js"></script>
<!-- カード情報登録の為のjs -->
<script>
        // SNSモーダル
        {
  const open_sns = document.getElementById('open_sns');
  const close_sns = document.getElementById('close_sns');
  const modal_sns = document.getElementById('modal_sns');
  const mask_sns = document.getElementById('mask_sns');

  open_sns.addEventListener('click', () => {
    modal_sns.classList.remove('hiddens');
    mask_sns.classList.remove('hiddens');
  });

  close_sns.addEventListener('click', () => {
    modal_sns.classList.add('hiddens');
    mask_sns.classList.add('hiddens');
  });

  mask_sns.addEventListener('click', () => {
    close_sns.click();
  });
}
    // 詳細・商品　のタブメニュー部分
    $(document).ready(function(){
            $('none').addClass('none');
        });
    // $('.tab-content>div').hide();
    $('.tab-content>div').first();
    $('.tab-buttons span').click(function () {
        var thisclass = $(this).attr('class');
        $('#lamp').removeClass().addClass('#lamp').addClass(thisclass);
        if(thisclass == 'content2'){
            $('none').removeClass('none');
        }else{
            $('none').addClass('none');
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

// タブメニュー　切り替え
document.getElementById('info').addEventListener('click',function(){
  document.getElementById('info').style.color = '#da551a';
  document.getElementById('info').style.background = 'white';
  document.getElementById('info').style.border = 'thin solid #f4a125';
  document.getElementById('menu').style.background = '#f4a125';
  document.getElementById('menu').style.color = 'white';
  document.getElementById('menu').style.borderRadius = '50px';
  document.getElementById('menu').style.border = 'none';
})
document.getElementById('menu').addEventListener('click',function(){
  document.getElementById('menu').style.color = '#da551a';
  document.getElementById('menu').style.background = 'white';
  document.getElementById('menu').style.border = 'thin solid #f4a125';
  document.getElementById('menu').style.borderRadius = '50px';
  document.getElementById('info').style.color = 'white';
  document.getElementById('info').style.background = '#f4a125';
  document.getElementById('info').style.borderRadius = '50px';
  document.getElementById('info').style.border = 'none';
})


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