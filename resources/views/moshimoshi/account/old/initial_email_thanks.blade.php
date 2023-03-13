@include('public/header',['title' => 'initial email thanks','description' => 'initial email thanks'])
<main>
	<div class="header_img">
        <a href="/">
            <img  src="{{('img/headerlogo.png')}}">
        </a>
    </div>
	<div class="m_top5">
		<div class="w_80 moshideli_btm">
			<h1>メールアドレスをご登録いただき、<br>ありがとうございます。</h1>
			<div class="mail_line_height">
				<p>ご登録いただいたメールアドレスへ、メールを送信いたしました。</p><br>
				<p class="bold">メールアドレス: {{$email}}</p><br>
				<p>現時点では、ご登録は完了していません。<br>
				送信されたメールのリンクより、ご登録に必要な情報をご入力ください。
				</p>
				<br>
				<p><span class="bold">URLの有効時間は、メール送信後1時間となっております。</span><br>
				1時間を過ぎた場合は、恐れ入りますが、メールアドレスのご登録を再度行っていただく必要がございます。
				<p>
				<br>
				<p>メールが届かない場合は、迷惑メール設定や受信ドメイン設定などをご確認ください。</p>
				<br>
				<p><a href= "https://mosideli-plus.com/user_guide-pl" style="text-decoration: underline">お問い合わせ・ご利用ガイド・FAQはこちら</a></p>
			</div>
		</div>
	</div>
</main>
@include('public/footer')