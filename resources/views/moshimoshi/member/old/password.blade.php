@include('public/header')

<section>  
    <div class="product_baskets">
        <div class="flex">
            <a href="/mypage">
            <div><span style="font-size:1.2em; padding-top:0.3em" class="left material-symbols-outlined">arrow_circle_left</span></div>
            </a>
            <div class="bold">パスワードの変更</div>
            <div></diV>
        </div>
    </div>
    <div class="basic_information">
        @include('message')
        <form id="r_change" action="{{ url('password') }}" method="post">
            @csrf
            <div class="done">
                <h3>登録済みパスワード</h3>
                <input type="password" name="current_password" class="textspace" placeholder="xxxxxxxx" required/>
            </div>
            <div class="new">
                <h3>新しいパスワード</h3>
                <input type="password" name="new_password" class="textspace" placeholder="xxxxxxxx" required/>
            </div>
            <div class="new">
                <h3>新しいパスワード（確認用）</h3>
                <input type="password" name="new_password2" class="textspace" placeholder="xxxxxxxx" required/>
            </div>
            <div class="t_center m_top5">
                <input class="addition" onclick="return really_change();" type="submit" value="変更する">
            </div>
        </form>
    </div>
</section>

<script>
     function really_change(){
        var result = confirm('本当に変更しますか？');
        if(result) {
            document.querySelector('#r_change').submit();
        } else {
            return false;
        }
    }
</script>

@include('public/footer')