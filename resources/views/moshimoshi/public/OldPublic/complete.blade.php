@include('public/header')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (Session::has('u_id'))
                        <div class="alert alert-success" role="alert">
                            {{$user->name}}<br>
                            {{$user->email}}<br>
                            お買い上げありがとうございました。
                        </div>
                    @endif
                    @if(isset($msg))
                        {{$msg}}<br>
                    @endif
                    <?php $summary = 0 ?>
                    @foreach($p_carts as $p_id => $product)
                    {{$product['name']}}<br>
                    {{$product['price']}}<br>
                    {{isset($product['o_name1']) ? $product['o_name1'] : ''}} {{isset($product['o_price1']) ? $product['o_price1'] : ''}}<br>
                    {{isset($product['o_name2']) ? $product['o_name2'] : ''}} {{isset($product['o_price2']) ? $product['o_price2'] : ''}}<br>
                    {{isset($product['o_name3']) ? $product['o_name3'] : ''}} {{isset($product['o_price3']) ? $product['o_price3'] : ''}}<br>
                    {{$product['quantity']}}<br>
                    商品合計{{$product['total']}}円<br>
                    <?php $summary += $product['total'] ?>
                    @endforeach
                    @if($summary < 1500)
                    店舗合計(送料込(300円)){{$summary + 300}}円<br>
                    @elseif($summary > 3000)
                    店舗合計(送料込(600円)){{$summary + 600}}円<br>
                    @else
                    店舗合計(送料込(20%)){{$summary + $summary*0.2}}円<br>
                    @endif
                    総額{{$all_summary}}円
                </div>
                <a href="/">トップへ戻る</a>
            </div>
        </div>
    </div>
</div>