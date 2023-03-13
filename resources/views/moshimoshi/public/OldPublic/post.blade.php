<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>もしもしデリバリー</title>
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
    </head>
<body>
    <div class="wrapper">
      <div class="header">
          <h1>投稿一覧ページ</h1>
      </div>
    </div>
    @foreach($datas as $data)
    <div class="m_tops">
      <div class="w_40">
      <h1><a href="/show/{{$data->id}}">{{$data->title}}</a></h1>    <hr>
      <p class="created m_btm">{{$data->created_at}}</p>
        <p>{!! nl2br($data->main)!!}</p>
        <!-- 「もしpublic path(画像の一般公開用URL)に この記事のid番号.jpg が存在するなら、それを表示してください」 -->
        @if(file_exists(public_path().'/storage/post_img/'. $data->id .'.jpg'))
        <img src="/storage/post_img/{{ $data->id }}.jpg">
        @elseif(file_exists(public_path().'/storage/post_img/'. $data->id .'.jpeg'))
            <img src="/storage/post_img/{{ $data->id }}.jpeg">
        @elseif(file_exists(public_path().'/storage/post_img/'. $data->id .'.png'))
            <img src="/storage/post_img/{{ $data->id }}.png">
        @elseif(file_exists(public_path().'/storage/post_img/'. $data->id .'.gif'))
            <img src="/storage/post_img/{{ $data->id }}.gif">
        @endif
        <!-- 記事ID3を表示する場合は、3.jpg, 3.jpeg, 3.png, 3.gif のどれかがないか探していき、あれば、それを表示するという条件を作っている -->
      </div>
    </div>
    @endforeach
</body>
<html>