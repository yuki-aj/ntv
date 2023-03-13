@include('public/header',['title' => 'about_us','description' => 'about_us'])

<div id="main" class="about-us flex">
    <h1 class="text-center">@lang('messages.about-us')</h1>
    <div class="about-us-box">
            @lang('messages.text-about-us')
    </div>
</div>

@include('public/footer')