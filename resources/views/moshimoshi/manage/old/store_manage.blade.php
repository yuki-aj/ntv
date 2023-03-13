@include('manage/header')
<style>
    @media  screen and (max-width: 420px) {
        .manage_img img {
            width:60%;
        }
    }
</style>
<h1 class="baskets">店舗管理画面</h1>

<div class="admin_page moshideli_btm">
    <div class="addition gap m_t3" style="border-radius: 50px;">
        <a href="/admin_manage/">
            <p>管理画面へ</p>
        </a>
    </div>
    <div class="addition gap m_t3" style="border-radius: 50px;">
        <a href="/store_information/0">
            <p>店舗追加</p>
        </a>
    </div>
</div>

<!-- 登録店舗一覧 -->
<div class="w_80">
    <table class="contact_table position">
        <h1 class="t_center">登録店舗一覧</h1>
        @foreach($stores as $store)
        @if($store->store_status==1)
        <div class="w_65">
            <tr>
                <td class="contact-body" style="width:70%;">
                    <h1 class="left">{{$store->name}}</h1>
                    <div class="flexs m_tb3 manage_img">
                        <div>
                            <img  src="/storage/store_image/{{isset($store->id)? $store->id : '0'}}-0.jpg">
                        </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/store_information/{{$store->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                        <a href ="/store_delete/{{$store->id}}" class="btn btn--black btn--cubic btn--shadow">無効</a>
                    </div>
                </th>
            </tr>
        </div>
        @endif
        @endforeach
    </table>
</div>

<!-- 休眠店舗一覧 -->
<div class="w_80">
    <table class="contact_table position">
        <h1 class="t_center">休眠店舗一覧</h1>
        @foreach($stores as $store)
        @if($store->store_status==0)
        <div class="w_65">
            <tr>
                <td class="contact-body" style="width:70%;">
                    <h1 class="left">{{$store->name}}</h1>
                        <div class="flexs m_tb3 manage_img">
                            <img src="/storage/store_back_image/{{ $store->id }}.jpg">
                        </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/store_information/{{$store->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                        <a href ="/store_delete/{{$store->id}}" class="btn btn--orange btn--cubic btn--shadow">有効</a>
                    </div>
                </th>
            </tr>
        </div>
        @endif
        @endforeach
    </table>
</div>

</body>
</html>