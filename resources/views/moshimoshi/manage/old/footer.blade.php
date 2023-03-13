
    <div class="fixed">
        <div class="flex gap w_90auto">
            <div class="fixeds moshidelilogo">
                <a href="/">
                <img src ="{{url('img/footer_logo.jpg')}}">
                    <!-- <p class="">ホーム</p> -->
                </a>
            </div>
            <div class="fixeds">
                <a href="/">
                <!-- <i class="bi bi-book"></i> -->
                <img src ="{{url('img/ei-book.jpg')}}">
                <h4 class="">よみもの</h4>
                </a>
            </div>
            <div class="fixeds">
                @if(Session::has('u_id'))
                <a href="/favorite">
                <!-- <i class="bi bi-heart"></i> -->
                <img src ="{{url('img/favo.jpg')}}">
                <h4 class="">お気に入り</h4>
                </a>
                @else
                <a href="/initial_email/1">
                <!-- <i class="bi bi-heart"></i> -->
                <img src ="{{url('img/favo.jpg')}}">
                <h4 class="">新規会員登録</h4>
                </a>
                @endif
            </div>
            <div class="fixeds">
                @if(Session::has('u_id'))
                    @switch(Session::get('kind'))
                        @case('0')
                        <a href="/admin_manage">
                            <!-- <i class="bi bi-person-circle"></i> -->
                            <img src ="{{url('img/login.jpg')}}">
                            <h4 class="">管理者ページ</h4>
                        </a>
                        @break
                        @case('2')
                        <a href="/store_information">
                            <!-- <i class="bi bi-person-circle"></i> -->
                            <img src ="{{url('img/login.jpg')}}">
                            <h4 class="">店舗管理ページ</h4>
                        </a>
                        @break
                        @case('3')
                        <a href="/mypage">
                            <!-- <i class="bi bi-person-circle"></i> -->
                            <img src ="{{url('img/login.jpg')}}">

                            <h4 class="">配達員ページ</h4>
                        </a>
                        @break
                        @default
                        <a href="/mypage">
                            <!-- <i class="bi bi-person-circle"></i> -->
                            <img src ="{{url('img/login.jpg')}}">
                            <h4 class="">マイページ</h4>
                        </a>
                    @endswitch
                @else
                <a href="/login">
                    <!-- <i class="bi bi-person-circle"></i> -->
                    <img src ="{{url('img/login.jpg')}}">
                    <h4 class="">ログイン</h4>
                </a>
                @endif
            </div>
            <div class="fixeds">
                <a href="/add_cart">
                <!-- <i class="bi bi-cart-fill"></i> -->
                @if(Session::get('carts'))
                <img src ="{{url('img/cart2.jpg')}}">
                @else
                <img src ="{{url('img/cart.jpg')}}">
                @endif
                <h4 class="">カート</h4>
                </a>
            </div>
        </div>
        <div class="footer_logo">
            <img src ="{{url('img/footer.jpg')}}">
        </div>
        <!-- <p class="bold t_center b_color">もしもしデリバリー</p> -->
    </div>

<!-- </div> -->

<script src="{{ asset('js/main.js',env('APP_ENV', 'local')=='production') }}"></script>
<script>
  // /*　初回ポップアップ */
//   window.onload = function () {
//     var popup = document.getElementById('js-popup');
//     if (!popup) return;
//     popup.classList.add('is-show');

//     var blackBg = document.getElementById('js-black-bg');
//     var closeBtn = document.getElementById('js-close-btn');
//     var closeimg = document.getElementById('js-close-img');
//     var closelogo = document.getElementById('js-close-logo');


//     closePopUp(blackBg);
//     closePopUp(closeBtn);
//     closePopUp(closeimg);
//     closePopUp(closelogo);

//     function closePopUp(elem) {
//         if (!elem) return;
//         elem.addEventListener('click', function () {
//             popup.classList.remove('is-show');
//         })
//     }
// }
    

// top 右下固定モーダル------------------
$(function () {
    var modalBtn = $('.js-modal__btn');
    var modalBtnClose = $('.js-modal__btn--close');
    var modalBtnCloseFix = $('.js-modal__btn--close--fix');
    var modalBg = $('.js-modal__bg');
    var modalMain = $('.js-modal__main');
    modalBtn.on('click', function (e) {
        $(this).next(modalBg).fadeIn();
        $(this).next(modalBg).next(modalMain).removeClass("_slideDown");
        $(this).next(modalBg).next(modalMain).addClass("_slideUp");
    });
    modalBtnClose.on('click', function (e) {
        modalBg.fadeOut();
        modalMain.removeClass("_slideUp");
        modalMain.addClass("_slideDown");
    });
    modalBtnCloseFix.on('click', function (e) {
        modalBg.fadeOut();
        modalMain.removeClass("_slideUp");
        modalMain.addClass("_slideDown");
    });
    modalMain.on('click', function (e) {
        e.stopPropagation();
    });
    modalBg.on('click', function () {
        $(this).fadeOut();
        $(this).next(modalMain).removeClass("_slideUp");
        $(this).next(modalMain).addClass("_slideDown");
    });
});
// // 背景クリックしたらモーダル閉じる
// addEventListener('click', outsideClose);
// function outsideClose(e) {
//     if (e.target == modal) {
//         modal.style.display = 'none';
//     };
// };

