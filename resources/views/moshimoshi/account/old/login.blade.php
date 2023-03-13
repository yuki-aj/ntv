@include('public/header',['title' => 'login','description' => 'login'])
<main class="">
    <div class="header_img">
        <a href="/">
            <img  src="{{('img/headerlogo.png')}}">
        </a>
    </div>
	<div class="w_80" style="margin:2.5em auto">
		<p class="t_center bold"style="font-size:1.2em; margin-bottom:2em;">
			もしデリIDでログイン
		</p>
		@include('message')
		<form class="h-adr" action="/login" method="POST">
			@csrf
			<div class="new">
				<input class="textspace" id="email" name="email" type="text" placeholder="メールアドレス" required>
			</div>
			<div class="new" style="margin-top:1em;">
					<input class="textspace pass-check" id="password" name="password" type="password" placeholder="パスワード" required>
			</div>
			<div class="t_center" style="margin-top:2.0em;">
				<button style="font-size:1.0em;" class="loginpage_btn_a" type= "submit" value="" >ログイン</button>
			</div>
		</form>
	
		<div class="t_center" style="margin-top:1em;">
			<p class="" style="font-size:1.0em; margin-top:2.5em;">新規会員登録する</p>
			<a href="/initial_email/1">
				<div class="t_center">
					<p style="font-size:1.0em;background: #299e3a; border:#299e3a" class="loginpage_btn_a">新規会員登録</p>
				</div>
			</a>
		</div>

		<div class="login_password t_center" style="margin-top:2em;">
			<h4><span><a class="forget" href="/reset_password">パスワードを忘れた方はこちら</a></span></h4>
		</div>
		<div class="login_password t_center" style="margin-top:1.0em;">
			<h4><span><a class="forget" href="/contact">メールアドレスを忘れた方はこちら</a></span></h4>
		</div>
	</div>
</main>
@include('public/footer')