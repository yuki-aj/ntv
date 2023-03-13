@include('manage/header')
<style>
@media  screen and (max-width: 800px) {
    .order_search label {
      min-width: 80px;
    }
    .accordion {
      margin: 0 auto;
      width:95%;
    }
    .toggle {
      display: none;
    }
    .option {
      position: relative;
      margin-bottom: 1em;
    }
    .title,
    .content {
      -webkit-backface-visibility: hidden;
      backface-visibility: hidden;
      transform: translateZ(0);
      transition: all 0.3s;
    }
    .title {
    display: block;
    }
    .title::after,
    .title::before {
      content: "";
      position: absolute;
      font-size: 2.0em;
      right: 50%;
      top: -0.4em;
      width: 2px;
      background-color: #000;
      transition: all 0.3s;
    }
    .title::after {
      transform: rotate(90deg);
    }
    .content {
      max-height: 0;
      overflow: hidden;
    }
    .content p {
      margin: 0;
      padding: 0.5em 1em 1em;
      font-size: 0.9em;
      line-height: 1.5;
    }
    .toggle:checked + .title + .content {
      max-height: 500px;
      transition: all 1.5s;
    }
    .toggle:checked + .title::before {
      transform: rotate(90deg) !important;
    }


    * {
  margin: 0;
  padding: 0;
  }

  /* CSS for CodePen */
  .accordion{
    margin-top: 2em;
  }

  .accordion__container {
    width: 300px;
    margin: 0 auto;
  }

  .accordion__title {
    /* background-color: #000; */
    /* border: 1px solid transparent; */
    /* color: #fff; */
    /* font-size: 1.25em; */
    /* padding: .625em .625em .625em 2em; */
    position: relative;
    cursor: pointer;
    user-select: none;
  }

  .accordion__title::before, .accordion__title::after {
    content: '';
    display: block;
    background-color: #000;
    position: absolute;
    top: 50%;
    width: 15px;
    height: 2px;
    right: 48%;
  }

  .accordion__title::after {
    transform: rotate(90deg);
    transition-duration: .3s;
  }

  /* .accordion__title:hover,
  .accordion__title:active,
  .accordion__title.is-active { 
    background-color: #00aaa7;
  } */

  .accordion__title.is-active::before {
    opacity: 0;
  }

  .accordion__title.is-active::after {
    transform: rotate(0);
  }

  .accordion__content {
    /* border-left: 1px solid transparent; */
    /* border-right: 1px solid transparent; */
    padding: 0 1.5em;
    line-height: 0;
    height: 0;
    overflow: hidden;
    opacity: 0;
    transition-duration: .3s;
  }

  .accordion__content.is-open {
    /* border: 1px solid #000; */
    padding: 0;
    line-height: normal; /* numberに書き換える*/
    height: auto;
    opacity: 1;
  }
}
</style>

