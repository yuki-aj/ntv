{{$user->name}} 様

『もしもしデリバリー』をご注文いただき、ありがとうございます。
配送が完了いたしました。

[ご注文内容確認]
店舗名:{{$s_name}}
注文ID:{{$order->o_id}}
注文者名:{{$order->u_name}} 様
ご注文日時:{{$order->created_at}}
配送予定日:{{$order->delivery_date}}
配送予定時刻:{{$order->delivery_time}}
@if($order->corporation_flag == 1)
利用種別:個人
@elseif($order->corporation_flag == 2)
利用種別:法人
@endif
支払い方法:{{$order->p_kind}}
配送先名:{{$order->d_name}} 様
配送先住所:{{$order->d_address}}
<?php $dis_price = 0; $s_id = ''; $p_id = ''; $s_d_price=0; ?>
@foreach($order_details as $key => $order_detail)
@if($order_detail->product_id == 'P')
<?php $dis_price = $order_detail->price; $p_id = $coupon->p_id;?>
@endif
@endforeach

[注文内容]
@foreach($order_details as $key => $order_detail)
@if($order_detail->product_id == 'P' || $order_detail->product_id == 'A')
使用クーポン:{{$coupon->title}}
割引額:{{$order_detail->price}}円
<?php $dis_price = $order_detail->price; $s_id = $coupon->id; ?>
@elseif($order_detail->s_id != 0 && $order_detail->product_id != 'P')
商品名:{{$order_detail->p_name}}
商品単価:{{$order_detail->p_price}}円
数量:{{$order_detail->quantity}}点
@if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
{{$order_detail->o_1_name}}:{{$order_detail->o_1_note}}:{{$order_detail->o_1_price}}円
@endif
@if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
{{$order_detail->o_2_name}}:{{$order_detail->o_2_note}}:{{$order_detail->o_2_price}}円
@endif
@if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
{{$order_detail->o_3_name}}:{{$order_detail->o_3_note}}:{{$order_detail->o_3_price}}円
@endif
@if(isset($order_detail->o_4_note) && isset($order_detail->o_4_name) && isset($order_detail->o_4_price))
{{$order_detail->o_4_name}}:{{$order_detail->o_4_note}}:{{$order_detail->o_4_price}}円
@endif
@if($coupon != '' && $p_id == $order_detail->product_id)
商品割引額:{{$dis_price}}円
@endif
商品合計:{{$order_detail->price}}円
@else
@if($order_detail->product_id == 'S')
<?php $s_d_price = $order_detail->price; ?>
@elseif($order_detail->product_id == 'D')
代引き手数料:{{$order_detail->price}}円
@elseif($order_detail->s_id == 0 && $order_detail->product_id == '16')
総数:{{$t_quantity}}点
@if($s_d_price != 0)
割引前送料:{{$order_detail->price}}円
{{$coupon->title}}:{{$s_d_price}}円
送料:{{$order_detail->price + $s_d_price}}円
@else
送料:{{$order_detail->price + $s_d_price}}円
@endif
店舗合計:{{$total}}円
@endif
@endif
@endforeach
@if($order->note != '')
注文メモ:【カトラリー】: {{$order->note[0]}}
@if(isset($order->note[1]))
【ギフトメッセージ】: {{$order->note[1]}}
@endif
@if(isset($order->note[2]))
【備考欄】: {{$order->note[2]}}
@endif
@endif

※マイページの「ご注文履歴」からもご確認いただけます。
※注文に関するお問い合わせ、注文にお心当たりがない場合は、下記までご連絡くださいますようお願いいたします。

━━━━━━＊
お食事宅配代行サービス『もしもしデリバリー』
有限会社もしもし
TEL：042-337-1888
https://mosideli.com/
お問い合わせ・ご利用ガイド・FAQはこちら
https://mosideli-plus.com/user_guide-pl