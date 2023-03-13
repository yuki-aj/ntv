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
    .info-table1,
    .info-table2 {
    width: 100%;
    font-size: 14px;
    border: 1px solid rgb(0, 0, 0);
    table-layout: fixed;
    border-collapse: collapse;
    }
    .info-table2 {
    margin-top: -1px;
    }
    .info-table1 th,
    .info-table2 th {
    padding: 5px;
    border: 1px solid rgb(0, 0, 0);
    background: #f2f2f2;
    /* background: rgb(240, 225, 200); */
    }
    .info-table1 td,
    .info-table2 td {
    padding: 5px;
    border: 1px solid rgb(0, 0, 0);
    }
    .beige {
    background: blanchedalmond;
    }
    .padding3 {
    padding: 3%;
    }
    span {
       color:red; 
    }
    a {
      text-decoration: none;
    }
    .flex {
        display:flex;
        align-items:center;
        
    }
    #main {
      width:90%;
      padding: 5%;
      margin:  0 auto;
      background:#fff;
    }
    .edit {
      text-decoration: none;
      background:#4682b4;
      color:#fff;
      padding:3px 10px;
    }
    .delete {
      color:#fff;
      background:#ff4500;
      padding:3px 10px;
      text-decoration: none;
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
    .search {
      width: 70%;
      margin: 0 auto;
    }
    .search_btn {
      background:#4682b4;
      padding: 0.5em;
      color:#fff;
      border: 1px solid #4682b4;
    }
    .center {
      text-align: center;
    }
    section {
      background:#f7f7f7;
    }
    .bold {
      font-weight:bold;
    }

</style>

<section>
  <div id="main">
    <div class="flex">
      <div class=""><a href="">Top</a></div><span style="color:gray;">　>　</span>    
      <div><a href="/user_list" style="color:#000;">ユーザー</a></div>
    </div>
    <form class="form-table order_search" action="/user_list" method="get">
        @csrf
        <h2 class="headline">ユーザー一覧</h2>
        <div class="search">
          <div class="row mb-3">
          <label for="full_name" class="col-sm-2 col-form-label">ユーザー名</label>
            <div class="col-sm-10">
            <input name="full_name" value="{{isset($user->full_name)? $user->full_name : ''}}" type="text" class="form-control" id="full_name" placeholder="ユーザー名を入力">
            </div>
            <div style="display:flex; align-items:center; margin-top:1em;">
              <label class="col-sm-2 col-form-label" for="name">ステータス</label>
              <label class="col-sm-2 col-form-label" style="min-width:80px;"><input name="invest_status" type="radio" {{isset($request->invest_status) && $request->invest_status == 1 ? 'checked': ''}} value="1"/>承認</label>
              <label class="col-sm-2 col-form-label" style="min-width:80px;"><input name="invest_status" type="radio" {{isset($request->invest_status) && $request->invest_status == 0 ? 'checked': ''}} value="0"/>未承認</label>
            </div>
            <div class="center">
              <input type="submit" value="絞り込む" class="search_btn">
            </div>
            <div style="margin-top:1em;" class="center">
              検索結果
              <span style="font-weight:bold; font-size:30px;">
                {{ $lists->total()}} 
              </span>
              件
            </div>
          </div>
        </div>
    </form>
    <div style="margin:1em 0;">
      {{ $lists->appends(request()->input())->links() }}
    </div>
    <table class="info-table1 center">
        <tr>
          <th colspan="8">ユーザー名</th>
          <th colspan="2">ステータス</th>
          <th colspan="4">更新日時</th>
          <th colspan="2">編集</th>
          <th colspan="2">削除</th>
        </tr>
        @foreach ($lists as $user)
          <tr>
            <td colspan="8" style="word-break:break-all; text-align:left; padding-left:1em;">
                {{$user->full_name}}
            </td>
            <td colspan="2" style="word-break:break-all">
                {{$user->invest_status}}
            </td>
            <td colspan="4" style="word-break:break-all">
                {{$user->updated_at}}
            </td>
            <td colspan="2">
                <a class="edit" href="/user_detail/{{$user->id}}">編集</a>
            </td>
            <td colspan="2">
            <a href="/user_delete/{{$user->id}}" onclick="return really_delete();" class="delete">削除</a>
            </td>
          </tr>
        @endforeach
    </table>
    <div style="margin:1em 0; padding-right:0;">
      {{ $lists->appends(request()->input())->links() }}
    </div>
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
  </script>

</body>
</html>