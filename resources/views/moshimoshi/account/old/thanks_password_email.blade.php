@include('public/header',['title' => 'thanks_password_email','description' => 'thanks_password_email'])
<main>
	<div class="header_img">
        <a href="/">
            <img  src="{{('img/headerlogo.png')}}">
        </a>
    </div>
	<div class="m_top5 moshideli_btm">
		<div class="w_80">
			<h1>メールアドレスのご記入、<br>ありがとうございます。</h1>
			<div class="mail_line_height">
				<p>いただいたメールアドレスへ、メールを送信しました。</p><br>
				<p class="bold">メールアドレス: {{$email}}</p><br>
				<p>現時点では、パスワード変更は完了していません。<br>
				<p>送信されたメールのリンクより、変更に必要な情報をご入力ください。<P><br>
				<p><span class="bold">URLの有効時間は、メール送信後1時間となっております。</span><br>
				1時間を過ぎた場合は、恐れ入りますが、メールアドレスのご登録を再度行っていただく必要がございます。
				<p>
				<br>
				<p>メールが届かない場合は、迷惑メール設定や受信ドメイン設定などをご確認ください。
				<p><a href= "https://mosideli-plus.com/user_guide-pl" style="text-decoration: underline">お問い合わせ・ご利用ガイド・FAQはこちら</a></p>
			</div>
		</div>
	</div>
</main>
@include('public/footer')