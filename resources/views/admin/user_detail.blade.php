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
  <title>northvolt investment</title>
  <link rel="shortcut icon" href="assets/img/favicon.ico">
  <link rel="apple-touch-icon" href="assets/img/webclipicon.png">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>

<header>
    <div style="display:flex; justify-content: space-between; width:85%; margin: 0 auto;">
    <p><a href="/user_list">管理画面</a></p>
    <p>
        <a href="/logout">ログアウト</a>
    </p>
    </div>
</header>

<style>
    span {
       color:red; 
    }
    p {
        margin: 0;
        padding: 0;
    }
    .flex {
        display:flex;
        align-items:center;
    }
    a {
      text-decoration: none;
    }
    #main {
      width:90%;
      padding: 5%;
      margin:  0 auto;
      background:#fff;
    }
    .big_box {
        border:1px solid #ccc;
        margin-top:1em;
    }
    .small_box {
        margin: 1em 0;
        padding:1em;
    }
    .delete {
        border: 1px solid #999;
        color: #000;
        padding: 3px;
        text-decoration: none;
        background: #f7f7f7;
    }
    .center {
        text-align:center;
    }
    .flash_message {
        color:#fff;
        background:red;
        margin: 1em 0;
    }
    header {
      background:#4682b4;
      box-shadow: 5px 5px 10px -5px;
    }
    header a {
      color:#fff;
      text-decoration: none;
      box-shadow:
    }
    header p {
     padding: 1em 0;
     margin: 0;
    }
    .headline {
      color:#4682b4;
      margin: 1em 0;
      border-bottom: 1px solid #ced4da;
      padding-bottom: 20px;
    }
    section {
      background:#f7f7f7;
    }
    .subheading {
        border-bottom:1px solid #ccc;
        color:#4682b4;
        padding: 0.5em 1em;
        font-weight:bold;
        background: #f7f7f7;
    }
    .gray {
        color:gray;
    }
</style>

