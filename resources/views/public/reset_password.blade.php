
<!DOCTYPE html>
<html lang="ja">
  <head prefix="og: http://ogp.me/ns#">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>northvolt investment</title>
    <meta name="description" content="MAKING AN INVEST INTO A BRIGHTER FUTURE">
  
    <meta property="og:type" content="website">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:url" content="https://samurai-tokuteiginou.com/gaisyoku/recruit">
    <meta property="og:image" content="">
    <meta property="og:site_name" content="">
  
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="">
  
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/style_pc.css" media="screen and (min-width:769px)">
    <link rel="stylesheet" href="assets/css/style_sp.css" media="screen and (max-width:768px)">
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <link rel="apple-touch-icon" href="assets/img/webclipicon.png">
  </head>

  <body class="login-pages">
    <style>
      .flash_message {
        color:#fff;
        background:red;
        margin-bottom:2em;
      }
    </style>
    <div class="login-pages-box">
      <form action="/reset_password" method="post" class="form">
        @csrf
        <span class="form-circle"><a href="/"><img src="assets/img/logo-sample-green.svg" alt="northvolt logo"></a></span>
        <p class="form-title">FORGOT PASSWORD</p>
        @if (session('flash_message'))
            <div class="flash_message">
                {{ session('flash_message') }}
            </div>
        @endif
        <div class="form-input form-mail">
          <input id="email" name="email" type="email" class="form-input-box" placeholder="E-mail">
        </div>
        <div class="form-btn">
          <button type="submit">Submit</button>
        </div>
        <div class="form-a">
          <a href="/login">Remember Your Password?</a>
        </div>
      </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="assets/js/common.js"></script>
  </body>

</html>