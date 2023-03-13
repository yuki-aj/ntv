@include('public/header',['title' => 'user_individual','description' => 'user_individual'])
<div id="main">
@include('message')
    <div class="company-box">
        <div class="company-introducebox">
            <div class="company-title">
                <h1 class="text-center">{{$user->company}}</h1>
                @if($myself)
                    @if($myself === 'admin')
                        <a class="edit-button" href='/search_user'>ユーザー検索</a>
                    @endif
                    <a class="edit-button" href='/edit_user/{{$user->id}}'>@lang('messages.companyinfo')</a>
                    @if($myself === 'admin')
                        @if($user->user_status)
                            <a class="edit-button" href='/switch_status/{{$user->id}}'>無効にする</a>
                        @else
                            <a class="edit-button" href='/switch_status/{{$user->id}}'>有効にする</a>
                        @endif
                    @endif
                @endif
            </div>
            <div class="flex info-company">
                <dl class="company-left">
                    <div class="flex">
                        <dt>@lang('messages.company-name')</dt>
                        <dd>{{$user->company}}</dd>
                    </div>
                    <div class="flex">
                        <dt>@lang('messages.established')</dt>
                        <dd>{{$user->establish}}</dd>
                    </div>
                    <div class="flex">
                        <dt>@lang('messages.number-employees')</dt>
                        <dd>{{$user->employee}}</dd>
                    </div>
                    <div class="flex">
                        <dt>@lang('messages.location')</dt>
                        <dd>@lang('messages.'.$user->address)</dd>
                    </div>
                    @if(Session::has('u_id'))
                    <div class="flex">
                        <dt>@lang('messages.department')</dt>
                        <dd>{{$user->department}}</dd>
                    </div>
                    <div class="flex">
                        <dt>@lang('messages.person-in-charge')</dt>
                        <dd>{{$user->name}}</dd>
                    </div>
                    <div class="flex">
                        <dt>@lang('messages.website')</dt>
                        <dd><a style="margin: 0;" href="{{$user->site_url}}">{{$user->site_url}}</a></dd>
                    </div>
                    <div id="modalwechat" class="modalwechat">
                        <button class="round_btn" onClick="shutwechat()">✖</button>
                        <div id="modalnone"><img id="wechat" class="wechat" src="{{url($user->wechat)}}"></div>
                    </div>
                    <div id="bg-wechat" onclick="shutwechat();"></div>
                    <div id="modalline" class="modalline">
                        <button class="round_btn" onClick="shutline()">✖</button>
                        <div id="modalnone"><img id="line" class="line" src="{{url($user->line)}}"></div>
                    </div>
                    <div id="bg-line" onclick="shutline();"></div>
                    <div class="flex">
                        <dt style="align-items: center; display:flex;">@lang('messages.sns')</dt>
                        <dd>
                            <div class="flex">
                            <a onClick="modalwechat()"><img class="wechat" src="{{asset('img/wechat.png')}}"></a>
                            <a onClick="modalline()"><img class="line" src="{{asset('img/line.png')}}"></a>
                            @if($user->facebook)
                            <a href="{{$user->facebook}}"><img src="{{asset('img/facebook.png')}}"></a>
                            @endif
                            </div>
                        </dd>
                    </div>
                    @else
                    <div>
                        @if($kind == 2)
                        <a class="login-mask" href='/initial_email/1'>@lang('messages.login-mask')</a>
                        @else
                        <a class="login-mask" href='/initial_email/2'>@lang('messages.login-mask')</a>
                        @endif
                    </div>
                    @endif
                </dl>
                <div class="company-right">
                    <div class="company-picture flex"><img class="office" src="{{url($user->img)}}">
                    </div>
                    @if(Session::has('u_id'))
                    <div class="c-infobutton">
                        @if(!$myself)
                        <div class="movie-button text-center"><a class="{{ $kind == 1 ? 'buyer' : '' }}" onClick="modalcontact()">@lang('messages.contact')</a></div>
                        @endif
                        <div id="modalcontact" class="modalcontact">
                            <button class="round_btn" onClick="shutcontact()">✖</button>
                            <div id="none" class="contactus-box">
                                <form id="contact-us" class="contact-modal" method="post" action="">
                                    @if($locale == 'en')
                                    <h2 class="text-center">@lang('messages.to(companyname)'){{$user->company}}</h2>
                                    @else
                                    <h2 class="text-center">{{$user->company}} @lang('messages.to(companyname)')</h2>
                                    @endif
                                    <div class="flex">
                                        <label for="title"><span>@lang('messages.required')</span>@lang('messages.subject')</label>
                                        <input id="title" class="clear-form" form="contact-us" type="search" value="" required>
                                    </div>
                                    <div class="flex">
                                        <label class="infotext" for="information"><span>@lang('messages.required')</span>@lang('messages.request')</label>
                                        <textarea id="information" class="clear-form" form="contact-us" type="search" value="" placeholder="" required></textarea>
                                    </div>
                                    <div class="flex">
                                        <label for="name">@lang('messages.person-in-charge')</label>
                                        <input style="outline: none; border:none;" id="name" form="contact-us" type="search" value="{{$post_user->name}}" required readonly>
                                    </div>
                                    <div class="flex">
                                        <label for="mail">@lang('messages.adress')</label>
                                        <input style="outline: none; border:none;" id="mail" form="contact-us" type="search" value="{{$post_user->email}}" required readonly>
                                    </div>
                                    <input id="to_u_id" type="hidden" value="{{$user->id}}" required>
                                    <div id="errormessage">@lang('messages.sendagain')</div>
                                </form>
                                <div class="sendbutton flex border-none searchbutton-box contactbutton">
                                    <input form="contact-us" class="searchbutton" onclick="sendmail();" type="button" value="@lang('messages.send')">
                                </div>
                            </div>
                            <div id="thanksmessage">@lang('messages.forcontacting')<br>
                            @if($locale == 'ja' || $locale == 'ko')
                                {{$user->company}} @lang('messages.responsefrom')
                            @elseif($locale == 'zh-TW' || $locale == 'zh-CN')
                                @lang('messages.responsefrom')
                            @elseif($locale == 'en' || $locale == 'fr')
                                @lang('messages.responsefrom')<br>{{$user->company}}
                            @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="c-introduction-box">
                    <div class="c-introduction-title">@lang('messages.company-introduction')</div>
                    <div class="company-pr">{{$user->note}}</div>
            </div>
        </div>
        <div id="bg-contact" onclick="shutcontact();"></div>
        <div class="search-title">
            @if($kind == 1)
            <h2>@lang('messages.searching-products')</h2>
            @elseif($kind == 2)
            <h2>@lang('messages.products')</h2>
            @endif
            @if($myself || $myself === 'admin')
            <a class="edit-button {{ $kind == 1 ? 'buyer' : '' }}" style="width: 140px;" href='/edit_product/{{$user->id}}/0'>@lang('messages.addproducts')</a>
            @else
            @endif
        </div>
        <div class="menu">
            @foreach($products as $key => $product)
            @if($myself || $kind == 3)
            <label for="menu_bar{{++$i}}" class="{{ $kind == 1 ? 'buyer-label' : '' }} "><span class="p-name">{{$product->name}}</span>
            @else
            <label for="menu_bar{{++$i}}" class="{{ $kind == 1 ? 'buyer-label' : '' }} "><span>{{$product->name}}</span>
            @endif
            <span style="display: flex;">
            @if($product->updated_at < $compare_date && $myself)
            <div style="font-size: 10px;">@lang('messages.renewal') @lang('messages.lastrenewal'){{$product->message}}</div>
            @endif
            @if($myself || $myself === 'admin')
            <a href='/edit_product/{{$user->id}}/{{$product->id}}'>@lang('messages.edit')</a>
            <a href='/delete_product/{{$product->id}}'>@lang('messages.delete')</a>
            @endif
            </span>
            </label>
            <input class="accordion" type="checkbox" id="menu_bar{{$i}}" {{$product->id == $p_id ? 'checked':''}} />
            <ul id="links{{$i}}" class="accordion-list-box">
                <li class="accordion-list">
                    <div class="product-box flex">
                        <div class="introduce-table">
                                <div id="bg{{$key}}" onClick="shut('{{$key}}')"></div>
                            <div class="product-small-box flex">
                                <div class="modal-wrap">
                                    <img id="main-img{{$key}}" src="{{url($product->img1)}}" onClick="modal('{{$key}}')">
                                    <div class="choice">
                                        @for($j=1; $j < 4 ; $j++)
                                        <?php $img = 'img'.$j; ?>
                                        <img src="{{url($product->$img)}}" onClick="mainChange('{{url($product->$img)}}','{{$key}}')">
                                        @endfor
                                    </div>
                                </div>
                                <div id="modal{{$key}}" class="modal">
                                    <button class="round_btn" onClick="shut('{{$key}}')">✖</button>
                                    <img id="modal-img{{$key}}" src="{{url($product->img1)}},{{$key}}">
                                </div>
                                <div class="product-text-box">
                                    @if(Session::has('u_id'))
                                        <ol class="breadcrumb" itemscope itemtype="#">
                                            <li itemprop="itemListElement" itemscope itemtype="#">
                                                @if(!empty($product->pdivision))
                                                    <span itemprop="name">{{$product->pdivision}}</span>
                                                @else
                                                    <span itemprop="name">@lang('messages.not-select')</span>
                                                @endif
                                                <meta itemprop="position" content="1" />
                                            </li>
                                            <li itemprop="itemListElement" itemscope itemtype="#">
                                                @if(!empty($product->psubdivision))
                                                    <span itemprop="name">{{$product->psubdivision}}</span>
                                                @else
                                                    <span itemprop="name">@lang('messages.not-select')</span>
                                                @endif
                                                    <meta itemprop="position" content="2" />
                                            </li>
                                            <li itemprop="itemListElement" itemscope itemtype="#">
                                                    <span itemprop="name">{{$product->type_name}}</span>
                                                <meta itemprop="position" content="3" />
                                            </li>
                                        </ol>
                                        <div class="product-text flex">
                                            <div class="product-title">@lang('messages.product-name')</div>
                                            <div class="product-info-box">{{$product->name}}</div>
                                        </div>
                                        <div class="product-text flex">
                                            <div class="product-title">@lang('messages.grade-keyword')</div>
                                            <div class="product-info-box">
                                            @foreach($product->grade as $grade)
                                            {{$grade}}<br>
                                            @endforeach
                                            </div>
                                        </div>
                                        @if($kind == 2)
                                            <div class="product-text flex">
                                                <div class="product-title">@lang('messages.origin')</div>
                                                <div class="product-info-box">{{$product->origin}}</div>
                                            </div>
                                        @endif
                                        <div class="product-text flex">
                                            <div class="product-title">@lang('messages.size')・@lang('messages.unit')</div>
                                            @if($product->size3 == 0)
                                            <div class="product-info-box">{{$product->size1}}×{{$product->size2}}({{$product->unit}})</div>
                                            @else
                                            <div class="product-info-box">{{$product->size1}}×{{$product->size2}}×{{$product->size3}}({{$product->unit}})</div>
                                            @endif
                                        </div>
                                        @if($kind == 2)
                                            <div class="product-text flex">
                                                <div class="product-title">@lang('messages.price')</div>
                                                <div class="product-info-box">
                                                    US$ {{$product->price}}{{$product->p_unit_name}}<br>
                                                    <div style="font-size: 10px;">@lang('messages.FOB')<br>@lang('messages.d-price')</div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="product-text flex">
                                            <div class="product-title">@lang('messages.description-products')</div>
                                            <div class="product-info-box product-note">{{$product->note}}</div>
                                        </div>
                                    @else
                                        @if($kind == 2)
                                            <a class="login-mask" href='/initial_email/1'>@lang('messages.login-mask')</a>
                                        @else
                                            <a class="login-mask" href='/initial_email/2'>@lang('messages.login-mask')</a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if($product->video_url)
                            <div class="movie-button text-center"><a class="{{ $kind == 1 ? 'buyer' : '' }}" href="{{$product->video_url}}">商品説明動画を見る</a></div>
                            @endif
                        </div>
                    </div>
                </li>
            </ul>
            @endforeach
        </div>
    </div>
