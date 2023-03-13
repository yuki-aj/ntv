@include('manage/header')

<style>
    table{
        padding: 10px;
        margin: 10px 0 !important;
    }
    td{
        padding: 10px;
    }
</style>

<?php $dis_price = 0; $s_id = ''; $p_id = ''; $s_d_price=0; ?><!-- discount -->
@foreach($details as $key => $detail)<!-- couponの値を代入 -->
    @if($detail->product_id == 'P')
        <?php $dis_price = $detail->price; $p_id = $coupon->p_id;?>
    @endif
@endforeach
<div style="margin-bottom:7.0em;">
    <div class="option w_90">
        @if(isset($msg) && $msg != '')
            <div class="m_top5 text-success margin10" style="padding:5px 10px;">{{$msg}}</div>
        @endif
      <table class="info-table1">
        <tr>
            <th colspan="4">配送日</th>
            <th colspan="4">引取時間</th>
            <th colspan="4">配送時間</th>
            <th colspan="4">支払方法</th>

        </tr>
        <tr>
            <td colspan="4">{{$order->delivery_date}}</td>
            <td colspan="4">{{$order->catch_time}}</td>
            <td colspan="4">{{$order->delivery_time}}</td>
                @if($order->c_flag == 01)
                <td colspan="4">カード</td>
                @elseif($order->c_flag == 10 || $order->c_flag == 11)
                    <td colspan="4">au PAY</td>
                @elseif($order->c_flag == 20 || $order->c_flag == 21)
                    <td colspan="4">代引</td>
                @endif
        </tr>
      </table>
      <table class="info-table2">
        <tr>
            <th colspan="4">ID</th>
            <th colspan="4">店舗名</th>
            <th colspan="4">店舗住所</th>
            <th colspan="4">店舗電話番号</th>
        </tr>
        <tr>
            <td colspan="4">...{{$order->new_o_id}}</td>
            <td colspan="4">{{$store->name}}</td>
            <td colspan="4">{{$store->address}}</td>
            <td colspan="4" style="word-break: break-all;">{{$store->tel}}</td>
        </tr>
      </table>
      <table class="info-table2">
        <tr>
          <td colspan="1" class="bold" style="background: rgb(240, 225, 200);">配送先</td>
          <td colspan="6" class="t_left"><span style="font-size:1.3em;">{{$order->d_name}}</span><br>{{$order->d_address}}<br><span style="color:blue;">{{$order->d_tel}}</span></td>
        </tr>
      </table>
        <table class="info-table2">
            <tr>
                <th colspan="5">注文内容</th>
            </tr>
            <?php $sum = 0;?>
            @foreach($details as $key => $detail)
            @if($order->o_id == $detail->order_id && $detail->product_id != 16 && $detail->product_id != 'P' && $detail->product_id != 'A' && $detail->product_id != 'S' && $detail->product_id != 'D')
            <tr>
                <td colspan="4"><div class="flex"><div>{{$detail['p_name']}}</div><div>　×{{$detail->quantity}}</div></div></td>
                <td colspan="1">{{number_format($detail['p_price'])}}円</td>
            </tr>
            <tr>
                <td colspan="1">
                @if(isset($detail->o_1_name) && $detail->o_1_name != '')
                    {{$detail->o_1_name}} {{$detail->o_1_note}} {{number_format($detail->o_1_price)}}円
                @else
                0円
                @endif
                </td>
                <td colspan="1">
                @if(isset($detail->o_2_name) && $detail->o_2_name != '')
                    {{$detail->o_2_name}} {{$detail->o_2_note}} {{number_format($detail->o_2_price)}}円
                @else
                0円
                @endif
                </td>
                <td colspan="1">
                @if(isset($detail->o_3_name) && $detail->o_3_name != '')
                {{$detail->o_3_name}} {{$detail->o_3_note}} {{number_format($detail->o_3_price)}}円
                @else
                0円
                @endif
                </td>
                <td colspan="1">
                @if(isset($detail->o_4_name) && $detail->o_4_name != '')  
                    {{$detail->o_4_name}} {{$detail->o_4_note}} {{number_format($detail->o_4_price)}}円
                @else
                0円
                @endif
                </td>
                <td colspan="1" style="color:blue;">
                {{number_format($detail->subtotal)}}円
                </td>
            </tr>
            @endif
            @endforeach
            <tr>
                <td colspan="1" style="background: rgb(240, 225, 200);">クーポン</td>
                <td colspan="3" class="">
                @if(isset($coupon) && $coupon != '')
                    {{$coupon->title}}
                @endif
                </td>
                <td colspan="1" style="color:red;">
                @if(isset($coupon) && $coupon != '')
                  {{$coupon->d_price}}円
                @else
                    0円
                @endif
                </td>
            </tr>
            <tr>
                <td colspan="1" style="background: rgb(240, 225, 200);">送料</td>
                <td colspan="3"></td>
                <td colspan="1" class="bold">
                    {{$detail->price + $s_d_price}}円
                    <?php $sum = $sum + $detail['postage'];?>
                </td>
            </tr>
            <tr>
                <td colspan="1" style="background: rgb(240, 225, 200);">合計</td>
                <td colspan="3"></td>
                <td colspan="1" class="bold">
                {{number_format($total)}}円
                </td>
            </tr>
            <tr>
                <td colspan="1" style="background: rgb(240, 225, 200);">配達員</td>
                <td colspan="4">
                {{$order->d_staff_id}}
                @if($order->d_staff_id == false)
                <a href="/staff_list/{{$order->id}}" class="" target="_blank" style="background:#f4a125; color:#fff; padding:0.3em; border:#ddd;">選択</a>
                @endif
                </td>
            </tr>
        </table>
        <table class="info-table2">
            <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">受取時刻</td>
            <td colspan="8">
            @if(isset($order->status_time[1]))
                {{$order->status_time[1]}}
            @endif            
            </td>
            </tr>
            <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">配送時刻</td>
            <td colspan="8">
            @if(isset($order->status_time[2]))
                {{$order->status_time[2]}}
            @endif            
            </td>
            </tr>
            <tr>
            <th colspan="6">配送管理</th>
            <th colspan="6">配送状況</th>
            </tr>
            <tr>
            @if($order->order_flag == 6)
            <td colspan="6" class="bold" style="color:red;">
                {{$order->detail}}
            </td>
            @else
            <td colspan="6">
                {{$order->detail}}
            </td>
            @endif
            <td colspan="6">
                <form class="order_flag" action="/manage_products" method="POST">
                    @csrf
                    <select class="padding3" id="order_flag" name="order_flag">
                        <option class="padding3" value="4" {{$order->order_flag == 4 ? 'selected': ''}}>---</option>
                        <option class="padding3" value="5" {{$order->order_flag == 5 ? 'selected': ''}}>受取完了</option>
                        <option class="padding3" value="6" {{$order->order_flag == 6 ? 'selected': ''}}>配送完了</option>
                    </select>
                    <input type="hidden" name="o_id" value="{{$order->o_id}}">
                    <input type="hidden" id="flag" name="flag" value="{{$order->order_flag}}">                   
                    <input onclick="return check();" type="submit" value="変更する" class="" onclick="return status_change();">
                </form>
            </td>
            </tr>
        </table>  
    </div>
</div>

<script>
    function status_change(){
        var result = window.confirm('ステータスを変更しますか？');
        if(result == false){
            return false;
        }
    };

    function check(){
    var order_flag = document.getElementById('order_flag').value;
    const flag = document.getElementById("flag").value;
        if(order_flag < flag){
            alert('正しい注文状況を選んで下さい');
            return false;
        }
        
    }
</script>

</body>
</html>