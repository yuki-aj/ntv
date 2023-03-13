@include('public/header',['title' => 'about_us','description' => 'about_us'])

<div id="main" class="about-us">
    <h1 class="text-center">@lang('messages.privacy-title')</h1>
    <div class="about-us-box">
        @lang('messages.privacy-date')
        <div style="line-height: 40px;">
            <div style="margin-bottom: 30px;">@lang('messages.privacy-content-title')</div>
            <ul>
                <li style="margin-bottom: 20px;">1. @lang('messages.privacy-content1')</li>
                <li style="margin-bottom: 20px;">2. @lang('messages.privacy-content2')</li>
                <li style="margin-bottom: 20px;">3. @lang('messages.privacy-content3')</li>
                <li style="margin-bottom: 20px;">4. @lang('messages.privacy-content4')</li>
                <li style="margin-bottom: 20px;">5. @lang('messages.privacy-content5')</li>
                @if($locale == 'zh-CN' || $locale == 'zh-TW')
                @else
                <li style="margin-bottom: 20px;">6. @lang('messages.privacy-content6')</li>
                @endif
            </ul>
        </div>
    </div>
</div>

@include('public/footer')