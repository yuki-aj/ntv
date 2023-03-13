@include('manage/header',['title' => 'search_user','description' => 'search_user'])

<style>
    .order_select {
        margin:0 auto;
    }
    .margin {
        margin:10px auto;
    }
</style>

<!-- <div class="admin_page"> -->
    <h1 class="baskets">クーポン追加</h1>
    <form id="coupon_check" method="post" action="/add_coupon">
        @csrf
            <select id="coupon" class="order_select flexbox" name="coupon_id">
            <option value="" disabled selected>クーポン選択</option>
            @foreach($coupons as $coupon)
            <option value="{{$coupon->id}}">{{$coupon->title}}</option>
            @endforeach
            </select>
            @foreach($u_ids as $key => $u_id)
            <input type="hidden" name="u_ids[]" value="{{$u_id}}">
            @endforeach
            <input class="margin orange flexbox" type="submit" value="付与する" onclick="return coupon_check();">
    </form>
<!-- </div> -->

<script>
    function coupon_check() {
        var coupon = document.getElementById('coupon').value;
        console.log(coupon);
        if(!coupon){
            alert('クーポンを選択してください。');
            return false;
        }
    }
</script>

</body>
</html>