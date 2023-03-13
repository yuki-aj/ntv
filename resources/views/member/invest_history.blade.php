
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
  <title>Invest-history | northvolt investment</title>
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
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/invest"><span class="hide-menu">Invest</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw"><span class="hide-menu">Withdraw</span></a></li>
          <li class="sidebar-item selected"><a class="sidebar-link transition-opacity" href="/invest_history"><span class="hide-menu">Invest History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/reference_history"><span class="hide-menu">Reference History</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link transition-opacity" href="/withdraw_history"><span class="hide-menu">Withdraw History</span></a></li>
        </ul>
      </nav>
    </aside>
    <div class="page-wrapper">
      <div class="contents">
        <div class="row-long">
          <div class="card">
            <h3 class="card-title border-none">Investment History</h3>
            <div class="card-table">
              <table class="table">
                <thead>
                  <tr>
                    <th>Plan Name</th>
                    <th>Amount</th>
                    <th>Invest Date</th>
                    <th>Mature Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($invest_history as $invest)
                  <table class="table invest">
                      <thead>
                        <tr>
                          @if(isset($invest->plan_name) && $invest->plan_name != '')
                          <th>{{$invest->plan_name}}</th>
                          @else
                          <th></th>
                          @endif
                          @if(isset($invest->amount) && $invest->amount != '')
                          <th>{{$invest->amount}}</th>
                          @else
                          <th></th>
                          @endif
                          @if(isset($invest->invest_date) && $invest->invest_date != '')
                          <th>{{$invest->invest_date}}</th>
                          @else
                          <th></th>
                          @endif
                          @if(isset($invest->mature_date) && $invest->mature_date != '')
                          <th>{{$invest->mature_date}}</th>
                          @else
                          <th></th>
                          @endif
                          @if(isset($invest->status) && $invest->status != '')
                          <th>{{$invest->status}}</th>
                          @else
                          <th></th>
                          @endif
                        </tr>
                      </thead>
                  </table>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
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