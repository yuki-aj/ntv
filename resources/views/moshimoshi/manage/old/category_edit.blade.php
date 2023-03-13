@include('public/header')
<div class="management">
        <h1>カテゴリー</h1>
        <form action="{{ url('category_edit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <table class="contact_table">
                <tr>
                    <th class="contact-item">アイコン</th>
                    <td class="">
                        <input type="file" placeholder="" name="category" class="form-text"/>
                        <img style="width:200px; height:200px;" src="/storage/category/{{isset($category->id)? $category->id : '0'}}.jpg">
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">カテゴリー名</th>
                    <td class="contact-body">
                        <input type="text" placeholder="" name="name" class="form-text" value="{{isset($category->name)? $category->name : ''}}">
                    </td>
                </tr>
            </table>
            
            <input type="hidden" name="c_id" value="{{isset($category->id)? $category->id : '0'}}">
            
            <div class="t_center">
                <input class="addition" type= "submit" value="更新する">
            </div>
        </form>
</div>
@include('public/footer')

