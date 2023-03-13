@include('public/header')
<section class="m_btm_1em">
    @if($store != null)
    <a href="/shop/{{$store->id}}">
        <div class="product_baskets">
            <div class="flex">
                <div><span class="left material-symbols-outlined" style="font-size:1.2em;">arrow_circle_left</span></div>
                <div class="bold">{{$store->name}}<br>対象クーポン一覧</div>
                <div></diV>
            </div>
        </div>
    </a>
    @else
    <a href="/search">
        <div class="coupon_list">
            <div class="flexses">
                <button style=""class="btn-circle1"><</button>
                <h1>{{$store->name}}<br>対象クーポン一覧</h1>
            </div>
        </div>
    </a>
    @endif
    @if(session('flash_message'))
        <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
    @endif
    @if(isset($coupons))
    @foreach($coupons as $coupon)
        <div class="user_coupon">
            <div class="btm_em">
                <p class="bold" style="color:#000; font-size:1.4em">{{$coupon->title}}</p>
            </div>
            <div class="t_left w_90">
                <div class="t_center">
                <a href="/add_coupon/{{$coupon->coupon_hash}}">
                    <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                </a>
                </div>
                <div>
                    <p class="m_top5" style="font-size:1.0em;">注文金額から {{$coupon->discount}}OFF</p>
                    <p class="coupon_time" style="font-size:1.0em;">期限:{{$coupon->from_date}}～{{$coupon->to_date}}</p>
                </div>
                <a href="/add_coupon/{{$coupon->coupon_hash}}">
                    <div class="addition" style="box-shadow:none; margin-top:1.5em;">
                        <p>クーポン取得</p>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
    @else 
    <div class="flexbox margin10">対象クーポンはありません。</div>
    @endif
</section>
@include('public/footer')