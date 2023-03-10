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
          margin-top: 10px;
        }

        .accordion__container {
          width: 300px;
          margin: 0 auto;
        }

        .accordion__title {
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
          right: 10%;
        }

        .accordion__title::after {
          transform: rotate(90deg);
          transition-duration: .3s;
          content: '';
        }
        .accordion__title.is-active::before {
          opacity: 0;
        }
        .accordion__title.is-active::after {
          transform: rotate(0);
        }
        .accordion__content {
          padding: 0 1.5em;
          line-height: 0;
          height: 0;
          overflow: hidden;
          opacity: 0;
          transition-duration: .3s;
        }
        .accordion__content.is-open {
          padding: 0;
          line-height: normal; /* number??????????????????*/
          height: auto;
          opacity: 1;
        }
        .style_none {
          font-size:1.5em;
        }
  }
</style>

<h1 class="baskets">{{$store->name}}</h1>

  <div class="admin_page">
      <div class="addition gap m_t3"  style="border-radius: 50px;">
          <a href="/store_information/{{Session::get('s_id')}}">
              <p>???????????????</p>
          </a>
      </div>
  </div>

  <!-- ?????????????????? -->
  <form class="form-table order_search" action="/shop_order_search/{{Session::get('s_id')}}" method="GET">
    @csrf
      <h1 style="text-align: center; margin:20px 0 0; color:#000;">??????????????????</h1>
      <div class="order_width">
        <div class="flexbox m_top3">
          <label for="u_name">????????????</label>
          <input class="order_text" name="u_name" type="text" value="{{isset($request->u_name) ? $request->u_name : ''}}" placeholder="?????????????????????">
        </div>
        <div class="flex m_top3">
          <label>?????????</label>
          <input class="order_date" name="from" type="date" value="{{isset($request->from) ? $request->from : ''}}">???
          <input class="order_date" name="to" type="date" value="{{isset($request->to) ? $request->to : ''}}">
        </div>
        <div class="flex admin_gap m_top3" style="flex-wrap:wrap;">
          <select class="order_select" name="delivery_date">
              <option value="">?????????</option>
              <option value="1" {{isset($request->delivery_date)&&($request->delivery_date==1) ? 'selected' : ''}}>?????????????????????</option>
              <option value="2" {{isset($request->delivery_date)&&($request->delivery_date==2) ? 'selected' : ''}}>?????????????????????</option>
          </select>
          <select class="order_select" name="corporation_flag">
              <option value="">????????????</option>
              <option value="1" {{isset($request->corporation_flag)&&($request->corporation_flag==1) ? 'selected' : ''}}>??????</option>
              <option value="2" {{isset($request->corporation_flag)&&($request->corporation_flag==2) ? 'selected' : ''}}>?????????</option>
              <option value="3" {{isset($request->corporation_flag)&&($request->corporation_flag==2) ? 'selected' : ''}}>????????????</option>
          </select>
          <select class="order_select" name="order_flag">
              <option value="">????????????</option>
              <option value="1" {{isset($request->order_flag)&&($request->order_flag==1) ? 'selected' : ''}}>????????????</option>
              <option value="2" {{isset($request->order_flag)&&($request->order_flag==2) ? 'selected' : ''}}>????????????</option>
          </select>
          <select class="order_select" name="pay_kind">
              <option value="">???????????????</option>
              <option value="1" {{isset($request->pay_kind)&&($request->pay_kind==1) ? 'selected' : ''}}>?????????</option>
              <option value="2" {{isset($request->pay_kind)&&($request->pay_kind==2) ? 'selected' : ''}}>au PAY</option>
              <option value="3" {{isset($request->pay_kind)&&($request->pay_kind==3) ? 'selected' : ''}}>??????</option>
          </select>
        </div>
      </div>
      <div class="categories flexbox">
        <div class="flexbox searchbutton-box">
          <input class="addition" style="font-size:0.8em; border-radius: 50px;" type="submit" value="????????????" class="searchbutton">
        </div>
      </div>
      <div class="categories flexbox">
        <div class="flexbox margin10">????????????<span style="font-weight:bold; font-size:24px;"> {{$lists->total()}} </span>???</div>
      </div>
  </form>
  <div class="margin10 flexbox">{{$lists->appends(request()->input())->links() }}</div>


