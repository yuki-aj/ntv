@include('manage/header')
<style>
    .select_times {
        width:45%;
    }
    .contact_table td, .contact_table th {
        padding-bottom:1em;
    }
</style>
<h1 class="baskets">商品情報</h1>
    <div class="admin_page moshideli_btm">
        <div class="addition gap m_t3" style="border-radius: 50px;">
            <a href="/product_list/{{$store_id}}">
                <p>商品一覧</p>
            </a>
        </div>  
        <div class="addition gap m_t3" style="border-radius: 50px;">
        <a href="/product_edit/{{$store_id}}/0">
                <p>商品追加</p>
            </a>
        </div>  
        <form class="r_update" action="{{ url('product_edit') }}" method="post" enctype="multipart/form-data">
        @csrf

        @if(isset($product->name))
        <div class="underline">
            <div class="t_center">
                <h1 class="bold">{{$product->name}}</h1>
                <a target="_blank" href="/product_detail/{{$product->id}}">
                <p style="color:blue">店舗TOP　https://v163-44-182-170.68mg.static.cnode.io/product_detail/{{$product->id}}</p>
                </a>
            </div>
        </div>
        @endif
            <table class="contact_table">
                <tr>
                    <th class="contact-item">カテゴリー</th>
                    <td class="manage_img">
                        <select name="no[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($categorys as $no => $category)
                                @if(isset($product->title1) && $product->title1 == $category->title)
                                    <option value="{{$category->no}}" selected>{{$category->title}}</option>
                                @else
                                    <option value="{{$category->no}}">{{$category->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="no[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($categorys as $no => $category)
                                @if(isset($product->title2) && $product->title2 == $category->title)
                                    <option value="{{$category->no}}" selected>{{$category->title}}</option>
                                @else
                                    <option value="{{$category->no}}">{{$category->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="no[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($categorys as $no => $category)
                                @if(isset($product->title3) && $product->title3 == $category->title)
                                    <option value="{{$category->no}}" selected>{{$category->title}}</option>
                                @else
                                    <option value="{{$category->no}}">{{$category->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="no[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($categorys as $no => $category)
                                @if(isset($product->title4) && $product->title4 == $category->title)
                                    <option value="{{$category->no}}" selected>{{$category->title}}</option>
                                @else
                                    <option value="{{$category->no}}">{{$category->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">ショップ内カテゴリー</th>
                    <td class="manage_img">
                        <select name="sc_id[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($customs as $sc_id => $custom)
                                @if(isset($product->s_category1) && $product->s_category1 == $custom->title)
                                    <option value="{{$custom->no}}" selected>{{$custom->title}}</option>
                                @else
                                    <option value="{{$custom->no}}">{{$custom->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="sc_id[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($customs as $sc_id => $custom)
                                @if(isset($product->s_category2) && $product->s_category2 == $custom->title)
                                    <option value="{{$custom->no}}" selected>{{$custom->title}}</option>
                                @else
                                    <option value="{{$custom->no}}">{{$custom->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="sc_id[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($customs as $sc_id => $custom)
                                @if(isset($product->s_category3) && $product->s_category3 == $custom->title)
                                    <option value="{{$custom->no}}" selected>{{$custom->title}}</option>
                                @else
                                    <option value="{{$custom->no}}">{{$custom->title}}</option>
                                @endif
                            @endforeach
                        </select>
                        <select name="sc_id[]" class="select_times">
                            <option value="">なし</option>
                            @foreach($customs as $sc_id => $custom)
                                @if(isset($product->s_category4) && $product->s_category4 == $custom->title)
                                    <option value="{{$custom->scno_id}}" selected>{{$custom->title}}</option>
                                @else
                                    <option value="{{$custom->no}}">{{$custom->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">オプション</th>
                    <td class="manage_img">
                        <!-- <div class="flex admin_gap"> -->
                            <!-- <div class="flexbox"> -->
                                <select name="o_id[]" class="select_times">
                                    <option value="">なし</option>
                                    @foreach($options as $o_id => $o_name)
                                    @if(isset($product->o_name1) && $product->o_name1 == $o_name)
                                        <option value="{{$o_id}}" selected>{{$o_name}}</option>
                                    @else
                                        <option value="{{$o_id}}">{{$o_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <select name="o_id[]" class="select_times">
                                    <option value="">なし</option>
                                    @foreach($options as $o_id => $o_name)
                                    @if(isset($product->o_name2) && $product->o_name2 == $o_name)
                                        <option value="{{$o_id}}" selected>{{$o_name}}</option>
                                    @else
                                        <option value="{{$o_id}}">{{$o_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            <!-- </div> -->
                            <!-- <div class="flexbox"> -->
                                <select name="o_id[]" class="select_times">
                                    <option value="">なし</option>
                                    @foreach($options as $o_id => $o_name)
                                    @if(isset($product->o_name3) && $product->o_name3 == $o_name)
                                        <option value="{{$o_id}}" selected>{{$o_name}}</option>
                                    @else
                                        <option value="{{$o_id}}">{{$o_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                <select name="o_id[]" class="select_times">
                                    <option value="">なし</option>
                                    @foreach($options as $o_id => $o_name)
                                    @if(isset($product->o_name4) && $product->o_name4 == $o_name)
                                        <option value="{{$o_id}}" selected>{{$o_name}}</option>
                                    @else
                                        <option value="{{$o_id}}">{{$o_name}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            <!-- </div> -->
                        <!-- </div> -->
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">商品画像</th>
                    <td class="manage_img">
                        @if($product == '')
                        <input id="product_image"  type="file"  name="product_image" value="" class="form-text" required>
                        @else
                        <input id="product_image"  type="file"  name="product_image" value="" class="form-text"/>
                        @endif
                        <a target="_blank" style="display: inline-block;" href="/storage/product_image/{{isset($product->id)? $product->id : ''}}_original.{{isset($product->extension)? $product->extension : ''}}">
                           <img src="/storage/product_image/{{isset($product->id)? $product->id : ''}}.{{isset($product->extension)? $product->extension : ''}}">
                        </a>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">商品名</th>
                    <td class="contact-body">
                        <input type="text" placeholder="例）ガパオライス" maxlength="255" name="name"  value="{{isset($product->name)? $product->name : ''}}" class="form-text" required/>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">金額</th>
                    <td class="contact-body">
                        <input type="number" min="1" max="1000000" placeholder="例）1050" name="price" value="{{isset($product->price)? $product->price : ''}}"  class="form-text" required/>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">商品説明</th>
                    <td class="contact-body">
                        <textarea name="note" placeholder="" maxlength="255" class="form-textarea">{{isset($product->note)? $product->note : ''}}</textarea>
                    </td>
                </tr>
                <tr>
                    <th class="contact-item">ハッシュタグ</th>
                    <td class="contact-body">
                        <textarea name="hashtag" placeholder="" maxlength="1000" class="form-textarea">{{isset($product->hashtag)? $product->hashtag : ''}}</textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="p_id" value="{{isset($product->id)? $product->id : '0'}}">
            <input type="hidden" name="s_id" value="{{$store_id}}">
            <input type="hidden" name="o_ids" value="1">
            <div class="t_center m_top3 m_btm2">
                @if(isset($product->id) && $product->id != 0)
                    @if(isset($product->id) && $product->p_status == 1)
                    <button class="none_btn"  type="hidden" onclick="return really_none();">
                        <a href ="/product_hidden/{{$product->id}}">
                            商品を非表示
                        </a>
                    </button>
                    <!-- <a onclick="return really_none();" href ="/product_hidden/{{$product->id}}" class="btn btn--black btn--cubic btn--shadow" style="border-radius:50px; font-size:0.6em;">商品を非表示</a> -->
                    @else
                    <button class="none_btn"  type="hidden" onclick="return display();">
                        <a href ="/product_hidden/{{$product->id}}">
                            商品を表示
                        </a>
                    </button>
                    @endif
                <button class="addition"  onclick="return really_update();" type="submit">情報を更新する</button>
                @else
                <button class="addition" type="submit" onclick="return img_check();">商品を追加する</button>
                @endif
            </div>
        </form>
    </div>


<script>
    // function img_check() {
    //     if(document.querySelector('#product_image')){
    //         var img_check = document.querySelector('#product_image').files[0]['name'];
    //         var result = img_check.substr(-3);
    //         var result2 = img_check.substr(-4);
    //         if(result != 'jpg' && result2 != 'jpeg'){
    //             alert('jpg/jpeg形式の画像を登録してください。');
    //             return false;
    //         }
    //     }
    // }
    function really_update(){
        var result = confirm('本当に更新しますか？');
        if(result) {
            document.querySelector('r_update').submit();
        } else {
            return false;
        }
    }
    function really_none(){
        var result = confirm('本当に非表示にしますか？');
        if(result) {
            document.querySelector('r_update').submit();
        } else {
            return false;
        }
    }
    function display(){
        var result = confirm('本当に表示にしますか？');
        if(result) {
            document.querySelector('r_update').submit();
        } else {
            return false;
        }
    }
</script>

</body>
</html>