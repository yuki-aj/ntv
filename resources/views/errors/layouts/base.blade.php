@include('manage/header')
<style>
    h1 {
        color:gray;
        font-size:1.2em;
    }
    .error-message {
        text-align:center;
        font-size:0.9em;
    }
</style>

<div class="width_fixed" style="position:relative;">
    <div id="menuopen" style="right:44px;position:absolute;">
        <a href="https://mosideli-plus.com/sitemap-pl">
            <img style="border-radius:0;" src ="{{url('img/side_tab.png')}}">
        </a>
    </div>
    <div style="padding-top:5em;"></div>
    <div class="t_center">
        <img style="width:40%;"src ="/img/404logo.png">
    </div>
    <div class="error-wrap">
    <section>
        <h1>@yield('title')</h1>
        <p class="error-message">@yield('message')</p>
    </section>
    </div>

    <div class="p_2em t_center">
     <p class="error_btn"><a href="/"><span>トップページへ戻る</span></a></p>
    </div>

@include('public/footer')
