@include('public/header')

<h1 class="product_baskets">お買い物カゴ</h1>

@if($new_carts)
    <?php $count = 0?>
    @foreach ($new_carts as $stripe_id => $s_products)
    <?php $summary = 0 ?>
    <div class="cartbox" style="margin-bottom:0;">
        @foreach ($s_products as $key => $new_product)
        <?php $s_id = $s_products[$key]['s_id']; ?>
        @if(reset($s_products) == $new_product)
        <div class="border" style="margin-bottom: 5%;">    
            <div class="b_color">
                <div class="storebox" style="padding:0 3%;">
                    <div class="flex" style="align-items:center">
                        <div onclick="location.href='/shop/{{$new_product['s_id']}}'"><span class="left material-symbols-outlined" style="font-size:1.2em; padding-top:0.3em">arrow_circle_left</span></div>
                        <h2>{{$new_product['s_name']}}</h2>
                        <div></diV>
                    </div>
                </div>
            </div>
            @endif
            <div class="under-line">
                <div class="flexbox space-between padding3 cart-box">
                    <p class="f_size_m bold" style="width:100%;">{{$new_product['name']}}</p>
                    <div>
                        <div class="flexbox space-between">
                            <p>商品単価</p>
                            <p>{{number_format($new_product['price'])}}円</p>
                        </div>
                        <div>
                            @if(isset($new_product['o_name1']) || isset($new_product['o_name2']) || isset($new_product['o_name3']) || isset($new_product['o_name4']))
                            <p class="bold b_color_gray">オプション</p>
                            @endif
                            @if(isset($new_product['o_name1']))
                            <div class="flexbox space-between">
                                <p>{{mb_substr($new_product['o_name1'],0,5)}}..　</p>
                                <p>{{number_format($new_product['o_price1'])}}円</p>
                            </div>
                            @endif
                            @if(isset($new_product['o_name2']))
                            <div class="flexbox space-between">
                                <p>{{mb_substr($new_product['o_name2'],0,5)}}..　</p>
                                <p>{{number_format($new_product['o_price2'])}}円</p>
                            </div>
                            @endif
                            @if(isset($new_product['o_name3']))
                            <div class="flexbox space-between">
                                <p>{{mb_substr($new_product['o_name3'],0,5)}}..　</p>
                                <p>{{number_format($new_product['o_price3'])}}円</p>
                            </div>
                            @endif
                            @if(isset($new_product['o_name4']))
                            <div class="flexbox space-between">
                                <p>{{mb_substr($new_product['o_name4'],0,5)}}..　</p>
                                <p>{{number_format($new_product['o_price4'])}}円</p>
                            </div>
                            @endif
                        </div>
                        <div class="flexbox space-between">
                            <p>個数</p>
                            <p>{{$new_product['quantity']}}個</p>
                        </div>
                        <div class="flexbox space-between">
                            <p>商品合計</p>
                            <p class=""><span class="f_size_m bold">{{number_format($new_product['total'])}}</span>円</p>
                        </div>
                    </div>
                    @foreach($products as $product)
                    @if($new_product['p_id'] == $product->id)
                    <img class="productimg" src="/storage/product_image/{{$product->id}}.{{$product->extension}}">
                    @endif
                    @endforeach
                </div>
                <div class="flexbox flex-right padding3">
                    <div class="flexbox padding3">
                        <form method="POST" action="{{url('/change_quantity')}}" id="change_quantity_{{$key}}">
                            @csrf
                            <input type="hidden" value="{{$key}}" name="p_key">
                            <input type="hidden" value="{{$new_product['s_id']}}" name="s_id">
                            <input type="hidden" value="{{$new_product['p_id']}}" name="p_id">
                            @if(isset($new_carts[$stripe_id][$key]['option_1']))
                            <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_1']}}" name="option_1">
                            @endif
                            @if(isset($new_carts[$stripe_id][$key]['option_2']))
                            <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_2']}}" name="option_2">
                            @endif
                            @if(isset($new_carts[$stripe_id][$key]['option_3']))
                            <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_3']}}" name="option_3">
                            @endif
                            @if(isset($new_carts[$stripe_id][$key]['option_4']))
                            <input type="hidden" value="{{$new_carts[$stripe_id][$key]['option_4']}}" name="option_4">
                            @endif
                            <label>
                                <select class="styled-select" onchange="submit(this.form)" name="quantity">
                                    <option disabled selected>数量変更</option>
                                    <?php for($i = 1; $i <= 100; $i++){?>
                                    <option value="{{$i}}">{{$i}}</option>
                                    <?php } ?>
                                </select>
                            </label>
                        </form>
                    </div>
                    <form method="POST" action="{{url('/delete_cart')}}">
                        @csrf
                        <input type="hidden" name="s_id" value="{{$new_product['s_id']}}">
                        <input type="hidden" name="p_id" value="{{$new_product['p_id']}}">
                        <input type="hidden" name="option_1" value="{{$new_product['option_1'] ?? ''}}">
                        <input type="hidden" name="option_2" value="{{$new_product['option_2'] ?? ''}}">
                        <input type="hidden" name="option_3" value="{{$new_product['option_3'] ?? ''}}">
                        <input type="hidden" name="option_4" value="{{$new_product['option_4'] ?? ''}}">
                        <input type="hidden" name="total" value="{{$new_product['total']}}">
                        <input type="hidden" value="0" name="quantity">
                        <div><input class="delete-btn" onclick="return really_delete();" type="submit" value="削除"></div>
                    </form>
                </div>
            </div>
            <?php $summary += $new_product['total'];?>
            <?php $count += $new_product['quantity']?>
            @endforeach
            <section class="cf">
                <div class="priceBox p_0 total-price">
                    <div class="padding5">
                        @if($summary <= 1500)
                        <div class="flex">
                            <div>店舗合計(送料375円込)</div>
                            <div><span class="f_size_m bold">{{number_format(floor($summary + 375))}}</span>円</div>
                        </div>
                        @elseif($summary >= 3000)
                        <div class="flex">
                            <div>店舗合計(送料750円込)</div>
                            <div><span class="f_size_m bold">{{number_format(floor($summary + 750))}}</span>円</div>
                        </div>
                        @else
                        <div class="flex">
                            <div>店舗合計(送料25%込)</div>
                            <div><span class="f_size_m bold">{{number_format(floor($summary + $summary * 0.25))}}</span>円</div>
                        </div>
                        @endif
                        <div>
                            @if(session('flash_message') && session('stripe_id') == $stripe_id)
                            <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
                            @endif
                            <div class="flex margin10" >
                                <p>配送希望日</p>
                                <select name="upper limit flexbox" class="select_time" style="background:#f7f7f7; color:#000; border:1px solid" onChange="location.href=value;">
                                    <option value="#" disabled>配送希望日</option>
                                    @foreach($datetime as $key =>$date)
                                    <option value="/update_apptdate/{{$date['value']}}" {{isset($apptdate)&&($apptdate==$date['value']) ? 'selected' : ''}}>{{$date['display']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(session('flash_message'))
                              <div class="flexbox margin10 text-success">{{session('flash_message')}}</div>
                            @endif

                            @if(Session::has('u_id'))
                            <form action="/pay" method="POST">
                                @csrf
                                <div class="m_top5 m_btm">
                                    <div class="w_76 t_center">
                                        <input type="hidden" value="{{$s_id}}" name="store_id">
                                        <button class="go_cart" type="submit">決済へ進む</button>
                                    </div>
                                </div>
                            </form>
                            @else
                            <div class="t_center  m_top5 m_btm2">
                                <button class="go_cart" onclick="location.href='/initial_email/1'"><span style="font-size:1.2em;">決済へ進む</span></button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    @endforeach
    @else
    <div class="t_center">
        <p>カートの中に商品はありません</p>
    </div>
@endif

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script>
    function really_delete(){
        var result = confirm('本当に削除しますか？');
        if(result) {
            document.querySelector('#r_delete').submit();
        } else {
            return false;
        }
    }
</script>

@include('public/footer')