<h1 class="baskets">注文管理</h1>
  <div class="admin_page">
    <div class="addition gap m_t3" style="border-radius: 50px;">
      <a href="/admin_manage/">
          <p>管理画面へ</p>
      </a>
    </div>
  </div>

  <!-- オーダー検索 -->
  <form class="form-table order_search" action="/order_search" method="get">
    @csrf
    <h1 style="text-align: center; margin:20px 0 0; color:#000;">オーダー検索</h1>
    <div class="order_width">
      <div class="flexbox m_top3">
        <label for="u_name">注文者名</label>
        <input class="order_text" name="u_name" type="text" value="{{isset($request->u_name) ? $request->u_name : ''}}" placeholder="注文者名を入力">
      </div>
      <div class="flexbox m_top3">
        <label for="name">店舗名</label>
        <input class="order_text" name="name" type="text" value="{{isset($request->name) ? $request->name : ''}}" placeholder="店舗名を入力">
      </div>
      <div class="flexbox m_top3">
        <label for="o_id">注文ID</label>
        <input class="order_text" name="o_id" type="text" value="{{isset($request->o_id) ? $request->o_id : ''}}" placeholder="注文IDを入力">
      </div>
      <div class="flexbox m_top3">
        <label>注文日</label>
        <input class="order_date" name="from" type="date" value="{{isset($request->from) ? $request->from : ''}}">～
        <input class="order_date" name="to" type="date" value="{{isset($request->to) ? $request->to : ''}}">
      </div>
      <div class="admin_flex space-between">
        <select class="order_select" name="order_flag">
            <option value="">注文状況</option>
            <option value="1" {{isset($request->order_flag)&&($request->order_flag==1) ? 'selected' : ''}}>注文完了</option>
            <option value="2" {{isset($request->order_flag)&&($request->order_flag==2) ? 'selected' : ''}}>注文確定</option>
            <option value="3" {{isset($request->order_flag)&&($request->order_flag==3) ? 'selected' : ''}}>配達員選定中</option>
            <option value="4" {{isset($request->order_flag)&&($request->order_flag==4) ? 'selected' : ''}}>配達員決定</option>
            <option value="5" {{isset($request->order_flag)&&($request->order_flag==5) ? 'selected' : ''}}>引取完了</option>
            <option value="6" {{isset($request->order_flag)&&($request->order_flag==6) ? 'selected' : ''}}>配送完了</option>
            <option value="7" {{isset($request->order_flag)&&($request->order_flag==7) ? 'selected' : ''}}>キャンセル</option>
        </select>
        <select class="order_select" name="delivery_date">
            <option value="">配送日</option>
            <option value="1" {{isset($request->delivery_date)&&($request->delivery_date==1) ? 'selected' : ''}}>配送日が近い順</option>
            <option value="2" {{isset($request->delivery_date)&&($request->delivery_date==2) ? 'selected' : ''}}>配送日が遠い順</option>
        </select>
        <select class="order_select" name="c_flag">
            <option value="">支払い状況</option>
            <option value="1" {{isset($request->c_flag)&&($request->c_flag == 01 ||$request->c_flag == 11 ||$request->c_flag == 21) ? 'selected' : ''}}>完了</option>
            <option value="2" {{isset($request->c_flag)&&($request->c_flag == 10 || $request->c_flag == 20) ? 'selected' : ''}}>未了</option>
        </select>
        <select class="order_select" name="corporation_flag">
            <option value="">利用種別</option>
            <option value="1" {{isset($request->corporation_flag)&&($request->corporation_flag==1) ? 'selected' : ''}}>個人利用</option>
            <option value="2" {{isset($request->corporation_flag)&&($request->corporation_flag==2) ? 'selected' : ''}}>法人利用</option>
            <option value="3" {{isset($request->corporation_flag)&&($request->corporation_flag==3) ? 'selected' : ''}}>もしもし利用</option>
        </select>
        <select class="order_select" name="pay_kind">
            <option value="">支払い方法</option>
            <option value="1" {{isset($request->pay_kind)&&($request->pay_kind==1) ? 'selected' : ''}}>カード</option>
            <option value="2" {{isset($request->pay_kind)&&($request->pay_kind==2) ? 'selected' : ''}}>au PAY</option>
            <option value="3" {{isset($request->pay_kind)&&($request->pay_kind==3) ? 'selected' : ''}}>代引</option>
        </select>
      </div>
    </div>
    <div class="categories flexbox">
      <div class="flexbox searchbutton-box">
        <input class="addition" style="font-size:1.1em; border-radius: 50px;" type="submit" value="検索する" class="searchbutton">
      </div>
    </div>
  </form>
  <div class="flexbox margin10">検索結果<span style="font-weight:bold; font-size:24px;"> {{ $lists->total()}} </span>件</div>
  <div class="flexbox margin10">{{ $lists->appends(request()->input())->links() }}</div>
  
