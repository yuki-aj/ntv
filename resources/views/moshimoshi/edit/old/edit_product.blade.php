@include('public/header',['title' => 'initial_information','description' => 'initial_information'])
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<style>
  <?php if($kind == 1){ ?>
  .information-div label,
  .information-div div label{
    background-color: #e9950f;
  }
  .languarge a {
    background-color: #0d730d;
  }
  .languarge a:hover {
    background-color: #e9950f;
  }
  <?php } ?>
</style>
<div class="informations text-center">
  @if($kind == 1)
  <h1>@lang('messages.searching-products')</h1>
  @else
  <h1>@lang('messages.product-register')</h1>
  @endif
  <p>@lang('messages.followinginfo')</p>
  @include('message')
  <form action="/edit_product" method="post" enctype="multipart/form-data" onsubmit="return check_form()">
    @csrf
    <div class="">
      <div class="information-div flex">
        <label for="name">@lang('messages.product-name')</label>
        <div class="translate-box flex" style="flex-direction: column; text-align:left;">
         <input id="name" name="name" class="product_check_form" type="text" placeholder="@lang('messages.fillcompany')" value="{{isset($product->name)? $product->name : ''}}" required>
         <span style="padding-top:5px; font-size:14px;">@lang('messages.textover-100')</span>
        </div>
      </div>
      <div class="information-div small-info-div flex">
        <div class="flex mb-20">
          <label for="category">@lang('messages.category')</label>
          <select id="select_a" class="category" name="division" required></select>
        </div>
        <div class="flex">
          <label for="category">@lang('messages.subcategory')</label>
          <select id="select_b" class="category" name="subdivision" required></select>
        </div>
      </div>
      <div class="information-div small-info-div flex">
        <div class="flex mb-20">
          <label for="type">@lang('messages.tree-species')</label>
          <select name="type" required="required">
            <option value="">@lang('messages.select')</option>
            @foreach($types as $key => $type_name)
            @if(isset($product->type) && $key == $product->type)
            <option value="{{$key}}" selected>{{$type_name}}</option>
            @else
            <option value="{{$key}}">{{$type_name}}</option>
            @endif
            @endforeach
          </select>
        </div>
        @if($kind == 2)
        <div class="flex">
          <label for="origin">@lang('messages.origin')</label>
          <select name="origin" required="required">
            <option value="">@lang('messages.select')</option>
            @foreach($prefectures as $key => $prefecture_name)
              @if(isset($product->origin) && $key == $product->origin)
                <option value="{{$key}}" selected>{{$prefecture_name}}</option>
              @else
                <option value="{{$key}}">{{$prefecture_name}}</option>
              @endif
            @endforeach
          </select>
        </div>
        @endif
      </div>
      <div class="information-div flex">
        <label for="size">@lang('messages.size')</label>
        <div id="size_change" class="flex">
        <input id="size1" class="size180" name="size1" type="number" value="{{isset($product->size1)? $product->size1 : ''}}" placeholder="" required>
        <div id="cross1">x</div>
        <input id="size2" class="size180" name="size2" type="number" value="{{isset($product->size2)? $product->size2 : ''}}" placeholder="" required>
        <div id="cross2">x</div>
        <input id="size3" class="size180" name="size3" type="number" value="{{isset($product->size3)? $product->size3 : ''}}" placeholder="">
        </div>
      </div>
      <div class="information-div small-info-div flex">
        <div class="flex">
          <label for="unit">@lang('messages.unit')</label>
          <select name="unit" required="required">
            <option value="">@lang('messages.select')</option>
            <option value="0" {{isset($product->unit) && $product->unit == 0 ? 'selected' : ""}}>mm</option>
            <option value="1" {{isset($product->unit) && $product->unit == 1 ? 'selected' : ""}}>inch</option>
          </select>
        </div>
      </div>
      <div class="information-div small-info-div flex">
        <div class="flex mb-20">
          <label for="price">@lang('messages.price')</label>
          <input id="price" name="price" type="number" placeholder="US$" value="{{isset($product->price)? $product->price : ''}}" required>
        </div>
        <div class="flex">
          <label for="p_unit">@lang('messages.price-unit')</label>
          <select name="p_unit" required="required">
            <option value="">@lang('messages.select')</option>
            @foreach($units as $key => $unit)
            @if(isset($product->p_unit) && $key == $product->p_unit)
            <option value="{{$key}}" selected>{{$unit}}</option>
            @else
            <option value="{{$key}}">{{$unit}}</option>
            @endif
            @endforeach
          </select>
        </div>
      </div>
      <div class="information-div flex">
        <div class="flex grade-box">
          <label for="grade">@lang('messages.grade-keyword')</label>
          <div class="flex grade-list-box">
            @foreach($grades as $key => $grade)
            <p class="flex"><input id="{{$key}}" type="checkbox" name="grade[]" value="{{$key}}" {{$grade['checked']}} ><label class="gradename" for="{{$key}}">{{$grade['name']}}</label></p>
            @endforeach
          </div>
        </div>
      </div>
      @foreach($language as $key => $lang)
      <?php $note = 'note_'.str_replace('-', '', $key); ?>
      <div class="information-div flex">
        <label for="note_{{$key}}">@lang('messages.description-products')({{$lang}})</label>
        <div class="translate-box flex" style="flex-direction: column; text-align:left;">
          <textarea class="length-check" name="note_{{str_replace('-', '', $key)}}" id="before-translate" required>{{isset($product->$note) ? $product->$note :''}}</textarea>
          <span style="padding-top:5px; font-size:14px;">@lang('messages.textover-p')</span>
        </div>
      </div>
      <div class="languarge flex">@lang('messages.multi-lingal')<a onclick="buttonClick('{{$key}}')">@lang('messages.translate')</a></div>
      @endforeach
      @foreach($languages as $key => $language)
      <?php $note = 'note_'.str_replace('-', '', $key); ?>
      <div class="information-div flex">
        <label for="note_{{$key}}">@lang('messages.description-products')({{$language}})</label>
        <div class="translate-box flex" style="flex-direction: column; text-align:left;">
          <textarea class="length-check" name="note_{{str_replace('-', '', $key)}}" id="translate-{{$key}}" required>{{isset($product->$note) ? $product->$note :''}}</textarea>
          <span style="padding-top:5px; font-size:14px;">@lang('messages.textover-p')</span>
        </div>
      </div>
      @endforeach
      <div class="information-div flex">
        <label for="video_url">@lang('messages.video')(@lang('messages.optional'))</label>
        <input name="video_url" type="url" placeholder="" value="{{isset($product->video_url)? $product->video_url : ''}}">
      </div>
      <div class="information-div file-choice flex">
        <label for="img_url1">@lang('messages.image')1</label>
        <div class="edit-img-box flex">
            @if(isset($imgs[1]))
            <img src="{{asset($imgs[1])}}">
            @endif
            <input class="img-file" name="img_url1" type="file" value="">
            <span style="font-size: 14px;">@lang('messages.img-size')</span>
            <div class="uploaded-error">
            @error('img_url1')
            {{ $message }}
            @enderror
            </div>
        </div>
      </div>
      <div class="information-div file-choice flex">
        <label for="img_url2">@lang('messages.image')2</label>
        <div class="edit-img-box flex">
          @if(isset($imgs[2]))
              <img src="{{asset($imgs[2])}}">
              @endif
          <input class="img-file" name="img_url2" type="file" value="">
          <span style="font-size: 14px;">@lang('messages.img-size')</span>
          <div class="uploaded-error">
          @error('img_url2')
          {{ $message }}
          @enderror
          </div>
        </div>
      </div>
      <div class="information-div file-choice flex">
        <label for="img_url3">@lang('messages.image')3</label>
        <div class="edit-img-box flex">
          @if(isset($imgs[3]))
              <img src="{{asset($imgs[3])}}">
              @endif
          <input class="img-file" name="img_url3" type="file" value="">
          <span style="font-size: 14px;">@lang('messages.img-size')</span>
          <div class="uploaded-error">
          @error('img_url3')
          {{ $message }}
          @enderror
          </div>
        </div>
      </div>
    </div>
    <input name="u_id" type="hidden" value="{{$u_id}}">
    <input name="p_id" type="hidden" value="{{isset($product->id) ? $product->id : '' }}">
    <button class="searchbutton" type="submit">@lang('messages.register')</button>
  </form>
