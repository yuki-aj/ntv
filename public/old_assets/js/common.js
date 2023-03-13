'use strict';
{
  $(function () {
    const btns = document.querySelectorAll('.btn-ripple');

    btns.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        const el = e.target;
        const ripple = document.createElement('span');
        const wh = Math.max(el.clientWidth, el.clientHeight);
        const half = wh / 2;
        ripple.style = `width:${wh}px;height:${wh}px;left:${e.layerX - half}px;top:${
          e.layerY - half
        }px`;
        ripple.classList.add('ripple');
        el.appendChild(ripple);
      });

      btn.addEventListener('animationend', (e) => {
        e.currentTarget.querySelector('.ripple').remove();
      });
    });
  });

  $(function () {
    const drop_btn = document.getElementById('dropdown-btn');
    const drop_menu = document.querySelector('.dropdown-menu');
    if (drop_btn) {
      drop_btn.addEventListener('click', function () {
        drop_menu.classList.toggle('is-open');
        this.classList.toggle('is-open');
      });
    }

    const hamburger_btn = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('sidebar');
    if (hamburger_btn) {
      hamburger_btn.addEventListener('click', function () {
        sidebar.classList.toggle('is-active');
        console.log('hamburger');
      });
    }
  });

  $(function () {
    $('.toggle-pass').on('click', function () {
      $(this).toggleClass('fa-eye fa-eye-slash');
      var input = $(this).prev('input');
      if (input.attr('type') == 'text') {
        input.attr('type', 'password');
      } else {
        input.attr('type', 'text');
      }
    });
  });
}
