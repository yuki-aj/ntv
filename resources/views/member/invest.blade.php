
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
  <title>Invest | northvolt investment</title>
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link rel="apple-touch-icon" href="assets/img/webclipicon.png">
</head>

<body>
  <header class="top-bar">
    <div class="top-bar-img"><a href="/dashboard" class="top-bar-brand"><img src="assets/img/logo-sample-green.svg" alt="northvolt logo"></a></div>
    <div class="nav-item pc">
      <div class="btn-ripple" id="dropdown-btn">
        <a href="" class="nav-item-box"><span class="hide-name">{{$user->user_name}}</span><span class="nav-item-icon"><i class="fa-solid fa-user"></i></span></a>
      </div>
      <ul class="dropdown-menu">
        <li><a href="/reset_password" class="dropdown-menu-item transition-opacity">Change Password</a></li>
        <li><a href="/logout" class="dropdown-menu-item transition-opacity">Logout</a></li>
      </ul>
    </div>
    <div class="hamburger sp" id="hamburger-btn"><i class="fa-solid fa-bars"></i></div>
  </header>

  <main class="main">
    <aside class="sidebar" id="sidebar">
      <nav class="sidebar-nav">
        <ul id="sidebarnav" class="sidebar-list">
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/dashboard"><span class="hide-menu">Dashboard</span></a></li>
          <li class="sidebar-item selected"><a class="sidebar-link transition-opacity" href="/invest"><span class="hide-menu">Invest</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw"><span class="hide-menu">Withdraw</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/invest_history"><span class="hide-menu">Invest History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/reference_history"><span class="hide-menu">Reference History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw_history"><span class="hide-menu">Withdraw History</span></a></li>
        </ul>
      </nav>
    </aside>
    <div class="page-wrapper">
      <div class="contents">
        @if($user->invest_status == 0)
        <p class="invest-error">You can not invest now! Please wait until approval of your account. or <a href="/#contact" class="inline">Concact us.</a></p>
        @else
        <p>Please Contact Us.</p>
        @endif
      </div>
    </div>
  </main>
  <footer>
    <small>Copyright &copy; northvolt investment. All Rights Reserved.</small>  
  </footer>
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="assets/js/common.js"></script>
</body>

</html>