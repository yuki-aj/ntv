@include('manage/header')

<h1 class="baskets">注文詳細</h1>

<?php $dis_price = 0 ?>
<div class="admin_page">
    <div class="addition gap m_t3 m_btm" style="border-radius: 50px;">
        <a href="/shop_order_search/{{$store->id}}">
            <p>注文管理へ</p>
        </a>
    </div>
</div>

<!-- PC -->
<div class="flexbox" id="big_layout">
  <div>
    <?php $dis_price = 0; $s_id = ''; $p_id = ''; $s_d_price=0; ?><!-- discount -->
    @foreach($order_details as $key => $order_detail)<!-- couponの値を代入 -->
        @if($order_detail->product_id == 'P')
            <?php $dis_price = $order_detail->price; $p_id = $coupon->p_id;?>
        @endif
    @endforeach
    <table class="order_table order_table" border="2">
        <tr>
            <th>注文ID</th>
            <th>注文者名</th>
            <th>注文日時</th>
            <th>支払い方法</th>
            <th>配送日</th>
            <th>配送時間</th>
            <th>受取時間</th>
            <th>注文状況</th>
            <th>配送先</th>
            <th>電話</th>
            <th>種別</th>
            <th>注文状況</th>
        </tr>
        <tr>
            <td><a href="https://dashboard.stripe.com/test/payments/{{$order->o_id}}">...{{$order->new_o_id}}</a></td>
            <td>{{$order->u_name}}</td>
            <td>{{$order->created_at}}</td>
            @if($order->c_flag == 01)
                <td>カード</td>
            @elseif($order->c_flag == 10 || $order->c_flag == 11)
                <td>au PAY</td>
            @elseif($order->c_flag == 20 || $order->c_flag == 21)
                <td>代引</td>
            @endif
            <td>{{$order->delivery_date}}</td>
            <td>{{$order->delivery_time}}</td>
            <td>{{$order->catch_time}}</td>
            <td>{{$order->detail}}</td>
            <td>{{$order->d_address}}</td>
            <td>{{$order->d_tel}}</td>
            <td>{{$order->kind}}</td>
            <td>
                <form class="order_flag" action="/shop_order_detail" method="POST">
                    @csrf
                    <select id="order_flag"  name="order_flag" style="margin-bottom:10px;"{{$order->order_flag==3 || $order->order_flag==4 || $order->order_flag==5 || $order->order_flag==6 ? 'disabled' : ''}}>
                        <option value="0" disabled>注文状況</option>
                        <option value="1"{{$order->order_flag==1 ? 'selected' : ''}}>注文完了</option>
                        <option value="2"{{$order->order_flag==2 ? 'selected' : ''}}>注文確定</option>
                        <option value="7"{{$order->order_flag==7 ? 'selected' : ''}}>キャンセル</option>
                    </select><br>
                    <input type="hidden" name="o_id" value="{{$order->o_id}}">
                    @if($order->order_flag == 1 || $order->order_flag == 2 || $order->order_flag == 7)
                    <input type="hidden" id="flag" name="flag" value="{{$order->order_flag}}">                   
                    <input type="submit" onclick="return check();" value="変更する" class="">
                    @endif
                </form>
            </td>
        </tr>
    </table>
    @foreach($order_details as $key => $order_detail)
        @if($order_details[$key] == $order_details[0])
            <!--初回のみテーブルの項目表示-->
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
                    <th>商品価格</th>
                    <th>オプション1</th>
                    <th>オプション2</th>
                    <th>オプション3</th>
                    <th>オプション4</th>
                    <th>個数</th>
                    <th>合計料金</th>
                    <th>注文状態</th>
                </tr>
        @endif
        @if($order_detail->product_id == 'P' || $order_detail->product_id == 'A')
        <!-- 商品か総額のクーポンの場合-->
        <table class="order_table" border="2">
            <tr>
                <th>{{$coupon->title}}</th>
            </tr>
            <tr>
                <td>{{number_format($order_detail->price)}}円</td>
            </tr>
        </table>
        <?php $dis_price = $order_detail->price; $s_id = $coupon->id; ?>
        @elseif($order_detail->s_id != 0 && $order_detail->product_id != 'P')
            <!--商品の場合-->
            <tr>
                <td>{{$order_detail->p_name}}</td>
                <td>{{number_format($order_detail->p_price)}}円</td>
                @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                <td>{{$order_detail->o_1_name}}{{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                <td>{{$order_detail->o_2_name}}{{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                <td>{{$order_detail->o_3_name}}{{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}円</td>
                @else
                <td></td>
                @endif
                @if(isset($order_detail->o_4_note) && isset($order_detail->o_4_name) && isset($order_detail->o_4_price))
                <td>{{$order_detail->o_4_name}}{{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}円</td>
                @else
                <td></td>
                @endif
                <td>{{$order_detail->quantity}}個</td>
                <td>{{number_format($order_detail->price)}}円</td>
                <td>{{$order_detail->detail}}</td>
            </tr>
        @else
        <!--送料と代引の場合-->
            @if($order_detail->product_id == 'S')
            <!--送料クーポン-->
                <?php $s_d_price = $order_detail->price; ?>
            @elseif($order_detail->product_id == 'D')
                <table class="order_table" border="2">
                    <tr>
                        <th>代引き手数料</th>
                    </tr>
                    <tr>
                        <td>{{number_format($order_detail->price)}}円</td>
                    </tr>
                </table>
            @elseif($order_detail->s_id == 0 && $order_detail->product_id == '16')
                @if($s_d_price != 0)
                    <table class="order_table" border="2">
                        <tr>
                            <th>割引前送料</th>
                            <th>{{$coupon->title}}</th>
                            <th>送料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price)}}円</td>
                            <td>{{number_format($s_d_price)}}円</td>
                            <td>{{number_format($order_detail->price + $s_d_price)}}円</td>
                        </tr>
                    </table>
                @else
                    <table class="order_table" border="2">
                        <tr>
                            <th>送料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price + $s_d_price)}}円</td>
                        </tr>
                    </table>
                @endif
                    <table class="order_table" border="2">
                        <tr>
                            <th>店舗合計</th>
                        </tr>
                        <tr>
                            <td>{{number_format($total)}}円</td>
                        </tr>
                    </table>
            @endif
        @endif
    @endforeach
    <table class="order_table" border="2">
        <tr>
            <th>受取完了時間</th>
            <th>配送完了時間</th>
        </tr>
        <tr>
        @if($order->status_time[1] != '')
            <td>{{$order->status_time[1]}}</td>
        @else
            <td></td>
        @endif
        @if($order->status_time[2] != '')
            <td>{{$order->status_time[2]}}</td>
        @else
            <td></td>
        @endif
        </tr>
    </table>
  </div>
