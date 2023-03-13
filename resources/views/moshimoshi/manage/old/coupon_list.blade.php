@include('manage/header')
<style>
    .contact_table tr {
       border-bottom: 2px solid #ccc;
    }
    select {
        float:right;
        padding:10px;
        width:242px;
    }
    .padding35 {
            padding-top:35px;
    }
    .orange_btn {
        width:15%;
    }
    @media  screen and (max-width: 600px) {
        .contact_table th,
        .contact_table td{
            display: block;
            width:100%;
            text-align:center;
        }
        .flexs {
            display:block;
        }
        .c_discount {
            font-size:1.5em;
        }
        .contact-body img {
            width:50%;
        }
        .m_t3 {
            text-align:center;
        }
        select {
            float:none;
        }
        .padding35 {
            padding-top:0;
        }
        .orange_btn {
            margin:5% auto;
            width:150px;
        }
    }
</style>

<h1 class="baskets">クーポン一覧</h1>
<div class="admin_page">
    <div class="addition" style="border-radius: 50px;">
        <a href="/admin_manage/">
            <p>管理画面へ</p>
        </a>
    </div>
    <div class="m_t3 t_center">
        <form action="/coupon_edit" name="ad_coupon" method="POST">
            @csrf
            <select name="store_id" id="add_s_id">
                <option selected disabled>店舗選択</option>
                <option value="0">全店舗</option>
                @foreach($stores as $key => $store)
                <option value="{{$store->id}}">{{$store->name}}</option>
                @endforeach
            </select>
            <div class="padding35"></div>
            <div class="orange_btn m_t3" style="border-radius: 50px;">
                    <p onclick="return add_coupon();">クーポン追加</p>
            </div>
        </form>
    </div>

    <div class='tab_coupons w_100 m_top5 moshideli_btm'>
        <span><a href="#start"><p class="start">開催中クーポン</p></a></span>
        <span><a href="#end"><p class="end">終了クーポン</p></a></span>
    </div>
    <div id="start"></div>

    <div class="admin_page">
        <table class="contact_table position">
            @foreach($coupons as $coupon)
            @if($date <= $coupon->to_date)
            <tr>
                <td style="height:auto;" class="contact-body admin_box coupon_padding">
                    <div>
                        <p class="">{{$coupon->s_name}}</p>
                        <p style="color:red;">{{$coupon->from_date}}~{{$coupon->to_date}}</p>
                    </div>
                    <div class="flexs">
                        <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                        <div>
                            <p class="">{{$coupon->title}}</p>
                            <p class="c_discount">{{$coupon->discount}}</p>
                        </div>
                    </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        @if($date < $coupon->to_date)
                            <a href="/coupon_edit/{{$coupon->id}}" class="p_5 btn btn--orange btn--cubic btn--shadow">編集</a>
                            @if($date < $coupon->from_date)
                                <a href ="/coupon_delete/{{$coupon->id}}" onclick="return really_delete();" class="p_5 btn btn--black btn--cubic btn--shadow">削除</a>
                            @endif
                        @endif
                    </div>
                </th>
            </tr>
            @endif
            @endforeach
        </table>
    </div>

    <div class='tab_coupons w_100 m_top5 moshideli_btm'>
        <span><a href="#start"><p class="end">開催中クーポン</p></a></span>
        <span><a href="#end"><p class="start">終了クーポン</p></a></span>
    </div>
    <div id="end"></div>

    <div class="admin_page">
            <table class="contact_table position">
                @foreach($coupons as $coupon)
                @if($coupon->to_date <= $date)
                <tr>
                <td style="height:auto;" class="contact-body admin_box coupon_padding">
                    <div>
                        <p class="">{{$coupon->s_name}}</p>
                        <p style="color:red;">{{$coupon->from_date}}~{{$coupon->to_date}}</p>
                    </div>
                    <div class="flexs">
                        <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                        <div>
                            <p class="">{{$coupon->title}}</p>
                            <p class="c_discount">{{$coupon->discount}}</p>
                        </div>
                    </div>
                </td>
                </tr>
                @endif
                @endforeach
            </table>
        </div>
    </div>

</div>

<script>
        function add_coupon() {
            var add_s_id = document.getElementById('add_s_id').value;
            if(isNaN(add_s_id) === true){
                alert('店舗を選択して下さい。');
                return false;
            }
            document.ad_coupon.submit();// new_cardをsubmit（送信）する
        }
        function really_delete(){
            var result = confirm('本当に削除しますか？');
            if(result) {
                return true;
            } else {
                return false;
            }
        }

</script>

</body>
</html>
