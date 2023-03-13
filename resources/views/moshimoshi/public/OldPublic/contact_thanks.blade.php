@include('public/header',['title' => 'contact thanks','description' => 'contact thanks'])

<main>
	<div class="header_img">
        <a href="/">
            <img  src="{{('img/headerlogo.png')}}">
        </a>
    </div>

	<div class="w_80 moshideli_form">
		<div class="m_top5">
			<p class="bold t_center">お問い合わせありがとうございます。</p>
			<div class="m_top5 mail_line_height">
				<p>ご入力いただいたメールアドレスへ、<br>受付完了メールを送信しました。</p><br>
				<p class="bold">メールアドレス:{{$email}}</p><br>
				<p>担当者から折り返しご連絡差し上げますので、しばらくお待ちください。</p>
				<p>受付完了メールが届かない場合は、迷惑メール設定や受信ドメイン設定などをご確認ください。</p><br>
				<p><a href= "https://mosideli-plus.com/user_guide-pl" style="text-decoration: underline">ご利用ガイド・FAQはこちら</a></p>
			</div>
		</div>
	</div>
</main>

@include('public/footer')