<!-- ????????? -->
<div class="responsive">
@foreach ($lists as $orderlist)
  <div class="accordion">
    <div class="option">
      <table class="info-table1">
        <tr>
          <th colspan="4">?????????</th>
          <th colspan="4">????????????</th>
          <th colspan="4">??????</th>
        </tr>
        <tr>
          <td colspan="4">{{$orderlist->delivery_date}}</td>
          <td colspan="4">{{$orderlist->catch_time}}</td>
          @if($orderlist->detail == '????????????')
          <td colspan="4" style="color:red; font-weight:bold;">{{$orderlist->detail}}</td>
          @else
          <td colspan="4" style="color:blue;">{{$orderlist->detail}}</td>
          @endif
        </tr>
      </table>
      <table class="info-table2">
          <tr>
            <th colspan="4">??????</th>
            <th colspan="4">??????</th>
            <th colspan="4">??????</th>
          </tr>
          <tr>
            <td colspan="4">{{$orderlist->delivery_time}}</td>
            <td colspan="4">{{$orderlist->kind}}</td>
            <td colspan="4">{{$orderlist->charge}}</td>
          </tr>
      </table>
      <table class="info-table2">
        <tr>
          <td colspan="1" class="bold" style="letter-spacing: 3px; writing-mode: vertical-rl; background: rgb(240, 225, 200);">?????????</td>
          <td colspan="6" class="t_left"><span style="font-size:1.3em;">{{$orderlist->d_name}}</span><br>{{$orderlist->d_address}}<br><span style="color:blue;">{{$orderlist->d_tel}}</span></td>
        </tr>
      </table>
      <div class="accordion__content">
        <table class="info-table2">
          <tr>
            <td colspan="1" class="bold" style="letter-spacing: 3px; writing-mode: vertical-rl; background: rgb(240, 225, 200);">?????????</td>
            <td colspan="6" class="t_left"><span style="font-size:1.3em;">{{$orderlist->u_name}}</span><br>{{$orderlist->o_address}}<br><span style="color:blue;">{{$orderlist->o_tel}}</span></td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
              <th colspan="5">????????????</th>
          </tr>
          <?php $sum = 0;?>
          @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id != 16 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D')
          <tr>
              <td colspan="4"><div class="flex"><div>{{$order_detail['p_name']}}</div><div>?????{{$order_detail->quantity}}</div></div></td>
              <td colspan="1">{{number_format($order_detail['p_price'])}}???</td>
          </tr>
          <tr>
              <td colspan="1">
                @if(isset($order_detail->o_1_name) && $order_detail->o_1_name != '')
                  {{$order_detail->o_1_name}} {{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}???
                @else
                0???
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_2_name) && $order_detail->o_2_name != '')
                  {{$order_detail->o_2_name}} {{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}???
                @else
                0???
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_3_name) && $order_detail->o_3_name != '')
                {{$order_detail->o_3_name}} {{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}???
                @else
                0???
                @endif
              </td>
              <td colspan="1">
                @if(isset($order_detail->o_4_name) && $order_detail->o_4_name != '')  
                  {{$order_detail->o_4_name}} {{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}???
                @else
                0???
                @endif
              </td>
              <td colspan="1" style="color:blue;">
                {{number_format($order_detail['subtotal'])}}???
                <?php $sum = $sum + $order_detail['subtotal'];?>
              </td>
          </tr>
          @endif
          @endforeach
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">????????????</td>
              <td colspan="3" class="t_left">{{$orderlist->note[0]}}</td>
              <td colspan="1"></td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">????????????</td>
              <td colspan="3" class="t_left">{{$orderlist->coupon_title}}</td>
              <td colspan="1" style="color:red;">
                  @if(isset($orderlist) && $orderlist->coupon_discount)
                    {{$orderlist->coupon_discount}}???
                    <?php $sum = $sum + $orderlist->coupon_discount;?>
                  @else
                    0???
                  @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">??????</td>
              <td colspan="3"></td>
              <td colspan="1">
                @foreach($order_details as $key => $order_detail)
                  @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 16)
                  {{number_format($order_detail['postage'])}}???
                  <?php $sum = $sum + $order_detail['postage'];?>
                  @endif
                @endforeach
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">??????</td>
              <td colspan="3"></td>
              <td colspan="1" class="bold">
              {{number_format($sum)}}???
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">??????</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[2])  && $orderlist->note[2] != '')
                {{$orderlist->note[2]}}
                @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background:rgb(240, 225, 200);">???????????????</td>
              <td colspan="4" class="t_left">
                @if(isset($orderlist->note[1]) && $orderlist->note[1] != '')
                  {{$orderlist->note[1]}}
                @endif
              </td>
          </tr>
          <tr>
              <td colspan="1" style="background: rgb(240, 225, 200);">?????????</td>
              <td colspan="4">
                {{$orderlist->d_staff_id}}
              </td>
          </tr>
        </table>
        <table class="info-table2">
          <tr>
            <th colspan="4">??????ID ???5???</th>
            <th colspan="4">????????????</th>
            <th colspan="4">????????????</th>
          </tr>
          <tr>
            <td colspan="4">{{$orderlist->new_o_id}}</td>
            <td colspan="4">{{$orderlist->created_a_t}}</td>
            <td colspan="4">
              <form class="order_flag" action="/shop_order_search/{{Session::get('s_id')}}" method="POST">
                @csrf
                <select id="shop_order_flag" name="order_flag" style="margin-bottom:10px;">
                    <option value disabled>????????????</option>
                    <option value="1"{{$orderlist->order_flag==1 ? 'selected' : ''}}>????????????</option>
                    <option value="2"{{$orderlist->order_flag==2 ? 'selected' : ''}}>????????????</option>
                </select><br>
                <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
                <input type="hidden" id="shop_flag" name="flag" value="{{$orderlist->order_flag}}">                   
                <input onclick="return check();"  type="submit" value="????????????" class="">
              </form>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="bold" style="background: rgb(240, 225, 200);">????????????</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[1]))
              {{$orderlist->status_time[1]}}
            @endif            
            </td>
          </tr>
          <tr>
            <td colspan="4" class="bold" style="background: rgb(240, 225, 200);">????????????</td>
            <td colspan="8">
            @if(isset($orderlist->status_time[2]))
              {{$orderlist->status_time[2]}}
            @endif            
            </td>
          </tr>
        </table>  
      </div>

      <!-- ?????????????????????????????? -->
      <div class="accordion__title js-accordion-title">
        <table class="info-table2">
          <tr>
            <td colspan="12" style="background:#f2f2f2;">
              <button type="button" class="style_none">???????????????</button>
            </td>
          </tr>
        </table>
      </div>

    </div>
  </div>
