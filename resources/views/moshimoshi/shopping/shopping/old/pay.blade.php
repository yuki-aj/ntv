@include('public/header')
<script>
    var all_summary = "{{$all_summary}}";
    var c_flag      = "{{$user->corporation_flag}}";
</script>

<style>
    .contact-body {
        padding:10px 0;
    }
    /*タブ切り替え全体のスタイル*/
    .tabs {
        margin-top: 50px;
        padding-bottom: 40px;
        background-color: #fff;
        width: 100%;
        margin: 0 auto;
    }
    /*タブのスタイル*/
    .tab_item {
        min-width: 0 !important;
        width:33.33333%;
        height: 40px;
        border-bottom: 3px solid #5ab4bd;
        background-color: #d9d9d9;
        line-height: 40px;
        font-size: 14px;
        text-align: center;
        color: #565656;
        display: block;
        float: left;
        text-align: center;
        font-weight: bold;
        transition: all 0.2s ease;
    }
    .tab_item:hover {
        opacity: 0.75;
    }
    /*ラジオボタンを全て消す*/
    .input_none,.hidden {
        display: none;
    }
    .show {
        display:block;
    }
    /*タブ切り替えの中身のスタイル*/
    .tab_content {
        display: none;
        padding: 40px auto 0;
        clear: both;
        overflow: hidden;
        margin-top:43px;
    }
    /*選択されているタブのコンテンツのみを表示*/
    #all:checked ~ #all_content,
    #programming:checked ~ #programming_content,
    #design:checked ~ #design_content {
    display: block;
    }

    /*選択されているタブのスタイルを変える*/
    .tabs input:checked + .tab_item {
    background-color: #5ab4bd;
    color: #fff;
    }
    #card_label,#au_label,#cash_label {
        width:50%;
    }
    .select_times {
        font-size:0.8em;
    }
</style>

