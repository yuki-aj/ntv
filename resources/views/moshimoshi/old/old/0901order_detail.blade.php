@include('public/header')
<h1 class="baskets">注文詳細</h1>
<div class="admin_page">
    <div class="addition gap m_t3 m_btm">
        <a href="/order_search/">
            <p>注文管理へ</p>
        </a>
    </div>
</div>
<!-- PC -->
<div class="flexbox" id="big_layout">
  <div>
    <!-- <h1 style="text-align: center; margin:20px 0 0; color:#000;">注文詳細</h1> -->
    <table class="order_table order_table" border="2">
        <tr>
            <th>注文id</th>
            <!-- <th>ユーザーid</th> -->
            <th>注文者名</th>
            <th>注文日時</th>
            <th>配達日</th>
            <th>配達時間</th>
            <th>受取時間</th>
            <th>注文状況</th>
            <th>配達先</th>
            <th>電話</th>
            <th>種別</th>
            <th>注文状況</th>
            <th>配達者設定</th>
        </tr>
        <tr>
            <td><a href="https://dashboard.stripe.com/test/payments/{{$order->o_id}}">...{{$order->new_o_id}}</a></td>
            <!-- <td>{{$order->u_id}}</td> -->
            <td>{{$order->u_name}}</td>
            <td>{{$order->created_at}}</td>
            <td>{{$order->delivery_date}}</td>
            <td>{{$order->delivery_time}}</td>
            <td>{{$order->catch_time}}</td>
            <td>{{$order->detail}}</td>
            <td>{{$order->d_address}}</td>
            <td>{{$order->d_tel}}</td>
            <td>{{$order->kind}}</td>
            <td>
                <form class="order_flag" action="/order_detail" method="POST">
                    @csrf
                    <select name="order_flag" style="margin-bottom:10px;">
                        <option value="0" disabled>注文状況</option>
                        <option value="1">注文完了</option>
                        <option value="2">注文確定</option>
                        <option value="3">配達員選定</option>
                        <option value="4">配達員決定</option>
                        <option value="5">受取完了</option>
                        <option value="6">配達完了</option>
                        <option value="7">キャンセル</option>
                    </select><br>
                    <input type="hidden" name="o_id" value="{{$order->o_id}}">
                    <input type="submit" value="変更する" class="">
                </form>
            </td>
            <td style="text-align:center;">
                <a href="/staff_list/{{$order->id}}" class="" target="_blank" style="border:1px solid #ccc;padding:5px;background-color:orange;color:#fff;">選択</a>
            </td>
        </tr>
    </table>
    @foreach($order_details as $key => $order_detail)
        @if($order_details[$key] == $order_details[0])
        <table class="order_table" border="2">
            <tr>
                <th>店舗名</th>
                <th>店舗住所</th>
                <th>店舗電話番号</th>
            </tr>
            <tr>
                <td>{{$order_detail->s_name}}</td>
                <td>{{$order_detail->address}}</td>
                <td>{{$order_detail->tel}}</td>
            </tr>
        </table>
        <table class="order_table" border="2">
            <tr>
                <th>商品名</th>
                <th>オプション1</th>
                <th>オプション2</th>
                <th>オプション3</th>
                <th>個数</th>
                <th>合計料金</th>
                <th>注文状態</th>
            </tr>
            <tr>
                <td>{{$order_detail->p_name}}</td>
                @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                <td>{{$order_detail->o_1_name}}{{$order_detail->o_1_note}} {{$order_detail->o_1_price}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                <td>{{$order_detail->o_2_name}}{{$order_detail->o_2_note}} {{$order_detail->o_2_price}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                <td>{{$order_detail->o_3_name}}{{$order_detail->o_3_note}} {{$order_detail->o_3_price}}円</td>
                @else
                <td></td>
                @endif
                <td>{{$order_detail->quantity}}個</td>
                <td>{{$order_detail->price}}円</td>
                <td>{{$order_detail->detail}}</td>
            </tr>
        @else
        @if($order_detail->s_id == 0)
            <table class="order_table" border="2">
                <tr>
                    <th>送料</th>
                    <th>総額</th>
                    <th>注文メモ</th>
                </tr>
                <tr>@if($order_detail->price == 0.2)
                    <td>20%</td>
                    @else
                    <td>{{$order_detail->price}}円</td>
                    @endif
                    <td>{{round($total)}}円</td>
                    @if($order->note != '')
                    <td>{{$order->note}}円</td>
                    @endif
                </tr>
            </table>
            @else
                <tr>
                    <td>{{$order_detail->p_name}}</td>
                    @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                    <td>{{$order_detail->o_1_note}} {{$order_detail->o_1_name}} {{$order_detail->o_1_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                    <td>{{$order_detail->o_2_note}} {{$order_detail->o_2_name}} {{$order_detail->o_2_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                    <td>{{$order_detail->o_3_note}} {{$order_detail->o_3_name}} {{$order_detail->o_3_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    <td>{{$order_detail->quantity}}個</td>
                    <td>{{$order_detail->price}}円</td>
                    <td>{{$order_detail->detail}}</td>
                </tr>
            </table>
            @endif
        @endif
    @endforeach
  </div>
</div>


<!--スマホ -->
<div class="flexbox m_btm" id="small_layout">
  <div>
    <!-- <h1 style="text-align: center; margin:20px 0 0; color:#000;">注文詳細</h1> -->
    <table class="order_table" border="2">
        <tr>
            <th>注文id</th>
        </tr>
        <tr>
            <td>
                <a href="https://dashboard.stripe.com/test/payments/{{$order->o_id}}">...{{$order->new_o_id}}</a>
            </td>
        </tr>
        <tr>
            <th>注文者名</th>
        </tr>
        <tr>
            <td>{{$order->u_name}}</td>
        </tr>
        <tr>
            <th>注文日時</th>
        </tr>
        <tr>
            <td>{{$order->created_at}}</td>
        </tr>
        <tr>
            <th>配達日</th>
        </tr>
        <tr>
            <td>{{$order->delivery_date}}</td>
        </tr>
        <tr>
            <th>配達時間</th>
        </tr>
        <tr>
            <td>{{$order->delivery_time}}</td>
        </tr>
        <tr>
            <th>受取時間</th>
        </tr>
        <tr>
            <td>{{$order->catch_time}}</td>
        </tr>
        <tr>
            <th>注文状況</th>
        </tr>
        <tr>
            <td>{{$order->detail}}</td>
        </tr>
        <tr>
            <th>配達先</th>
        </tr>
        <tr>
            <td>{{$order->d_address}}</td>
        </tr>
        <tr>
            <th>電話</th>
        </tr>
        <tr>
            <td>{{$order->d_tel}}</td>
        </tr>
        <tr>
            <th>種別</th>
        </tr>
        <tr>
            <td>{{$order->kind}}</td>
        </tr>
        <tr>
            <th>注文状況</th>
        </tr>
        <tr>
            <td> 
                <form class="order_flag" action="/order_detail" method="POST">
                    @csrf
                    <select name="order_flag" style="margin-bottom:10px;">
                        <option value="0" disabled>注文状況</option>
                        <option value="1">注文完了</option>
                        <option value="2">注文確定</option>
                        <option value="3">配達員選定</option>
                        <option value="4">配達員決定</option>
                        <option value="5">受取完了</option>
                        <option value="6">配達完了</option>
                        <option value="7">キャンセル</option>
                    </select><br>
                    <input type="hidden" name="o_id" value="{{$order->o_id}}">
                    <input type="submit" value="変更する" class="">
                </form>
            </td>
        </tr>
        <tr>
            <th>配達者設定</th>
        </tr>
        <tr>
            <td style="text-align:center;">
                <a href="/staff_list/{{$order->id}}" class="" target="_blank" style="border:1px solid #ccc;padding:5px;background-color:orange;color:#fff;">選択</a>
            </td>
        </tr>
    </table>
    @foreach($order_details as $key => $order_detail)
        @if($order_details[$key] == $order_details[0])
        <table class="order_table m_top3" border="2">
            <tr>
                <th>店舗名</th>
                <th>店舗住所</th>
                <th>店舗電話番号</th>
            </tr>
            <tr>
                <td>{{$order_detail->s_name}}</td>
                <td>{{$order_detail->address}}</td>
                <td>{{$order_detail->tel}}</td>
            </tr>
        </table>
        <table class="order_table" border="2">
            <tr>
                <th>商品名</th>
                <th>オプション1</th>
                <th>オプション2</th>
                <th>オプション3</th>
                <th>個数</th>
                <th>合計料金</th>
                <th>注文状態</th>
            </tr>
            <tr>
                <td>{{$order_detail->p_name}}</td>
                @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                <td>{{$order_detail->o_1_name}}{{$order_detail->o_1_note}} {{$order_detail->o_1_price}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                <td>{{$order_detail->o_2_name}}{{$order_detail->o_2_note}} {{$order_detail->o_2_price}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                <td>{{$order_detail->o_3_name}}{{$order_detail->o_3_note}} {{$order_detail->o_3_price}}円</td>
                @else
                <td></td>
                @endif
                <td>{{$order_detail->quantity}}個</td>
                <td>{{$order_detail->price}}円</td>
                <td>{{$order_detail->detail}}</td>
            </tr>
        @else
        @if($order_detail->s_id == 0)
            <table class="order_table" border="2">
                <tr>
                    <th>送料</th>
                    <th>総額</th>
                </tr>
                <tr>@if($order_detail->price == 0.2)
                    <td>20%</td>
                    @else
                    <td>{{$order_detail->price}}円</td>
                    @endif
                    <td>{{round($total)}}円</td>
                </tr>
            </table>
            @if($order->note != '')
            <table class="order_table" border="2">
                <tr>
                    <th>注文メモ</th>
                </tr>
                <tr>
                    <td>{{$order->note}}円</td>
                </tr>
            </table>
            @endif
            @else
                <tr>
                    <td>{{$order_detail->p_name}}</td>
                    @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                    <td>{{$order_detail->o_1_note}} {{$order_detail->o_1_name}} {{$order_detail->o_1_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                    <td>{{$order_detail->o_2_note}} {{$order_detail->o_2_name}} {{$order_detail->o_2_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                    <td>{{$order_detail->o_3_note}} {{$order_detail->o_3_name}} {{$order_detail->o_3_price}}円</td>
                    @else
                    <td></td>
                    @endif
                    <td>{{$order_detail->quantity}}個</td>
                    <td>{{$order_detail->price}}円</td>
                    <td>{{$order_detail->detail}}</td>
                </tr>
            </table>
            @endif
        @endif
    @endforeach
  </div>
</div>

</body>
</html>