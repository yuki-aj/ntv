@include('manage/header')
<style>
    table {
        border-collapse: separate;
    }
    .contact-body label{
        display:inline-block;
        text-align:center;
        margin:0 15px;
    }
</style>

<h1 class="baskets">クーポン編集</h1>
<div class="admin_page">
    <form name="c_add" id="r_update" action="{{ url('coupon_edit')}}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="contact_table">
            <tbody>
                <tr>
                    @if(isset($store->name) || isset($coupon))
                    <th class="contact-item">店舗</th>
                    @else
                    <th class="contact-item">全店舗共通</th>
                    @endif
                    <td class="contact-body">
                        <select id="s_check" name="s_id" class="favoshop_edit w_100" required {{isset($store->id) && $store->id ==0 ? '' : 'disabled'}}>
                            @if(isset($coupon))
                                <option value="0" {{isset($coupon->id) && $coupon->s_id == 0 ? 'selected' : ''}}>ALL</option>
                                @foreach($stores as $all_store)
                                    <option value="{{$all_store->id}}" {{isset($coupon->id) && $all_store->id == $coupon->s_id ? 'selected' : ''}}>{{$all_store->name}}</option>
                                @endforeach
                            @else
                                <option value="0" {{isset($store->id) ? '' : 'selected'}}>ALL</option>
                                @if(isset($store->id))
                                    @foreach($stores as $all_store)
                                        <option value="{{$all_store->id}}" {{isset($store->id) && $all_store->id == $store->id ? 'selected' : ''}}>{{$store->name}}</option>
                                    @endforeach
                                @endif
                            @endif
                        </select>
                        <input type="hidden" name="s_id" id="hidden_text" value="" />
                    </td>
                </tr>
                @if($coupon->s_id == 0)
                <tr>
                    <th class="contact-item">クーポン対象商品</th>
                    <td class="contact-body">
                        @if($coupon->s_id ==0 && $coupon->title == '')
                        <select id="p_check" name="p_id" class="favoshop_edit w_100" required >
                        @else
                        <select id="p_check" name="p_id" class="favoshop_edit w_100" required {{isset($coupon->s_id) && isset($coupon->p_id) ? 'disabled' : ''}}>
                        @endif
                        <option value="0"{{$coupon->s_id ==0 && $coupon->p_id == 0 ? '' : 'selected'}}>ALL</option>
                        <option value="16" {{$coupon->s_id ==0 && $coupon->p_id == '16' ||  $coupon->p_id == '17' || $coupon->p_id == '18' ? 'selected' : ''}}>送料</option>
                        </select>
                    </td>
                </tr>
                @elseif($coupon->s_id != 0)
                <tr>
                    <th class="contact-item">クーポン対象商品</th>
                    <td class="contact-body">
                        @if($coupon->s_id !=0 && $coupon->title == '')
                        <select id="p_check" name="p_id" class="favoshop_edit w_100" required >
                        @else
                        <select id="p_check" name="p_id" class="favoshop_edit w_100" required {{isset($coupon->s_id) && isset($coupon->p_id) ? 'disabled' : ''}}>
                        @endif
                        @if($products != '' && $product != '')
                            <option value="{{$product->id}}" selected>{{$product->name}}</option>
                        @elseif($products != '')
                        <option value="0"{{$coupon->p_id == 0 ? '' : 'selected'}}>ALL</option>
                        <option value="16" {{$coupon->p_id == '16' ||  $coupon->p_id == '17' || $coupon->p_id == '18' ? 'selected' : ''}}>送料</option>
                        @foreach($products as $all_product)
                        <option value="{{$all_product->id}}" {{isset($coupon->p_id) && $all_product->id == $coupon->p_id ? 'selected' : ''}}>{{$all_product->name}}</option>
                        @endforeach
                        @endif
                        </select>
                    </td>
                </tr>
                @endif
                <tr>
                    <th class="contact-item">タイトル</th>
                    <td class="contact-body">
                        <input type="text" id="title" name="title" value="{{$coupon->title}}" class="form-text" required />
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">画像</th>
                    <td class="contact-body admin_box">
                        <input type="file" name="coupon_img" class="form-text" required/>
                        <p style="font-size:0.7em;">※拡張子の制限なし</p>
                        <img src="/storage/coupon_img/{{isset($coupon->id)? $coupon->id : '0'}}.{{isset($coupon->extension)? $coupon->extension : '0'}}">
                    </td>
                </tr>

                <tr>
                    <th class="contact-item">割引</th>
                    <td class="contact-body">
                        <input id="discount" type="text" placeholder="" name="discount" value="{{$coupon->discount}}" class="form-text" required {{isset($coupon->discount) && $coupon->discount ==0 ? '' : 'disabled'}}/>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">期間</th>
                    <td class="contact-body">
                        <input type="date" placeholder="" id="from_date" name="from_date" class="form-text" value="{{isset($coupon->from_date)? $coupon->from_date : ''}}" {{isset($coupon->from_date) && $coupon->from_date > $date ? '' : 'disabled'}} required/>
                        から
                        <input type="date" placeholder="" id="to_date" name="to_date" class="form-text"value="{{isset($coupon->to_date)? $coupon->to_date : ''}}" {{isset($coupon->to_date) && $coupon->to_date > $date  ? '' : 'disabled'}} required/>
                        まで
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">公開設定</th>
                    <td class="contact-body" style="display:flex; justify-content:center; align-items:center;">
                    <div class="flexs" style="gap:30px;">
                    <div class="flex" style="width:auto;">
                        <div>
                            <input id="radio1" type="radio" placeholder="" name="p_flag" class="form-text" {{$coupon->p_flag != '' && $coupon->p_flag == 0 ? 'checked': ''}} value="0"/>
                        </div>
                        <div>
                            <label for="radio1" style="margin:0;">限定公開</label>
                        </div>
                    </div>
                    <div class="flex" style="width:auto;">
                        <div>
                            <input id="radio2" type="radio" placeholder="" name="p_flag" class="form-text" {{isset($coupon->p_flag) && $coupon->p_flag == 1 ? 'checked': ''}} value="1"/>
                        </div>
                        <div>
                            <label for="radio2" style="margin:0;">公開</label>
                        </div>   
                    </div>   
                </td>
                </tr>
            </tbody>
        </table>
        <div class="t_center m_top3 m_btm">
            <input type="hidden" name="c_id" value="{{isset($coupon->id)? $coupon->id : '0'}}"/>
            @if(isset($coupon->id) && $coupon->id != 0)
            <button class="addition m_btm"  onclick="return really_update();" type="submit">更新する</button>
            @else
            <button class="addition m_btm" type="submit" onclick="return Store_Check();">クーポンを追加する</button>
            @endif
        </div>
    </form>
