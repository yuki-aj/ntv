
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
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round|Material+Icons+Sharp|Material+Icons+Two+Tone" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>
  <style>
    .big_box {
        border:1px solid #ccc;
        width:70%;
        margin: 0 auto;
    }
    .small_box {
        margin: 1em 0;
        padding:1em;
    }
    .subheading {
        border-bottom:1px solid #ccc;
        color:#4682b4;
        padding: 0.5em 1em;
        font-weight:bold;
        background: #f7f7f7;
    }
    .center {
      text-align:center;
    }
    section {
      margin-top:5em;
    }
  </style>
  
  
  <section>
    <div class="big_box">
          <p class="subheading">パスワード変更</p>
          <form action="{{url('new_password')}}" method="post">
              @csrf
            <div class="small_box">
                <div class="row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">パスワード</label>
                    <div class="col-sm-10">
                      <input class="form-control pass-check" name="password" type="password" placeholder="※8文字以上で入力してください。" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password2" class="col-sm-2 col-form-label">パスワード(確認)</label>
                    <div class="col-sm-10">
                      <input class="form-control pass-check" name="password2" type="password" placeholder="※8文字以上で入力してください。" required>
                    </div>
                </div>
                <div class="center">
                    <input onclick="return check_form()" class="addition" type= "submit" value="変更する">
                </div>
            </div>
          </form>
    </div>
  </section>

  <script>
      function check_form(){
        var password_check = document.querySelectorAll('.pass-check');
        if(password_check[0].value != password_check[1].value){
          alert("パスワードが一致していません。");
          return false;
        }
    }
  </script>

</body>
</html>