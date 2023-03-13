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
            <h1>投稿詳細ページ</h1>
        </div>
        <div class="sidebar">
            <p></p>
        </div>

        <div class="content3 w_40 m_tb">
            <p class="created">
                {{$data->created_at}}
            </p>
            <h1>
                {{$data->title}}
            </h1>
            <hr>
            <p>
                {{$data->main}}
            </p>
        </div>

        <!-- <div class="footer">
            <p>お問い合わせ</p>
        </div> -->
    </div>
    </body>
</html> 