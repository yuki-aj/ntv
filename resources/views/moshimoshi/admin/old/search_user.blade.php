@include('manage/header',['title' => 'search_user','description' => 'search_user'])
<style>
  label {
    font-size:0.8em;
  }
  .order_select {
    width:100%;
  }
</style>
<h1 class="baskets">ユーザー検索</h1>
<div class="admin_page">
    <div class="addition m_t3" style="border-radius: 50px;">
        <a href="/admin_manage/">
            <p>管理画面へ</p>
        </a>
    </div>
    <div class="addition gap m_t3" style="border-radius: 50px;">
        <a href="/edit_user/0">
            <p>店舗管理者追加</p>
        </a>
    </div>
</div>
<div id="main" class=" search-flex">
  <form class="form-table order_search" action="/search_user" method="get">
      @csrf
      <h1 style="text-align: center; margin:20px 0 0; color:#000;">ユーザー検索</h1>
      <div class="order_width">
        <div style="display:flex; align-items:center;" class="m_top3">
          <label for="name">有効・無効</label>
          <label style="min-width:80px;"><input name="user_status" type="radio" {{isset($request->user_status) && $request->user_status == 1 ? 'checked': ''}} value="1"/>有効</label>
          <label style="min-width:80px;"><input name="user_status" type="radio" {{isset($request->user_status) && $request->user_status == 0 ? 'checked': ''}} value="0"/>無効</label>
        </div>
        <div style="display:flex; align-items:center; flex-direction:space-between;" class="m_top3">
          <label for="name">ユーザー名</label>
          <input class="order_text" name="name" type="text" value="{{isset($request->name) ? $request->name : ''}}" placeholder="ユーザー名を入力">
        </div>
        <div style="display:flex; align-items:center; flex-direction:space-between;" class="m_top3 " style="justify-content:left;">
          <label for="kind">ユーザー種別</label>
          <select class="order_select" name="kind">
            <option value="">全種類</option>
            <!-- <option value="1" {{isset($request->kind)&&($request->kind==1)&& isset($request->corporation_flag)&&($request->corporation_flag==1) ? 'selected' : ''}}>個人ユーザー</option> -->
            <!-- <option value="2" {{isset($request->kind)&&($request->kind==1)&& isset($request->corporation_flag)&&($request->corporation_flag==2) ? 'selected' : ''}}>法人ユーザー</option> -->
            <option value="1" {{isset($request->kind)&&($request->kind==1) ? 'selected' : ''}}>ユーザー</option>
            <option value="2" {{isset($request->kind)&&($request->kind==2) ? 'selected' : ''}}>店舗管理者</option>
            <option value="3" {{isset($request->kind)&&($request->kind==3) ? 'selected' : ''}}>配達員</option>
          </select>
        </div>
        <div style="display:flex; align-items:center; flex-direction:space-between;" class="m_top3">
          <label>登録日</label>
          <input class="order_date" name="from" type="date" value="{{isset($request->from) ? $request->from : ''}}">～
          <input class="order_date" name="to" type="date" value="{{isset($request->to) ? $request->to : ''}}">
        </div>
        <div style="display:flex; align-items:center; flex-direction:space-between;" class="m_top3">
          <label for="birthday">誕生月</label>
          <input class="order_text" max="12" min="0" name="birthday" type="number" value="{{isset($request->birthday) ? $request->birthday : ''}}" placeholder="誕生月を入力">
        </div>
        <div style="display:flex; align-items:center; flex-direction:space-between;" class="m_top3">
          <label for="email">メールアドレス</label>
          <input class="order_text" min="0" name="email" type="text" value="{{isset($request->email) ? $request->email : ''}}" placeholder="メールアドレスを入力">
        </div>
      </div>
      <div class="categories flexbox m_top3">
        <div class="flexbox searchbutton-box">
          <input class="addition" type="submit" style="font-size:1.1em; border-radius: 50px;" value="検索する" class="searchbutton">
        </div>
      </div>
  </form>
</div>
<div class="flexbox margin10">
  検索結果
  <span style="font-weight:bold; font-size:24px;">
   {{ $lists->total()}} 
  </span>
  件
</div>
<div class="flexbox margin10">
  {{ $lists->appends(request()->input())->links() }}
</div>
@if(session('flash_message'))
  <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
