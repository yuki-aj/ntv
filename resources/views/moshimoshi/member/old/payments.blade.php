@include('public/header')

<section class="desc_w60">  
    <div class="b_gray">
        <!-- <div class="p_3"> -->
        <div class="change_page h_375">
            <!-- <div class="flex"> -->
                <h2 class="left"><a href="/mypage" class="btn-circle-3d-emboss"><</a></h2>

                <h1>お支払い方法の変更</h1>
            <!-- </div> -->
            <div class="changepay">
                <h4 class="">クレジットカード</h4>
                <div class="contacts-bodys newpay" id="open">
                                        <label class="ECM_Radio-Input flex left p_10">
                                            <input class="ECM_RadioInput-Input" type="radio" name="radio">
                                            <span class="ECM_RadioInput-DummyInput"></span>
                                            <span class="ECM_RadioInput-LabelText">クレジットカードを新しく追加する</span>
                                        </label>
                
            </div>
            <div class="changepay">
                <h4 class="">その他の支払い方法</h4>
                <div class="contacts-bodys newpay">
                <label class="ECM_Radio-Input flex left p_10">
                                            <input class="ECM_RadioInput-Input" type="radio" name="radio">
                                            <span class="ECM_RadioInput-DummyInput"></span>
                                            <span class="ECM_RadioInput-LabelText">au pay</span>
                                        </label>
                
                    
                    <!-- <input type="text" name="名前" class="changetext" placeholder="moshimoshi@delivery.com"/> -->
                </div>
                <h3 class="gray">au Pay登録済みの端末を事前にご用意いただき、宅配時にもしデリスタッフに提示してください。</h3>
            </div>
            <div class="t_center m_top5">
                <input class="addition" type= "submit" value="変更する">
            </div>
            <!-- <div class="changeok">
                <a href="/mypage">
                    <button class="ok_button">
                        <h2>変更する</h2>
                    </button>
                </a>
            </div> -->
        </div>
        <!-- モーダル ----------------->
        <div id="mask" class="hidden"></div>
            <section id="modal" class="hidden">
                
                <h1>クレジットカード新規追加</h1>
                <div class="p_20">
                <img class="crejit" src ="img/crejitcard.jpg">
                
                    <div class="" >
                        <h3>クレジットカード番号</h3>
                        <input type="number" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"maxlength="16" class="textspace" required>
                    </div>
                    <div class="flex gap w_90">
                        <div class="changeleft ">
                        <h4 class="gray">有効期限</h4>
                            <input type="number" name="" value="" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            maxlength="4" class="changetextpay" placeholder="月/年" required>
                        </div>
                        <div class="changeleft">
                        <h4 class="gray">セキュリティコード</h4>
                            <input type="number" oninput="javascript:if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"  maxlength="4" minlength="3" class="changetextpay" placeholder="123" required>
                            <!-- <h3 class="gray">カード裏面の3桁/4桁の数</h3> -->
                        </div>
                    </div>
                    
                    <div class="close-btn" id="close"><i class="fas fa-times"></i></div>
                
                    <div class="changeok">
                <a href="/payment">
                    <button class="ok_button">
                        <h2>追加する</h2>
                    </button>
                </a>
            </div>
                
                        <!-- <div id="close">
                            <a href="/changepay">
                                <p>閉じる</p>
                            </a>
                        </div> -->
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>

@include('public/footer')