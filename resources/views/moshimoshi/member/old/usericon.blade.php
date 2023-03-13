@include('public/header')
<section class="desc_w60">  
    <div class="b_gray"> 
        <div class="p_3">
            <div class="change_page">
                <!-- <div class="flex"> -->
                    <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>

                    <h1>ユーザーアイコンの変更</h1>
                <!-- </div> -->
                <div class="done">
                        <h3>登録済みアイコン</h3>
                        <input type="text" name="" class="textspace" placeholder="もしもし　花子"/>
                </div>

                <div class="new">
                        <h3>新しいユーザーアイコン</h3>
                        <input type="file" name="" class="textspace" />
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