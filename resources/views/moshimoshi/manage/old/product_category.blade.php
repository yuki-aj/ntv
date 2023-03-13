@include('manage/header')

<h1 class="baskets">メニューカテゴリー編集</h1>
    <div class="admin_page">
        <form action="{{ url('product_category/'.$s_id) }}" method="post" enctype="multipart/form-data">
          @csrf
            <table class="contact_table w_100 admin_box">
                <tr>
                    <th class="contact-item">順番</th>
                    <td class="contact-body">
                        <input type="number" placeholder="" name="no" class="form-text" value="" required/>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">タイトル</th>
                    <td class="contact-body">
                        <input type="text" placeholder="" id="title" name="title" class="form-text" value="" required/>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="type" value="10">
            <input type="hidden" name="s_id" value="{{$s_id}}"/>
            <div class="t_center m_top3">
                <input class="addition" onclick="return check_count();" type= "submit" value="更新する">
            </div>
        </form>
    </div>

<script>
    function check_count(){
        if(document.getElementById('title')){
            var title = document.getElementById('title');
            var title_count = title.value.length;
            console.log(title_count);
            if(title_count > 255){
                alert('タイトルの文字数オーバーです。255文字以内で入力ください。');
                return false;
            }
        }
    }
</script>

</body>
</html>