@include('public/header')
<style>
    .moshideli_btm {
        margin-bottom:10.0em;
    }
</style>

<div class="relative">
        <div class="product_baskets">
            <div class="flex">
                <a href="/myorder/{{$order->u_id}}">
                    <div><span class="left material-symbols-outlined" style="font-size:1.2em; padding-top:0.3em">arrow_circle_left</span></div>
                </a>
                <div class="bold">注文履歴詳細</div>
                <div></diV>
            </div>
        </div>
</div>

@if($order->order_flag == 7)
<p class="m_top3 t_center bold" style="color:red;">※この注文はキャンセルとなりました。</p>
@endif

<section class="moshideli_btm">
    <div class="w_90">
        <div class="history p_3">
            <div class="details_flex">
                <p class="">注文ID: </p>
                <p class="details_right">{{$order->last_o_id}}</p>
            </div>
            <div class="details_flex">
                <p class="">店名：</p>
                <p class="details_right">{{$order->name}}</p>
            </div>
            <div class="details_flex">
                <p class="">注文日時：</p>
                <p class="details_right">{{$order->date_created}}</p>
            </div>
            <div class="details_flex">
                <p class="">配送予定日：</p>
                <p class="details_right">{{$order->delivery_date}}</p>
            </div>
            <div class="details_flex">
                <p class="">配送予定時刻：</p>
                <p class="details_right">{{$order->delivery_time}}</p>
            </div>
            @if($order->date_status_time != '')
                <div class="details_flex">
                    <p class="">配送完了日時：</p>
                    <p class="details_right">{{$order->date_status_time}}</p>
                </div>
            @endif
                <div class="details_flex">
                    <p class="">利用種別：</p>
                    <p class="details_right">{{$order->kind}}</p>
                </div>
                <div class="details_flex">
                    <p class="">支払い方法：</p>
                    @if($order->c_flag == 01)
                    <p class="details_right">クレジットカード</p>
                    @elseif($order->c_flag == 10 || $order->c_flag == 11)
                    <p class="details_right">au PAY</p>
                    @else
                    <p class="details_right">代引</p>
                    @endif
                </div>
                <div class="details_flex">
                    <p class="">配送先：</p>
                    <p class="details_right">{{$order->d_address}}</p>
                </div>
            <?php $dis_price = 0; $s_id = ''; $p_id = ''; $s_d_price=0; $coupon_used_flag = false ;?><!-- discount -->
            @foreach($order_details as $key => $order_detail)<!-- couponの値を代入 -->
                @if($order_detail->product_id == 'P')
                    <?php $dis_price = $order_detail->price; $p_id = $coupon->p_id;?>
                @endif
            @endforeach
            @foreach($order_details as $key => $order_detail)
                <div class="orederproduct" style="border-bottom:none;">
                    @if($order_detail->product_id == 'P' || $order_detail->product_id == 'A')
                        <!-- 商品か総額のクーポンの場合-->
                        <div class="details_flex">
                            <h2 class="">{{$coupon->title}}</h2>
                            <p class="details_right">{{number_format($order_detail->price)}}円</p>
                            <?php $dis_price = $order_detail->price; $s_id = $coupon->id; ?>
                        </div>
                    @elseif($order_detail->s_id != 0 && $order_detail->product_id != 'P')
                        <!--商品の場合-->
                        <h2 class="m_top3">{{$order_detail->p_name}}</h2>
                            <div style="margin:0;" class="flexspace">
                                <img src="{{url($order_detail->img)}}">
                                <div class="width50">
                                    <div class="flexbox space-between">
                                        <p class="">数量：</p>
                                        <p class="">{{$order_detail->quantity}}点</p>
                                    </div>
                                    <div class="flexbox space-between">
                                        <p class="">単価：</p>
                                        <p class="">{{number_format($order_detail->d_price)}}円</p>
                                    </div>
                                    @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{mb_substr($order_detail->o_1_name,0,3)}} {{mb_substr($order_detail->o_1_note,0,3)}}：</p>
                                        <p class="">{{number_format($order_detail->o_1_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{mb_substr($order_detail->o_2_name,0,3)}} {{mb_substr($order_detail->o_2_note,0,3)}}：</p>
                                        <p class="">{{number_format($order_detail->o_2_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{mb_substr($order_detail->o_3_name,0,3)}} {{mb_substr($order_detail->o_3_note,0,3)}}：</p>
                                        <p class="">{{number_format($order_detail->o_3_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_4_note) && isset($order_detail->o_4_name) && isset($order_detail->o_4_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{mb_substr($order_detail->o_4_name,0,3)}} {{mb_substr($order_detail->o_4_note,0,3)}}：</p>
                                        <p class="">{{number_format($order_detail->o_4_price)}}円</p>
                                    </div>
                                    @endif

                                    @if($coupon != '' && $p_id == $order_detail->product_id && $coupon_used_flag == false)
                                    <!-- <div class="flexbox space-between top-line">
                                        <p class="">割引額：</p>
                                        <p class="">{{number_format($dis_price)}}円</p>
                                    </div> -->
                                    <?php $coupon_used_flag = true ;?>
                                    @endif
                                    <div class="flexbox space-between top-line">
                                        <p class="">商品合計：</p>
                                        <p class="">{{number_format($order_detail->price)}}円</p>
                                    </div>
                                </div>
                            </div>
                    @else
                    <!--送料と代引の場合-->
                        @if($order_detail->product_id == 'S')
                        <!--送料クーポン-->
                            <?php $s_d_price = $order_detail->price; ?>
                        @elseif($order_detail->product_id == 'D')
                            <div class="details_flex p_t1">
                                <p class="">代引手数料</p>
                                <p class="details_right">{{number_format($order_detail->price)}}円</p>
                            </div>
                        @elseif($order_detail->s_id == 0 && $order_detail->product_id == '16')
                            <div class="details_flex p_t1">
                                <p class="">総数</p>
                                <p class="details_right">{{$t_quantity}}点</p>
                            </div>
                            @if($s_d_price != 0)
                                <div class="details_flex p_t1">
                                    <p class="">割引前送料</p>
                                    <p class="details_right">{{number_format($order_detail->price)}}円</p>
                                </div>
                                <div class="details_flex p_t1">
                                    <p class="">{{$coupon->title}}</p>
                                    <p class="details_right">{{number_format($s_d_price)}}円</p>
                                </div>
                                <div class="details_flex p_t1">
                                    <p class="">送料</p>
                                    <p class="details_right">{{number_format($order_detail->price + $s_d_price)}}円</p>
                                </div>
                            @else
                                <div class="details_flex p_t1">
                                    <p class="">送料</p>
                                    <p class="details_right">{{number_format($order_detail->price + $s_d_price)}}円</p>
                                </div>
                            @endif
                            <div class="details_flex">
                                <p class="">店舗合計</p>
                                <p class="details_right"><span class="bold" style="font-size:1.5em;">{{number_format($total)}}</span>円</p>
                            </div>
                        @endif
                    @endif
                </div>
            @endforeach
            @if($order->note != '')
                <div class="moshideli" style="text-align:right;">
                    <p class="t_left">注文メモ：</p>
                    <p class="details_right" style="font-size:0.7em;">
                    @if(isset($order->note[0]) && $order->note[0] != '')
                        <div class="flex"><div>　カトラリー</div> <span>{{$order->note[0]}}</span></div>
                    @endif
                    @if(isset($order->note[1]) && $order->note[1] != '')
                    <div class="flex"><div>　ギフトメッセージ</div> <span>{{$order->note[1]}}</span></div>
                    @endif
                    @if(isset($order->note[2]) && $order->note[2] != '')
                    <div class="flex"><div>　備考欄</div> <span>{{$order->note[2]}}</span></div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>

@include('public/footer')