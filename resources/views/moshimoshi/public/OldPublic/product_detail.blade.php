@include('public/header')
<style>
    @media  screen and (max-width: 400px) {
        .says:after{
            top:10px;
            border:10px solid transparent;
            left:20px;
        }
        .say:after {
            left:8em;
        }
    }
</style>
<a href='/shop/{{$store->id}}'>
    <div class="product_baskets">
        <div class="flex">
            <div><span class="left material-symbols-outlined" style="font-size:1.2em; padding-top:0.3em">arrow_circle_left</span></div>
            <div class="bold">{{$store->name}}</div>
            <div></diV>
        </div>
    </div>
</a>

<section class="w_90">
    <form class="cart_add" action="/add_cart" method="POST">
        @csrf
        <div class="flex">
            <div class="product_name">{{$product->name}}</div>
            <div class="product_name">{{number_format($product->price)}}<span class="no_bold"> 円</span></div>
        </div>
        <div class="p_detail">
            <img src="/storage/product_image/{{$product->id}}.{{isset($product->extension)? $product->extension : ''}}">
        </div>
        <div class="balloon">
            <div class="faceicon">
                <img src = "{{$store->staff_img}}">
            </div>
            <div class="says">
                <p>{{$store->catch_copy}}</p>
            </div>
        </div>
        <div class="product_note">
            <p>{{$product->note}}</p>
        </div>
        @if(isset($options[0]))
        <div class="option_all">
            <p class="bold">オプション</p>
            <?php $i = 0; ?>
            @foreach($options as $key => $option)
                @if($option->title != '')
                    <div class="foo t_left">
                        {{$option->title}}
                    </div>
                    <?php $i++ ?>
                @endif
                    <div class="w_65">                
                        <label class="ECM_Radio-Input">
                            <div class="flexspace">
                                <div class="flex_left">
                                    @if(strpos($option->o_name,'(必須)') !== false)
                                        @if($option)
                                        <input class="ECM_RadioInput-Input" type="radio" id='option_{{$option->id}}' name='option_{{$i}}' value='{{$option->id}}' checked>
                                        <span class="ECM_RadioInput-DummyInput"></span>
                                        <span class="ECM_RadioInput-LabelText"></span>
                                        @endif
                                    @else
                                    <input class="ECM_RadioInput-Input radio_button" type="radio" id='option_{{$option->id}}' name='option_{{$i}}' value='{{$option->id}}'>
                                    <span class="ECM_RadioInput-DummyInput"></span>
                                    <span class="ECM_RadioInput-LabelText"></span>
                                    @endif
                                    <p class="option_name">{{$option->name}}</p>
                                </div>
                                <div>
                                    <p class="option_name">{{$option->price}}</p>
                                </div>
                            </div> 
                        </label>
                    </div>
            @endforeach
        </div> 
        @endif   
        <div class="m_btm top_line">
            <div class="up_down">
                <div class="spinner_area"><input type="button" value="－" class="btnspinner" data-cal="-1" data-target=".counter1"></div>
                <div class="t_center"><input type="number" pattern="^[0-9]+$" value="1"  name="quantity" min="1" class="counter1  yellow"  data-max="100" data-min="1"></div>
                <div class="spinner_area"><input type="button" value="＋" class="btnspinner yellow" data-cal="1" data-target=".counter1"></div>
            </div>
        </div>
        <!-- <div class="flex">
            <div class="spinner_area"><input type="button" value="－" class="btnspinner" data-cal="-1" data-target=".counter1"></div>
            <input type="number" class="t_center" value="1" data-max="100" data-min="1">
            <div class="spinner_area"><input type="button" value="＋" class="btnspinner yellow" data-cal="1" data-target=".counter1"></div>
        </div> -->
        <input class="s_id" type="hidden" name="s_id" value="{{$product->s_id}}">
        <input class="p_id" type="hidden" name="p_id" value="{{$product->id}}">
        <div class="t_center w_76">
            <button type="submit"  class="go_cart moshideli_btm"><span style="font-size:1.2em;">カートに追加</span></button>
        </div>
    </form>
</section>

<script>
    //モーダルの中のラジオボタンの処理(1つのみ選択)
    var radio_val;
        $('.radio_button').on('click',function(){
        if($(this).val() == radio_val) {
            $(this).prop('checked', false);
            radio_val = null;
        } else {
            radio_val = $(this).val();
        }
    });

    $(function(){
        var arySpinnerCtrl = [];
        var spin_speed = 20; //変動スピード
        //長押し押下時
        $('.btnspinner').on('touchstart mousedown click', function(e){
            if(arySpinnerCtrl['interval']) return false;
            var target = $(this).data('target');
            arySpinnerCtrl['target'] = target;
            arySpinnerCtrl['timestamp'] = e.timeStamp;
            arySpinnerCtrl['cal'] = Number($(this).data('cal'));
            //クリックは単一の処理に留める
            if(e.type == 'click'){
                spinnerCal();
                arySpinnerCtrl = [];
                return false;
            }
            //長押し時の処理
            setTimeout(function(){
                //インターバル未実行中 + 長押しのイベントタイプスタンプ一致時に計算処理
                if(!arySpinnerCtrl['interval'] && arySpinnerCtrl['timestamp'] == e.timeStamp){
                    arySpinnerCtrl['interval'] = setInterval(spinnerCal, spin_speed);
                }
            }, 500);
        });
        
        //長押し解除時 画面スクロールも解除に含む
        $(document).on('touchend mouseup scroll', function(e){
            if(arySpinnerCtrl['interval']){
                clearInterval(arySpinnerCtrl['interval']);
                arySpinnerCtrl = [];
            }
        });
        
        //変動計算関数
        function spinnerCal(){
            var target = $(arySpinnerCtrl['target']);
            var num = Number(target.val());
            num = num + arySpinnerCtrl['cal'];
            if(num > Number(target.data('max'))){
                target.val(Number(target.data('max')));
            }else if(Number(target.data('min')) > num){
                target.val(Number(target.data('min')));
            }else{
                target.val(num);
            }
        }
    });
</script>

@include('public/footer')