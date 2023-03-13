<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>Summernote with Bootstrap 4</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"><!-- これがないと画像が読み込めない -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
 
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  </head>
  <body>
    
    <form method="post" action="{{asset('summernote/store')}}" enctype="multipart/form-data">
    @csrf
    <textarea id="summernote" name="summernote"></textarea>
    <button type=”submit” class="btn btn-danger btn-block">保存</button>
    </form>
    <script>
    //   $('#summernote').summernote({
    //     placeholder: 'Hello Bootstrap 4',
    //     tabsize: 2,
    //     height: 100
    //   });
    jQuery(document).ready(function($) {
    $('#summernote').summernote({
        placeholder: 'Hello Bootstrap 4',
        tabsize: 2,
        height: 100,
 
  
     callbacks: {
      onImageUpload : function(files, editor, welEditable) {
         for(var i = files.length - 1; i >= 0; i--) {
                 sendFile(files[i], this);
          }
      }
     } 
 });
 
  function sendFile(file, el) {
    var form_data = new FormData();
    form_data.append('file', file);
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data: form_data,
      type: "POST",
      contentType: 'multipart/form-data',
      // 画像保存用のルート設定
      url: 'temp',
      cache: false,
      contentType: false,
      processData: false,
      success: function(url) {
        $(el).summernote('editor.insertImage', url);
        // $('#summernote').summernote('insertImage',url);
      }
    });
  }
});
    </script>
  </body>
</html>
Without Bootstrap