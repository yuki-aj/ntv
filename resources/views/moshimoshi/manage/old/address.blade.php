@include('public/header')
<section class="desc_w60">  
    <div class="b_gray">
        <div class="p_3">
            <div class="change_page">
                    <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>
                    <h1>住所の変更</h1>
                <div class="done">
                        <h3>登録済み住所</h3>
                        <input type="text" name="名前" class="textspace"placeholder="東京都多摩市落合２丁目３８番地１０３号"/>
                    </div>
                <div class="new">
                        <h3>新しい住所</h3>
                        <input type="text" name="名前" class="textspace" />
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