@endforeach
</div>

<!-- ????????? -->
@foreach ($lists as $orderlist)
<div class="m_top5 large_screen">
  <div class="w_90">
    <table class="info-table1 t_center">
      <tr>
        <th colspan="4">?????????</th>
        <th colspan="4">??????</th>
        <th colspan="8">?????????</th>
        <th colspan="8">?????????</th>
        <th colspan="4">????????????</th>
        <th colspan="4">??????</th>
        <th colspan="4">??????</th>
        <th colspan="4">??????ID ???5???</th>
      </tr>
      <tr>
          <td colspan="4">{{$orderlist->delivery_date}}</td>
          <td colspan="4">{{$orderlist->delivery_time}}</td>
          <td colspan="8" class="t_left">{{$orderlist->d_name}}<br>{{$orderlist->d_address}}<br>{{$orderlist->d_tel}}</td>
          <td colspan="8" class="t_left">{{$orderlist->u_name}}<br>{{$orderlist->o_address}}<br>{{$orderlist->o_tel}}</td>
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

    <table class="info-table2 t_center">
      <tr>
        <th colspan="4">??????</th>
        <th colspan="4">????????????</th>
        <th colspan="4">????????????</th>
        <th colspan="8">?????????</th>
        <th colspan="8">????????????</th>
        <th colspan="8">????????????</th>
        <th colspan="4">???????????????</th>
      </tr>
      <tr>
          @if($orderlist->detail == '????????????')
          <td colspan="4" style="color:red; font-weight:bold;">{{$orderlist->detail}}</td>
          @else
          <td colspan="4">{{$orderlist->detail}}</td>
          @endif
          <td colspan="4">{{$orderlist->catch_time}}</td>
          <td colspan="4"> 
            <form class="order_flag" action="/shop_order_search/{{Session::get('s_id')}}" method="POST">
              @csrf
              <select id="order_flag" name="order_flag" style="margin-bottom:10px;">
                  <option value disabled>????????????</option>
                  <option value="1"{{$orderlist->order_flag==1 ? 'selected' : ''}}>????????????</option>
                  <option value="2"{{$orderlist->order_flag==2 ? 'selected' : ''}}>????????????</option>
              </select><br>
              <input type="hidden" name="o_id" value="{{$orderlist->o_id}}">
              <input type="hidden" id="flag" name="flag" value="{{$orderlist->order_flag}}">                   
              <input onclick="return check();"  type="submit" value="????????????" class="">
            </form>
          </td>
          <td colspan="8">
            @if(isset($orderlist->d_staff_id))
              {{$orderlist->d_staff_id}}
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
    <table class="info-table2 t_center">
      <tr>
        <th colspan="44">????????????</th>
        <th colspan="4"></th>
      </tr>
      <tr>
        <th colspan="7">?????????</th>
        <th colspan="5">??????</th>
        <th colspan="7">OP1</th>
        <th colspan="7">OP2</th>
        <th colspan="7">OP3</th>
        <th colspan="7">OP4</th>
        <th colspan="4">??????</th>
        <th colspan="4">??????</th>
      </tr>
      <?php $sum = 0;?>
        @foreach($order_details as $key => $order_detail)
        @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id != 16 && $order_detail->product_id != 'P' && $order_detail->product_id != 'A' && $order_detail->product_id != 'S' && $order_detail->product_id != 'D')
        <tr>
          <td colspan="7">{{$order_detail['p_name']}}</td>
          <td colspan="5">{{number_format($order_detail['p_price'])}}???</td>
          <td colspan="7">
          @if(isset($order_detail->o_1_name) && $order_detail->o_1_name != '')
            {{$order_detail->o_1_name}} {{$order_detail->o_1_note}} {{number_format($order_detail->o_1_price)}}???
          @else
          @endif
          </td>
          <td colspan="7">
          @if(isset($order_detail->o_2_name) && $order_detail->o_2_name != '')
            {{$order_detail->o_2_name}} {{$order_detail->o_2_note}} {{number_format($order_detail->o_2_price)}}???
          @else
          @endif
          </td>
          <td colspan="7">
          @if(isset($order_detail->o_3_name) && $order_detail->o_3_name != '')
            {{$order_detail->o_3_name}} {{$order_detail->o_3_note}} {{number_format($order_detail->o_3_price)}}???
          @else
          @endif
          </td>
          <td colspan="7">
          @if(isset($order_detail->o_4_name) && $order_detail->o_4_name != '')  
            {{$order_detail->o_4_name}} {{$order_detail->o_4_note}} {{number_format($order_detail->o_4_price)}}???
          @else
          @endif
          </td>
          <td colspan="4">{{$order_detail->quantity}}</td>
          <td colspan="4">{{number_format($order_detail['subtotal'])}}???</td>
          <?php $sum = $sum + $order_detail['subtotal'];?>

        </tr>
        @endif
        @endforeach
      <tr>
        <td colspan="7"></td>
        <td colspan="19"></td>
        <th colspan="7">????????????</th>
        <td colspan="11">{{$orderlist->coupon_title}}</td>
        <td colspan="4" style="color:red;">
          @if(isset($orderlist) && $orderlist->coupon_discount)
            {{$orderlist->coupon_discount}}???
            <?php $sum = $sum + $orderlist->coupon_discount;?>
          @else
            0???
          @endif
        </td>
      </tr>
      <tr>
        <th colspan="7">??????</th>
        <td colspan="19" class="t_left">      
            @if(isset($orderlist->note[2])  && $orderlist->note[2] != '')
            {{$orderlist->note[2]}}
            @else
            ??????
            @endif
        </td>
        <th colspan="7">??????</th>
        <td colspan="11"></td>
        <td colspan="4">
        @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 16)
            {{number_format($order_detail['postage'])}}???
            <?php $sum = $sum + $order_detail['postage'];?>
            @endif
        @endforeach
        </td>
      </tr>
      <tr>
      <th colspan="7">????????????????????????</th>
        <td colspan="19" class="t_left">
          @if(isset($orderlist->note[1]) && $orderlist->note[1] != '')
            {{$orderlist->note[1]}}
          @else
            ??????
          @endif
        </td>
        <th colspan="7">??????</th>
        <td colspan="11"></td>
        <td colspan="4">
        @foreach($order_details as $key => $order_detail)
          @if($orderlist->o_id == $order_detail->order_id && $order_detail->product_id == 'D')
            {{number_format($order_detail->price)}}???
            <?php $sum = $sum + $order_detail->price;?>
            @endif
        @endforeach
        </td>
      </tr>
      <tr>
        <td colspan="7"></td>
        <td colspan="19"></td>
        <th colspan="7">????????????</th>
        <td colspan="11"></td>
        <td colspan="4" class="bold">{{number_format($sum)}}???</td>
      </tr>
    </table>
  </div>