<!-- スマホ -->
<div class="responsive">
@foreach ($lists as $orderlist)
  <div class="accordion">
    <div class="option">
      <table class="info-table1">
        <tr>
            <th colspan="4">配送日</th>
            <th colspan="4">配送時間</th>
            <th colspan="4">引取時間</th>
        </tr>
        <tr>
            <td colspan="4">{{$orderlist->delivery_date}}</td>
            <td colspan="4">{{$orderlist->delivery_time}}</td>
            <td colspan="4">
            <form  class="order_flag" action="/order_search" method="POST">
                  @csrf
                  <input  style="margin-bottom:10px;" id="catch_time" pattern="[0-9]{2}:[0-9]{2}"  type="time" step="1800"  list="data-list"  name="catch_time" value="{{$orderlist->catch_time}}">
                  <input type="hidden" name="order_id" value="{{$orderlist->o_id}}"><br>
                  <input  onclick="return time();" type="submit" value="変更する" class="">
                  <datalist id="data-list">
                  <option value="10:30"></option>
                  <option value="11:00"></option>
                  <option value="11:30"></option>
                  <option value="12:00"></option>
                  <option value="12:30"></option>
                  <option value="13:00"></option>
                  <option value="13:30"></option>
                  <option value="14:00"></option>
                  <option value="14:30"></option>
                  <option value="15:00"></option>
                  <option value="15:30"></option>
                  <option value="16:00"></option>
                  <option value="16:30"></option>
                  <option value="17:00"></option>
                  <option value="17:30"></option>
                  <option value="18:00"></option>
                  <option value="18:30"></option>
                  <option value="19:00"></option>
                  <option value="19:30"></option>
                </datalist>
              </form>

            </td>
        </tr>
      </table>
      <table class="info-table2">
        <tr class="t_left">
            <td colspan="4">{{$orderlist->name}}</td>
        </tr>
      </table>
      <table class="info-table2">
        <tr>
          <td colspan="2" class="bold" style="letter-spacing: 3px;  background: rgb(240, 225, 200);">配送先</td>
          <td colspan="9" class="t_left"><span style="font-size:1.3em;">{{$orderlist->d_name}}</span><br>{{$orderlist->d_address}}<br><span style="color:blue;">{{$orderlist->d_tel}}</span></td>
        </tr>
      </table>
      <div class="accordion__content">
        <table class="info-table2">
          <tr>
            <td colspan="2" class="bold" style="letter-spacing: 3px; background: rgb(240, 225, 200);">注文者</td>
            <td colspan="9" class="t_left"><span style="font-size:1.3em;">{{$orderlist->u_name}}</span><br>{{$orderlist->o_address}}<br><span style="color:blue;">{{$orderlist->o_tel}}</span></td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
              <th colspan="5">注文内容</th>
          </tr>
          <?php $sum = 0;?>
          @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id != 16 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D')
          <tr>
              <td colspan="4"><div class="flex"><div>{{$order_detail['p_name']}}</div><div>　×{{$order_detail->quantity}}</div></div></td>
              <td colspan="1">{{number_format($order_detail['p_price'])}}円</td>
          </tr>
          <tr>
              <td colspan="1">
                @if(isset($order_detail->o_1_name) && $order_detail->o_1_name != '')
                  {{$order_detail->o_1_name}} {{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_2_name) && $order_detail->o_2_name != '')
                  {{$order_detail->o_2_name}} {{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_3_name) && $order_detail->o_3_name != '')
                {{$order_detail->o_3_name}} {{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_4_name) && $order_detail->o_4_name != '')  
                  {{$order_detail->o_4_name}} {{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1" style="color:blue;">
                {{number_format($order_detail['subtotal'])}}円
                <?php $sum = $sum + $order_detail['subtotal'];?>
              </td>
          </tr>
          @endif
          @endforeach
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">カトラリ</td>
              <td colspan="3" class="t_left">{{$orderlist->note[0]}}</td>
              <td colspan="1"></td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">クーポン</td>
              <td colspan="3" class="bold">{{$orderlist->coupon_title}}</td>
              <td colspan="1" style="color:red;">
                  @if(isset($orderlist) && $orderlist->coupon_discount)
                    {{$orderlist->coupon_discount}}円
                    <?php $sum = $sum + $orderlist->coupon_discount;?>
                  @else
                    0円
                  @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">送料</td>
              <td colspan="3"></td>
              <td colspan="1">
                @foreach($order_details as $key => $order_detail)
                  @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 16)
                  {{number_format($order_detail['postage'])}}円
                  <?php $sum = $sum + $order_detail['postage'];?>
                  @endif
                @endforeach
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">代引</td>
              <td colspan="3"></td>
              <td colspan="1">
                @foreach($order_details as $key => $order_detail)
                  @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 'D')
                  {{number_format($order_detail->price)}}円
                  <?php $sum = $sum + $order_detail->price;?>
                  @endif
                @endforeach
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">合計</td>
              <td colspan="3"></td>
              <td colspan="1" class="bold">
              {{number_format($sum)}}円
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">備考</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[2])  && $orderlist->note[2] != '')
                {{$orderlist->note[2]}}
                @else
                  なし
                @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">ギフト</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[1]) && $orderlist->note[1] != '')
                  {{$orderlist->note[1]}}
                @else
                  なし
                @endif
              </td>
          </tr>
          <tr>
            <form action="{{ url('admin_memo') }}" method="POST">
            @csrf
              <td colspan="1" style="background: rgb(240, 225, 200);">管理メモ
                <button type="submit" style="background:#f4a125; color:#fff; padding:0.3em; border:#ddd;">保存</button>
              </td>
              <td colspan="4">
                  <textarea type="text" placeholder="" id="memo" name="memo" class="w_100 t_left" style="box-sizing: border-box; padding: 1em 0;">{{isset($orderlist->memo)? $orderlist->memo : ''}}</textarea>
                  <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
              </td>
            </form>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">配達員</td>
              <td colspan="4">
                {{$orderlist->d_staff_id}}
                @if($orderlist->d_staff_id == false)
                <a href="/staff_list/{{$orderlist->id}}" class="" target="_blank" style="background:#f4a125; color:#fff; padding:0.3em; border:#ddd;">選択</a>
                @endif
              </td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
            <th colspan="4">支払</th>
            <th colspan="4">注文日時</th>
            <th colspan="4">種別</th>
          </tr>
          <tr>
            <td colspan="4">{{$orderlist->charge}}</td>
            <td colspan="4">{{$orderlist->created_a_t}}</td>
            <td colspan="4">{{$orderlist->kind}}</td>
          </tr>
          <tr>
            <th colspan="4">状況</th>
            <th colspan="4">注文ID 下5桁</th>
            <th colspan="4">領収書</th>
          </tr>
          <tr>
            @if($orderlist->order_flag == 6)
            <td colspan="4" style="color:red;">{{$orderlist->detail}}</td>
            @else
            <td colspan="4" style="color:blue;">{{$orderlist->detail}}</td>
            @endif
            <td colspan="4">{{$orderlist->new_o_id}}</td>
            <td colspan="4">{{$orderlist->u_name}}</td>
          </tr>
          <tr>
            <th colspan="4">注文変更</th>
            <th colspan="4"></th>
            <th colspan="4">支払変更</th>
          </tr>
          <tr>
            <td colspan="4">
              <form class="order_flag" action="/order_search" method="POST">
                @csrf
                <select id="order_flag" name="order_flag" style="margin-bottom:10px;">
                    <option value disabled>注文状況</option>
                    <option value="1"{{$orderlist->order_flag==1 ? 'selected' : ''}}>注文完了</option>
                    <option value="2"{{$orderlist->order_flag==2 ? 'selected' : ''}}>注文確定</option>
                    <option value="3"{{$orderlist->order_flag==3 ? 'selected' : ''}}>配達員選定中</option>
                    <option value="4"{{$orderlist->order_flag==4 ? 'selected' : ''}}>配達員決定</option>
                    <option value="5"{{$orderlist->order_flag==5 ? 'selected' : ''}}>引取完了</option>
                    <option value="6"{{$orderlist->order_flag==6 ? 'selected' : ''}}>配送完了</option>
                    <option value="7"{{$orderlist->order_flag==7 ? 'selected' : ''}}>キャンセル</option>
                </select><br>
                <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                <input type="hidden" id="flag" name="flag" value="{{$orderlist->order_flag}}">                   
                <input onclick="return check();"  type="submit" value="変更する" class="">
              </form>
            </td>
            <td colspan="4">
              <!-- <div style="background:#000;">
                <input type="checkbox" id="toggle1" class="toggle">
                <label class="title" for="toggle1"></label>
              </div> -->
            </td>
            <td colspan="4">
              @if($orderlist->c_flag == 01 || $orderlist->c_flag == 11 || $orderlist->c_flag == 21)
              支払い済
              @else
              <form class="order_flag" action="/order_search" method="POST">
                  @csrf
                  <select name="c_flag" style="margin-bottom:10px;">
                      <option value="" disabled>注文状況</option>
                      <option value="1">完了</option>
                      <option value="2">未了</option>
                  </select><br>
                  <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                  <input type="submit" value="変更する" class="">
              </form>
              @endif
            </td>
          </tr>
          <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">受取時刻</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[1]))
              {{$orderlist->status_time[1]}}
            @endif            
            </td>
          </tr>
          <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">配送時刻</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[2]))
              {{$orderlist->status_time[2]}}
            @endif            
            </td>
          </tr>
        </table>  
      </div>


      <div class="accordion__title js-accordion-title">
        <table class="info-table2">
          <tr>
              @if($orderlist->order_flag == 6)
              <td colspan="4" class="bold" style="color:red;">{{$orderlist->detail}}</td>
              @else
              <td colspan="4" class="bold" style="color:blue;">{{$orderlist->detail}}</td>
              @endif
              <td colspan="4" style="background:#f2f2f2;">
              </td>
              <td colspan="4">
                合計<span class="bold">
                @if(isset($sum) != '')
                  {{number_format($sum)}}円
                @endif
              </span>
              </td>
          </tr>
        </table>
      </div>



      <!-- <div class="target">
        <table class="info-table2">
          <tr>
            <td colspan="1" style="letter-spacing: 3px; writing-mode: vertical-rl; background: rgb(240, 225, 200);">注文者</td>
            <td colspan="6" class="t_left"><span style="font-size:1.3em;">{{$orderlist->u_name}}</span><br>{{$orderlist->o_address}}<br><span style="color:blue;">{{$orderlist->o_tel}}</span></td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
              <th colspan="5">注文内容</th>
          </tr>
          <?php $sum = 0;?>
          @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id != 16 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D')
          <tr>
              <td colspan="4">{{$order_detail['p_name']}}　×{{$order_detail->quantity}}</td>
              <td colspan="1">{{number_format($order_detail['p_price'])}}円</td>
          </tr>
          <tr>
              <td colspan="1">
                @if(isset($order_detail->o_1_name) && $order_detail->o_1_name != '')
                  {{$order_detail->o_1_name}} {{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_2_name) && $order_detail->o_2_name != '')
                  {{$order_detail->o_2_name}} {{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_3_name) && $order_detail->o_3_name != '')
                {{$order_detail->o_3_name}} {{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_4_name) && $order_detail->o_4_name != '')  
                  {{$order_detail->o_4_name}} {{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}円
                @else
                0円
                @endif
              </td>
              <td colspan="1" style="color:blue;">
                {{number_format($order_detail['subtotal'])}}円
                <?php $sum = $sum + $order_detail['subtotal'];?>
              </td>
          </tr>
          @endif
          @endforeach
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">カトラリ</td>
              <td colspan="3" class="t_left">{{$orderlist->note[0]}}</td>
              <td colspan="1"></td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">クーポン</td>
              <td colspan="3" class="bold">{{$orderlist->coupon_title}}</td>
              <td colspan="1" style="color:red;">
                  @if(isset($orderlist) && $orderlist->coupon_discount)
                    {{$orderlist->coupon_discount}}円
                    <?php $sum = $sum + $orderlist->coupon_discount;?>
                  @else
                    0円
                  @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">送料</td>
              <td colspan="3"></td>
              <td colspan="1">
                @foreach($order_details as $key => $order_detail)
                  @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 16)
                  {{number_format($order_detail['postage'])}}円
                  <?php $sum = $sum + $order_detail['postage'];?>
                  @endif
                @endforeach
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">合計</td>
              <td colspan="3"></td>
              <td colspan="1" class="bold">
              {{number_format($sum)}}円
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">備考</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[2])  && $orderlist->note[2] != '')
                {{$orderlist->note[2]}}
                @else
                  なし
                @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">ギフト</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[1]) && $orderlist->note[1] != '')
                  {{$orderlist->note[1]}}
                @else
                  なし
                @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">管理メモ</td>
              <td colspan="4">
                <form action="{{ url('admin_memo') }}" method="POST">
                  @csrf
                  <textarea type="text" placeholder="" id="memo" name="memo" class="w_100 t_left" style="box-sizing: border-box; padding: 1em 0;">{{isset($orderlist->memo)? $orderlist->memo : ''}}</textarea>
                  <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                  <button type="submit" class="btn--orange btn--cubic btn--shadow">保存</button>
                </form>
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">配達員</td>
              <td colspan="4">
                {{$orderlist->d_staff_id}}
                @if($orderlist->d_staff_id == false)
                <a href="/staff_list/{{$orderlist->id}}" class="" target="_blank" style="border:1px solid #ccc;padding:5px;background-color:orange;color:#fff;">選択</a>
                @endif
              </td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
            <th colspan="4">支払</th>
            <th colspan="4">注文日時</th>
            <th colspan="4">種別</th>
          </tr>
          <tr>
            <td colspan="4">
            @if($orderlist->c_flag == 01)
              支払い済
            @else
              <form class="order_flag" action="/order_search" method="POST">
                  @csrf
                  <select name="c_flag" style="margin-bottom:10px;">
                      <option value="0" disabled>注文状況</option>
                      <option value="1">完了</option>
                      <option value="2">未了</option>
                  </select><br>
                  <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                  <input type="submit" value="変更する" class="">
              </form>
            @endif
            </td>
            <td colspan="4">{{$orderlist->created_at}}</td>
            <td colspan="4">{{$orderlist->kind}}</td>
          </tr>
          <tr>
            <th colspan="4">状況</th>
            <th colspan="4">ID</th>
            <th colspan="4">領収書</th>
          </tr>
          <tr>
            <td colspan="4" style="color:red;">{{$orderlist->detail}}</td>
            <td colspan="4">...{{$orderlist->new_o_id}}</td>
            <td colspan="4">{{$orderlist->u_name}}</td>
          </tr>
          <tr>
            <th colspan="4">注文変更</th>
            <th colspan="4"></th>
            <th colspan="4">支払変更</th>
          </tr>
          <tr>
            <td colspan="4">
              <form class="order_flag" action="/order_search" method="POST">
                @csrf
                <select id="order_flag" name="order_flag" style="margin-bottom:10px;">
                    <option value disabled>注文状況</option>
                    <option value="1"{{$orderlist->order_flag==1 ? 'selected' : ''}}>注文完了</option>
                    <option value="2"{{$orderlist->order_flag==2 ? 'selected' : ''}}>注文確定</option>
                    <option value="3"{{$orderlist->order_flag==3 ? 'selected' : ''}}>配達員選定中</option>
                    <option value="4"{{$orderlist->order_flag==4 ? 'selected' : ''}}>配達員決定</option>
                    <option value="5"{{$orderlist->order_flag==5 ? 'selected' : ''}}>受取完了</option>
                    <option value="6"{{$orderlist->order_flag==6 ? 'selected' : ''}}>配送完了</option>
                    <option value="7"{{$orderlist->order_flag==7 ? 'selected' : ''}}>キャンセル</option>
                </select><br>
                <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                <input type="hidden" id="flag" name="flag" value="{{$orderlist->order_flag}}">                   
                <input onclick="return check();"  type="submit" value="変更する" class="">
              </form>
            </td>
            <td colspan="4" style="background:#f2f2f2;">
              <div style="background:#000;">
                <input type="checkbox" id="toggle1" class="toggle">
                <label class="title" for="toggle1"></label>
              </div>
            </td>
            <td colspan="4">
              @if($orderlist->c_flag == 01)
              支払い済
              @else
              <form class="order_flag" action="/order_search" method="POST">
                  @csrf
                  <select name="c_flag" style="margin-bottom:10px;">
                      <option value="0" disabled>注文状況</option>
                      <option value="1">完了</option>
                      <option value="2">未了</option>
                  </select><br>
                  <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                  <input type="submit" value="変更する" class="">
              </form>
              @endif
            </td>
          </tr>
          <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">受取時刻</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[1]))
              {{$orderlist->status_time[1]}}
            @endif            
            </td>
          </tr>
          <tr>
            <td colspan="4" style="background: rgb(240, 225, 200);">配送時刻</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[2]))
              {{$orderlist->status_time[2]}}
            @endif            
            </td>
          </tr>
        </table>  
      </div> -->
    </div>
  </div>
