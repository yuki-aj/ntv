@include('public/header')

<script>
    var stripe_pk = "{{$stripe_pk}}";
</script>

<div class="header_img">
    <a href="/"><img src="{{('img/headerlogo.png')}}"></a>
</div>

<section class="desc_w60">
    <div class="change_page h_375">
        <h1>{{$user->name}} 様</h1>
        <div class="history">
            <div class="p_3">
                <h1>新規カード登録</h1>
                <div class="card-body">
                    <form action="{{url('addnewcard')}}" class="card-form" id="form_payment" method="POST">
                        @csrf
                        <div class="form-group">
                            <div><label for="cardNumber">カード番号</label></div>
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

                        <div class="form-group space-between">
                            <div><label for="cardName">カード名義</label></div>
                            <input type="text" name="cardName" id="cardName" class="form-control" value="" placeholder="カード名義を入力" required>
                        </div>
                        <div class="t_center m_top3">
                            <input type="hidden" name="c_customer" value="1">
                            <button type="submit" id="create_token" class="addition">カードを登録する</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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