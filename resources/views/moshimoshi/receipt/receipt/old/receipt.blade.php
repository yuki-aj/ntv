@include('public/header')
<style>
    #menuopen img {
        display: none;
    }
</style>
<section class="w_80 receiptbox" style="padding:3em 0;">
    <div class="t_center">
        <p class="bold">領収書</p>
    </div>
    <div style="text-align:right;">
        @if($order->c_flag == 01)
        <p>{{$created_at}}</p>
        @else
        <p>{{$receipt_time}}</p>
        @endif

    </div>
    <!-- 注文者の名前 -->
    @if($order->corporation_flag != 2)
    <p class="m_btm_1em"><span style="border-bottom: 1px solid #333;padding: 3px;">{{$order->u_name}}　様</span></p>
    @else
    <p class="m_btm_1em"><span style="border-bottom: 1px solid #333;padding: 3px;">{{$order->u_name}}　御中</span></p>
    @endif
    <!-- 法人の場合 注文者の住所記載-->
    <div class="m_btm" style="font-size:0.8em;">
        @if($order->corporation_flag == 2)
        <p>〒{{$zipcode}} {{$order->o_address}}</p>
        @endif
    </div>
    <p class="m_btm_1em" style="border-top: 1px solid #333;border-bottom: 1px solid #333;padding: 3px;"><span style="padding-left:1.0em;">但　『もしもしデリバリー』ご利用代金として</span></p>
    <div class="flex">
        <p>注文日時</p>
        <p>{{$created_date}}</p>
    </div>
    <div class="flex">
        <p>配送日時</p>
        <p>{{$completion_time}}</p>
    </div>
    <div class="flex m_btm_1em">
        <p>決済方法</p>
        <p>
            @if($order->c_flag == 01)
                クレジットカード
            @elseif($order->c_flag == 10 || $order->c_flag == 11)
                au PAY
            @elseif($order->c_flag == 20 || $order->c_flag == 21)
                代金引換
            @endif
        </p>
    </div>

    <?php $sum = 0;?>
    @foreach($order_details as $key => $order_detail)
        @if($order_detail->product_id == 'P' || $order_detail->product_id == 'A'|| $order_detail->product_id == 'S')
        <div class="flex">
            <p>クーポン利用</p>
            <p>{{number_format($order_detail->price)}}円</p>
            <?php $sum = $sum + $order_detail->price;?>
        </div>
        @elseif($order_detail->product_id == 'D')
        <div class="flex">
            <p>代引手数料</p>
            <p>{{number_format($order_detail->price)}}円</p>
            <?php $sum = $sum + $order_detail->price;?>
        </div>
        @endif
        @if($order_detail->product_id == 16)
        <div class="flex">
            <p>送料</p>
            <p>{{number_format($order_detail->price)}}円</p>
            <?php $sum = $sum + $order_detail->price;?>

        </div>
        @endif
        @if($order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D' &&  $order_detail->product_id != '16')
        <div class="flex">
            <p>注文総額</p>
            <p>{{number_format($order_detail->price)}}円</p>
            <?php $sum = $sum + $order_detail->price;?>
        </div>
        @endif
    @endforeach
    <div class="flex ">
        <p>合計金額</p>
        <p>{{number_format($sum)}}円</p>
    </div>
    <!-- フッター -->
    <div class="m_top5 flex" style="text-align:right;">
        <div class="t_center" style="width:20%;" >
            <!-- <img style="width:100%;" src ="{{url('img/favicon.png')}}"> -->
            <img style="width:150%;" src ="{{url('img/logo.jpg')}}">
        </div>
        <div style="font-size:0.8em;">
            <p>有限会社もしもし</p>
            <p>〒206-0033</p>
            <p>多摩市落合2-38</p>
            <p>D'グラフォート多摩センター煉瓦坂103号</p>
            <p>TEL.042-337-1888　FAX.042-337-1885</p>
            <p>moshideli@e-mosimosi.com</p>
        </div>
    </div>
</section>

</body>
</html>