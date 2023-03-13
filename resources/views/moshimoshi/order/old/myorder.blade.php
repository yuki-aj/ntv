@include('public/header')
<style>
    @media  screen and (max-width: 600px) {
        .w_76 {
            width:90%;
        }
        .orderstore {
            margin-top:1.0em;
        }
    }
</style>
<div class="product_baskets">
    <div class="flex">
        <a href = "/mypage">
        <span style="font-size:1.2em; padding-top:0.3em" class="left material-symbols-outlined">arrow_circle_left</span>
        </a>
        <div class="bold">ご注文履歴</div>
        <div></diV>
    </div>
</div>

<section class="desc_w60">  
    <div class="w_90">
        <h3 class="t_center gray">※反映には時間がかかることがあります。</h3>
        <div class="flexbox margin10">注文履歴<span class="bold" style="font-size:1.5em;"> {{ $order->total()}} </span>件</div>
        <div class="myorder_link  margin10">{{ $order->appends(request()->input())->links() }}</div>
        @if($order->total() == 0)
            <div class="t_center m_top5">
                <p>まだ注文履歴はありません。</p>
            </div>
        @else
        @foreach($order as $key => $detail)
            <div class="history p_btm3">
                <div class="w_76" style="padding-top:1em;">
                        <p class="t_center">{{$detail->date_created}}</p>
                    <div class="flexses">
                        <form class="details_right" action="/myorder_detail" method="POST">
                            @csrf
                            <input type="hidden" name="o_id" value="{{$detail->o_id}}">
                            <input type="hidden" name="u_id" value="{{$detail->u_id}}">
                            <input type="submit" value="詳細" style="padding:0 20px;" class="loginpage_btn_a">
                        </form>
                        @if($detail->order_flag == 6)
                        <form class="details_right" action="/receipt" method="POST">
                            @csrf
                            <input type="hidden" name="o_id" value="{{$detail->o_id}}">
                            <input type="submit" value="領収書"style="padding:0 20px;" class="loginpage_btn_b">
                        </form>
                        @endif
                    </div>
                        @if($detail->order_flag == 7)
                        <p class="m_top3 t_center bold" style="color:red; font-size:0.9em;">※この注文はキャンセルとなりました。</p>
                        <div class="order_status">{{$detail->status}}</div>
                        @else
                        <div class="order_status">{{$detail->status}}</div>
                        @endif
                    <div class="p_2em">
                        <h2 class="underline">注文ID:　{{$detail->last_o_id}}</h2>
                        <h2 class="underline"><a href="/shop/{{$detail->s_id}}">店名:　{{$detail->name}}</a></h2>
                        <h2 style="font-size:0.9em;">配送日:　{{$detail->delivery_date}}</h2>
                    </div>  
                        @if($detail->order_flag == 6)
                        <div class="orderstore">
                            <p>配送完了時間:　{{$detail->date_status_time}}</p>
                        </div>
                        @endif
                </div>
            </div>
        @endforeach
        @endif
        <div class="myorder_link flexbox margin10 moshideli_btm">{{ $order->appends(request()->input())->links() }}</div>
    </div>
</section>

@include('public/footer')