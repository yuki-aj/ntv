
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
  <title>Dashboard | northvolt investment</title>
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
          <li class="sidebar-item selected"><a class="sidebar-link transition-opacity" href="/dashboard"><span class="hide-menu">Dashboard</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/invest"><span class="hide-menu">Invest</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw"><span class="hide-menu">Withdraw</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/invest_history"><span class="hide-menu">Invest History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/reference_history"><span class="hide-menu">Reference History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw_history"><span class="hide-menu">Withdraw History</span></a></li>
        </ul>
      </nav>
    </aside>
    <div class="page-wrapper">
      <div class="contents">
        <div class="row">
          <div class="card">
            <div class="card-text">
              <h5 class="">Invest</h5>
              @if(isset($user->invest) && $user->invest != '')
              <p class="">฿{{$user->invest}}</p>
              @else
              <p class="">฿0.00000000</p>
              @endif
            </div>
          </div>
          <div class="card">
            <div class="card-text">
              <h5 class="">Balance</h5>
              @if(isset($user->balance) && $user->balance != '')
              <p class="">฿{{$user->balance}}</p>
              @else
              <p class="">฿0.00000000</p>
              @endif
            </div>
          </div>
          <div class="card">
            <div class="card-text">
              <h5 class="">Earning</h5>
              @if(isset($user->earning) && $user->earning != '')
              <p class="">฿{{$user->earning}}</p>
              @else
              <p class="">฿0.00000000</p>
              @endif
            </div>
          </div>
        </div>
        <div class="row-long">
          <div class="card">
            <h3 class="card-title">Account Information</h3>
            <dl class="card-body">
              <dt>Username</dt>
              <dd>{{$user->user_name}}</dd>
              <dt>Email</dt>
              <dd>{{$user->email}}</dd>
              <dt>Full Name</dt>
              <dd>{{$user->full_name}}</dd>
              <dt>Wallet Address</dt>
              <dd>{{$user->wallet_address}}</dd>
              <dt>Member since</dt>
              <dd>{{$user->since}}</dd>
            </dl>
          </div>
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