@if($order->order_flag == 5)
まだ配送は完了していません。
配送完了に変更するには、再度リンクをクリックしてください。
@elseif($order->order_flag == 6)
配送が完了しました。
配送をお手伝いいただき、ありがとうございます。
@endif