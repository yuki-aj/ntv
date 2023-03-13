<!DOCTYPE html>

<html lang="ja">

<head>
    <meta charset="UTF-8">
    @if(isset($store->id))
    <title>{{$seo[1]}}{{isset($store_name) ? '|'.$store_name : ''}}</title>
    @elseif(isset($c_id))
    <title>{{$seo[1]}}{{isset($category_name[$c_id]) ? '|'.$category_name[$c_id] : ''}}</title>
    @else
    <title>{{$seo[1]}}</title>
    @endif
    @if(isset($store->name))
    <meta name="description" content="{{isset($store->catch_copy) ? $store->catch_copy : ''}}">
    @else
    <meta name="description" content="{{$seo[2]}}">
    @endif
    <meta name="keywords" content="{{$seo[3]}}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/flickity@2.3.0/dist/flickity.pkgd.min.js"></script><!-- slider -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Modaal/0.4.4/js/modaal.min.js"></script>
    <script src="https://coco-factory.jp/ugokuweb/wp-content/themes/ugokuweb/data/9-6-1/js/9-6-1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <script src="js/jquery.layerBoard.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"><!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link rel="stylesheet" href="{{ asset('css/style.css',env('APP_ENV', 'local')=='production') }}">
    <link rel="stylesheet" href="{{ asset('css/manage_style.css',env('APP_ENV', 'local')=='production') }}">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script><!-- 郵便番号 -->
    <link href="https://fonts.googleapis.com/css2?family=BIZ+UDPGothic:wght@400;700&family=BIZ+UDPMincho&family=Kosugi+Maru&family=RocknRoll+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="apple-touch-icon" href="/img/favicon.png" sizes="180x180">
    <link rel="shortcut icon" href="/img/favicon.png">
</head>

<body style="visibility:hidden" onLoad="document.body.style.visibility='visible'">
    
<div class="width_fixed" style="position:relative;">
    <div id="menuopen" style="right:44px;position:absolute;">
        <a href="https://mosideli-plus.com/sitemap-pl">
            <img style="border-radius:0;" src ="{{url('img/side_tab.png')}}">
        </a>
    </div>
