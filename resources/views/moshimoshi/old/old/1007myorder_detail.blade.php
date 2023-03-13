@include('public/header')
<div class="relative">
    <a href="/mypage">
        <button class="absolute btn-circle1"><</button>
    </a>
    <h1 class="baskets">注文履歴詳細</h1>
</div>
<section class="desc_w60">  
    <div class="p_3">
        <div class="">
            <div class="history p_3">
                <div class="flexbox space-between">
                    <p class="">注文id：</p>
                    <p class="details_right bold">{{$order->last_o_id}}</p>
                </div>
                <div class="details_flex">
                    <p class="">店名：</p>
                    <p class="details_right bold">{{$order->name}}</p>
                </div>
                <div class="details_flex">
                    <p class="">注文日時：</p>
                    <p class="details_right bold">{{$order->created_at}}</p>
                </div>
                <div class="details_flex">
                    <p class="">配達予定日：</p>
                    <p class="details_right bold">{{$order->delivery_date}}</p>
                </div>
                <div class="details_flex">
                    <p class="">配達予定時刻：</p>
                    <p class="details_right bold">{{$order->delivery_time}}</p>
                </div>
                @if($order->status_time[2] != '')
                <div class="details_flex">
                    <p class="">配達完了日時：</p>
                    <p class="details_right bold">{{$order->status_time[2]}}</p>
                </div>
                @endif
                <div class="details_flex">
                    <p class="">利用種別：</p>
                    <p class="details_right bold">{{$order->kind}}</p>
                </div>
                <div class="details_flex">
                    <p class="">支払い方法：</p>
                    @if($order->c_flag == 01)
                    <p class="details_right bold">クレジットカード</p>
                    @elseif($order->c_flag == 10 || $order->c_flag == 11)
                    <p class="details_right bold">aupay</p>
                    @else
                    <p class="details_right bold">代引</p>
                    @endif
                </div>
                <div class="details_flex">
                    <p class="">配達先：</p>
                    <p class="details_right bold">{{$order->d_address}}</p>
                </div>
                <?php $dis_price = 0; $s_id = ''; $p_id = ''; $s_d_price=0; ?><!-- discount -->
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
                        <h2 class="bold">{{$order_detail->p_name}}</h2>
                        <div class="m_tb3">
                            <div class="flexspace">
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
                                        <p class="">{{$order_detail->o_1_note}}：</p>
                                        <p class="">{{number_format($order_detail->o_1_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{$order_detail->o_2_note}}：</p>
                                        <p class="">{{number_format($order_detail->o_2_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{$order_detail->o_3_note}}：</p>
                                        <p class="">{{number_format($order_detail->o_3_price)}}円</p>
                                    </div>
                                    @endif
                                    @if(isset($order_detail->o_4_note) && isset($order_detail->o_4_name) && isset($order_detail->o_4_price))
                                    <div class="flexbox space-between">
                                        <p class="">{{$order_detail->o_4_note}}：</p>
                                        <p class="">{{number_format($order_detail->o_4_price)}}円</p>
                                    </div>
                                    @endif
                                    @if($coupon != '' && $p_id == $order_detail->product_id)
                                    <div class="flexbox space-between top-line">
                                        <p class="">割引額：</p>
                                        <p class="">{{number_format($dis_price)}}円</p>
                                    </div>
                                    @endif
                                    <div class="flexbox space-between top-line">
                                        <p class="">商品合計：</p>
                                        <p class="">{{number_format($order_detail->price)}}円</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <!--送料と代引の場合-->
                    @if($order_detail->product_id == 'S')
                    <!--送料クーポン-->
                        <?php $s_d_price = $order_detail->price; ?>
                    @elseif($order_detail->product_id == 'D')
                        <div class="details_flex">
                            <h2 class="">代引き手数料</h2>
                            <p class="details_right">{{number_format($order_detail->price)}}円</p>
                        </div>
                    @elseif($order_detail->s_id == 0 && $order_detail->product_id == '16')
                        <div class="details_flex">
                            <h2 class="">総数</h2>
                            <p class="details_right">{{$t_quantity}}点</p>
                        </div>
                        @if($s_d_price != 0)
                            <div class="details_flex">
                                <h2 class="">割引前送料</h2>
                                <p class="details_right">{{number_format($order_detail->price)}}円</p>
                            </div>
                            <div class="details_flex">
                                <h2 class="">{{$coupon->title}}</h2>
                                <p class="details_right">{{number_format($s_d_price)}}円</p>
                            </div>
                            <div class="details_flex">
                                <h2 class="">送料</h2>
                                <p class="details_right">{{number_format($order_detail->price + $s_d_price)}}円</p>
                            </div>
                        @else
                            <div class="details_flex">
                                <h2 class="">送料</h2>
                                <p class="details_right">{{number_format($order_detail->price + $s_d_price)}}円</p>
                            </div>
                        @endif
                        <div class="details_flex">
                            <h1 class="">店舗合計</h1>
                            <p class="details_right bold f_30">{{number_format($total)}}円</p>
                        </div>
                    @endif
                @endif
            @endforeach
            </div>
            @if($order->note != '')
            <div class="">
                <p class="">注文メモ：</p>
                <p class="details_right bold">{{$order->note}}</p>
            </div>
            @endif
        </div>
    </div>
</section>
@include('public/footer')