@include('public/header')

    <div class="header_img">
        <a href="/"><img src="{{('img/headerlogo.png')}}"></a>
    </div>
    @if(Session::has('u_id'))
        <div class="alert alert-success" role="alert">
            <h1 class="bold">{{$user->name}} 様</h1>
            <h1 class="bold">ご注文ありがとうございました。</h1>
            @if(isset($msg))
                <p class="t_center">{{$msg}}</p>
            @endif
        </div>
    @endif
    @if(Session::has('u_id'))
        <div class="alert alert-success" role="alert">
            <p class="p_3 t_center">{{$email}}宛に<br>注文完了確認メールをお送りいたしました。</p>
            @if($temp_carts != '')
                <div class="t_center">
                    <p>※まだカートに商品があります。</p>
                </div>
                <div class="w_80">
                    <div class="go_cart t_center m_btm">
                        <a href="/add_cart">続けて購入する</a>
                    </div>
                </div>
            @endif
            <div class="t_center">
                <p>※マイページの「ご注文履歴」からも<br>ご注文内容の確認ができます。</p>
            </div>
        </div>
    @endif
    <div class="priceBox1" style="border-top:0; padding:0;">
        <div class="w_90 m_btm2">
            <div class="border p_3">
                <?php $summary = 0 ?>
                <p>[ご注文内容]</p>
                <dl class="price cf">
                        <dt>店名</dt>
                        <dd><span class="basePri ormtexyce1">{{$store['name']}}</span></dd>
                </dl>
                @foreach($p_carts as $p_id => $product)
                    <dl class="price cf">
                        <dt>商品名</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['name']}}</span></dd>
                    </dl>
                    <dl class="price cf">
                        <dt>単価</dt>
                        <dd><span class="basePri ormtexyce1">{{number_format($product['price'])}}</span>円</dd>
                    </dl>
                    @if(isset($product['o_name1']))
                    <dl class="price cf">
                        <dt>オプション1</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['o_name1']}}{{number_format($product['o_price1'])}}</span>円</dd>
                    </dl>
                    @endif
                    @if(isset($product['o_name2']))
                    <dl class="price cf">
                        <dt>オプション2</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['o_name2']}}{{number_format($product['o_price2'])}}</span>円</dd>
                    </dl>
                    @endif
                    @if(isset($product['o_name3']))
                    <dl class="price cf">
                        <dt>オプション3</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['o_name3']}} {{number_format($product['o_price3'])}}</span>円</dd>
                    </dl>
                    @endif
                    @if(isset($product['o_name4']))
                    <dl class="price cf">
                        <dt>オプション4</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['o_name4']}} {{number_format($product['o_price4'])}}</span>円</dd>
                    </dl>
                    @endif
                    <dl class="price cf">
                        <dt>数量</dt>
                        <dd><span class="basePri ormtexyce1">{{$product['quantity']}}点</span></dd>
                    </dl>
                    <dl class="price cf" style="border-bottom:1px solid #ccc; padding-bottom:2%;">
                        <dt>商品合計</dt>
                        <dd><span class="basePri ormtexyce1">{{number_format($product['total'])}}</span>円</dd>
                        <?php $summary += $product['total'] ?>
                    </dl>
                @endforeach
                @if($temp_summary < 1500)
                    <dl class="price cf">
                        <dt>店舗合計(送料375円込)</dt>
                        <dd><span class="basePri ormtexyce1">{{number_format($temp_summary + 375)}}</span>円</dd>
                    </dl>
                @elseif($temp_summary > 3000)
                    <dl class="price cf">
                        <dt>店舗合計(送料750円込)</dt>
                        <dd><span class="basePri ormtexyce1">{{number_format($temp_summary + 750)}}</span>円</dd>
                    </dl>
                @else
                    <dl class="price cf">
                        <dt>合計(送料{{floor($temp_summary*0.25)}}円込)</dt>
                        <dd><span class="basePri ormtexyce1">{{number_format(floor($temp_summary + $temp_summary*0.25))}}</span>円</dd>
                    </dl>
                @endif
                @if($cod != 0)
                    <dl class="price cf">
                        <dt>代引き手数料</dt>
                        <dd><span class="basePri ormtexyce1 bold f_size26">{{$cod}}</span>円</dd>
                    </dl>
                @endif
                    <dl class="price cf">
                        <dt>総額</dt>
                        <dd><span class="basePri ormtexyce1 bold f_size26">{{number_format($all_summary)}}</span>円</dd>
                    </dl>
                @if(session('flash_message'))
                    <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="w_80" style="margin-bottom:6em;">
        <div class="moshideli_btm">
            <div class="go_cart t_center m_top5 m_btm">
                <a class="" href="/">トップへ戻る</a>
            </div>
        </div>
    </div>


@include('public/footer')