@include('public/header')
<div class="container">
    <p>ヘッダー</p>
</div>
<h1 class="baskets">決済</h1>
<h2 class="orange confirm bold">注文はまだ確定していません。</h2>
<div class="w_90">
    <h3 class="gray">
        <a href="/">ホーム</a> >
        <a href="/cart">カート</a> >
        <a href="/unregistered_pay">支払い</a>
    </h3>

</div>
<form action="">
    <h2></h2>

    <div class="p_3">
        <p class="line_gray">[ 注文者情報 ]</p>
        <div class="m_tb">
            <div class="content1_2">
                <p class="line_gray">お名前</p>
                <div class="flex m_t3">
                <p>姓：</p>
                <input type="text" placeholder="例）山田 " name="" class="textspace" required />
        </div>
        <div class="flex m_t3">
                <p>名：</p>
                <input type="text" placeholder="例）太郎" name="" class="textspace" required />
        </div>
        </div>
        <div class="m_tb">
            <div class="content1_2">
                <p class="line_gray">お届け先</p>
                <div class="m_t3">
                    <p>注文者住所:
                    <input type="text" placeholder="例）多摩市豊ヶ丘1-1-1 " name="" class="textspace" required />
                    </p>
                </div>
                <div class="m_t3">
                    <p>配送先住所:
                    <input type="text" placeholder="例）多摩市豊ヶ丘1-1-1 " name="" class="textspace" required />
                    </p>
                </div>
            </div>
        </div>
        <form>
                <label class="ECM_RadioInput m_0">
                        <input class="ECM_RadioInput-Input button" type="radio" id="sbh1" name="test"/>
                    <div class="workflex">
                        <span class="ECM_RadioInput-DummyInput"></span>
                        <span class="ECM_RadioInput-LabelText">職場利用</span>
                    </div>
                        <div class="sbh1">
                            <p>事業所名<input class="textspace" type="text"></p>
                            <p>会社住所<input class="textspace" type="text"></p>
                        </div>
                </label>
        </form>


        <div class="m_tb">
            <div class="content1_2">
                <p class="line_gray">電話番号</p>
                <input type="tel" placeholder="例）03-1234-5678" name="" class="textspace" required />
            </div>
        </div>
        <div class=" m_tb">
            <div class="content1_2">
                <p class="line_gray">メール</p>
                <input type="tel" placeholder="例）moshimoshi@delivery.com" name="" class="textspace" required />

            </div>

        </div>

        <p class="line_gray">支払い方法</p>
        <div class="content1_2">

            <div class="acflex">
                <div>
                    <!-- <input type="radio" id="huey" name="drone" value="huey" checked> -->
                    <label class="ECM_RadioInput2 m_0">
                        <input class="ECM_RadioInput-Input2" type="radio" name="radio2">
                        <span class="ECM_RadioInput2-DummyInput"></span>
                        <span class="ECM_RadioInput2-LabelText">クレジットカード</span>
                    </label>
                    <!-- <label for="huey" class="line_gray">クレジットカード</label> -->
                </div>
                <div class="content1_2_1">
                    <a href="/payments">
                        <h3>変更</h3>
                    </a>
                </div>
            </div>
            <div class="m_tb">
                <p>カード番号</p>
                <input type="text" placeholder="" name="" class="textspace" />
                <p>有効期限</p>
                <input type="text" placeholder="" name="" class="textspace" />
                <p>セキュリティ番号</p>
                <input type="text" placeholder="" name="" class="textspace" />
            </div>
            <div class="line m_tb">
                <ul>
                    <label class="ECM_RadioInput2 m_0">
                        <input class="ECM_RadioInput-Input2" type="radio" name="">
                        <span class="ECM_RadioInput2-DummyInput"></span>
                        <span class="ECM_RadioInput2-LabelText">au pay</span>
                    </label>
                </ul>
                <ul>
                    <div class="target">
                        <label class="ECM_RadioInput2 m_0">
                            <input class="ECM_RadioInput-Input2" type="radio" name="">
                            <span class="ECM_RadioInput2-DummyInput"></span>
                            <span class="ECM_RadioInput2-LabelText">代引き
                                <h3>職場利用+5000円以上</h3>
                            </span>
                        </label>
                    </div>
                </ul>
            </div>
        </div>
        <div class="m_tb">
        <p class="line_gray">領収書</p>
            <form autocomplete=off>
                <div class="flex m_t3">
                【
                    <label class="flex">
               
                        <input class="js-check ECM_RadioInput-Input2" type="radio" name="rs" value="1" onclick="formSwitch()" >
                        <span class="ECM_RadioInput2-DummyInput"></span>
                        <span class="ECM_RadioInput2-LabelText">発行しない</span>

                    </label>
                    /
                    <label class="flex">
                        <input class="js-check ECM_RadioInput-Input2" type="radio" name="rs" value="1" onclick="formSwitch()">
                        <span class="ECM_RadioInput2-DummyInput"></span>
                            <span class="ECM_RadioInput2-LabelText">発行する</span>
                    </label>
                    】
                </div>
                    <span id="sample">宛名：<input class="textspace" type="text" name="othertext" value="" size="30"> </span>
        </form>
        </div>
                <!-- <h3>[<input type="checkbox"  class="checkbox03" >別住所<input type="checkbox">職場利用 ]</h3> -->
            <!-- </div> -->
            <!-- <div class="content1_2_1">
                <a href="/address">
                    <h3>変更</h3>
                </a>
            </div> -->
        <!-- </div> -->