@endif
<!-- <div class="flexbox"> -->
<div class="m_top5">
  <div class="w_90">
  <table class="info-table1 t_center">
      <tr>
        <th colspan="4">クーポン<br>
          <label><input id="checkAll" type="checkbox" name="coupon" value="checkall"></label><br>
          <form id="coupon_check" method="post" action="/add_coupon">
          @csrf
            <input class="margin10 orange" type="submit" value="付与" class="searchbutton" onclick="return coupon();">
          </form>
        </th>
        <!-- <th colspan="4">種別</th> -->
        <th colspan="4">ユーザー名</th>
        <th colspan="8">店舗名</th>
        <th colspan="8" class="display_change">email</th>
        <th colspan="4" class="display_change">電話番号</th>
        <th colspan="4" class="display_change">誕生月</th>
        <th colspan="4" class="display_change">更新日</th>
        <th colspan="4">ステータス</th>
        <th colspan="4">ユーザー編集</th>
      </tr>
      @foreach ($lists as $user)
        <tr>
          <td colspan="4">
            @if($user->kind == 1 || $user->kind == 3)
              <input form="coupon_check" class="checks" type="checkbox" name="u_ids[]" value="{{$user->id}}">
            @endif
            </td>
          <!-- <td colspan="4">{{$user->corporation_flag}}</td> -->
          <td colspan="4" style="word-break:break-all">
            @if($user->kind == 1)
              <a style="color:blue" href="https://dashboard.stripe.com/test/customers/{{$user->stripe_id}}" target="_blank">{{$user->name}}</a>
            @else
              {{$user->name}}
            @endif
          </td>
          <td colspan="8">
            @if($user->s_id != 0)
              <a class="admin-company" style="color:blue" href="{{url('store_information/'.$user->s_id)}}">
                <div class="admin-link" >{{$user->s_id != 0 ? $user->s_name : 'ー'}}</div>
              </a>
            @else
              <div class="admin-link">{{$user->s_id != 0 ? $user->s_name : 'ー'}}</div>
            @endif
          </td>
          <td colspan="8" class=" display_change">{{$user->email}}</td>
          <td colspan="4" class=" display_change">{{$user->tel}}</td>
          <td colspan="4" class=" display_change">{{$user->birthday}}</td>
          <td colspan="4" class=" display_change">{{$user->date_updated}}</td>
          <td colspan="4">
            <form class="form-table" action="/search_user" method="POST">
              @csrf
                <input type="hidden" value="{{$user->user_status}}" name="u_status">
                <input type="hidden" value="{{$user->id}}" name="u_id">
                @if($user->user_status ==1)
                  <input class="margin10 orange" type="submit" value="有効" class="searchbutton">
                @else
                  <input style="color:#000; border:1px solid #000;" class="margin10 white" type="submit" value="無効" class="searchbutton">
                @endif
            </form>
          </td>
          <td colspan="4">
            @if($user->kind == 2)
              <a class="" href="/edit_user/{{$user->id}}" style="border:1px solid #ccc;padding:5px;background-color:orange;color:#fff;">編集</a>
            @endif
          </td>
        </tr>
      @endforeach
  </table>
  </div>
</div>
<div class="flexbox margin10">{{ $lists->appends(request()->input())->links() }}</div>

<!-- <form action="/csv" method="post">
  @csrf
  <div>
    <button class="addition m_btm" type="submit">CSVダウンロード</button>
  </div>
</form> -->


<script>
  
  //チェックボタンを外す処理
  $(function(){
    //インプット要素を取得する
    var inputs = $('input');
    //読み込み時に「:checked」の疑似クラスを持っているinputの値を取得する
    var checked = inputs.filter(':checked').val();
    
    //インプット要素がクリックされたら
    inputs.on('click', function(){
        
        //クリックされたinputとcheckedを比較
        if($(this).val() === checked) {
            //inputの「:checked」をfalse
            $(this).prop('checked', false);
            //checkedを初期化
            checked = '';
            
        } else {
            //inputの「:checked」をtrue
            $(this).prop('checked', true);
            //inputの値をcheckedに代入
            checked = $(this).val();
            
        }
    });
    
  });
  //「全て選択」のチェックボックス
  let checkAll = document.getElementById("checkAll");
  //「全て選択」以外のチェックボックス
  let el = document.getElementsByClassName("checks");

  //全てのチェックボックスをON/OFFする
  const funcCheckAll = (bool) => {
      for (let i = 0; i < el.length; i++) {
          el[i].checked = bool;
      }
  }

  //「checks」のclassを持つ要素のチェック状態で「全て選択」のチェック状態をON/OFFする
  const funcCheck = () => {
      let count = 0;
      for (let i = 0; i < el.length; i++) {
          if (el[i].checked) {
              count += 1;
          }
      }
      if (el.length === count) {
          checkAll.checked = true;
      } else {
          checkAll.checked = false;
      }
  };

  //「全て選択」のチェックボックスをクリックした時
  checkAll.addEventListener("click",() => {
      funcCheckAll(checkAll.checked);
  },false);

  //「全て選択」以外のチェックボックスをクリックした時
  for (let i = 0; i < el.length; i++) {
      el[i].addEventListener("click", funcCheck, false);
  }
  function coupon() {
    var result = false;
    let u_ids = document.getElementsByClassName('checks');
      // console.log(result);
      for(let i = 0; i < u_ids.length; i++){
        if(u_ids[i].checked){
          result = true;
          console.log(result);
          break;
        }
      };
    if(result == false){
      alert('クーポンの宛先を選択してください。');
      return false;
    };
  }
    //モーダル
    $(function(){
      // 変数に要素を入れる
      var open = $('.modal-open'),
        close = $('.mdl-close'),
        container = $('.modal-container');

      //開くボタンをクリックしたらモーダルを表示する
      open.on('click',function(){	
        container.addClass('active');
        return false;
      });
      //閉じるボタンをクリックしたらモーダルを閉じる
      close.on('click',function(){	
        container.removeClass('active');
      });
      //モーダルの外側をクリックしたらモーダルを閉じる
      $(document).on('click',function(e) {
        if(!$(e.target).closest('.modal-body').length) {
          container.removeClass('active');
        }
      });
    });
    function store_user_check(){
      var password_check = document.querySelectorAll('.pass-check');
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