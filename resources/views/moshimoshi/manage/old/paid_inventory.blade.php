@include('manage/header')
<h1 class="baskets">広告枠</h1>
<style>
    .contact-body img {
        width:20%;
    }
    label {
        font-size:0.8em;
    }
    @media  screen and (max-width: 600px) {
        .manage_img img {
            width:50%;
        }
        .contact_table th,
        .contact_table td{
            display: block;
            width:90%;
            margin: 0 auto;
            text-align:center;
        }
        .contact-body {
            height: auto;
        }
        .admin_flex {
            flex-wrap:nowrap;
        }
        .orange_btn {
            width:150px;
        }
        .flexs {
            justify-content: center;
        }
        .m_left {
            text-align:left;
        }

    }
</style>

<div class="admin_page">
    <div class="addition" style="border-radius: 50px;">
        <a href="/admin_manage/">
            <p>管理画面へ</p>
        </a>
    </div>
    <h2 class="bold"></h2>
    <table class="contact_table position">
        <tr>
            <td class="contact-body admin_box">
                <div class="admin_flex admin_gap">
                    <!-- <p class="bold">広告一覧</p> -->
                </div>
            </td>
            <th style="width:98%;">
                <a href="/paid_inventory_detail/6/0">
                    <div class="orange_btn">
                        <p>追加</p>
                    </div>
                </a>
            </th>
        </tr>
        @foreach($paid_inventorys as  $paid_inventory)
            <tr class="">
                <td class="contact-body">
                    <div class="admin_flex">
                        <img src="/storage/paid_inventory_img/6-{{isset($paid_inventory->id)? $paid_inventory->id : '0'}}.{{isset($paid_inventory->extension)? $paid_inventory->extension : '0'}}">
                        <div class="m_left">
                            <p class="textoverflow">{{$paid_inventory->title}}</p>
                            <p>{{$paid_inventory->name}}</p>
                        </div>
                    </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/paid_inventory_detail/{{$paid_inventory->type}}/{{$paid_inventory->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                        <a href="/paid_inventory_delete/{{$paid_inventory->id}}" onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                    </div>
                </th>
            </tr>
        @endforeach
    </table>
    <form class="r_update" id="mainform" action="{{ url('paid_inventory_update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <table class="contact_table">
            <tr>
                <th class="contact-item">タイトル画像</th>
                <td class="manage_img">
                    <input type="file" placeholder="" name="paid_inventory_img2" class="" />
                    <p style="font-size:0.7em;">※拡張子の制限なし</p>
                    <img src="/storage/paid_inventory_img2/{{isset($ad->type)? $ad->type : '0'}}-{{isset($ad->id)? $ad->id : '0'}}.{{isset($ad->extension)? $ad->extension : '0'}}" required>
                </td>
            </tr>
            <tr>
                <th class="contact-item">背景画像</th>
                <td class="manage_img">
                    <input type="file" placeholder="" name="paid_inventory_img" class="" />
                    <p style="font-size:0.7em;">※拡張子の制限なし</p>
                    <img src="/storage/paid_inventory_img/{{isset($ad->type)? $ad->type : '0'}}-{{isset($ad->id)? $ad->id : '0'}}.{{isset($ad->extension)? $ad->extension : '0'}}" required>
                </td>
            </tr>
            <tr>
                <th class="contact-item">背景色</th>
                <td class="contact-body">
                    <label>
                        <input type="text" placeholder="例）000000" id="url" name="url" value="{{isset($ad->url)? $ad->url : ''}}" class="" required/>
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item">タイトル</th>
                <td class="contact-body">
                    <label>
                        <input type="text" id="title" placeholder="例）お食事と一緒に●●も楽しめます！" name="title" value="{{isset($ad->title)? $ad->title : ''}}" class="" required/>
                    </label>
                </td>
            </tr>
            <tr>
                <th class="contact-item">リード文</th>
                <td class="contact-body">
                <textarea style="height: 200px;" id="read" name="read" placeholder="例）内装やコンセプトにもお店のこだわりが詰まっています！「もしデリ＆来店」で使えるクーポン付き♪5/15（日）まで有効です！" class="form-textarea">{{isset($ad->read)? $ad->read : ''}}</textarea>
                </td>
            </tr>
            <tr>
                <th class="contact-item">表示</th>
                <td class="contact-body">
                    <div class="flexs" style="gap:80px;">
                        <div class="flex" style="width:auto;">
                            <div>
                            <input type="radio" id="contactChoice1"  name="display" value="1" {{isset($ad->no) && $ad->no==1 ? 'checked' : ''}}>
                            </div>
                            <div>
                            <label for="contactChoice1">表示</label>
                            </div>                       
                        </div> 
                        <div class="flex" style="width:auto;">                      
                            <div>                       
                                <input type="radio" id="contactChoice2" name="display" value="0"  {{isset($ad->no) && $ad->no==0 ? 'checked' : ''}}>
                            </div>                       
                            <div>                       
                                <label for="contactChoice2">非表示</label>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <input type="hidden" name="type" value="7">
        <div class="t_center m_top3 m_btm">
            <button class="addition"  onclick="return really_update();" type="submit">保存する</button>
        </div>
    </form>
</div>

<script>
    function really_delete(){
        var result = confirm('本当に削除しますか？');
        if(result) {
            return true;
        } else {
            return false;
        }
    }
    function really_update(){
        if(document.getElementById('title') || document.getElementById('read')){
            var title = document.getElementById('title');
            var title_count = title.value.length;
            var read = document.getElementById('read');
            var read_count = read.value.length;
            var t_length = title_count + read_count;
            console.log(t_length);
            if(t_length > 255){
                alert('タイトル、リード分の文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
        var url = document.getElementById('url');
        var url_count = url.value.length;
        console.log(url_count);
        if(url_count > 6){
            alert('背景色は#を除き6文字でお願いします。');
            return false;
        }
    }
</script>

</body>
</html>
