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
  <title>northvolt investment</title>
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
        padding:5px;
    }

  </style>
  <div class="login-pages-box">
    <form action="/login" method="post" class="form">
    @csrf
      <span class="form-circle"><a href="/"><img src="assets/img/logo-sample-green.svg" alt="northvolt logo"></a></span>
      <p class="form-title">LOG IN</p>
      @if (session('flash_message'))
          <div class="flash_message">
              {{ session('flash_message') }}
          </div>
      @endif
      <div class="form-input form-user">
        <input type="text" id="user_name" name="user_name" class="form-input-box" placeholder="Username" required>
      </div>
      <div class="form-input form-password">
        <input type="password" id="password" name="password" class="form-input-box" placeholder="Password" required>
        <i class="toggle-pass fa fa-eye-slash"></i>
      </div>
      <div class="form-btn">
      <button type="submit" onclick="return post_check();">Login</button>
      </div>
      <div class="form-a">
        <a href="/registration">New Registration?</a>
        <a href="/reset_password">Forgot Password?</a>
      </div>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="assets/js/common.js"></script>
</body>

</html>