<!-- 
        <div class=" m_tb">
            <div class="content1_2">
                <p class="line_gray">領収書</p>
                    <div class="flexs">
                    [
                    <label class="ECM_RadioInput m_0">
                        <input class="ECM_RadioInput-Input" type="radio" name="radio">
                        <span class="ECM_RadioInput-DummyInput"></span>
                        <span class="ECM_RadioInput-LabelText">発行しない</span>
                    </label>
                    /
                    <label id="open" class="ECM_RadioInput m_0">
                        <div id="mask" class="hidden"></div>
                        <input class="ECM_RadioInput-Input" type="radio" name="radio">
                        <span class="ECM_RadioInput-DummyInput"></span>
                        <span class="ECM_RadioInput-LabelText">発行する</span>
                    </label>
                    ]
                </div>
                </div>
        </div> -->
        <!-- <div class="line"> -->
        <div class="m_tb acflex">
            <p class="line_gray">配送希望日</p>
            <select class="numes" placeholder="日にち">
                <option value="1">2022年6月7日（火）</option>
                <option value="1">2022年6月8日（水）</option>
                <option value="1">2022年6月9日（木）</option>
                <option value="1">2022年6月10日（金）</option>
                <option value="1">2022年6月11日（土）</option>
            </select>
        </div>
        <div class="m_tb acflex">
            <p class="line_gray">配送希望時間</p>
            <select class="numes" placeholder="時間">
                <option value="1">11:30 - 12:00</option>
                <option value="1">12:00 - 12:30</option>
                <option value="1">12:30 - 13:00</option>
                <option value="1">13:00 - 13:30</option>
                <option value="1">13:30 - 14:00</option>
            </select>
        </div>
        <div class="m_tops">
            <th class="contact-item">
                <p class="line_gray">注文メモ</p>
            </th>
            <td class="contact-body">
                <textarea name="問い合わせ" placeholder="ご要望がございましたらこちらにご記入下さい。" class="form-textareas"></textarea>
            </td>
        </div>
        <!-- <div class="m_tb">
            <p class="line_gray">クーポンのご利用</p>
            <label class="optionbox">
                <input type="checkbox" id="03-C1" name="checkbox03" value="1">
                <label for="03-C1" class="checkbox03"></label>[10% OFFクーポン]
            </label>
            <input type="checkbox">[10% OFFクーポン]
        </div> -->
        <div class="priceBox">
            <dl class="price cf">

                <dt>商品名</dt>
                <dd>
                    <span class="basePrice basePrice1">カレー</span>

                </dd>
            </dl>
            <dl class="price cf">

                <dt>数量</dt>
                <dd>
                    <span class="basePrice basePrice1">2</span>

                </dd>
            </dl>
            <dl class="price cf">
                <dt>基本価格</dt>
                <dd>
                    <span class="basePrice basePrice1">1,900</span>
                    円
                </dd>
            </dl>
            <dl class="price cf">
                <dt>オプション：大盛り/福神漬け</dt>
                <dd>
                    <span class="optionTotal optionTotal1">0</span>
                    円
                </dd>
            </dl>
            <dl class="price cf">
                <dt>配送料</dt>
                <dd>
                    <span class="deriveryPrice">600</span>
                    円
                </dd>
            </dl>
            <dl class="price cf">
                <dt>お支払金額</dt>
                <dd>
                    <span class="total total1">2,500</span>
                    円
                </dd>
            </dl>
        </div>

        <div class="content1_2 m_t3">
                <p class="line_gray">カトラリー（割り箸、おしぼり等）を利用する</p>
                <p class="orange">※必須</p>
                <div class="flex">
                    [
                    <label class="ECM_RadioInput m_0">
                        <input class="ECM_RadioInput-Input" type="radio" name="radio">
                        <span class="ECM_RadioInput-DummyInput"></span>
                        <span class="ECM_RadioInput-LabelText">利用する</span>
                    </label>
                    /
                    <label class="ECM_RadioInput m_0">
                        <input class="ECM_RadioInput-Input" type="radio" name="radio">
                        <span class="ECM_RadioInput-DummyInput"></span>
                        <span class="ECM_RadioInput-LabelText">利用しない</span>
                    </label>
                    ]
                </div>

    </div>

    <div class="confirm">
        <a href="/unregistered_confirm">
            <input class="contact_submit" type="" value="注文する" />
        </a>
    </div>
    <div class="confirm">
        <input class="return" type="" value="戻る" />
    </div>
    </div>
    </div>
</form>

@include('public/footer')