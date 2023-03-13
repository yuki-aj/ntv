@include('public/header',['title' => 'new_password','description' => 'new_password'])
<main class="moshideli_btm">
	<div class="header_img">
        <a href="/">
            <img  src="{{('/img/headerlogo.png')}}">
        </a>
    </div>
	<div class="m_top3">
		<div class="w_80">
			<div class="login_box p_btm3">
				<h1>パスワード変更</h1>
				<div class="t_center">
					<h4>※新しいパスワードを設定して下さい。</h4>
				</div>
				@include('message')
				<form class="h-adr" action="{{url('new_password')}}" method="post" onsubmit="return check_form()">
					@csrf
					<div class="login ">
						<div class="flex margin10">
							<label for="email">パスワード</label>
							<input class="padding10" name="password" type="password" placeholder="※8文字以上で入力してください。" required>
						</div>
						<div class="flex margin10">
							<label for="password">パスワード(確認)</label>
								<input class="padding10 pass-check"  name="password2" type="password" placeholder="※8文字以上で入力してください。" required>
						</div>
					</div>
					<div class="t_center m_top5">
							<input class="addition" type= "submit" value="変更する">
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
<script>
    function check_form(){
      var password_check = document.querySelectorAll('.pass-check');
      if(password_check[0].value != password_check[1].value){
        alert("パスワードが一致していません。");
        return false;
      }
	}
</script>
@include('public/footer')