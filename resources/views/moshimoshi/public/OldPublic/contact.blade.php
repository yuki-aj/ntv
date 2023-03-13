@include('public/header')

<div class="header_img">
    <a href="/">
        <img src="{{('img/headerlogo.png')}}">
    </a>
</div>

<section class="m_top3 m_btm2">
	<div class="w_80">
		<h1>お問い合わせ</h1>
		<form action="{{url('contact')}}" method="POST">
			@csrf
				<div class="new m_top3">
					<label for="email">お名前
						<input class="textspace padding10"  name="name" type="text" placeholder="※もしもし　花子" required>
					</label>
				</div>
				<div class="new m_top3">
					<label for="email">メールアドレス</label>
					<input class="textspace padding10" id="email" name="email" type="text" placeholder="※account@example.com" required>
				</div>
				<div class="new m_top3">
					<label for="tel">電話番号</label>
					<input class="textspace padding10" id="tel" name="tel" type="text" placeholder="※09012345678" required>
				</div>
				<div class="new m_top3">
					<label for="password">タイトル</label>
					<select  name="title" class="padding10 w_100" style="text-align:-webkit-center;" required>
						<option disabled selected>タイトルを選択して下さい。</option>
						<option value="ご注文について">ご注文について</option>
						<option value="ご利用方法について">ご利用方法について</option>
						<option value="クーポンについて">クーポンについて</option>
						<option value="会員情報について">会員情報について</option>
						<option value="出店について">出店について</option>
						<option value="その他">その他</option>
					</select>
				</div>
				<div class="new m_top3">
					<label for="password">内容</label>
					<textarea class="textspace padding10 pass-check"  name="information"  placeholder="※お問い合わせ内容を入力してください。" required> </textarea>
				</div>
				<div class="t_center m_top3 moshideli_form">
					<input class="addition" type="submit" value="送信">
				</div>
		</form>
	</div>
</section>

@include('public/footer')