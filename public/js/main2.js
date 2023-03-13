/*モーダル*/
  function mainChange(url,key) {
    let main_img = document.getElementById('main-img'+key);//main_imgにmain-imgを入れる
    let non_click = main_img.src;//non_clickはmain_imgのソース.src
    if ((non_click) == url){
      return;
    }//('img/' + non_click)とurlが同じなら出る(押せないようにする)
    // console.log(url);
    main_img.classList.add('anime');//main_imgにanimeを追加
    main_img.src = url;//main_img.src = url;なら、
    main_img.addEventListener('animationend', () => {
      main_img.classList.remove('anime');//animationendを呼び出す(中身はanimeを取る)
    })
  }//大きく表示させる画像の処理と、選んだあとはクリックできないようにする
  
  function modal(key) {
    let main_img_src = document.getElementById('main-img'+key).src;//main_img_srcを定義して'main-img'を呼び出す
    console.log(main_img_src);
    let modal_img = document.getElementById('modal-img'+key);//modal_imgを定義して'modal-img'を呼び出す
    modal_img.src = main_img_src;//modal_img.src = main_img_src二つは同じ
    let bg_black = document.getElementById('bg'+key);//bg_blackを定義して'bg'を呼び出す
    bg_black.classList.add('bg');//bg_blackにクラス'bg'を追加する
    let modal = document.getElementById('modal'+key);//modalを定義して'.modal'を呼び出す
    modal.classList.add('is-show');//modalに'is-show'を追加する
  }//modalを表示させる
  
  function shut(key) {
    let bg_black = document.getElementById('bg'+key);
    bg_black.classList.remove('bg');
    let modal = document.getElementById('modal'+key);
    modal.classList.remove('is-show');
  }//modalを閉じる
  
  function modalcontact() {
    let bg_black = document.getElementById('bg-contact');//bg_blackを定義して'bg'を呼び出す
    bg_black.classList.add('bg');//bg_blackにクラス'bg'を追加する
    let modal = document.querySelector('.modalcontact');//modalを定義して'.modalcontact'を呼び出す
    modal.classList.add('is-show');//modalに'is-show'を追加する
    let thanksmessage = document.getElementById('thanksmessage');
    thanksmessage.style.display ="none";
    let errormessage = document.getElementById('errormessage');
    errormessage.style.display ="none";
  }//modalを表示させる  
  
  function shutcontact() {
    let bg_black = document.getElementById('bg-contact');
    bg_black.classList.remove('bg');
    let modal = document.querySelector('.modalcontact');
    modal.classList.remove('is-show');
    let clear_form = document.querySelectorAll('.clear-form');
    for(let i = 0; i < clear_form.length; i++){
      clear_form[i].value = '';
    }//formの件名とお問い合わせ内容を消す
    let thanksmessage = document.getElementById('thanksmessage');
    thanksmessage.style.display ="";
    let errormessage = document.getElementById('errormessage');
    errormessage.style.display ="";
    let none = document.getElementById('none');
    none.style.display="";
  }//modalを閉じる
  function modalwechat() {
    let bg_black = document.getElementById('bg-wechat');//bg_blackを定義して'bg'を呼び出す
    bg_black.classList.add('bg');//bg_blackにクラス'bg'を追加する
    let modal = document.querySelector('.modalwechat');//modalを定義して'.modalcontact'を呼び出す
    modal.classList.add('is-show');//modalに'is-show'を追加する
  }//modalを表示させる
  function shutwechat() {
    let bg_black = document.getElementById('bg-wechat');
    bg_black.classList.remove('bg');
    let modal = document.querySelector('.modalwechat');
    modal.classList.remove('is-show');
    let none = document.getElementById('wechatnone');
    none.style.display="";
  }//modalを閉じる
  function modalline() {
    let bg_black = document.getElementById('bg-line');//bg_blackを定義して'bg'を呼び出す
    bg_black.classList.add('bg');//bg_blackにクラス'bg'を追加する
    let modal = document.querySelector('.modalline');//modalを定義して'.modalcontact'を呼び出す
    modal.classList.add('is-show');//modalに'is-show'を追加する
  }//modalを表示させる
  function shutline() {
    let bg_black = document.getElementById('bg-line');
    bg_black.classList.remove('bg');
    let modal = document.querySelector('.modalline');
    modal.classList.remove('is-show');
    let none = document.getElementById('linenone');
    none.style.display="";
  }//modalを閉じる
  
  (function($) {
    var $nav   = $('#navArea');
    var $btn   = $('.toggle_btn');
    var $mask  = $('#navmask');
    var open   = 'open'; // class
    // menu open close
    $btn.on( 'click', function() {
      if ( ! $nav.hasClass( open ) ) {
        $nav.addClass( open );
      } else {
        $nav.removeClass( open );
      }
    });
    // mask close
    $mask.on('click', function() {
      $nav.removeClass( open );
    });
  } )(jQuery);


if (window.matchMedia( "(max-width: 768px)" ).matches) {
  /* ウィンドウサイズが 768px以下の場合のコードをここに */
    //アコーディオンをクリックした時の動作
  $('.more-searchtitle').on('click', function() {//タイトル要素をクリックしたら
    var findElm = $(this).next(".more-box");//直後のアコーディオンを行うエリアを取得し
    $(findElm).slideToggle();//アコーディオンの上下動作
      
    if($(this).hasClass('close')){//タイトル要素にクラス名closeがあれば
      $(this).removeClass('close');//クラス名を除去し
    }else{//それ以外は
      $(this).addClass('close');//クラス名closeを付与
    }
  });
  } else {
  /* ウィンドウサイズが 768px以上の場合のコードをここに */
  }