</div>
@endforeach

<div class="flexbox m_top3 m_btm2 padding10 margin10">{{ $lists->appends(request()->input())->links() }}</div>


<script>
  
  $(function(){
    $(document).on("click", "button", function(){
        var text = $(this).text();
        $("button").text("???????????????");
        if (text === "???????????????") {
            $(this).text("?????????");
        }
    });
});
    function check(){
    var order_flag = document.getElementById('shop_order_flag').value;
    var flag = document.getElementById("shop_flag").value;
        if(order_flag < flag){
          console.log(order_flag);
          console.log(flag);
          alert('??????????????????????????????????????????');
          return false;
        }
    }


    //?????????????????????
    document.addEventListener("DOMContentLoaded",() => {
    const title = document.querySelectorAll('.js-accordion-title');
      
      for (let i = 0; i < title.length; i++){
        let titleEach = title[i];
        let content = titleEach.previousElementSibling;
        var flag = "close"; //flag?????????????????????
        titleEach.addEventListener('click', () => {
          titleEach.classList.toggle('is-active');
          content.classList.toggle('is-open');
          if(flag == "close"){ //??????flag???close????????????
            $(this).text("CLOSE");
            flag = "open"; //flag???open?????????
          }
          else{ //??????flag???open????????????
            $(this).text("MORE");
            flag = "close"; //flag???close?????????
          }
        });
      }

    });
</script>

</body>
</html>