<section>
    <div id="main">
        <div class="flex">
        <div class=""><a href="">Top</a></div><span style="color:gray;">　>　</span>
        <div class=""><a href="/user_list">ユーザー</a></div><span style="color:gray;">　>　</span>
        <div class=""><a href="/user_detail" style="color:#000;">編集</a></div>    
        </div>
        <div class="flex headline">
        <h2 class="">ユーザー編集</h2>　　 <p class="gray"><span>＊</span>は入力必須項目です。</p>
        </div>
        <form class="form-table update" action="/user_detail/{{$user->id}}" method="post">
            @csrf
            <div class="big_box">
                <p class="subheading">基本</p>
                <div class="small_box">
                    <div class="row mb-3">
                        <label for="full_name" class="col-sm-2 col-form-label">Full Name<span>＊</span></label>
                        <div class="col-sm-10">
                        <input name="full_name" value="{{isset($user->full_name)? $user->full_name : ''}}" type="text" class="form-control" id="full_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="user_name" class="col-sm-2 col-form-label">User Name<span>＊</span></label>
                        <div class="col-sm-10">
                        <input name="user_name" value="{{isset($user->user_name)? $user->user_name : ''}}" type="text" class="form-control" id="user_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">E-mail<span>＊</span></label>
                        <div class="col-sm-10">
                        <input name="email" value="{{isset($user->email)? $user->email : ''}}" type="email" class="form-control" id="email">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="referral_code" class="col-sm-2 col-form-label">Referral Code<span>＊</span></label>
                        <div class="col-sm-10">
                        <input name="referral_code" value="{{isset($user->referral_code)? $user->referral_code : ''}}" type="number" class="form-control" id="referral_code">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="wallet_address" class="col-sm-2 col-form-label">Wallet Address<span>＊</span></label>
                        <div class="col-sm-10">
                        <input name="wallet_address" value="{{isset($user->wallet_address)? $user->wallet_address : ''}}" type="text" class="form-control" id="wallet_address">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="wallet_address" class="col-sm-2 col-form-label">ステータス<span>＊</span></label>
                        <div class="col-sm-10">
                        <select name="invest_status" class="form-select" aria-label="Default select example">
                            <option  value="0" {{($user->invest_status==0) ? 'selected' : ''}}>未承認</option>
                            <option  value="1" {{($user->invest_status==1) ? 'selected' : ''}}>承認</option>
                        </select>    
                        </div>
                    </div>
                </div>
            </div>
            <div class="big_box">
                <p class="subheading">ダッシュボード</p>
                <div class="small_box">
                    <div class="row mb-3">
                        <label for="invest" class="col-sm-2 col-form-label">Invest</label>
                        <div class="col-sm-10">
                        <input name="invest" value="{{isset($user->invest)? $user->invest : ''}}" type="text" class="form-control" id="invest">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="balance" class="col-sm-2 col-form-label">Balance</label>
                        <div class="col-sm-10">
                        <input name="balance" value="{{isset($user->balance)? $user->balance : ''}}" type="text" class="form-control" id="balance">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="earning" class="col-sm-2 col-form-label">Earning</label>
                        <div class="col-sm-10">
                            <input name="earning" value="{{isset($user->earning)? $user->earning : ''}}" type="text" class="form-control" id="earning">
                        </div>
                    </div>
                </div>
            </div>

            <div class="center" style="margin-top:1em;">
            <input onclick="return really_update();" type="submit" value="変更する" class="">
            </div>
        </form>
        <div class="big_box">
            <div class="subheading flex">パスワード変更　 <p class="gray"><span>＊</span>は入力必須項目です。</p></div>

            <div class="small_box">  
                <div class="basic_information">
                    @if (session('flash_message'))
                        <div class="flash_message center">
                            {{ session('flash_message') }}
                        </div>
                    @endif
                    <form id="" action="/password" method="post">
                        @csrf
                        <div class="row mb-3">
                            <label for="current_password" class="col-sm-2 col-form-label">現在のパスワード<span>＊</span></label>
                            <div class="col-sm-10 form-password">
                            <input type="text" name="current_password" value="{{isset($user->password)? $user->password : ''}}" class="form-control" id="current_password" disabled>
            
                        </div>
                        </div>
                        <div class="row mb-3">
                            <label for="new_password" class="col-sm-2 col-form-label">新しいパスワード<span>＊</span></label>
                            <div class="col-sm-10">
                            <input type="password" name="new_password" value="" class="form-control" id="new_password">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="new_password2" class="col-sm-2 col-form-label">新しいパスワード（確認用）<span>＊</span></label>
                            <div class="col-sm-10">
                            <input type="password" name="new_password2" value="" class="form-control" id="new_password2">
                            </div>
                        </div>
                        <div class="center">
                            <input onclick="return really_update();" type="submit" value="変更する" class="">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form class="form-table update" action="/invest_history/{{$user->id}}" method="post">
            @csrf
            <div class="big_box">
                <p class="subheading">インベストヒストリー</p>
                <div class="small_box">
                    <div class="row mb-3">
                        <label for="plan_name" class="col-sm-2 col-form-label">Plan Name</label>
                        <div class="col-sm-10">
                        <input name="plan_name" value="{{isset($invest->plan_name)? $invest->plan_name : ''}}" type="text" class="form-control" id="plan_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                        <input name="amount" value="{{isset($invest->amount)? $invest->amount : ''}}" type="text" class="form-control" id="amount">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="invest_date" class="col-sm-2 col-form-label">Invest Date</label>
                        <div class="col-sm-10">
                        <input name="invest_date" value="{{isset($invest->invest_date)? $invest->invest_date : ''}}" type="text" class="form-control" id="invest_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mature_date" class="col-sm-2 col-form-label">Mature Date</label>
                        <div class="col-sm-10">
                        <input name="mature_date" value="{{isset($invest->mature_date)? $invest->mature_date : ''}}" type="text" class="form-control" id="mature_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                        <input name="status" value="{{isset($invest->status)? $invest->status : ''}}" type="text" class="form-control" id="status">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="0" required />
                    <input type="hidden" name="user_id" value="{{$user->id}}" required />
                    <div class="center">
                    <input type="submit" value="追加する" class="">
                    </div>
                </div>
            </div>
        </form>
        @foreach($invest_historys as $invest)
        <div class="big_box">
            <form class="form-table update" action="/invest_history/{{$user->id}}" method="post">
                @csrf
                <div class="small_box">
                    <div class="row mb-3">
                        <label for="plan_name" class="col-sm-2 col-form-label">Plan Name</label>
                        <div class="col-sm-10">
                        <input name="plan_name" value="{{isset($invest->plan_name)? $invest->plan_name : ''}}" type="text" class="form-control" id="plan_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                        <input name="amount" value="{{isset($invest->amount)? $invest->amount : ''}}" type="text" class="form-control" id="amount">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="invest_date" class="col-sm-2 col-form-label">Invest Date</label>
                        <div class="col-sm-10">
                        <input name="invest_date" value="{{isset($invest->invest_date)? $invest->invest_date : ''}}" type="text" class="form-control" id="invest_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="mature_date" class="col-sm-2 col-form-label">Mature Date</label>
                        <div class="col-sm-10">
                        <input name="mature_date" value="{{isset($invest->mature_date)? $invest->mature_date : ''}}" type="text" class="form-control" id="mature_date">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                        <input name="status" value="{{isset($invest->status)? $invest->status : ''}}" type="text" class="form-control" id="status">
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{$invest->id}}" required />
                    <input type="hidden" name="user_id" value="{{$user->id}}" required />
                    <div class="center">
                        <input onclick="return really_update();" type="submit" value="変更する" class="">
                        <a href="/invest_delete/{{$user->id}}/{{$invest->id}}"  onclick="return really_delete();" class="delete">削除</a>
                    </div>
                </div>
            </form>
        </div>
        @endforeach
    </div>
</section>

<script>
    function really_delete(){//削除
        var result = confirm('本当に削除しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
    function really_update(){//更新
        var result = confirm('本当に更新しますか？');
        if(result) {
            document.querySelector('update').submit();
        } else {
            return false;
        }
    }
</script>

</body>
</html>