<section>
    <h1 class="baskets">決済</h1>
        <h2 class="orange bold t_center">注文はまだ確定していません。</h2>
    <div class="desc_w60">  
        <div class="p_3">
            <form id="p_form" action="{{url('ordercompletion')}}" method="POST">
            @csrf
                <p class="bold t_center m_top5">配送先情報</p>
                <table class="contact_table" style="width:100%;">
                    <tr>
                        <th class="contact-item"><p class="line_gray">配送希望日</p></th>
                        <td class="contact-body">
                            <select name="delivery_date" id="delivery_date" class="numes select_times" style="width:100%" onChange="ChangeDate();">
                                @foreach($week_schedules as $key =>$date)
                                <option class="date_selected" value="{{$key}}" {{isset($apptdate) && ($apptdate == $key) ? 'selected' : ''}}>{{$date['day']}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item"><p class="line_gray">使用可能なクーポン</p></th>
                        <td class="contact-body">
                            <select onchange="coupon_check()" name="coupon" id="coupon" class="numes" form="coupon_check">
                                <option disabled selected>選択する</option>
                                @if($one_coupon != '')
                                <option value="0">使用しない</option>
                                @endif
                                @foreach($coupons as $key => $coupon)
                                @if($one_coupon != '' && $coupon->id == $one_coupon->id)
                                <option value="{{$coupon->id}}" selected>{{$coupon->title}}</option>
                                @else
                                <option value="{{$coupon->id}}">{{$coupon->title}}</option>
                                @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item"><p class="line_gray">お名前</p></th>
                        <td class="contact-body">
                            <input id="name" class="padding10" name="name" type="text" value="{{isset($user->name) ? $user->name: ''}}"placeholder="名前を入力してください" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">
                            <label class="ECM_Radio-Input line_gray flexbox" for="check_1" style="vertical-align:middle; justify-content:flex-start;">配送先1
                                <input class="ECM_RadioInput-Input" type="radio" id="check_1" name="check_address" value="1" style="vertical-align:middle; margin:0 5px;">
                                <span class="ECM_RadioInput-DummyInput"></span>
                                <span class="ECM_RadioInput-LabelText"></span>
                            </label>
                        </th>
                        <td class="contact-body h-adr">
                            <span class="p-country-name" style="display:none;">Japan</span>
                            <input id="name1" class="padding10" style="margin-top:3%;" name="name1" type="text" value="{{isset($user->d_name) ? $user->d_name: ''}}"placeholder="名前を入力してください">
                            <input class="p-postal-code padding10" style="margin-top:3%;" id="postcode1" name="postcode1" type="text" type="text" size="8" maxlength="8" placeholder="郵便番号" value="{{isset($user->d_postcode) ? $user->d_postcode :''}}">
                            <input id="address1" style="margin-top:3%;" class="p-region p-locality p-street-address p-extended-address padding10" name="address1" type="text" placeholder="住所" class="padding10" value="{{isset($user->d_address) ? $user->d_address: ''}}"/>
                            <input id="d_tel1" style="margin-top:3%;"name="d_tel1" type="tel" placeholder="電話番号" class="padding10" value="{{isset($user->d_tel) ? $user->d_tel: ''}}" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item">
                            <label class="line_gray flexbox" for="check_2" style="vertical-align:middle; justify-content:flex-start;">配送先2
                                <input class="ECM_RadioInput-Input" type="radio" id="check_2" name="check_address" value="2" style="vertical-align:middle; margin:0 5px;">
                                <span class="ECM_RadioInput-DummyInput"></span>
                                <span class="ECM_RadioInput-LabelText"></span>
                            </label>
                        </th>
                        <td class="contact-body h-adr">
                            <span class="p-country-name" style="display:none;">Japan</span>
                            <input id="name2" style="margin-top:3%;" class="padding10" name="name2" type="text" value="{{isset($user->d_name2) ? $user->d_name2: ''}}"placeholder="名前を入力してください">
                            <input class="p-postal-code padding10" style="margin-top:3%;" id="postcode2" name="postcode2" type="text" type="text" size="8" maxlength="8" placeholder="郵便番号" value="{{isset($user->d_postcode2) ? $user->d_postcode2 :''}}">
                            <input id="address2" style="margin-top:3%;" class="p-region p-locality p-street-address p-extended-address padding10" name="address2" type="text" placeholder="住所" value="{{isset($user->d_address2) ? $user->d_address2: ''}}"/>
                            <input id="d_tel2" style="margin-top:3%;" name="d_tel2" type="tel" placeholder="電話番号" class="padding10" value="{{isset($user->d_tel2) ? $user->d_tel2: ''}}" >
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item"><p class="line_gray">メールアドレス</p></th>
                        <td class="contact-body">
                            <input class="padding10" name="email" type="email" placeholder="" value="{{isset($user->email) ? $user->email : ''}}" required readonly>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item"><p class="line_gray">配送希望時間</p></th>
                        <td class="contact-body">
                            <select name="delivery_time" id="delivery_time" class="numes">
                                @foreach($time_schedules as $key =>$d_time)
                                <option value="{{$d_time}}">{{$d_time}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="contact-item"><p class="line_gray">カトラリー(お箸・スプーンなど)</p></th>
                        <td class="contact-body">
                            <select name="cutlery" id="cutlery" class="numes">
                                <option disabled selected>選択する</option>
                                <option value="1">あり</option>
                                <option value="2">なし</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div>
                    <p class="flexbox line_gray m_top5 m_btm">お支払方法選択</p>
                    @if($user->corporation_flag == 3)
                    <!-- もしもしユーザーの場合 -->
                    <div class="tabs">
                        <input class="input_none" id="programming" type="radio" name="c_flag" value="10" onchange="pay_kind_check(this.value,)">
                        <label id="au_label" class="tab_item" for="programming">au PAY</label>
                        <input class="input_none" id="design" type="radio" name="c_flag" value="20" onchange="pay_kind_check(this.value,)">
                        <label id="cash_label" class="tab_item" for="design">代引</label>
                        <div class="tab_content padding3" id="programming_content">
                            au PAYでお支払いの場合、商品受け取り時に配達員に直接お支払いください。
                        </div>
                        <div class="tab_content padding3" id="design_content">
                            代引の場合、下記のお支払金額に+300円かかります。
                        </div>
                    </div>
                    @elseif($user->corporation_flag == 2 && 5000 <= $all_summary)
                    <!-- 法人で5000円以上の場合 -->
                    <div class="tabs">
                        <input class="input_none" id="all" type="radio" name="c_flag" value="01" onchange="pay_kind_check(this.value,)">
                        <label class="tab_item" for="all">カード決済</label>
                        <input class="input_none" id="programming" type="radio" name="c_flag" value="10" onchange="pay_kind_check(this.value)">
                        <label class="tab_item" for="programming">au PAY</label>
                        <input class="input_none" id="design" type="radio" name="c_flag" value="20" onchange="pay_kind_check(this.value)">
                        <label class="tab_item" for="design">代引</label>
                        <div class="tab_content padding3" id="all_content">
                            @if($user->last4 != "")
                            <p>カード番号</p>
                                <input id="last4" class="width50" name="cardnumber" type="text" placeholder="" value="{{isset($user->last4) ? '********'.$user->last4 : ''}}" disabled >
                            <p>有効期限</p>
                                <input id="expiration" class="width50" name="expiration" type="text" placeholder="" value="{{isset($user->expired) ? $user->expired : ''}}" disabled >
                            <div class="m_top3">
                                <a href="/myprofile_payment">
                                  <p style="color:green;">※他のカードをご利用の場合はこちら</p>
                                </a>
                            </div>
                            @else
                            <p>お客様カード情報がありません。<br>カード情報を登録してください。</p>
                            <div class="m_top3">
                                <input id="last4" name="cardnumber" type="hidden">
                                <input id="expiration" name="expiration" type="hidden">
                                <a style="text-decoration: underline;" href="/new_payment">カード情報を登録する</a>
                            </div>
                            @endif
                        </div>
                        <div class="tab_content padding3" id="programming_content">
                            au PAYでお支払いの場合、商品受け取り時に配達員に直接お支払いください。
                        </div>
                        <div class="tab_content padding3" id="design_content">
                            代引の場合、代引き手数料は無料です。
                        </div>
                    </div>
                    @else
                    <!-- 個人と法人5000円以下の場合 -->
                    <div class="tabs">
                        <input class="input_none" id="all" type="radio" name="c_flag" value="01">
                        <label id="card_label" class="tab_item" for="all">カード決済</label>
                        <input class="input_none" id="programming" type="radio" name="c_flag" value="10">
                        <label id="au_label" class="tab_item" for="programming">au PAY</label>
                        <div class="tab_content padding3" id="all_content">
                            @if($user->last4 != "")
                            <p>カード番号</p>
                                <input id="last4" class="width50" name="cardnumber" type="text" placeholder="" value="{{isset($user->last4) ? '********'.$user->last4 : ''}}" disabled >
                            <p>有効期限</p>
                                <input id="expiration" class="width50" name="expiration" type="text" placeholder="" value="{{isset($user->expired) ? $user->expired : ''}}" disabled >
                            <div class="m_top3">
                                <a href="/myprofile_payment">
                                  <p style="color:green;">※他のカードをご利用の場合はこちら</p>
                                </a>
                            </div>
                            @else
                            <p>お客様カード情報がありません。<br>カード情報を登録してください。</p>
                                <input id="last4" name="cardnumber" type="hidden">
                                <input id="expiration" name="expiration" type="hidden">
                            <div class="m_top3">
                                <a style="text-decoration: underline" href="/new_payment">カード情報を登録する</a>
                            </div>
                            @endif
                        </div>
                        <div class="tab_content padding3" id="programming_content">
                            au PAYでお支払いの場合、商品受け取り時に配達員に直接お支払いください。
                        </div>
                    </div>
                    @endif
                </div>
                <div class="m_tops">
                    <th class="contact-item"><p class="line_gray">ギフトメッセージ</p></th>
                    <td class="contact-body">
                        <textarea id="gift" name="gift" placeholder="宛名・差出人・メッセージをご入力ください。
もしデリのオリジナルカードにスタッフが代筆して商品と共にお届けします。" class="form-textareas"></textarea>
                    </td>
                </div>
                <div class="m_tops">
                    <th class="contact-item"><p class="line_gray">備考欄</p></th>
                    <td class="contact-body">
                        <textarea id="note" name="note" placeholder="ご要望がございましたらご記入下さい。" class="form-textareas"></textarea>
                    </td>
                </div>
                <p class="bold t_center">注文内容</p>
                <div class="priceBox" style="border-top:0; border-bottom:none; padding:0; margin:0;">
                    <?php $count = 0?>
                    @foreach($new_carts as $stripe_id => $s_products)
                    <?php $summary = 0 ?>
                    @if($stripe_id == $pay_id)
                    <div class="p_3 m_top3">
                    @foreach ($s_products as $key => $product)
                        <dl class="price cf" style="padding-top:2%; border-top:1px solid #ccc;">
                            <dt>商品名</dt>
                            <dd><span class="basePri ormtexyce1">{{$product['name']}}</span></dd>
                        </dl>
                        <dl class="price cf">
                            <dt>価格</dt>
                            <dd><span class="basePrice basePrice1">{{number_format($product['price'])}}</span>円</dd>
                        </dl>
                        @if($product['option_1'] != '')
                        <dl class="price cf">
                            <dt>{{$product['o_name1']}}</dt>
                            <dd><span class="optionTotal optionTotal1">{{number_format($product['o_price1'])}}円</span></dd>
                        </dl>
                        @endif
                        @if($product['option_2'] != '')
                        <dl class="price cf">
                            <dt>{{$product['o_name2']}}</dt>
                            <dd><span class="optionTotal optionTotal1">{{number_format($product['o_price2'])}}円</span></dd>
                        </dl>
                        @endif
                        @if($product['option_3'] != '')
                        <dl class="price cf">
                            <dt>{{$product['o_name3']}}</dt>
                            <dd><span class="optionTotal optionTotal1">{{number_format($product['o_price3'])}}円</span></dd>
                        </dl>
                        @endif
                        @if(isset($product['option_4']) && $product['option_4'] != '')
                        <dl class="price cf">
                            <dt>{{$product['o_name4']}}</dt>
                            <dd><span class="optionTotal optionTotal1">{{number_format($product['o_price4'])}}円</span></dd>
                        </dl>
                        @endif
                        <dl class="price cf">
                            <dt>数量</dt>
                            <dd><span class="basePrice basePrice1">{{$product['quantity']}}</span></dd>
                        </dl>
                        @if(isset($product['d_price']))
                        <dl class="price cf" style="border-bottom:1px solid #ccc; padding-bottom:2%;">
                            <dt>商品割引額</dt>
                            <dd><span class="basePrice basePrice1">-{{number_format($product['d_price'])}}</span>円</dd>
                        </dl>
                        @endif
                        <!-- <dl class="price cf" style="border-bottom:1px solid #ccc; padding-bottom:2%;">
                            <dt>商品合計</dt>
                            <dd><span class="basePrice basePrice1 p_total">{{number_format($product['total'])}}</span>円</dd>
                        </dl> -->
                        <div class="w_90 border-bottom:1px solid #000;"></div>
                    <?php $summary += $product['total'];?>
                    <?php $count += $product['quantity']?>
                    @endforeach
                    <dl class="border_top price cf">
                        <dt>小計</dt>
                        <dd><span class="deriveryPrice">{{number_format($summary)}}</span>円</dd>
                    </dl>
                    @if($one_coupon != '')
                    <dl class="price cf">
                        <dt>クーポン利用</dt><br>
                        <dt class="m_left">{{$one_coupon->title}}</dt>
                        <dd><span class="deriveryPrice">-{{$one_coupon->discount}}</span></dd>
                    </dl>
                    @endif
                    @if(session('flash_message'))
                    <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
                    @endif
                    @if($d_amount != '')
                    <dl class="price cf">
                        <dt>割引後合計金額</dt>
                        <dd><span class="deriveryPrice">{{number_format($temp_summary)}}</span>円</dd>
                    </dl>
                    @endif
                    @if($d_post_amount != $postage->price && $d_post_amount != $parcent_amount)
                    <dl class="price cf">
                        <dt>割引前配送料</dt>
                        <dd><span class="deriveryPrice">{{number_format($d_post_amount)}}</span>円</dd>
                    </dl>
                    @endif
                    <dl class="price cf">
                        <dt>配送料</dt>
                            @if($temp_summary <= 1500)
                            <dd><span class="deriveryPrice">{{number_format(floor($postage_price))}}</span>円</dd>
                            <input type="hidden" name="summary" value="{{$postage->price}}">
                            @elseif(3000 <= $temp_summary)
                            <dd><span class="deriveryPrice">{{number_format(floor($postage_price))}}</span>円</dd>
                            <input type="hidden" name="summary" value="{{$postage->price}}">
                            @else
                            <dd><span class="deriveryPrice">25%({{number_format(floor($postage_price))}}円)</span></dd>
                            <input type="hidden" name="summary" value="{{$postage->price}}">
                            @endif
                    </dl>
                    @if($user->corporation_flag == 3)
                        <dl class="price cf hidden" id="cod">
                            <dt>代引き手数料</dt>
                            <dd><span class="deriveryPrice">300</span>円</dd>
                        </dl>
                    @elseif($user->corporation_flag == 2 && 5000 <= $all_summary)
                        <dl class="price cf hidden" id="cod">
                            <dt>代引き手数料</dt>
                            <dd><span class="deriveryPrice">0</span>円</dd>
                        </dl>
                    @else
                    @endif
                    <dl class="border_t_b padding_t_b price cf ">
                        <dt>お支払い金額</dt>
                        <dd><span id="all_summary" class="total total1">{{number_format(floor($all_summary))}}</span>円</dd>
                    </dl>
                    </div>
                    <div class="w_80 t_center m_top5 moshideli_btm">
                        @if($one_coupon != '')
                        <input type="hidden" name="coupon" value="{{$one_coupon->id}}">
                        @endif
                        <input type="hidden" name="order_flag" value="1">
                        <input type="hidden" name="s_id" value="{{$store->id}}">
                        <button class="go_cart" onclick="card_check();return false;"><span style="font-size:1.3em;">購 入</span></button>
                    </div>
                        @endif
                    @endforeach
                </div>
                <div class="clear"></div>
                <div style="margin-bottom:4.0em;"></div>

            </form>
            <form name="c_check" method="POST" action="{{url('/pay')}}" id="coupon_check">
                @csrf
                <input type="hidden" name="store_id" value="{{$store->id}}">
            </form>
        </div>
    </div>
</section>

<script>
    var summary = document.getElementById('all_summary');
    var text = summary.textContent;
    function pay_kind_check(value) {
        // console.log(value);
        var cod = document.getElementById('cod');
        if(value == 20 && c_flag != 3) {
            cod.classList.remove('hidden');
            cod.classList.add('show');
            summary.textContent = text;
        }else if(value == 20 && c_flag == 3){
            cod.classList.remove('hidden');
            cod.classList.add('show');
            a_summary = parseInt(all_summary) + 300;
            summary.textContent = a_summary;
        }else{
            cod.classList.add('hidden');
            cod.classList.remove('show');
            summary.textContent = text;
        }
    }
    function ChangeDate() {
        var Change_date = document.getElementById('delivery_date').value;
        var temp_url = '/update_apptdate/';
        var send_url = temp_url.concat(Change_date);
        window.location.href = send_url; // 通常の遷移
    }
    function coupon_check() {
        var coupon_check = document.getElementById('coupon').value;
        document.c_check.submit();
    }
    function card_check(){
        let check_address = document.getElementsByName('check_address');
        let d_flag = false;
        for (let i = 0; i < check_address.length; i++) {
            if (check_address[i].checked) {
                var temp_d = check_address[i].value;
                d_flag = temp_d;
            }
            // console.log(temp_d);
            temp_d = "";
        }
        if(!d_flag){//エリア外の場合
            alert('配送先住所を選択してください。');
            return false;
        }else if(d_flag == 1) {
            var post_address = document.getElementById('postcode1').value;
            var name         = document.getElementById('name1').value;
            var address      = document.getElementById('address1').value;
            var tel          = document.getElementById('d_tel1').value;
        }else if(d_flag == 2) {
            var post_address = document.getElementById('postcode2').value;
            var name         = document.getElementById('name2').value;
            var address      = document.getElementById('address2').value;
            var tel          = document.getElementById('d_tel2').value;
        }
        if(post_address == ''){
            alert('郵便番号を入力して下さい。')
            return false;
        }
        if(name == ''){
            alert('名前を入力してください。')
            return false;
        }
        if(address == ''){
            alert('住所を入力してください。')
            return false;
        }
        if(tel == ''){
            alert('電話番号を入力してください。')
            return false;
        }
        // var date_selected = document.getElementById('delivery_date');
        var gift = document.getElementById('gift');
        var g_count = gift.value.length;
        var note = document.getElementById('note');
        var n_count = note.value.length;
        var t_length = g_count + n_count;
        console.log(t_length);
        if(t_length > 1900){
            alert('ギフト、備考欄の文字数オーバーです。文字数を減らしてお試しください。');
            return false;
        }
        var date_selected = document.getElementById('delivery_date');
        var x = date_selected.selectedIndex;
        let d_date  = date_selected.options[x].text;
        // console.log(x);
        // console.log(d_date);
        // var post_address = document.getElementById('postcode').value;
        if(post_address.length != 7){
            alert('郵便番号を正しく入力してください。');
            return false;
        }
        // check_p_code = post_address.match(/^\d{3}-\d{4}$/);//000-0000の形式で入力されているか判定
        // // console.log(check_p_code);
        // if(!check_p_code){
        //     alert('郵便番号は"-"を入れて入力してください。');
        //     return false;
        // }
        // var t_post_check = post_address.substring(0, post_address.indexOf("-"));//多摩市の場合206から始まる
        // substring(0.2)
        var post_address = String(post_address);
        var t_post_check = post_address.substring(0,3);
        // console.log(t_post_check);
        var post_list = ['1920353','1920354','1920363','1920362','1920355','1920361','1920352'];//多摩市以外の住所を配列にする
        var p_flag = false;//配送フラグ
        for($i = 0; $i < post_list.length; $i++){
            if(t_post_check == 206){p_flag = true; break;}//多摩市の場合
            if(post_list[$i] == post_address){//それ以外の配送地域の場合
                p_flag = true;
                break;
            }
        }
        if(!p_flag){//エリア外の場合
            alert('配送エリア外です。住所を確認して下さい。');
            return false;
        }
        var cutlery = document.getElementById('cutlery').value;
        if(cutlery == '選択する') {
            alert('カトラリーを選択してください。');
            return false;
        }
        var pay_check = document.getElementById('all').checked;
        if(pay_check == true){
            var card       = document.getElementById('last4').value;
            var expiration = document.getElementById('expiration').value;
            if (card == "" || expiration == "") {
                alert('クレジットカード情報を登録して下さい。');
                return false;
            }
        }
        var pay_check = document.getElementsByName('c_flag');
        var flag = false;
        for (i = 0; i < pay_check.length; i++) {
            if (pay_check[i].checked) {
                flag = true;
                document.querySelector('#p_form').submit();
                break;
            }
        }
        if(!flag){
            alert('支払い方法を選択してください。');
            return false;
        }
    }
</script>

@include('public/footer')