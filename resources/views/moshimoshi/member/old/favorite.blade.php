@include('public/header')

<style>
    .bx-wrapper .bx-pager {
        bottom:10px;
    }
</style>

<div class="product_baskets">
    <div class="flex">
        <a href="/mypage">
        <div><span style="font-size:1.2em; padding-top:0.3em" class="left material-symbols-outlined">arrow_circle_left</span></div>
        </a>
        <div class="bold">お気に入り</div>
        <div></diV>
    </div>
</div>


<div class='tabs'>
    <div class='tab-content'>
        <div class='content1'>
            <div class="searchcontent actives" id="abouts">
                <div class="products">
                    <ul>
                        @if($stores != [])
                        @foreach($stores as $s_id => $store)
                        <li>
                            <div class="surround">
                                <div class="allshop">
                                    <a href="/shop/{{$s_id}}">
                                        <div class="balloon w_90">
                                            <div class="faceicon">
                                                <img src="/storage/staff_image/{{$s_id}}.jpg">
                                            </div>
                                            <div class="says">
                                                <p class="">{{$store['s_catch_copy']}}</p>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="slider_width w_90">
                                        <div class="slider">
                                            @for($i=0; $i < 3; $i++)
                                                <div class="favorite">
                                                    <a class="item_img" href="/shop/{{$s_id}}">
                                                        <img src="/storage/store_image/{{$s_id}}-{{$i}}.jpg">
                                                    </a>
                                                    @if(Session::has('u_id'))
                                                    <a class="" href="/update_favorite/{{$s_id}}" onclick="return favo_remove();">
                                                        <div class="big_favo_icon">
                                                            <img style="" src ="{{url('img/favorite_hover.png')}}">
                                                        </div>
                                                    </a>
                                                    @endif
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="store_box w_90m">
                                        <div class="store_name">
                                            <div class="flexspace">
                                                <div class="p_r5">
                                                    <a href="/shop/{{$s_id}}">
                                                        <h2 class="bold">{{$store['s_name']}}</h2>
                                                    </a>
                                                </div>
                                                @if(Session::has('u_id'))
                                                    @if($store['coupon_flag'] == 1)
                                                    <a class="t_c" href="/shop_coupon/{{$s_id}}">
                                                        <img src ="{{url('img/coupon.png')}}">
                                                    </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- スケジュールメモ -->
                                    @if($store['s_schedule_memo'] != '')
                                    <div class="w_90">
                                        <p class="time_color">{{$store['s_schedule_memo']}}</p>
                                    </div>
                                    @endif
                                    <div class="calendar w_90m">
                                        <div class="calendarwrap">
                                            <div class="calendar_container">
                                                <table class="calendar_table" summary="デリバリーカレンダー">
                                                    <tbody>
                                                        <thead>
                                                            <tr>
                                                                @foreach($store['week_schedules'] as $key => $week_schedule)
                                                                    <td aria-label="" class="calendar_daywrap">
                                                                        <p>{{$week_schedule['w']}}</p>
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tr>
                                                        @foreach($store['week_schedules'] as $key => $week_schedule)
                                                            <td class="calendar_cell">
                                                                @if($week_schedule['mark'] == '-')
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
                        @else
                        <div class="flexbox margin10">お気に入り登録されている店舗はまだありません。</div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
        $('.slider').bxSlider({
            auto: false,
            touchEnabled: false
        });
    });
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