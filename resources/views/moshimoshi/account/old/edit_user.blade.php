@include('public/header',['title' => 'initial_information','description' => 'initial_information'])

<main>
  <div class="header_img">
    <a href="/">
        <img  src="{{('/img/headerlogo.png')}}">
    </a>
  </div>
  <div class="m_top3" style="margin-bottom:6.0em;">
    <div class="login_w_80">
      <div class="login_box">
        <div class="p_3">
          <!-- 0:admin、1:ユーザー、2:店舗管理人、3:配達員 -->
          @if(isset($user->kind) && $user->kind == 1)
            <h1>新規会員登録する</h1>
          @elseif(isset($user->kind) && $user->kind == 2 && isset($user->name))
            <h1>{{$user->name}} 様 情報編集</h1>
          @elseif(empty($user))
            <h1>店舗管理者追加</h1>
          @elseif(isset($user->kind) && $user->kind == 3)
            <h1>配達員会員登録</h1>
          @endif
            <div class="t_center">
              <h4 class="gray">※以下の情報をご入力ください。</h4>
            </div>
          @if(isset($alert['text']))
            @if($alert['text'])
              <div class="alert {{$alert['class']}} text-center">
                <p class="alert-message">{{$alert['text']}}</p>
              </div>
            @endif
          @endif
          <form class="h-adr" action="/edit_user" method="post" name="post_form">
            @csrf
            <div class="login">
              <div class="flexspace margin10">
                <label for="name">お名前　</label>
                <input class="padding10" id="name" maxlength="255" class="check_form" name="name" type="text" placeholder="名前を入力してください" value="{{isset($user->name) ? $user->name :''}}" required>
              </div>
              <div class="flexspace margin10">
                <label for="kana">フリガナ　</label>
                <input class="padding10" id="kana" maxlength="255" class="check_form" name="kana" type="text" placeholder="フリガナを入力してください" value="{{isset($user->kana) ? $user->kana :''}}" required>
              </div>
              <div class="flexspace margin10">
                <label for="email">メールアドレス　</label>
                <input class="padding10" name="email" maxlength="255" type="email" placeholder="" value="{{isset($user->email) ? $user->email : ''}}" {{ $hash || isset($user->email) ? 'readonly' : '' }} required>
              </div>
              @if(isset($user->kind) && $user->kind == 3)
              <div class="flexspace margin10">
                <label for="line_id">LINE登録　</label>
                <input name="line_id" type="hidden" value="{{$user->line_id ? $user->line_id : ''}}">
                <a href="https://lin.ee/JxZ5h0R" target="_blank">
                  <img src="https://scdn.line-apps.com/n/line_add_friends/btn/ja.png" alt="友だち追加" height="36" border="0">
                </a>
              </div>
              <div class="flexspace margin10">
                <label for="line_name">LINEユーザー名</label>
                <input id="line_names" class="padding10" name="line_name" type="text" placeholder="LINEユーザー名を入力してください" value="{{isset($user->line_name) ? $user->line_name :''}}" required>
              </div>
              @elseif(isset($kind) && $kind == 0 && isset($u_id) && $u_id == 0)
              <div class="flexspace margin10">
                <label for="s_id">店舗名選択</label>
                <select id='s_id' name='s_id' class='padding10 store_select'>
                  @foreach($stores as $key => $store)
                    <option value='{{$store->id}}'>{{$store->name}}</option>
                  @endforeach
                </select>
              </div>
              <div style="text-align:right;">
                <button type="button" class="line-button modal-open mdl">LINEユーザー検索</button>
              </div>
              <div class="flexspace margin10">
                <label for="line_name">LINEユーザー名</label>
                <input id="line_name" class="padding10" name="line_name" type="text" placeholder="検索後、自動入力されます" value="" required readonly>
              </div>
              <div class="flexspace margin10 line-box">
                <label for="line_id">LINEユーザーID</label>
                  <div style="display:flex; flex-direction:column;">
                    <input id="line_id" class="padding10" name="line_id" type="text" placeholder="検索後、自動入力されます" value="" required readonly>
                  </div>
              </div>
              <input name="kind" type="hidden" value="0">
              @endif
              @if(isset($user->kind) && $user->kind == 1)
                <div class="flexspace margin10">
                  <label for="c_flag">会員種別</label>
                  <select id='c_flag' name='c_flag' class='padding10 c_flag_select' required>
                    <option value disabled selected>会員種別</option>
                    <option value='1'>個人</option>
                    <option value='2'>法人(個人事業も可)</option>
                  </select>
                </div>
              @endif
                <div class="flexspace margin10">
                  <label for="tel">電話番号</label>
                  <input id="tel" class="padding10" name="tel" maxlength="11" type="tel" placeholder="09012345678" value="{{isset($user->tel) ? $user->tel :''}}" {{ isset($user->tel) && $user->tel != '' ? 'readonly' : '' }} required>
                </div>
                <div class="flexspace margin10">
                  <label for="postcode">住所</label>
                  <div style="justify-content: space-between;">
                    <span class="p-country-name" style="display:none;">Japan</span>
                    <div class="w_100">
                      <input name="postcode" id="postcode" type="text" class="padding10 p-postal-code" size="8" maxlength="7" placeholder="郵便番号" value="{{isset($user->postcode) ? $user->postcode :''}}" required style="margin-bottom:5%;">
                    </div>
                    <div class="w_100">
                      <input name="address" type="text" class="padding10 p-region p-locality p-street-address p-extended-address" style="font-size: 0.5em;" placeholder="例）多摩市〇〇1-2-3 〇〇マンション123"  value="{{isset($user->address) ? $user->address :''}}" required/>
                    </div>
                  </div>
                </div>
              @if(isset($user->kind) && $user->kind == 1 && $user->birthday == '')
                <div class="flexspace margin10">
                  <label for="birthday">お誕生日</label>
                  <input class="padding10" name="birthday" id="birthday" type="date" value="1990-08-01{{isset($user->birthday) ? $user->birthday :''}}" required>
                </div>
              @endif
              <div class="flexspace margin10">
                <label for="password">パスワード</label>
                <div class="pass" style="flex-direction:column;">
                  <input class="padding10 pass-check" name="password" maxlength="24" type="password" {{ $hash ? 'required' : '' }} placeholder="※8文字以上で入力してください。" value="{{isset($user->password) ? $user->password :''}}" required>
                </div>
              </div>
              <div class="flexspace margin10">
                <label for="password2">パスワード(確認)</label>
                <input class="padding10 pass-check" name="password2" maxlength="24" type="password" {{ $hash ? 'required' : '' }} value="{{isset($user->password) ? $user->password :''}}" required>
              </div>
              <input name="hash" type="hidden" value="{{$hash}}">
              <input name="id" type="hidden" value="{{isset($user->id) ? $user->id : ''}}">
                <input name="already_s_id" type="hidden" value="{{isset($user->s_id) ? $user->s_id : ''}}">
            </div>
            <div class="t_center m_btm_1em">
                <button class="loginpage_btn_a" style="width:80%; background: #f4a125; border:1px solid #f4a125;" onclick="return post_check();"><p style="font-size:1.2em;">登録</p></button>
            </div>
          </form>
          <!-- LINEユーザー検索モーダル -->
          <div id="modal-remove" class="modal-container" style="max-width:700px; margin:0 auto; right:0; left:0;">
            <div class="modal-body">
              <div class="modal-close mdl-close">×</div>
              <div class="modal-content">
                  <form class="line_search margin10">
                    @csrf
                    <div class="">
                      <input id="line_user_name" class="padding10" name="line_user_name" type="text" placeholder="LINEのユーザー名を入力してください" value="" required>
                      <button class="m_top5" type="button" id="line-search">ユーザー名を検索する</button>
                    </div>
                  </form>
                  <div id="line_list" class="margin10"></div>
              </div>
            </div>
          </div>
        </div> 
      </div>
    </div>
  </div>
