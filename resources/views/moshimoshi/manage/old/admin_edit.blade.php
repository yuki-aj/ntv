@include('manage/header')

<h1 class="baskets">{{$type_name}}編集</h1>
<div class="admin_page">
    <form id="r_update" action="{{ url('admin_edit') }}" method="post" enctype="multipart/form-data">
      @csrf
        <table class="contact_table w_100 admin_box">
            @if($type == 0 ||$type == 3 || $type == 5 || $type == 8)
            <tr>
                <th class="contact-item">タイトル</th>
                <td class="contact-body">
                    <input type="text" placeholder="" id="title" name="title" class="form-text" value="{{isset($custom->title)? $custom->title : ''}}" required/>
                </td>
            </tr>
            @elseif($type == 1)
            <tr>
                <th class="contact-item">お知らせ</th>
                <td class="contact-body">
                    <input type="text" placeholder="" id="title" name="title" class="form-text" value="{{isset($custom->title)? $custom->title : ''}}" required/>
                </td>
            </tr>
            @endif
            @if($type == 1 || $type == 2 || $type == 3 || $type == 8)
            <tr>
                <th class="contact-item">リンク先</th>
                <td class="contact-body">
                    <input type="url" placeholder="https://domain.com" id="url" name="url" class="form-text" value="{{isset($custom->url)? $custom->url : ''}}"/>
                </td>
            </tr>
            @endif
            @if($type == 0 || $type == 2 || $type == 3 || $type == 8)
            <tr>
                <th class="contact-item">画像</th>
                <td class="contact-body">
                    <input type="file" name="admin_img" class="form-text" required/>
                        <p style="font-size:0.7em;">※拡張子の制限なし</p>
                        <img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}">
                    </td>
            </tr>
            @endif
            @if($type == 0)
            <tr>
                <th class="contact-item">画像2</th>
                <td class="contact-body">
                    <input type="file" name="admin_img2" class="form-text" required/>
                        <p style="font-size:0.7em;">※拡張子の制限なし</p>
                        <img src="/storage/admin_img/0-{{isset($custom->no)? $custom->no : '0'}}-2.{{isset($custom->extension)? $custom->extension : '0'}}">
                    </td>
            </tr>
            @endif
            @if($type == 3)
            <tr>
                <th class="contact-item">掲載期間</th>
                <td class="contact-body">
                    <input type="date" placeholder="" id="from_date" name="from_date" class="form-text" value="{{isset($custom->from_date)? $custom->from_date : ''}}" required/>
                    から
                    <input type="date" placeholder="" id="to_date" name="to_date" class="form-text"value="{{isset($custom->to_date)? $custom->to_date : ''}}" required/>
                    まで
                </td>
            </tr>
            @endif
            @if($type == 4)
            <tr>
                <div style="text-align:center;">
                    <select class="favoshop_edit" style="text-align: center;"; name="title" required>
                        <option value="">---</option>
                        @foreach($stores as $store)
                            <option value="{{$store->id}}" {{isset($custom->title) && $custom->title == $store->id ? 'selected' : ''}}>{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
            </tr>
            @endif
        </table>
        <input type="hidden" name="type" value="{{$type? $type : '0'}}">
        <input type="hidden" name="no"   value="{{isset($custom->no)? $custom->no : '0'}}">
        <input type="hidden" name="c_id" value="{{isset($custom->id)? $custom->id : '0'}}"/>
        <input type="hidden" name="s_id" value="{{$store_id}}">
        <div class="t_center m_top3">
            @if($type == 0 && !isset($custom->id))
                <button class="addition m_btm"  onclick="return check_count();" type="submit">カテゴリーを追加する</button>
            @elseif($type == 0 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">カテゴリーを更新する</button>
            @endif
            @if($type == 1 && !isset($custom->id))
                <button class="addition m_btm"  onclick="return check_count();" type="submit">お知らせを追加する</button>
            @elseif($type == 1 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">お知らせを更新する</button>
            @endif
            @if($type == 2 && !isset($custom->id))
                <button class="addition m_btm" onclick="return check_count();"  type="submit">スライダーを追加する</button>
            @elseif($type == 2 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">スライダーを更新する</button>
            @endif
            @if($type == 3 && !isset($custom->id))
                <button class="addition m_btm" onclick="return check_count();" type="submit">広告を追加する</button>
            @elseif($type == 3 && isset($custom->id))
                <button class="addition m_btm"  onclick="return really_update();" type="submit">広告を更新する</button>
            @endif
            @if($type == 4 && !isset($custom->id))
                <button class="addition m_btm" onclick="return check_count();"  type="submit">PICKUPを追加する</button>
            @elseif($type == 4 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">PICKUPを更新する</button>
            @endif
            @if($type == 5 && !isset($custom->id))
                <button class="addition m_btm" onclick="return check_count();"  type="submit">SEOを追加する</button>
            @elseif($type == 5 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">SEOを更新する</button>
            @endif
            @if($type == 8 && !isset($custom->id))
                <button class="addition m_btm" onclick="return check_count();"  type="submit">よみものを追加する</button>
            @elseif($type == 8 && isset($custom->id))
                <button class="addition m_btm" onclick="return really_update();" type="submit">よみものを更新する</button>
            @endif
        </div>
    </form>
</div>

<script>
    function check_count(){
        if (document.getElementById('from_date')) {
            let from_date  = document.getElementById('from_date').value;
            let to_date    = document.getElementById('to_date').value;
            
            if(from_date > to_date){
                alert('正しい日にちを選択してください。');
                return false;
            }
        }
        if (document.getElementById('title')) {
            var title = document.getElementById('title');
            var title_count = title.value.length;
            console.log(title_count);
            // return;
            if(title_count > 255){
                alert('タイトルの文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('url')) {
            var url = document.getElementById('url');
            var url_count = url.value.length;
            console.log(url_count);
            if(url_count > 255){
                alert('リンク先の文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
    }
    function really_update(){
        if (document.getElementById('from_date')) {
            let from_date  = document.getElementById('from_date').value;
            let to_date    = document.getElementById('to_date').value;
            
            if(from_date > to_date){
                alert('正しい日にちを選択してください。');
                return false;
            }
        }
        if (document.getElementById('title')) {
            var title = document.getElementById('title');
            var title_count = title.value.length;
            console.log(title_count);
            // return;
            if(title_count > 255){
                alert('タイトルの文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        if (document.getElementById('url')) {
            var url = document.getElementById('url');
            var url_count = url.value.length;
            console.log(url_count);
            if(url_count > 255){
                alert('リンク先の文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        var result = confirm('本当に更新しますか？');
        if(result) {
            document.querySelector('#r_update').submit();
            return true;
        } else {
            return false;
        }
    }
</script>

</body>
</html>