@endforeach
</div>


<!-- 大画面 -->
@foreach ($lists as $orderlist)
<div class="m_top5 large_screen">
  <div class="w_90">
    <table class="info-table1">
      <tr>
        <th colspan="4">配送日</th>
        <th colspan="4">時間</th>
        <th colspan="4">引取時間</th>
        <th colspan="4">店舗名</th>
        <th colspan="8">配送先</th>
        <th colspan="8">注文者</th>
        <th colspan="4">注文日時</th>
        <th colspan="4">種別</th>
        <th colspan="4">支払</th>
        <th colspan="4">注文ID 下5桁</th>
      </tr>
      <tr>
        <td colspan="4">{{$orderlist->delivery_date}}</td>
        <td colspan="4">{{$orderlist->delivery_time}}</td>
        <td colspan="4">
              <form  class="order_flag" action="/order_search" method="POST">
                  @csrf
                  <input  pattern="[0-9]{2}:[0-9]{2}" id="catch_time" type="time" step="1800"  list="data-list" min="10:00" max="21:00" name="catch_time" value="{{$orderlist->catch_time}}">
                  <input type="hidden" name="order_id" value="{{$orderlist->o_id}}"><br>
                  <input onclick="return time();" type="submit" value="変更する" class="">
                  <datalist id="data-list">
                    <option value="10:30"></option>
                    <option value="11:00"></option>
                    <option value="11:30"></option>
                    <option value="12:00"></option>
                    <option value="12:30"></option>
                    <option value="13:00"></option>
                    <option value="13:30"></option>
                    <option value="14:00"></option>
                    <option value="14:30"></option>
                    <option value="15:00"></option>
                    <option value="15:30"></option>
                    <option value="16:00"></option>
                    <option value="16:30"></option>
                    <option value="17:00"></option>
                    <option value="17:30"></option>
                    <option value="18:00"></option>
                    <option value="18:30"></option>
                    <option value="19:00"></option>
                    <option value="19:30"></option>
                  </datalist>
              </form>
          </td>
        <td colspan="4">{{$orderlist->name}}</td>
        <td colspan="8">{{$orderlist->d_name}}<br>{{$orderlist->d_address}}<br>{{$orderlist->d_tel}}</td>
        <td colspan="8">{{$orderlist->u_name}}<br>{{$orderlist->o_address}}<br>{{$orderlist->o_tel}}</td>
        <td colspan="4">{{$orderlist->created_a_t}}</td>
        <td colspan="4">{{$orderlist->kind}}</td>
        <td colspan="4">{{$orderlist->charge}}</td>
        @if($orderlist->c_flag == 01)
        <td colspan="4"><a href="https://dashboard.stripe.com/test/payments/{{$orderlist->o_id}}" target="_blank">{{$orderlist->new_o_id}}</a></td>
        @else
        <td colspan="4">{{$orderlist->new_o_id}}</td>
        @endif
      </tr>
    </table>

    <table class="info-table2">
      <tr>
        <th colspan="4">状況</th>
        <th colspan="4">注文変更</th>
        <th colspan="4">支払変更</th>
        <th colspan="8">領収書</th>
        <th colspan="8">配達員</th>
        <th colspan="8">受取時間</th>
        <th colspan="8">配達時間</th>
        <th colspan="4">カトラリー</th>
      </tr>
      <tr>
        @if($orderlist->detail == '配送完了')
        <td colspan="4" style="color:red; font-weight:bold;">{{$orderlist->detail}}</td>
        @else
        <td colspan="4">{{$orderlist->detail}}</td>
        @endif
        <td colspan="4"> 
          <form class="order_flag" action="/order_search" method="POST">
            @csrf
            <select id="order_flag" name="order_flag" style="margin-bottom:10px;">
                <option value disabled>注文状況</option>
                <option value="1"{{$orderlist->order_flag==1 ? 'selected' : ''}}>注文完了</option>
                <option value="2"{{$orderlist->order_flag==2 ? 'selected' : ''}}>注文確定</option>
                <option value="3"{{$orderlist->order_flag==3 ? 'selected' : ''}}>配達員選定中</option>
                <option value="4"{{$orderlist->order_flag==4 ? 'selected' : ''}}>配達員決定</option>
                <option value="5"{{$orderlist->order_flag==5 ? 'selected' : ''}}>引取完了</option>
                <option value="6"{{$orderlist->order_flag==6 ? 'selected' : ''}}>配送完了</option>
                <option value="7"{{$orderlist->order_flag==7 ? 'selected' : ''}}>キャンセル</option>
            </select><br>
            <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
            <input type="hidden" id="flag" name="flag" value="{{$orderlist->order_flag}}"> 
            <input onclick="return check();"  type="submit" value="変更する" class="">
          </form>
        </td>
        <td colspan="4">
          @if($orderlist->c_flag == 01 || $orderlist->c_flag == 11 || $orderlist->c_flag == 21)
            支払い済
            @else
            <form class="order_flag" action="/order_search" method="POST">
                @csrf
                <select name="c_flag" style="margin-bottom:10px;">
                    <option value="" disabled>注文状況</option>
                    <option value="1">完了</option>
                    <option value="2">未了</option>
                </select><br>
                <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                <input type="submit" value="変更する" class="">
            </form>
            @endif
        </td>
        <td colspan="8">{{$orderlist->u_name}}</td>
        <td colspan="8">
          {{$orderlist->d_staff_id}}
          @if($orderlist->d_staff_id == false)
          <a href="/staff_list/{{$orderlist->id}}" class="" target="_blank" style="border:1px solid #ccc;padding:5px;background-color:orange;color:#fff;">選択</a>
          @endif
        </td>
        <td colspan="8">
          @if(isset($orderlist->status_time[1]))
            {{$orderlist->status_time[1]}}
          @endif
        </td>
        <td colspan="8">
          @if(isset($orderlist->status_time[2]))
            {{$orderlist->status_time[2]}}
          @endif
      </td>
        <td colspan="4">{{$orderlist->note[0]}}</td>
      </tr>
    </table>
    <table class="info-table2">
      <tr>
        <th colspan="44">注文内容</th>
        <th colspan="4"></th>
      </tr>
      <tr>
        <th colspan="7">商品名</th>
        <th colspan="5">価格</th>
        <th colspan="7">OP1</th>
        <th colspan="7">OP2</th>
        <th colspan="7">OP3</th>
        <th colspan="7">OP4</th>
        <th colspan="4">個数</th>
        <th colspan="4">合計</th>
      </tr>
      <?php $sum = 0;?>
      @foreach($order_details as $key => $order_detail)
      @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id != 16 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D')
      <tr>
        <td colspan="7">{{$order_detail['p_name']}}</td>
        <td colspan="5">{{number_format($order_detail['p_price'])}}円</td>
        <td colspan="7">
        @if(isset($order_detail->o_1_name) && $order_detail->o_1_name != '')
          {{$order_detail->o_1_name}} {{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}円
        @else
        @endif
        </td>
        <td colspan="7">
        @if(isset($order_detail->o_2_name) && $order_detail->o_2_name != '')
          {{$order_detail->o_2_name}} {{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}円
        @else
        @endif
        </td>
        <td colspan="7">
        @if(isset($order_detail->o_3_name) && $order_detail->o_3_name != '')
          {{$order_detail->o_3_name}} {{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}円
        @else
        @endif
        </td>
        <td colspan="7">
        @if(isset($order_detail->o_4_name) && $order_detail->o_4_name != '')  
          {{$order_detail->o_4_name}} {{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}円
        @else
        @endif
        </td>
        <td colspan="4">{{$order_detail->quantity}}</td>
        <td colspan="4">{{number_format($order_detail['subtotal'])}}円</td>
        <?php $sum = $sum + $order_detail['subtotal'];?>

      </tr>
      @endif
      @endforeach
      <tr>
        <th colspan="7"></th>
        <td colspan="19"></td>
        <th colspan="7">クーポン</th>
        <td colspan="11">
        {{$orderlist->coupon_title}}
        </td>
        <td colspan="4" style="color:red; ">
          @if(isset($orderlist) && $orderlist->coupon_discount)
            {{$orderlist->coupon_discount}}円
            <?php $sum = $sum + $orderlist->coupon_discount;?>
          @else
            0円
          @endif
        </td>
      </tr>
      <tr>
        <th colspan="7">備考</th>
        <td colspan="19">
          @if(isset($orderlist->note[2])  && $orderlist->note[2] != '')
          {{$orderlist->note[2]}}
          @else
          なし
          @endif
        </td>
        <th colspan="7">送料</th>
        <td colspan="11"></td>
        <td colspan="4">
        @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 16)
           {{number_format($order_detail['postage'])}}円
           <?php $sum = $sum + $order_detail['postage'];?>
           @endif
        @endforeach
        </td>
      </tr>
      <tr>
        <th colspan="7">ギフトメッセージ</th>
        <td colspan="19">
          @if(isset($orderlist->note[1]) && $orderlist->note[1] != '')
          {{$orderlist->note[1]}}
          @else
          なし
          @endif
        </td>
        <th colspan="7">代引</th>
        <td colspan="11"></td>
        <td colspan="4">
        @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 'D')
           {{number_format($order_detail->price)}}円
           <?php $sum = $sum + $order_detail->price;?>
           @endif
        @endforeach
        </td>
      </tr>
      <tr>
      <form action="{{ url('admin_memo') }}" method="POST">
        @csrf
        <th colspan="7">管理メモ
        <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
        <button type="submit" class="btn btn--orange btn--cubic btn--shadow"><p>保存</p></button>
        </th>
        <td colspan="19">
          <textarea type="text" placeholder="" id="memo" name="memo" class="w_100" style="box-sizing: border-box; padding: 1em 0;">{{isset($orderlist->memo)? $orderlist->memo : ''}}</textarea>
      </form>
        </td>
        <th colspan="7">合計金額</th>
        <td colspan="11"></td>
        <td colspan="4" class="bold">
            {{number_format($sum)}}円
        </td>
      </tr>
    </table>
  </div>
</div>
@endforeach


  <div class="flexbox margin10">{{ $lists->appends(request()->input())->links() }}</div>

<script>
    function time(){
    var catch_time = document.getElementById('catch_time').value;
      if(catch_time == ''){
        console.log(catch_time);
        alert('引取時間を正しく入力してください。')
        return false;
      }
    }

    function check(){
    var order_flag = document.getElementById('order_flag').value;
    var flag = document.getElementById('flag').value;
        if(order_flag < flag){
        console.log(order_flag);
        console.log(flag);
        alert('正しい注文状況を選んで下さい');
            return false;
        }
        
    }
    // // アコーディオン
    // var target = document.querySelector('.target')
    // var button = document.querySelector('.toggle')
    // button.addEventListener('click', function() {
    // target.classList.toggle('is-hidden')
    // })


    document.addEventListener("DOMContentLoaded",() => {
  const title = document.querySelectorAll('.js-accordion-title');
  
  for (let i = 0; i < title.length; i++){
    let titleEach = title[i];
    let content = titleEach.previousElementSibling;
    titleEach.addEventListener('click', () => {
      titleEach.classList.toggle('is-active');
      content.classList.toggle('is-open');
    });
  }

});

    
</script>

</body>
</html>