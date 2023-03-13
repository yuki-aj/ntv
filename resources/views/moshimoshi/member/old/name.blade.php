@include('public/header')

<section>  
    <div class="product_baskets">
        <div class="flex">
                <div>
                    <a href="/mypage"><span style="font-size:1.2em; padding-top:0.3em" class="left material-symbols-outlined">arrow_circle_left</span></a>
                </div>
                <div class="bold">基本情報の変更</div>
                <div></diV>
        </div>
    </div>
    
    <form id="r_change" action="{{ url('name') }}" method="post" enctype="multipart/form-data">
       @csrf
        <!-- 基本情報 -->
        <div class="basic_information">
            <h1 class="bold t_center">基本情報</h1>
            <div class="new">
                <h3>お名前</h3>
                <input type="text" name="name" value="{{$user->name}}" class="textspace" />
            </div>
            <div class="new">
                <h3>フリガナ</h3>
                <input type="text" name="kana" value="{{$user->kana}}" class="textspace" />
            </div>
            <div class="new">
                <h3>電話番号</h3>
                <input maxlength="11" type="text" name="tel" class="textspace" value="{{isset($user->tel) ? $user->tel : ''}}" {{ $user->tel ? 'readonly' : '' }} />
            </div>
            <div class="new">
                <h3>メールアドレス ＊登録後変更はできません</h3>
                <input type="text" name="email" class="textspace" value="{{isset($user->email) ? $user->email : ''}}" {{ $user->email ? 'readonly' : '' }}  />
            </div>  
            <div class="new">
                <h3>誕生月　＊登録後変更はできません</h3>
                <input type="text" name="birthday" class="textspace" value="{{isset($user->birthday) ? $user->birthday : ''}}" {{ $user->birthday ? 'readonly' : '' }}  />
            </div>  
            <div class="new">
                <h3>郵便番号</h3>
                <span class="p-country-name" style="display:none;">Japan</span>
                <input class="p-postal-code textspace" size="8" maxlength="7" type="text" name="postcode" value="{{$user->postcode}}" class="textspace" />
            </div>
            <div class="new">
                <h3>住所</h3>
                <input class="p-region p-locality p-street-address p-extended-address textspace" type="text" name="address" value="{{$user->address}}" class="textspace" />
            </div>
        </div>

        <!-- 配送先１ -->
        <div class="basic_information">
            <h1 class="bold t_center">配送先1</h1>
            <div class="new">
                <h3>お名前</h3>
                <input type="text" name="d_name" value="{{$user->d_name ? $user->d_name : ''}}" class="textspace" />
            </div>
            <div class="new">
                <h3>電話番号</h3>
                <input maxlength="11" class="textspace" type="tel" name="d_tel" value="{{$user->d_tel ? $user->d_tel : ''}}"/>
            </div>
            <div class="new">
                <h3>郵便番号</h3>
                <span class="p-country-name" style="display:none;">Japan</span>
                <input id="postcode1" class="p-postal-code textspace" size="8" maxlength="7" type="text" name="d_postcode" value="{{$user->d_postcode ? $user->d_postcode : ''}}"/>
            </div>
            <div class="new">
                <h3>住所</h3>
                <input class="p-region p-locality p-street-address p-extended-address textspace" type="text" name="d_address" value="{{$user->d_address ? $user->d_address : ''}}"/>
            </div>
        </div>

         <!-- 配送先２ -->
        <div class="basic_information">
            <h1 class="bold t_center">配送先2</h1>
            <div class="new">
                <h3>お名前</h3>
                <input type="text" name="d_name2" value="{{$user->d_name2 ? $user->d_name2 : ''}}" class="textspace" />
            </div>
            <div class="new">
                <h3>電話番号</h3>
                <input maxlength="11" class="textspace" type="tel" name="d_tel2" value="{{$user->d_tel2 ? $user->d_tel2 : ''}}"/>
            </div>
            <div class="new">
                <h3>郵便番号</h3>
                <span class="p-country-name" style="display:none;">Japan</span>
                <input id="postcode2" class="p-postal-code textspace"  size="8" type="text" name="d_postcode2" value="{{$user->d_postcode2 ? $user->d_postcode2 : ''}}" maxlength="7" />
            </div>
            <div class="new">
                <h3>住所</h3>
                <input class="p-region p-locality p-street-address p-extended-address textspace" type="text" name="d_address2" value="{{$user->d_address2 ? $user->d_address2 : ''}}"/>
            </div>
        </div>

        <div class="t_center moshideli_btm" style="margin-top:1.5em;">
            <div class="w_80">
                <input style="font-size:1.0em;" onclick="return really_change();" class="loginpage_btn_a" type= "submit" value="変更する">
            </div>
        </div>
    </form>
</section>

<script>
     function really_change(){
        var post_address = document.getElementById('postcode1').value;
        var post_address2 = document.getElementById('postcode2').value;
        if(post_address.length != 7){
            alert('郵便番号を正しく入力してください。');
            return false;
        }
        var post_address = String(post_address);
        var post_address2 = String(post_address2);
        var t_post_check = post_address.substring(0,3);
        var t_post_check2 = post_address2.substring(0,3);
        // console.log(t_post_check);
        var post_list = ['1920353','1920354','1920363','1920362','1920355','1920361','1920352'];//多摩市以外の住所を配列にする
        var p_flag = false;//配送フラグ
        var p_flag2 = false;//配送フラグ
        for($i = 0; $i < post_list.length; $i++){
            if(t_post_check == 206 || t_post_check == ''){p_flag = true; break;}//多摩市の場合
            if(post_address == ''){p_flag = true; break;}//多摩市の場合
            if(post_list[$i] == post_address){//それ以外の配送地域の場合
                p_flag = true;
                break;
            }
        }
        for($i = 0; $i < post_list.length; $i++){
            if(t_post_check2 == 206 || t_post_check2 == ''){p_flag2 = true; break;}//多摩市の場合
            if(post_address2 == ''){p_flag2 = true; break;}//多摩市の場合
            if(post_list[$i] == post_address2){//それ以外の配送地域の場合
                p_flag2 = true;
                break;
            }
        }
        if(!p_flag || !p_flag2){//エリア外の場合
            alert('配送エリア外です。住所を確認して下さい。');
            return false;
        }
        var result = confirm('本当に変更しますか？');
        if(result) {
            document.querySelector('#r_change').submit();
        } else {
            return false;
        }
    }
</script>

@include('public/footer')