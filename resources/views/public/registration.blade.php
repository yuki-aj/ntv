
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <!-- ▼OGP の設定 -->
  <meta property="og:type" content="">
  <meta property="og:title" content="">
  <meta property="og:description" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <meta property="og:site_name" content="">
  <meta property="og:locale" content="ja_JP">
  <meta property="fb:app_id" content="">
  <!-- ▼Twitter Cards の設定-->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="">
  <link rel="stylesheet" href="assets/css/reset.css">
  <link rel="stylesheet" href="assets/css/common.css">
  <link rel="stylesheet" href="assets/css/style_pc.css" media="screen and (min-width:769px)">
  <link rel="stylesheet" href="assets/css/style_sp.css" media="screen and (max-width:768px)">
  <title></title>
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link rel="apple-touch-icon" href="assets/img/webclipicon.png">
</head>

<body class="login-pages">
  <style>
    .warning .-mesalertsage {
      background-color: red !important;
      color: white !important;
      text-align: center;
    }
    .flash_message {
        color:#fff;
        background:red;
        margin-bottom:2em;
    }
  </style>
  <div class="login-pages-box">
    <form action="{{url('registration')}}" method="post" class="form">
    @csrf  
    <span class="form-circle"><a href="/"><img src="assets/img/logo-sample-green.svg" alt="northvolt logo"></a></span>
      <p class="form-title">REGISTRATION</p>
      @if (session('flash_message'))
          <div class="flash_message">
              {{ session('flash_message') }}
          </div>
      @endif
      <div class="form-input form-name">
        <input name="full_name" id="full_name" type="text" class="form-input-box" placeholder="Full Name" required>
      </div>
      <div class="form-input form-user">
        <input name="user_name" id="user_name" type="text" class="form-input-box" placeholder="Username" required>
      </div>
      <div class="form-input form-mail">
        <input name="email" id="email" type="text" class="form-input-box" placeholder="E-mail" required>
      </div>
      <div class="form-input form-referral">
        <input name="referral_code" id="referral_code" type="text" class="form-input-box" placeholder="Referral Code">
      </div>
      <div class="form-input form-wallet">
        <input name="wallet_address" id="wallet_address" type="text" class="form-input-box" placeholder="Wallet Address" required>
      </div>
      <div class="form-input form-password">
        <input name="password" id="password" type="password" class="pass-check form-input-box" placeholder="Password" required>
        <i class="toggle-pass fa fa-eye-slash"></i>
      </div>
      <div class="form-input form-password">
        <input name="password2" id="password2" type="password" class="pass-check form-input-box" placeholder="Confirm Password" required>
        <i class="toggle-pass fa fa-eye-slash"></i>
      </div>
      <div class="form-btn">
        <input type="hidden" name="invest" value="">
        <input type="hidden" name="balance" value="">
        <input type="hidden" name="earning" value="">
        <button type="submit" onclick="return post_check();">Submit</button>
      </div>
      <div class="form-a">
        <a href="/login">Already Registered?</a>
      </div>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="assets/js/common.js"></script>
  <script>
    function post_check(){
      var password_check = document.querySelectorAll('.pass-check');
      if(password_check[0].value == ''){
        alert("パスワードを入力してください。");
        return false;
      }
      if(password_check[0].value != password_check[1].value){
        alert("パスワードが一致していません。");
        return false;
      }
      for(let i = 0; i < password_check.length; i++){
        if(password_check[i].value.match(/[^0-9 a-z A-Z !"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~ ]/g) || !password_check[i].value.match(/^.{8,24}$/g)){
          alert("パスワードの入力形式に誤りがあります。");
          return false;
        }
      }
    }
  </script>
</body>

</html>