</div>

<!--スマホ -->
<div class="flexbox m_btm" id="small_layout">
  <div>
    <table class="order_table" border="2">
        <tr>
            <th>注文ID</th>
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
        @if($order->status_time[1] != '')
        <tr>
            <th>受取完了時間</th>
        </tr>
        <tr>
            <td>{{$order->status_time[1]}}</td>
        </tr>
        @endif
        @if($order->status_time[2] != '')
        <tr>
            <th>配送完了時間</th>
        </tr>
        <tr>
            <td>{{$order->status_time[2]}}</td>
        </tr>
        @endif
        <tr>
            <th>支払い方法</th>
        </tr>
        <tr>
        @if($order->c_flag == 01)
            <td>カード</td>
        @elseif($order->c_flag == 10 || $order->c_flag == 11)
            <td>au PAY</td>
        @elseif($order->c_flag == 20 || $order->c_flag == 21)
            <td>代引</td>
        @endif
        </tr>
        <tr>
            <th>配送日</th>
        </tr>
        <tr>
            <td>{{$order->delivery_date}}</td>
        </tr>
        <tr>
            <th>配送時間</th>
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
            <th>配送先</th>
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
                <th>注文状態</th>
            </tr>
            <tr>
                <td>{{$order_detail->detail}}</td>
            </tr>
        <tr>
            <th>注文状況</th>
        </tr>
        <tr>
            <td> 
                <form class="order_flag" action="/shop_order_detail" method="POST">
                    @csrf
                    <select name="order_flag" style="margin-bottom:10px;"{{$order->order_flag==3 || $order->order_flag==4 || $order->order_flag==5 || $order->order_flag==6 ? 'disabled' : ''}}>
                        <option value="1"{{$order->order_flag==1 ? 'selected' : ''}}>注文完了</option>
                        <option value="2"{{$order->order_flag==2 ? 'selected' : ''}}>注文確定</option>
                        <option value="3"{{$order->order_flag==3 ? 'selected disabled' : ''}}>配達員選定中</option>
                        <option value="4"{{$order->order_flag==4 ? 'selected disabled' : ''}}>配達員決定</option>
                        <option value="5"{{$order->order_flag==5 ? 'selected disabled' : ''}}>受取完了</option>
                        <option value="6"{{$order->order_flag==6 ? 'selected disabled' : ''}}>配送完了</option>
                        <option value="7"{{$order->order_flag==7 ? 'selected' : ''}}>キャンセル</option>
                    </select><br>
                    <input type="hidden" name="o_id" value="{{$order->o_id}}">
                    @if($order->order_flag == 1 || $order->order_flag == 2 || $order->order_flag == 7)
                    <input type="submit" value="変更する" class="">
                    @endif
                </form>
            </td>
        </tr>
        @foreach($order_details as $key => $order_detail)
            @if($order_details[$key] == $order_details[0])
                <tr>
                    <th>店舗名</th>
                </tr>
                <tr>
                    <td>{{$order_detail->s_name}}</td>
                </tr>
                <tr>
                    <th>店舗住所</th>
                </tr>
                <tr>
                    <td>{{$order_detail->address}}</td>
                </tr>
                <tr>
                    <th>店舗電話番号</th>
                </tr>
                <tr>
                    <td>{{$order_detail->tel}}</td>
                </tr>
            @endif
            @if($order_detail->product_id == 'P' || $order_detail->product_id == 'A')
            <!-- 商品か総額のクーポンの場合-->
                <tr>
                    <th>{{$coupon->title}}</th>
                </tr>
                <tr>
                    <td>{{number_format($order_detail->price)}}円</td>
                </tr>
            @elseif($order_detail->s_id != 0 && $order_detail->product_id != 'P')
            <!--商品の場合-->
                <tr>
                    <th>商品名</th>
                </tr>
                <tr>
                    <td>{{$order_detail->p_name}}</td>
                </tr>
                <tr>
                    <th>商品価格</th>
                </tr>
                <tr>
                    <td>{{number_format($order_detail->p_price)}}円</td>
                </tr>
                @if(isset($order_detail->o_1_note) && isset($order_detail->o_1_name) && isset($order_detail->o_1_price))
                    <tr>
                        <th>オプション1</th>
                    </tr>
                    <tr>
                        <td>{{$order_detail->o_1_name}}{{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}円</td>
                    </tr>
                @endif
                @if(isset($order_detail->o_2_note) && isset($order_detail->o_2_name) && isset($order_detail->o_2_price))
                    <tr>
                        <th>オプション2</th>
                    </tr>
                    <tr>
                        <td>{{$order_detail->o_2_name}}{{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}円</td>
                    </tr>
                @endif
                @if(isset($order_detail->o_3_note) && isset($order_detail->o_3_name) && isset($order_detail->o_3_price))
                    <tr>
                        <th>オプション3</th>
                    </tr>
                    <tr>
                        <td>{{$order_detail->o_3_name}}{{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}円</td>
                    </tr>
                @endif
                @if(isset($order_detail->o_4_note) && isset($order_detail->o_4_name) && isset($order_detail->o_4_price))
                    <tr>
                        <th>オプション4</th>
                    </tr>
                    <tr>
                        <td>{{$order_detail->o_4_name}}{{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}円</td>
                    </tr>
                @endif
                <tr>
                    <th>個数</th>
                </tr>
                <tr>
                    <td>{{$order_detail->quantity}}個</td>
                </tr>
                <tr>
                    <th>合計料金</th>
                </tr>
                <tr>
                    <td>{{number_format($order_detail->price)}}円</td>
                </tr>
            @else
            <!--送料と代引の場合-->
                @if($order_detail->product_id == 'S')
                <!--送料クーポン-->
                    <?php $s_d_price = $order_detail->price; ?>
                @elseif($order_detail->product_id == 'D')
                        <tr>
                            <th>代引き手数料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price)}}円</td>
                        </tr>
                @elseif($order_detail->s_id == 0 && $order_detail->product_id == '16')
                    @if($s_d_price != 0)
                        <tr>
                            <th>割引前送料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price)}}円</td>
                        </tr>
                        <tr>
                            <th>{{$coupon->title}}</th>
                        </tr>
                        <tr>
                            <td>{{number_format($s_d_price)}}円</td>
                        </tr>
                        <tr>
                            <th>送料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price + $s_d_price)}}円</td>
                        </tr>
                    @else
                        <tr>
                            <th>送料</th>
                        </tr>
                        <tr>
                            <td>{{number_format($order_detail->price + $s_d_price)}}円</td>
                        </tr>
                    @endif
                        <tr>
                            <th>店舗合計</th>
                        </tr>
                        <tr>
                            <td>{{number_format($total)}}円</td>
                        </tr>
                @endif
            @endif
        @endforeach
        <tr>
            <th>受取完了時間</th>
        </tr>
        <tr>
        @if($order->status_time[1] != '')
            <td>{{($order->status_time[1])}}</td>
        @else
            <td></td>
        @endif
        </tr>
        <tr>
            <th>配送完了時間</th>
        </tr>
        <tr>
        @if($order->status_time[2] != '')
            <td>{{$order->status_time[2]}}</td>
        @else
            <td></td>
        @endif
        </tr>
    </table>
  </div>
</div>

<script>
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