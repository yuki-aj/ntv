<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <script src="{{ asset('public/js/app.js') }}"></script>
                    <form action="{{ asset('subscribe_process') }}" method="POST">
                        {{ csrf_field() }}
                            <script
                                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                    data-key="{{ env('STRIPE_KEY') }}"
                                    data-amount="1000"
                                    data-name="Stripe Demo"
                                    data-label="定期決済をする"
                                    data-description="Online course about integrating Stripe"
                                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                    data-locale="auto"
                                    data-currency="JPY">
                            </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="https://buy.stripe.com/test_7sI2ao6fsc63clabIM">test</a>

<div class="content">
    <form action="{{ asset('charge') }}" method="POST">
        @csrf
            <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="{{ env('STRIPE_KEY') }}"
                    data-amount="1000"
                    data-name="もしもしデリバリー Demo"
                    data-label="決済をする"
                    data-description="もしデリ テスト"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                    data-locale="ja"
                    data-currency="JPY">
            </script>
    </form>
    <div>
        @if(isset($msg))
        {{ $msg }}<br>
        @endif
        @if(isset($customer))
        お客様メールアドレス<br>{{$customer->email}}<br>
        @endif
        @if(isset($charge))<br>
        お客様id<br>{{$charge->id}}<br>
        金額 {{$charge->amount}} 円<br>
        @endif
    </div>
</div>