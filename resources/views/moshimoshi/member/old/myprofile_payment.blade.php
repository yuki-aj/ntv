@include('public/header')

<script>
    var stripe_pk = "{{$stripe_pk}}";
</script>

<section>
    @if(Session::get('carts'))
        <div class="product_baskets">
            <div class="flex">
                <a href="/pay">
                    <div style="font-size:1.0em;"><span class="left material-symbols-outlined">arrow_circle_left</span></div>
                </a>
    @else
        <div class="product_baskets">
            <div class="flex">
                <a href="/mypage">
                    <div><span style="font-size:1.2em; padding-top:0.3em" class="left material-symbols-outlined">arrow_circle_left</span></div>
                </a>
    @endif
                <div class="bold">お支払い方法の変更</div>
                <div></diV>
            </div>
        </div>
    <div class="change_page h_375">
        @if(!empty($default_card))
        <div class="history p_3">
            <h3>お支払カード情報</h3>
            <div class="flexbox space-between">
                <p>カード会社名</p>
                <p class="width50">{{$default_card['brand']}}</p>
            </div>
            <div class="flexbox space-between">
                <p>カード名義人</p>
                <p class="width50">{{$default_card['name']}}</p>
            </div>
            <div class="flexbox space-between">
                <p>カード番号下４桁</p>
                <p class="width50">{{$default_card['number']}}</p>
            </div>
            <div class="flexbox space-between">
                <p>有効期限</p>
                <p class="width50">{{$default_card['exp_month']}}月/{{$default_card['exp_year']}}年</p>
            </div>
        </div>
        @endif
        <div class="history p_3">
            <h3>クレジットカード追加</h3>
            <div class="card-body">
                @if(Session::has('default_card') && Session::has('u_id'))
                <form action="{{url('addcard')}}" class="card-form" id="form_payment" method="POST"><!--storeに送る-->
                @else
                <form action="{{url('addnewcard')}}" class="card-form" id="form_payment" method="POST"><!--storeに送る-->
                @endif
                @csrf
                    <div class="form-group">
                        <label for="cardNumber">カード番号</label>
                        <div id="cardNumber"></div>
                    </div>

                    <div class="form-group">
                        <label for="nasecurityCodeme">セキュリティコード</label>
                        <div id="securityCode"></div>
                    </div>

                    <div class="form-group">
                        <label for="expiration">有効期限</label>
                        <div id="expiration"></div>
                    </div>
                    <div class="form-group flexbox space-between">
                        <div><label for="cardName">カード名義</label></div>
                        <input type="text" name="cardName" id="cardName" class="form-control" value="" placeholder="カード名義を入力" required>
                    </div>
                    <div class="form-group flexbox m_top3">
                        @if(isset($default_card))
                        <button type="submit" id="create_token" class="btn orange_btn">カードを追加する</button>
                        @else
                        <button type="submit" id="create_token" class="btn orange_btn">カードを登録する</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        @if($sub_card != [])
        <div class="history p_3">
            <h3>登録済カード情報</h3>
            @foreach($sub_card as $key => $s_card)
                <div class="flexbox space-between">
                    <p>カード会社</p>
                    <p class="width50">{{$s_card['brand']}}</p>
                </div>
                <div class="flexbox space-between">
                    <p>カード番号下４桁</p>
                    <p class="width50">{{$s_card['number']}}</p>
                </div>
                <div class="flexbox space-between">
                    <p>有効期限</p>
                    <p class="width50">{{$s_card['exp_month']}}月/{{$s_card['exp_year']}}年</p>
                </div>
                <div class="flexbox">
                    <div class="width100">
                        <form class="flexbox" action="{{url('switchcard')}}"  method="POST" onsubmit="return switchcard()">
                            @csrf
                            <input type="hidden" name="c_id" value="{{$s_card['id']}}">
                            <button class="btn orange_btn">支払カードに変更する</button>
                        </form>
                        <form class="flexbox" action="{{url('deletecard')}}"  method="POST" onsubmit="return deletecard()">
                            @csrf
                            <input type="hidden" name="c_id" value="{{$s_card['id']}}">
                            <button class="btn orange_btn">カード情報削除</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<script src="https://js.stripe.com/v3/"></script>
<script src="js/payment.js"></script>
<script>
    function deletecard(){
        if(window.confirm('本当に削除しますか？')){
        }else{
            return false;
        }
    }
</script>

@include('public/footer')