</div>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script>
  function check_form(){
    // var check_form = document.getElementById("company").value;
    var check_form = document.getElementById("name").value;
    if (check_form.match(/[^0-9 a-z A-Z !"#$%&'()\*\+\-\.,\/:;<=>?@\[\\\]^_`{|}~ ]/g) || !check_form.match(/\S/g)){
    alert("@lang('messages.fillcompany')");
    return false;
    }
    var product_length_check = document.querySelector('.product_check_form');
    var product_text_length = 0;
    if(product_length_check.value.match(/[ -~]/)) {
        product_text_length += 0.5;
      }else{
        product_text_length += 1;
      }
      if(product_length_check.value.length * product_text_length > 100){
        alert("@lang('messages.textover-100')");
        return false;
      }
    var length_check = document.querySelectorAll('.length-check');
    for(let i = 0; i < length_check.length; i++){
      var text_length = 0;
      if(length_check[i].value.match(/[ -~]/)) {
        text_length += 0.5;
      }else{
        text_length += 1;
      }
      if(length_check[i].value.length * text_length > 200){
        alert("@lang('messages.textover-p')");
        return false;
      }
    }
  }
  let division     = {{$division ? ($division):"0"}}; 
  //divisionにカテゴリーの値を入れる。なければ0を入れる。
  let subdivision = {{$subdivision ? ($subdivision):"0"}}; 
  //subdivisionにサブカテゴリーの値を入れる。なければ0を入れる。 
  function buttonClick(locale) {
    @foreach($languages as $key => $language)
    tranclater(locale,'{{$key}}');
    @endforeach
  }
  function tranclater(locale,language) {
    //入力された日本語を取得して、変数textareaに代入
    var textarea = $('#before-translate').val();
    //非同期通信を実行するために、下記の内容でコントローラーに情報を渡す
    $.ajax({
      type: 'POST',
      url: '/translate',
      data: {
        translate: textarea,
        from: locale,
        to: language,
      }, //レスポンスを下記のような形式で指定する
      dataType: 'json', //データ形式をjsonに指定
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }, //POSTリクエストのときに必要な記述
    }).done(function(data) { //レスポンスが正常だったら
      //$('#before-translate').empty();
      id = '#translate-'+language;
      $(id).val(data.translation);
    }).fail(function() { //レスポンスが異常だったら
      alert('error'); //エラーのアラートを表示
    });
  }
  var a_options = [
    ['@lang('messages.select')', '0'],
    @foreach($categories as $key => $category)
      @if($key == $division)
        ['{{$category["name"]}}', '{{$key}}', true],
      @else
        ['{{$category["name"]}}', '{{$key}}', false],
      @endif
    @endforeach
  ];
  var b_options = [
    [
      ['@lang('messages.select')', '0'],
    ],
    @foreach($categories as $key1 => $category)[
      ['@lang('messages.select')', '0'],
      @foreach($category['subdivision'] as $key2 => $middle_name)
        @if($key1 == $division && $key2 == $subdivision)
          ['{{$middle_name}}', '{{$key2}}', true],
        @else
          ['{{$middle_name}}', '{{$key2}}', false],
        @endif 
      @endforeach
    ],
    @endforeach
  ];

  $(function() {
    var $select_a = $('#select_a');
    var $select_b = $('#select_b');

    $select_a.change(function() {
      $('#select_b option').remove();
      var index = $(this).prop("selectedIndex");
      b_options[index].forEach(function(option_b, idx) {
        var $option_tag_a = $('<option>').val(option_b[1]).text(option_b[0]).prop('selected', option_b[2]);
        $select_b.append($option_tag_a);
      });
      $select_b.change();
      change_size_form()
    });

    a_options.forEach(function(option_a, idx) {
      var $option_tag_b = $('<option>').val(option_a[1]).text(option_a[0]).prop('selected', option_a[2]);
      $select_a.append($option_tag_b);
      $select_a.change();
    });
  });

    // var a_select = document.getElementById('select_a');
    //カテゴリーのid取得
    var b_select = document.getElementById('select_b');
    //サブカテゴリーのid取得
    let size1  = document.getElementById("size1");
    let size2  = document.getElementById("size2");
    let size3  = document.getElementById("size3");
    //各サイズのid取得
    let cross1 = document.getElementById("cross1");
    let cross2 = document.getElementById("cross2");
    //サイズの間の×マークのd取得
    size1.style.display="none";
    size2.style.display="none";
    size3.style.display="none";
    cross1.style.display="none";
    cross2.style.display="none";
    //サイズを見えないようにする

    // a_select.addEventListener('change',change_size_form);
    b_select.addEventListener('change',change_size_form);
    //カテゴリー、サブカテゴリーを選択した時にchange_size_formを呼び出す

    function change_size_form(){
          let division_select        = document.getElementById("select_a");
          let division_select_idx    = division_select.selectedIndex;
          let division_val           = division_select.options[division_select_idx].value;
          //#select_a(カテゴリー)のoptionのvalueを取得してdivision_valに入れる
          let subdivision_select     = document.getElementById("select_b");
          let subdivision_select_idx = subdivision_select.selectedIndex;
          let subdivision_val        = subdivision_select.options[subdivision_select_idx].value;
          //#select_b(サブカテゴリー)のoptionのvalueを取得してsubdivision_valに入れる
          // if (subdivision_val == '') {
          //   subdivision_val = 0;
            //サブカテゴリーが選択されていないかロードしてきた時は0を入れる
          // }
          let all_division           = division_val + subdivision_val;
          //カテゴリーとサブカテゴリーのvalueを足してall_divisionに入れる
          console.log(all_division);
          size1.style.display="block";
          size2.style.display="block";
          size3.style.display="block";
          cross1.style.display="block";
          cross2.style.display="block";
          //サイズ区分を見えるようにする
          let size_change = document.getElementById("size_change");
          //サイズが入ったivのidを取得
      
      switch(all_division){
        case '46':
          //46の場合
          size1.setAttribute("placeholder","@lang('messages.diameter')");
          size2.setAttribute("placeholder","@lang('messages.length')");
          // size3.style.display="none";
          size3.style.opacity="0";
          size3.style.zIndex="-1";
          cross2.style.display="none";
          //size3を非表示にする
          size_change.style.justifyContent="left";
          cross1.style.margin="0 15px";
          break;
        default:
          //それ以外の場合
          size1.setAttribute("placeholder","@lang('messages.thickness')");
          size2.setAttribute("placeholder","@lang('messages.width')");
          size3.setAttribute("placeholder","@lang('messages.length')");
          size_change.style.justifyContent="space-between";
          size3.style.opacity="1";
          size3.style.zIndex="1";
          cross1.style.margin="0";
          break;
      }
      if(division_val == 5){
        //カテゴリーが5の場合
          size1.setAttribute("placeholder","@lang('messages.width')");
          size2.setAttribute("placeholder","@lang('messages.depth')");
          size3.setAttribute("placeholder","@lang('messages.height')");
          size_change.style.justifyContent="space-between";
          cross1.style.margin="0";
      }
    }
</script>
@include('public/footer')