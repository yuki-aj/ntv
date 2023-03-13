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

$(".openbtn").click(function () {
  $(this).toggleClass('active');
  $(".header-menu").toggleClass('active');
  $("body").toggleClass("active");
});

function PageTopAnime() {

  var scroll = $(window).scrollTop();
  if (scroll >= 200){
    $('#page-top').addClass('move');
  }else{
    if($('#page-top').hasClass('move')){
      $('#page-top').removeClass('move');
    }
  }
  
  var wH = window.innerHeight;
  var footerPos =  $('#footer').offset().top;
  if(scroll+wH >= (footerPos+100)) {
    var pos = (scroll+wH) - footerPos+100
    $('#page-top').css('bottom',pos);
  }else{
    if($('#page-top').hasClass('move')){
      $('#page-top').css('bottom','5%');
    }
  }
}

$(window).scroll(function () {
PageTopAnime();
});

$('#page-top').click(function () {
  $('body,html').animate({
      scrollTop: 0
  }, 500);
  return false;
});

// tabMenu();

const tabMenuArrow = () => {
  $('#b-next').click(function () {
    if ($('.plans li:last-of-type').hasClass('active')) {
      $('.plans .active').removeClass('active');
      $('.plans li:first-of-type').addClass('active');
      $('.contents .active').removeClass('active');
      $('.contents dl:first-of-type').addClass('active');
    } else {
      $('.plans .active').next('li').addClass('active');
      $('.plans .active').prev('li').removeClass('active');
      $('.contents .active').next('.contents-item').addClass('active');
      $('.contents .active').prev('.contents-item').removeClass('active');
    }
  });

  $('#b-previous').click(function () {
    if ($('.plans li:first-of-type').hasClass('active')) {
      $('.plans .active').removeClass('active');
      $('.plans li:last-of-type').addClass('active');
      $('.contents .active').removeClass('active');
      $('.contents dl:last-of-type').addClass('active');
    } else {
      $('.plans .active').prev('li').addClass('active');
      $('.plans .active').next('li').removeClass('active');
      $('.contents .active').prev('.contents-item').addClass('active');
      $('.contents .active').next('.contents-item').removeClass('active');
    }
  });
};

tabMenuArrow();

const tabMenu = () => {
  function GetHashID(hashIDName) {
    if (hashIDName) {
      $('.plans li')
        .find('a')
        .each(function () {
          var idName = $(this).attr('href');
          if (idName == hashIDName) {
            var parentElm = $(this).parent();
            $('.plans li').removeClass('active');
            $(parentElm).addClass('active');
            $('.area').removeClass('active');
            $(hashIDName).addClass('active');
          }
        });
    }
  }

  $('.plans a').on('click', function () {
    var idName = $(this).attr('href');
    GetHashID(idName);
    return false;
  });
};

tabMenu();

// Intersection API
const setObserver = () => {
  const callback = (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('scroll-up');
      } else {
        entry.target.classList.remove('scroll-up');
      }
    });
  };

  const options = {
    rootMargin: '500px 0px -100px',
  };
  const observer = new IntersectionObserver(callback, options);
  const targets = document.querySelectorAll('.fadeUp');

  targets.forEach((elem) => {
    observer.observe(elem);
  });
};

setObserver();

const topEnd = gsap.timeline({
  scrollTrigger: {
    trigger: '.scrollPoint',
    start: 'top 70%',
    end: 'bottom center',
    markers: false,
    scrub: 5,
  },
});

topEnd
  .fromTo(
    '.flex-left',
    {
      yPercent: -20,
    },
    {
      yPercent: 0,
    }
  )
  .fromTo(
    '.flex-right',
    {
      yPercent: 20,
    },
    {
      yPercent: 0,
    },
    '<'
  );

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

