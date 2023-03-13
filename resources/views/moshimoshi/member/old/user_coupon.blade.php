@include('public/header')
    <a href="/mypage">
    <div class="product_baskets">
        <div class="flex">
            <div><span class="left material-symbols-outlined" style="font-size:1.2em; padding-top:0.3em">arrow_circle_left</span></div>
            <div class="bold">取得クーポン一覧</div>
            <div></diV>
        </div>
    </div>
    </a>
    @if(session('flash_message'))
    <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
    @endif
    @if(isset($coupons[0]))
    @foreach($coupons as $coupon)
    <div class="user_coupon">
        <div class="btm_em">
            <p>{{$coupon->s_name}}</p>
            <p class="bold" style="color:#000; font-size:1.4em">{{$coupon->title}}</p>
        </div>
        <div class="t_left w_90">
            <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
            <div>
                <p class="m_top5" style="font-size:1.0em;">注文金額から {{$coupon->discount}}OFF</p>
                <p class="coupon_time" style="font-size:1.0em;">有効期限:{{$coupon->to_date}}</p>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="flexbox margin10">取得済みクーポンはありません。</div>
    @endif

@include('public/footer')