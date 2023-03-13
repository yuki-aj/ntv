@include('manage/header')

<div class="width_fixed">
    <div class="header_img">
        <a href="/">
            <img src="{{url('img/headerlogo.png')}}">
        </a>
    </div>
    <div class="m_top5 w_80">
        @if($order->order_flag == 1)
            <!-- <h1>注文受付は完了していません。</h1> -->
            <p class="bold m_top5">「注文確定」に変更するには、ステータスを変更してください。</p>
        @elseif($order->order_flag == 2)
            <h1>注文受付が完了しました。</h1>
            <p class="bold m_top5">お時間になりましたら、配達員に商品をお渡しください。</p>
        @endif
    </div>

    <div class="">
        <form class="w_60 t_center order_flag" action="/receive_shop" method="POST">
            @csrf
            <select id="order_flag" class="order_select" name="order_flag">
                <option value="1" {{$order->order_flag == 1 ? 'selected': ''}}>注文受付中</option>
                <option value="2" {{$order->order_flag == 2 ? 'selected': ''}}>注文確定</option>
            </select>
            <input type="hidden" name="id" value="{{$order->id}}">
            <input type="hidden" id="flag" name="flag" value="{{$order->order_flag}}">                   
            <input type="submit" value="変更する" class="addition" onclick="return status_change();">
        </form>
    </div>

<script>
    function status_change(){
        var result = window.confirm('ステータスを変更しますか？');
        if(result == false){
            return false;
        }
        var order_flag = document.getElementById('order_flag').value;
        const flag = document.getElementById("flag").value;
        if(order_flag < flag){
            alert('注文確定後、注文受付中に戻すことはできません。');
            return false;
        }
    };
    
</script>

@include('public/footer')