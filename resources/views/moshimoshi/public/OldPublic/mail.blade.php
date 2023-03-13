@include('public/header')

<section class="desc_w60">  
    <div class="b_gray">
        <div class="p_3">
            <div class="change_page">
                    <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>
                    <h1>メールアドレスの変更</h1>
                <div class="done">
                    <h3>登録済みメールアドレス</h3>
                    <input type="mail" name="" class="textspace"placeholder="moshimoshi@delivery.com" />
                </div>
                <div class="new">
                    <h3>新しいメールアドレス</h3>
                    <input type="mail" name="" class="textspace" />
                </div>
                <div class="changeok">
                    <a href="/mypage">
                        <button class="ok_button">
                            <h2>変更する</h2>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@include('public/footer')