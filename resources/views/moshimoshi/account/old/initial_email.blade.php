@include('public/header')
<main>
	<div class="header_img">
        <a href="/">
            <img  src="{{('/img/headerlogo.png')}}">
        </a>
    </div>
	<div style="margin:2.0em 0;">
		<div class="w_80">
			@if($kind == 1)
			<p class="t_center bold"style="font-size:1.2em; margin-bottom:1em;">
				新規会員登録する
			</p>
			<div class="register t_center p_btm3">
				<img src ="{{url('img/touroku.png')}}">
			</div>
			@elseif($kind == 3)
			<p class="t_center bold"style="font-size:1.2em; margin-bottom:1em;">
				配達員登録
			</p>
			@endif
			@include('message')
			<form action="{{url('initial_email')}}" method="POST">
				@csrf
					<div class="new">
						<input name="email" class="textspace" type="email" placeholder="メールアドレス" required>
						<input name="kind" type="hidden" value="{{$kind}}" required>
					</div>
					@if($kind != 3)
					<div class="policy m_top3 bold">
						<p style="font-size:0.94em;">
							<input type="checkbox" required>
							<a href= "https://mosideli-plus.com/"><span style="color:#299e3a;">「利用規約」</span></a>に同意します。
						</p>
						<p style="font-size:0.7em;">※利用規約をお読みの上、チェックを入れてください</p>
					</div>
					@endif
					<div class="t_center" style="margin-top:1.5em;">
						<button style="font-size:1.0em;" class="loginpage_btn_a" type= "submit" value="" >新規会員登録</button>
					</div>
			</form>
			<div class="t_center">
				<p style="font-size:1.0em; margin-top:2.5em;">会員の方はこちら</p>
			</div>
			<a href="/login">
				<div class="t_center">
						<p style="font-size:1.0em;background: #299e3a; border:#299e3a" class="loginpage_btn_a">ログイン</p>
				</div>
			</a>
		</div>
	</div>
</main>
@include('public/footer')