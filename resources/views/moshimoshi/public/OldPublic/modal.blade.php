
<script>
  $(".mdl").on('click',function(){//商品選択時モーダルポップアップ
    var form     = $(this).closest('.modal-add').get(0);
    var s_id     = form.elements['s_id'].value;//store_id
    var p_id     = form.elements['p_id'].value;//product_id
    var quantity = form.elements['quantity'].value;//product_id
    $.ajax({//モーダルの中身を表示する為のajax
        type: "POST",
        //ここでデータの送信先URLを指定します。
        url: "/add_modal",//add_modalに送る
        dataType: "json",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {
          s_id: s_id,//ショップid
          p_id: p_id,//商品id
          quantity: quantity,//個数
        },
      })
      //処理が成功したら
      .done(function(data) {
        // console.log(2);
        let result = JSON.parse(JSON.stringify(data));
        for($i = 0; $i < result['options'].length; $i++){
         if(result['options'][$i]['title']){
          // console.log(result['options'][$i]['title']);
         }
          // console.log(result['options'][$i]['name']);
          // console.log(result['options'][$i]['price']);
        }
        // console.log('成功');
      // //HTMLファイル内の該当箇所にレスポンスデータを追加します。
          $(".option_all").html(result['option_box']);//商品名
          $(".p_name").html(result['p_name']);//商品名
          // $(".p_name").html(result['p_name']);//商品名
          $(".p_price").html(result['p_price']);//価格
          $(".p_note").html(result['p_note']);//商品説明
          $(".cart_add .s_id").val(result['s_id']);//店舗id
          $(".cart_add .p_id").val(result['p_id']);//商品id
          $(".cart_add .quantity").val(result['quantity']);//注文数
          $(".cart_add .p_img").attr('src',result['p_img']);//商品画像
        //モーダルの中のラジオボタンの処理(1つのみ選択)
          var radio_val;
          $('.radio_button').on('click',function(){
            if($(this).val() == radio_val) {
              $(this).prop('checked', false);
              radio_val = null;
            } else {
              radio_val = $(this).val();
            }
          });
      })
      //処理がエラーであれば
      .fail(function(xhr) {
        // console.log('失敗2');
        //通信失敗時の処理
        //失敗したときに実行したいスクリプトを記載
      })
      .always(function(xhr, msg) { 
        //通信完了時の処理
        //結果に関わらず実行したいスクリプトを記載
      });
  });
  //モーダル
  $(function(){
    // 変数に要素を入れる
    var open = $('.modal-open'),
      close = $('.mdl-close'),
      container = $('.modal-container');

    //開くボタンをクリックしたらモーダルを表示する
    open.on('click',function(){	
      document.getElementById('mdl_img').addEventListener("load", function(event) {
      container.addClass('active');
      return false;
      })
    });
    //閉じるボタンをクリックしたらモーダルを閉じる
    close.on('click',function(){	
      container.removeClass('active');
    });
    //モーダルの外側をクリックしたらモーダルを閉じる
    $(document).on('click',function(e) {
      if(!$(e.target).closest('.modal-body').length) {
        container.removeClass('active');
      }
    });
  });
  $("#mdl_cart").on('click',function(){//数量変更時の処理
    var form       = $(this).closest('.cart_add').get(0);
    var s_id       = form.elements['s_id'].value;//store_id
    var p_id       = form.elements['p_id'].value;//product_id
    var p_quantity = form.elements['quantity'].value;//product_id
    if(p_quantity <= 0){//0個で送った場合の処理
        alert('個数を入力してください');
        return false;
    }
  });
</script>