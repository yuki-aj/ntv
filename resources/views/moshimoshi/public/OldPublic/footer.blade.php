<div class="m_b">
</div>
<div class="fixed">
    <div class="flex w_90auto">
        <div class="fixeds moshidelilogo">
            <a href="/">
                <img src="{{url('img/home.png')}}">
            </a>
        </div>
        <div class="fixeds">
            <a href="https://mosideli-plus.com/">
                <img src="{{url('img/yomimono.png')}}">
            </a>
        </div>
        <div class="fixeds">
            <a href="https://mosideli-plus.com/user_guide">
                <img src="{{url('img/tsukaikata.png')}}">
            </a>
        </div>
        <div class="fixeds">
            @if(Session::has('u_id'))
            @switch(Session::get('kind'))
            @case('0')
            <a href="/admin_manage">
                <img src="{{url('img/admin.png')}}">
            </a>
            @break
            @case('2')
            <a href="/store_information">
                <img src="{{url('img/admin.png')}}">
            </a>
            @break
            @case('3')
            <a href="/mypage">
                <img src="{{url('img/admin.png')}}">
            </a>
            @break
            @default
            <a href="/mypage">
                <img src="{{url('img/mypage.png')}}">
            </a>
            @endswitch
            @else
            <a href="/login">
                <img src="{{url('img/login.png')}}">
            </a>
            @endif
        </div>
        <div class="fixeds">
            <a href="/add_cart">
                @if(Session::get('carts'))
                <img src="{{url('img/cart2.png')}}">
                @else
                <img src="{{url('img/cart1.png')}}">
                @endif
            </a>
        </div>
    </div>
    <div class="footer_logo">
        <img src="{{url('img/footerlogo.png')}}">
    </div>
</div>

</div><!-- headerの閉じタブ -->

<script src="{{ asset('js/main.js',env('APP_ENV', 'local')=='production') }}"></script>
<script>
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
    $(function () {
        //.accordionの中のp要素がクリックされたら
        //.accordionの中のp要素に隣接するul要素が開いたり閉じたりする。
        $('.accordion p').click(function () {
            $(this).next('ul').slideToggle();
        });
    });
    $(function () {
        $('.accordion .li .p:first').addClass('selected');
        $('.accordion .li .p').click(function () {
            $(this).toggleClass('selected');
            $(this).next().slideToggle();
        });
    });
    $('input[type=checkbox]').change(function () {
        counter = 0;
        clicked = $(this).data('index');
        $('input[type=checkbox]').each(function () {
            if ($(this)[0].checked) {
                counter++;
            }
        });
        if (counter == 3) {
            toDisable = clicked;
            while (toDisable == clicked) {
                toDisable = Math.round(Math.random() * 2);
            }
            $("input:eq(" + toDisable + ")")[0].checked = false;
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

    function entryChange2() {
        if (document.getElementById('changeSelect')) {
            id = document.getElementById('changeSelect').value;
        }
    }
</script>

</body>
</html>