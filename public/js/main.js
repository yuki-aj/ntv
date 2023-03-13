'use strict';


//モーダル
{
  const open = document.getElementById('open');
  const close = document.getElementById('close');
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

  mask.addEventListener('click', () => {
    // modal.classList.add('hidden');
    // mask.classList.add('hidden');
    close.click();
  });
}


// モーダル
{
  const open = document.getElementById('opens');
  const close = document.getElementById('closes');
  const modal = document.getElementById('food');
  const mask = document.getElementById('masks');

  open.addEventListener('click', () => {
    modal.classList.remove('hidden');
    mask.classList.remove('hidden');
  });

  close.addEventListener('click', () => {
    modal.classList.add('hidden');
    mask.classList.add('hidden');
  });

  mask.addEventListener('click', () => {
    // modal.classList.add('hidden');
    // mask.classList.add('hidden');
    close.click();
  });
}


// buttonをクリックするとtargetが表示される
// var $target = document.querySelector('.target')
// var $button = document.querySelector('.button')
// $button.addEventListener('click', function() {
//   $target.classList.toggle('is-hidden')
// })


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



// スライダー
$('.slider').slick({
  autoplay: true,         //自動再生
  autoplaySpeed: 2000,  //自動再生のスピード
  speed: 2000,  //スライドするスピード
  // dots: true,//スライドしたのドット
  arrows: true,          //左右の矢印
  infinite: true,//スライドのループ
  pauseOnHover: false,   //ホバーしたときにスライドを一時停止しない　
});


// アコーディオン　開いたり閉じたりする
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



//  (お店・商品)　（メニュー・店舗情報）のタブメニュー部分
$('.tab-content>div').hide();
$('.tab-content>div').first().slideDown(2000);
$('.tab-buttons span').click(function () {
    var thisclass = $(this).attr('class');
    $('#lamp').removeClass().addClass('#lamp').addClass(thisclass);
    $('.tab-content>div').each(function () {
        if ($(this).hasClass(thisclass)) {
            $(this).fadeIn(1000);
        }
        else {
            $(this).hide();
        }
    });
});

// タブメニュー　タブ切り替え
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

// クリックしたら金額が増減する
document.addEventListener('click', function (e) {
  e = e || window.event;
  var target = e.target || e.srcElement,
    text = target.textContent || target.innerText;

  var val = 0;

  //クリックしたDOMが.js-qty_upだったら
  if (target.classList.contains('js-qty_up')) {
    val = 1;
  } else if (target.classList.contains('js-qty_down')) {
    val = -1;
  } else {
    return false;
  }
  var parent = getParents(target, 'js-qty');//親の.js-qtyを取得して
  var input = parent.querySelectorAll('.js-qty_target');//親の.js-qtyの子の.js-qty_targetを取得して
  //Nodelistを回す
  for (let i = 0; i < input.length; i++) {
    if (input[i].classList.contains('js-qty_target')) {
      //.js-qty_target持ってるDOMに対して
      var num = parseInt(input[i].value);
      num = isNaN(num) ? 1 : num;
      input[i].value = num + val < 1 ? 1 : num + val;
    }
  }

}, false);



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

{
  // getElementByIdとは、指定したid値を持つ要素をElementオブジェクトとして返すメソッド
  // HTMLタグの中から指定したidを取得して何らかの処理をしたい場合などに使用
  // querySelector()を使うとid属性値・class属性値などを意識せずにjQuery感覚でHTML要素をセレクタ指定することができる
  // addEventListener()は、JavaScriptからさまざまなイベント処理を実行することができるメソッド
  // addとは指定した文字列などをclassに対して要素にクラスを追加する
  // removeとは要素からクラスを削除する


  const open = document.getElementById('open');
  const overlay = document.querySelector('.overlay');
  const close = document.getElementById('close');

  open.addEventListener('click', () => {
    overlay.classList.add('show');
    open.classList.add('hide');
  });

  close.addEventListener('click', () => {
    overlay.classList.remove('show');
    open.classList.remove('hide');
  });
}





//vanilla jsで親要素探索する用の関数
function getParents(el, parentSelector /* optional */) {
if (parentSelector === undefined) {
  return false;
}

var p = el.parentNode;

while (!p.classList.contains(parentSelector)) {
  var o = p;
  p = o.parentNode;
}
return p;
}

 



 /*　初回ポップアップ */
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

// トップのスライドショー
// {
//   function play() {
//     setTimeout(() => {
//       images[currentIndex].classList.remove('current');
//       currentIndex++;
//       if (currentIndex > images.length - 2) {
//         currentIndex = 0;
//       }
//       images[currentIndex].classList.add('current');
//       play();
//     }, 4000); 
//     // 4秒
//   }
//   const images = document.querySelectorAll('.moveimg img')
//   let currentIndex = 0;

//   play();
// }

// // アコーディオン
// {
//   // dtをクリックしたら子要素が開いたり閉じたりする
//   const dts = document.querySelectorAll('dt');
//   dts.forEach(dt => {
//     dt.addEventListener('click', () => {
//       dt.parentNode.classList.toggle('appear');


//       // 1つだけ開いた状態にさせる。他の質問は閉じるようになる。
//       dts.forEach(el => {
//         if (dt !== el) {
//           el.parentNode.classList.remove('appear');
//         }
//       });
//     });
//   });
// } 