</div>

<script>
    let elm = document.getElementsByName("p_flag");
    let flag = false; // ③
    function Store_Check() {
        let s_check = document.getElementById('hidden_text').value = document.getElementById('s_check').value;
        for(let i = 0; i < document.c_add.p_flag.length; i++){ // ④
            if(document.c_add.p_flag[i].checked){ // ⑤
            flag = true; // ⑥
            }
        }
        if(!flag) {
            alert('公開設定をしてください。');
            return false;
        }
        let store_check = document.getElementById('s_check').value;
        let product_check = document.getElementById("p_check").value;
        let discount_check = document.getElementById("discount").value;
        if(discount_check.indexOf('%') == -1 && discount_check.indexOf('％') == -1 && discount_check.indexOf('円') == -1) {
            alert('割引額は、円か%を付けて指定してください。');
            return false;
        }
        if(store_check != 0 && product_check != 16 && product_check != 0 ) {
            if(discount_check.indexOf('％') != -1 || discount_check.indexOf('%') != -1){
                alert('商品の割引は、%指定できません。円で指定してください。');
                return false;
            }
        }
        var title = document.getElementById('title');
        var title_count = title.value.length;
        if(title_count > 255){
            alert('タイトルの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        let from_date  = document.getElementById('from_date').value;
        let to_date    = document.getElementById('to_date').value;
        if(from_date > to_date){
            alert('日付を正しく選択してください。');
            return false;
        }
    }
    // 更新
    function really_update(){
        for(let i = 0; i < document.c_add.p_flag.length; i++){ // ④
            if(document.c_add.p_flag[i].checked){ // ⑤
            flag = true; // ⑥
            }
        }
        console.log(flag);
        if(!flag) {
            alert('公開設定をしてください。');
            return false;
        }
        var title = document.getElementById('title');
        var title_count = title.value.length;
        console.log(title_count);
        if(title_count > 255){
            alert('タイトルの文字数オーバーです。255文字以内で入力ください。');
            return false;
        }
        let from_date  = document.getElementById('from_date').value;
        let to_date    = document.getElementById('to_date').value;
        if(from_date > to_date){
            alert('日付を正しく選択してください。');
            return false;
      }
      var result = confirm('本当に更新しますか？');
        if(result) {
        document.querySelector('#r_update').submit();
        } else {
            return false;
        }
    }
</script>

</body>
</html>
