<form action="{{url('add_cart')}}" method="POST" onsubmit="return add_cart()">
    @csrf
    <input type="hidden" name="s_id" value="{{$product->s_id}}">
    <input type="hidden" name="p_id" value="{{$product->id}}">
    <div>
        <div>商品名  {{$product->name}}</div>
        <div><img style="width:200px;" src="{{url($product->img)}}"></div>
        <div>価格  {{$product->price}}円</div>
        <label>購入個数</label>
        <div class="">
        @if(Session::has('carts') && $product->id == isset($cart[$product->id]['p_id']))
        <!--sessionにカートがあって、尚且つプロダクトidとsessionのプロダクトidが同じだったらカート内の個数を表示する -->
        <input id="quantity" type="number" value="{{$cart[$product->id]['quantity']}}" name="quantity">
        @else
        <input id="quantity" type="number" pattern="^[0-9]+$" value="1" name="quantity">
        @endif
    </div>
        <lavel>個</lavel><br>
        <div>
            <button type="submit">カートへ追加</button>
        </div>
    </div>
</form>
<script>
    //0個で送った場合の処理
    function add_cart() {
        var quantity = document.getElementById('quantity').value;
        if(quantity <= 0){
            alert('個数を入力してください');
            return false;
        }
    }
</script>