</main>

<script>
    var line_list = document.getElementById('line_list');
    $("#line-search").on('click',function(){//line_id検索時
    var form       = $(this).closest('.line_search').get(0);
    var line_user_name = form.elements['line_user_name'].value;//line_id
    if(line_user_name == ''){
      alert('LINEユーザー名を入力してください');
      return false;
    };
        line_list.innerHTML = '';
    $.ajax({//モーダルの値を送る処理
        type: "POST",
        //ここでデータの送信先URLを指定します。
        url: "/line_id",//line_idに送る
        dataType: "json",
        scriptCharset: 'utf-8',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
          line_user_name: line_user_name,//line_name
        },
      })
      //処理が成功したら
      .done(function(data) { 
        let datas = JSON.parse(JSON.stringify(data));
        // console.log(datas);
        line_list.innerHTML = datas;
        let form_csrf = document.getElementsByClassName('csrf');
        $(form_csrf).prepend('@csrf');//formの中に@csrfを追加
      })
      //処理がエラーであれば
      .fail(function(xhr) {  
        //通信失敗時の処理
        //失敗したときに実行したいスクリプトを記載
      })
      .always(function(xhr, msg) { 
        //通信完了時の処理
        //結果に関わらず実行したいスクリプトを記載
      });
  });

  function line_send(){
    var line_user_id = document.getElementById('line_u_id').value;
    var obj = document.getElementById('line_u_id');
    let idx = obj.selectedIndex;
    let line_name  = obj.options[idx].text;
    document.getElementById('line_id').value = line_user_id;
    document.getElementById('line_name').value = line_name;
    //モーダルを消す処理
    document.querySelector('#modal-remove').classList.remove('active');
    line_list.innerHTML = '';
    document.getElementById('line_user_name').value = '';
    // line_name = '';
    // console.log(line_name);
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

@include('public/footer')