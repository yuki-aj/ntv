@include('public/header',['title' => 'reset_password','description' => 'reset_password'])
<main>
	<div class="header_img">
        <a href="/">
            <img  src="{{('img/headerlogo.png')}}">
        </a>
    </div>
	<div class="m_top3" style="margin-bottom:7.0em;">
		<div class="login_w_80">
			<div class="login_box p_btm3">
				<h1>パスワード変更</h1>
				<div class="t_center p_btm3">
					<h4>※登録いただいたメールアドレスを入力してください。</h4>
				</div>
				@include('message')
				<form class="h-adr" action="reset_password" method="POST">
					@csrf
					<div class="login">
						<div class="admin_flex" style="justify-content:center;">
							<label for="email"><p>メールアドレス</p></label>
							<input class="padding10" id="email" name="email" type="text" placeholder="account@example.com" required>
						</div>
					</div>
					<div class="t_center m_top5">
						<input class="addition" type= "submit" value="送信">
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
@include('public/footer')