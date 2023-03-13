@include('manage/header')
<style>
    .contact_table tr {
        border:none;
    }
</style>

<h1 class="baskets">オプション一覧</h1>
<div class="admin_page">
    <table class="contact_table position">
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/store_information/{{$s_id}}">
                <p>店舗管理へ</p>
            </a>
        </div>
        <!-- 追加 -->
        <form action="{{ url('option_edit') }}" method="post" name="ansform" enctype="multipart/form-data">
            @csrf
            <tr>
                <td class="contact-body flex admin_gap">
                    <input type="text" name="o_name" id="names" list="o_name" placeholder="テキスト入力もしくはダブルクリック" autocomplete="off"  required>
                    <datalist id="o_name">
                        @foreach($o_names as $o_id => $o_name)
                        <option value="{{$o_name}}">
                        @endforeach
                    </datalist>
                    <label for="require" style="font-size:14px; display:flex; align-items:center; line-height:30px; white-space: nowrap; color:red;">必須<input id="require" type="checkbox" name="require" value="(必須)" style="margin:0 5px;"/></label>
                    <input type="text" placeholder="大盛り" id="name" name="name" class="form-text" value="" required />
                    <input type="number" placeholder="金額　例）0" id="price" name="price" class="form-text" value="" required />
                    <input type="hidden" name="id" value="0" required />
                    <input type="hidden" name="s_id" value="{{$s_id}}" required />
                    <button class="addition" onclick="return count_check();" type= "submit" value="追加">追加</button>
                </td>
            </tr>
        </form>
        
        <!-- 編集 -->
        @foreach($options as $option)
        <form id="r_delete" action="{{ url('option_edit') }}" method="post" name="ansform" enctype="multipart/form-data">
            @csrf
            <tr>
                <td class="option_list flex admin_gap">
                    <input type="text" name="o_name" value="{{$option->o_name}}" disabled required />
                    <input type="text" id="r_name" name="name" value="{{$option->name}}" required />
                    <input type="number" id="r_price" name="price" value="{{$option->price}}" required />
                    <input type="hidden" name="id" value="{{$option->id}}" required />
                    <input type="hidden" name="s_id" value="{{$s_id}}" required />
                    
                    <button class="addition" onclick="return really_update();" type= "submit" value="更新">更新</button>
        </form>
                <div class="flex">
                   <a href ="/option_delete/{{$option->id}}/{{$option->o_id}}" onclick="return really_delete();" class="p_5 btn btn--black" style="padding:3% 0; font-size:0.5em;">削除</a>
                </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>

<script>
    //削除
    function really_delete(){
        var result = confirm('本当に削除しますか？');
        if(result) {
            document.querySelector('#r_delete').submit();
            return true;
        } else {
            return false;
        }
    }
    // 追加
    function count_check(){
        if (document.getElementById('names')) {
            var names = document.getElementById('names');
            var names_count = names.value.length;
            console.log(names_count);
            if(names_count > 255){
                alert('文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('name')) {
            var name = document.getElementById('name');
            var name_count = name.value.length;
            console.log(name_count);
            if(name_count > 255){
                alert('文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('price')) {
            var price = document.getElementById('price');
            var price_count = price.value.length;
            console.log(price_count);
            if(price_count > 11){
                alert('数字オーバーです。11桁以内で入力ください。');
                return false;
            }
        }
    }
    // 更新
    function really_update(){
        if (document.getElementById('r_name')) {
            var name = document.getElementById('r_name');
            var name_count = name.value.length;
            console.log(name_count);
            if(name_count > 255){
                alert('文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('r_price')) {
            var price = document.getElementById('r_price');
            var price_count = price.value.length;
            console.log(price_count);
            if(price_count > 11){
                alert('数字オーバーです。11桁以内で入力ください。');
                return false;
            }
        }
        var result = confirm('本当に更新しますか？');
        if(result) {
            document.querySelector('r_update').submit();
            return true;
        } else {
            return false;
        }
    }
</script>

</body>
</html>