// querySelectorとは指定したセレクタに一致する最初のHTML要素(Element)を取得する
// event.preventDefaultはsubmitイベントの発生元であるフォームが持つデフォルトの動作をキャンセルする

{
  const menuItems = document.querySelectorAll('.searchmenu li a');
  const contents = document.querySelectorAll('.searchcontent');

  menuItems.forEach(clickedItem => {
    clickedItem.addEventListener('click', e => {
      e.preventDefault();

      menuItems.forEach(item => {
        item.classList.remove('actives');
      });   
      clickedItem.classList.add('actives');

      contents.forEach(content => {
          content.classList.remove('actives');
      });
      document.getElementById(clickedItem.dataset.id).classList.add('actives')
    });
  });
}

    // タブメニュー　　メニュー・店舗情報
{
    const menuItems = document.querySelectorAll('.store_tabmenu li a');
    const contents = document.querySelectorAll('.store_tabs');

    menuItems.forEach(clickedItem => {
        clickedItem.addEventListener('click', e => {
            e.preventDefault();

            menuItems.forEach(item => {
                item.classList.remove('active');
            });
            clickedItem.classList.add('active');

            contents.forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(clickedItem.dataset.id).classList.add('active')
        });
    });
}
  
  //  モーダル
  {
    const open = document.getElementById('open');
    const close = document.getElementById('close');
    const closed = document.getElementById('closed');
    const modal = document.getElementById('modal');
    const mask = document.getElementById('mask');

    open.addEventListener('click', () => {
        modal.classList.remove('hidden');
        mask.classList.remove('hidden');
    });

    close.addEventListener('click', () => {
        modal.classList.add('hidden');
        mask.classList.add('hidden');
    });

    closed.addEventListener('click', () => {
        modal.classList.add('hidden');
        mask.classList.add('hidden');
    });

    mask.addEventListener('click', () => {
        modal.classList.add('hidden');
        mask.classList.add('hidden');
        close.click();
        closed.click();
    });
}

  //  snsモーダル
  {
    const opens = document.getElementById('opens');
    const closes = document.getElementById('closes');
    const modals = document.getElementById('modals');
    const masks = document.getElementById('masks');

    opens.addEventListener('click', () => {
        modals.classList.remove('hiddens');
        masks.classList.remove('hiddens');
    });

    closes.addEventListener('click', () => {
        modals.classList.add('hiddens');
        masks.classList.add('hiddens');
    });

    masks.addEventListener('click', () => {
        modals.classList.add('hiddens');
        masks.classList.add('hiddens');
        close.click();
    });
}


// shop.blade 商品選択モーダルのアコーディオン部分
$(function(){
//.accordionの中のp要素がクリックされたら
//.accordionの中のp要素に隣接するul要素が開いたり閉じたりする。
$('.accordion p').click(function(){
$(this).next('ul').slideToggle();
});
});
$(function(){
	$('.accordion .li .p:first').addClass('selected');
	$('.accordion .li .p').click(function(){
		$(this).toggleClass('selected');
		$(this).next().slideToggle();
	});
});
$('input[type=checkbox]').change(function(){
  counter = 0;
  clicked = $(this).data('index');
  $('input[type=checkbox]').each(function(){
    if($(this)[0].checked){
      counter++;
    }
  });
  if(counter==3){    
    toDisable = clicked;
    while(toDisable==clicked){
      toDisable=Math.round(Math.random()*2);
    }
    $("input:eq("+toDisable+")")[0].checked = false;
  }
});
  // ラジオボタンクリックしたら表示
  var selecterBox = document.getElementById('sample');

function formSwitch() {
    check = document.getElementsByClassName('js-check')
    if (check[0].checked) {
        selecterBox.style.display = "none";

    } else if (check[1].checked) {
        selecterBox.style.display = "block";

    } else {
        selecterBox.style.display = "none";
    }
}
window.addEventListener('load', formSwitch());

function entryChange2(){
if(document.getElementById('changeSelect')){
id = document.getElementById('changeSelect').value;
}
}


</script>

</body>
</html>