@include('public/header')
<div class="management">
    <table class="contact_table">
        <h1>オプション</h1>
        <tr>
            <td class="contact-body flex_icon title_gap">
                <p>項目名</p>
                <p>金額</p>
            </td>
            @foreach($options as $option)
            <form action="{{ url('option_edit') }}" method="post" name="ansform" enctype="multipart/form-data">
                @csrf
                <td class="contact-body flex gap">
                    <input type="text" placeholder="小盛り" name="name" class="form-text" value="{{$option->name}}" required />
                    <input type="number" placeholder="金額　例）0" name="price" class="form-text" value="{{$option->price}}" required />
                    <input class="" type= "submit" value="更新">
                    <a href ="" class="btn btn--orange btn--cubic btn--shadow">削除</a>
                </td>
            </form>
            @endforeach
            <form action="{{ url('product_list') }}" method="post" name="ansform" enctype="multipart/form-data">
                @csrf
                <td class="contact-body flex gap">
                    <input type="text" placeholder="小盛り" name="name" class="form-text" required />
                    <input type="number" placeholder="金額　例）0" name="price" class="form-text" required />
                    <input class="" type= "submit" value="追加">
                </td>
            </form>
        </tr>
    </table>
</div>