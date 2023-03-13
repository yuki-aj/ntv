<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>もしもし投稿ページ</title>
<!-- Styles -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}"><!-- asset＝CSSファイルと画像を呼び出す。 -->

<!-- Quill -->
<!-- <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet"> -->
<!-- <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script> -->

<script src="{{url('/js/ckeditor/ckeditor.js')}}"></script>

</head>
<body>
    <div class="wrapper w_40">
    <div class="header"><h1>投稿ページ</h1></div>
    <div class="content_wrapper ">
    <div class="content2">

    <form action="{{ url('wysiwyg') }}" method="post" name="ansform" enctype="multipart/form-data">
        @csrf
        <h2>タイトル</h2>
        <input type="text" name="title" class="formtitle w_100 wysiwyg">
        <p>&nbsp;</p>
        <h2>本文</h2>
        <textarea id="ckeditor" type="hidden" name="main"></textarea>
        <!-- <div id="ckeditor" style="height: 200px;"></div> -->
        <!-- <input type="file" name="post_img"> -->
        <p>&nbsp;</p>
        <div class="submit">
            <input type="submit" class="submitbtn" name="subbtn">
        </div>
    </form>
    </div>
    </div>
    </div>

<script>

CKEDITOR.replace( 'ckeditor' );
</script>

</body>
</html>