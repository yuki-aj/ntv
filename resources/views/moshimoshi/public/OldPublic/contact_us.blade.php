@include('public/header',['title' => 'contact_us','description' => 'contact_us'])
<main id="main">
    @include('message')
    <form id="contact-us" class="contact_us" method="post" action="contact_us">
    @csrf
        <h1 class="text-center">@lang('messages.contact')</h1>
        <div id="none" class="success-box">
            <div class="flex">
                <label for="title"><span>@lang('messages.required')</span>@lang('messages.subject')</label>
                <input id="title" name="title" class="clear-form" form="contact-us" type="search" value="" required>
            </div>
            <div class="flex">
                <label class="infotext" for="information"><span>@lang('messages.required')</span>@lang('messages.request')</label>
                <textarea id="information" name="information" class="clear-form" cols="30" rows="10" form="contact-us" type="search" value="" placeholder="" required></textarea>
            </div>
            <div class="flex">
                <label for="company_name"><span>@lang('messages.required')</span>@lang('messages.company-name')</label>
                <input id="company_name" name="company_name" form="contact-us" type="search" value="" required>
            </div>
            <div class="flex">
                <label for="u_name"><span>@lang('messages.required')</span>@lang('messages.person-in-charge')</label>
                <input id="u_name" name="u_name" form="contact-us" type="search" value="" required>
            </div>
            <div class="flex">
                <label for="u_mail"><span>@lang('messages.required')</span>@lang('messages.adress')</label>
                <input name="u_mail" id="u_mail" form="contact-us" type="email" value="" placeholder="account@example.com" required>
            </div>
            <input id="to_u_id" type="hidden" value="" required>
            <div id="errormessage" class="flex text-center">@lang('messages.sendagain')</div>
            <div class="sendbutton flex border-none searchbutton-box contactbutton">
                <button class="searchbutton mt-40" type="submit">@lang('messages.send')</button>
            </div>
        </div>
        
        <div id="thanksmessage">@lang('messages.forcontacting')<br>@lang('messages.ourcustomer')</div>
    </form>
</main>
<script>
    let send_flag = {{$send_flag}};
    if(send_flag){
        let thanksmessage = document.getElementById('thanksmessage');
        thanksmessage.style.display ="block";
        let none = document.getElementById('none');
        none.style.display="none";
    }
</script>
@include('public/footer')