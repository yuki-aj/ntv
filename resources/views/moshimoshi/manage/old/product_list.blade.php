@include('manage/header')
<style>
    .contact-body img {
        width:100%;
    }
    .admin_flex a {
        width:20%;
    }
</style>

<h1 class="baskets">商品一覧</h1>
<div class="admin_page">
    <table class="contact_table position">
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/store_information/{{$s_id}}">
                <p>店舗管理へ</p>
            </a>
        </div>
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/product_edit/{{$s_id}}/0">
                <p>商品追加</p>
            </a>
        </div>
        @foreach($products as $product)
        @if($product->p_status == 0)
        <tr class="product_hidden">
        @else
        <tr>
        @endif
        <!-- <tr> -->
            <td class="contact-body admin_box">
            @if($product->p_status == 0)
                <div class="blur admin_flex admin_gap" style="flex-wrap:nowrap;">
                    <a target="_blank" style="display: inline-block;" href="/storage/product_image/{{ $product->id }}_original.{{isset($product->extension)? $product->extension : ''}}">
                        <img src="/storage/product_image/{{ $product->id }}.{{isset($product->extension)? $product->extension : ''}}">
                    </a>    
                    <div>  
                        <div class="flex_shop_edit">
                            <p>{{$product->name}}</p>
                            <p>{{number_format($product->price)}}円</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="admin_flex admin_gap" style="flex-wrap:nowrap;">
                    <a target="_blank" style="display: inline-block;" href="/storage/product_image/{{ $product->id }}_original.{{isset($product->extension)? $product->extension : ''}}">
                        <img src="/storage/product_image/{{ $product->id }}.{{isset($product->extension)? $product->extension : ''}}">
                    </a>
                    <div>  
                        <div class="flex_shop_edit">
                            <p>{{$product->name}}</p>
                            <p>{{number_format($product->price)}}円</p>
                        </div>
                    </div>
                </div>
            @endif
            </td>
            <th>
                <div class="flex admin_gap">
                    <a href="/product_edit/{{$s_id}}/{{$product->id}}" class="p_5 btn btn--orange btn--cubic btn--shadow">編集</a>
                    <a href ="/product_delete/{{$product->id}}" onclick="return really_delete();" class="p_5 btn btn--black btn--cubic btn--shadow">削除</a>
                    <!-- <a href ="/product_delete/{{$product->id}}" onclick="return really_delete();" class="p_5 btn btn--black btn--cubic btn--shadow">削除</a> -->
                </div>
            </th>
        </tr>
        @endforeach
    </table>
</div>

<script>
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