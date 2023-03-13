@include('manage/header')

<h1 class="baskets">有料広告枠</h1>
<form class="r_update" id="mainform" action="{{ url('paid_inventory_detail') }}" method="post" enctype="multipart/form-data">
    @csrf
    <table class="contact_table">
        <tr>
            <th class="contact-item">画像</th>
            <td class="manage_img">
                <input type="file" placeholder="" name="paid_inventory_img" class="form-text" />
                <p style="font-size:0.7em;">※拡張子の制限なし</p>
                <img src="/storage/paid_inventory_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->id)? $custom->id : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}">
            </td>
        </tr>
        <tr>
            <th class="contact-item">リンクURL</th>
            <td class="contact-body">
                <label>
                    <input type="url" placeholder="例）moshideli.com" id="url" name="url" value="{{isset($custom->url)? $custom->url : ''}}" class="form-text" />
                </label>
            </td>
        </tr>
        <tr>
            <th class="contact-item">タイトル</th>
            <td class="contact-body">
                <label>
                    <input type="text" placeholder="例）伊豆諸島の旅気分が味わえます" id="title" name="title" value="{{isset($custom->title)? $custom->title : ''}}" class="form-text" />
                </label>
            </td>
        </tr>
        <tr>
            <th class="contact-item">店名</th>
            <td class="contact-body">
                <select class="add_edit"  name="s_name" required>
                    <option value="">---</option>
                    @foreach($stores as $store)
                    <option value="{{$store->id}}" {{isset($custom->s_id) && $custom->s_id == $store->id ? 'selected' : ''}}>{{$store->name}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
    </table>
    <input type="hidden" name="no"   value="{{isset($custom->no)? $custom->no : '0'}}">
    <input type="hidden" name="type" value="6">
    <input type="hidden" name="id" value="{{isset($custom->id)? $custom->id : ''}}">
    <div class="t_center m_top3 m_btm">
        <button class="addition"  onclick="return really_update();" type="submit">追加する</button>
    </div>
</form>
    
<script>
    function really_update(){
        if (document.getElementById('url')) {
            var url = document.getElementById('url');
            var url_count = url.value.length;
            console.log(url_count);
            // return;
            if(url_count > 255){
                alert('URLが文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('title')) {
            var title = document.getElementById('title');
            var title_count = title.value.length;
            console.log(title_count);
            // return;
            if(title_count > 255){
                alert('タイトルが文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
    }
</script>

</body>
</html>