</div>
<script>
function sendmail() {
  var titles = document.getElementById('title').value;
  var informations = document.getElementById('information').value;
  var names = document.getElementById('name').value;
  var mails = document.getElementById('mail').value;
  var to_u_id = document.getElementById('to_u_id').value;
  let thanksmessage = document.getElementById('thanksmessage');
  thanksmessage.style.display ="none";
  let errormessage = document.getElementById('errormessage');
  errormessage.style.display ="none";

  var title       = titles.replace(/[\t\s ]/g, '');
  var information = informations.replace(/[\t\s ]/g, '');
  var name        = names.replace(/[\t\s ]/g, '');
  var mail        = mails.replace(/[\t\s ]/g, '');
  //半角、全角空白、改行を空にする
  if(title == '' || information == '' || name == '' || mail == ''){
    alert("@lang('messages.invalid-entry')");
    return false;
  }
  $.ajax({
    type: 'POST',
    dataType: 'html',
    url: '/contact',
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    dataType:'text',
    data: {
      'title':title,
      'information':information,
      'name':name,
      'mail':mail,
      'to_u_id':to_u_id,
    },
    success: function (data) {
      let thanksmessage = document.getElementById('thanksmessage');
      thanksmessage.style.display = "block";
      let none = document.getElementById('none');
      none.style.display= "none";
    },
    error: function () {
      let errormessage = document.getElementById('errormessage');
      errormessage.style.display = "block";
    }
  })
  }
</script>
<style>
    @for($j=1; $j <=count($products); $j++)
     #menu_bar{{$j}}:checked~#links{{$j}} .accordion-list {
        max-height: 9999px;
        height: max-content;
        box-sizing: border-box;
        opacity: 1;
    }
    @endfor
</style>

@include('public/footer')