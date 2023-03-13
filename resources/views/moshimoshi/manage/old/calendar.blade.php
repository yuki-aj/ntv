@include('manage/header')

<h1 class="baskets">配送可能時間設定</h1>

<div class="admin_page">
  <form action="{{ url('calendar/'.$s_id) }}" method="post" name="calendar_form">
    @csrf
      <div class="flex m_tb3 gap w_50">
          <label>    
            <input class="form-check-input" type="radio" name="open" value="1" onclick="formSwitch()">
            営業日
          </label>
          <label>    
            <input class="form-check-input" type="radio" name="open" value="0" onclick="formSwitch()">
            定休日
          </label>
      </div>
      <div class="calendar_c">
        <div class="flex">
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="7" id="" name="date">
                ALL
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="1" id="" name="date">
                月
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="2" id="" name="date">
                火
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="3" id="" name="date">
                水
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="4" id="" name="date">
                木
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="5" id="" name="date">
                金
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="6" id="" name="date">
                土
            </label>
          </div>
          <div class="form-check">
            <label>    
              <input class="form-check-input" type="radio" value="0" id="" name="date">
                日
            </label>
          </div>
        </div>
        <div class="m_top3">
          <input class="form-check" type="radio" value="8" id="" name="date">
          <label class="form-check-label">
              日付
              <input class="form-check" type="date" id="today" name="day">
          </label>
        </div>
      </div>
      <div id="from_to_date">
        <div class="flex calendar_time">
          <select id="from1" name="from1" class="calendar_choice">
              <option value="">ー</option>
              <option value="11:00">11:00</option>
              <option value="11:30">11:30</option>
              <option value="12:00">12:00</option>
              <option value="12:30">12:30</option>
              <option value="13:00">13:00</option>
              <option value="13:30">13:30</option>
              <option value="14:00">14:00</option>
              <option value="14:30">14:30</option>
              <option value="15:00">15:00</option>
              <option value="15:30">15:30</option>
              <option value="16:00">16:00</option>
              <option value="16:30">16:30</option>
              <option value="17:00">17:00</option>
              <option value="17:30">17:30</option>
              <option value="18:00">18:00</option>
              <option value="18:30">18:30</option>
              <option value="19:00">19:00</option>
              <option value="19:30">19:30</option>
              <option value="20:00">20:00</option>
          </select>
          ～
          <select id="to1" name="to1" class="calendar_choice">
              <option value="">ー</option>
              <option value="11:00">11:00</option>
              <option value="11:30">11:30</option>
              <option value="12:00">12:00</option>
              <option value="12:30">12:30</option>
              <option value="13:00">13:00</option>
              <option value="13:30">13:30</option>
              <option value="14:00">14:00</option>
              <option value="14:30">14:30</option>
              <option value="15:00">15:00</option>
              <option value="15:30">15:30</option>
              <option value="16:00">16:00</option>
              <option value="16:30">16:30</option>
              <option value="17:00">17:00</option>
              <option value="17:30">17:30</option>
              <option value="18:00">18:00</option>
              <option value="18:30">18:30</option>
              <option value="19:00">19:00</option>
              <option value="19:30">19:30</option>
              <option value="20:00">20:00</option>
          </select>
        </div>
        <div class="flex calendar_time">
          <select id="from2" name="from2" class="calendar_choice">
              <option value="">ー</option>
              <option value="11:00">11:00</option>
              <option value="11:30">11:30</option>
              <option value="12:00">12:00</option>
              <option value="12:30">12:30</option>
              <option value="13:00">13:00</option>
              <option value="13:30">13:30</option>
              <option value="14:00">14:00</option>
              <option value="14:30">14:30</option>
              <option value="15:00">15:00</option>
              <option value="15:30">15:30</option>
              <option value="16:00">16:00</option>
              <option value="16:30">16:30</option>
              <option value="17:00">17:00</option>
              <option value="17:30">17:30</option>
              <option value="18:00">18:00</option>
              <option value="18:30">18:30</option>
              <option value="19:00">19:00</option>
              <option value="19:30">19:30</option>
              <option value="20:00">20:00</option>
          </select>
          ～
          <select id="to2" name="to2" class="calendar_choice">
              <option value="">ー</option>
              <option value="11:00">11:00</option>
              <option value="11:30">11:30</option>
              <option value="12:00">12:00</option>
              <option value="12:30">12:30</option>
              <option value="13:00">13:00</option>
              <option value="13:30">13:30</option>
              <option value="14:00">14:00</option>
              <option value="14:30">14:30</option>
              <option value="15:00">15:00</option>
              <option value="15:30">15:30</option>
              <option value="16:00">16:00</option>
              <option value="16:30">16:30</option>
              <option value="17:00">17:00</option>
              <option value="17:30">17:30</option>
              <option value="18:00">18:00</option>
              <option value="18:30">18:30</option>
              <option value="19:00">19:00</option>
              <option value="19:30">19:30</option>
              <option value="20:00">20:00</option>
          </select>
        </div>
      </div>
      <div class="t_center m_top5">
          <button class="addition" onclick="return  time_check();">更新する</button>
      </div>
  </form>
</div>

<script type="text/javascript">

  //今日の日時を表示
  window.onload = function () {
    //今日の日時を表示
    var date = new Date()
    var year = date.getFullYear()
    var month = date.getMonth() + 1
    var day = date.getDate()
  
    var toTwoDigits = function (num, digit) {
      num += ''
      if (num.length < digit) {
        num = '0' + num
      }
      return num
    }
    
    var yyyy = toTwoDigits(year, 4)
    var mm = toTwoDigits(month, 2)
    var dd = toTwoDigits(day, 2)
    var ymd = yyyy + "-" + mm + "-" + dd;
    
    document.getElementById("today").value = ymd;

  }


  function time_check() {
    var flag = false;
    let today = document.getElementById("today").value;
    for(var i=0; i<document.calendar_form.date.length-1;i++){
        if(document.calendar_form.date[i].checked){
          flag = true;
        }
    }
    if(flag == false && today == ''){
        alert('日付を指定してください。');
        return false;
    }
    let open_check = document.getElementsByName('open');
    let check_date = document.getElementsByName('date');
    let date_flag = false;
    if(open_check[1].checked){//定休日
      for($i = 0; $i < check_date.length; $i++){
        if(check_date[$i].checked){
          date_flag = true;
        }
      }
      if(!date_flag){
        alert('曜日か日付を選択して下さい。');
        return false;
      }
      document.calendar_form.submit();
    }else if(open_check[0].checked){//営業日
      for($i = 0; $i < check_date.length; $i++){
        if(check_date[$i].checked){
          date_flag = true;
        }
      }
      if(!date_flag){
        alert('曜日か日付を選択して下さい。');
        return false;
      }
      let from_date1  = document.getElementById('from1').value;
      let to_date1    = document.getElementById('to1').value;
      let from_date2  = document.getElementById('from2').value;
      let to_date2    = document.getElementById('to2').value;
      if(from_date1 > to_date1 || from_date2 > to_date2){//終了時間の方が早い場合
        alert('時間を正しく選択してください。');
        return false;
      }
      if(from_date1 == '' ){// 午前の最初が空の時
        if(from_date2 != '' && to_date2 != '' && to_date1 == ''){// 午後の営業時間のみ入力されているとき
         return true;
        }
          alert('時間を正しく選択してください。');
          return false;
      }
      if(from_date2 == '' ){// 午後の最初が空の時
        if(from_date1 != '' && to_date1 != '' && to_date2 == ''){// 午後の営業時間のみ入力されているとき
         return true;
        }
          alert('時間を正しく選択してください。');
          return false;
      }
      if(from_date2 != '' && to_date1 > from_date2){//午後の開始時間が午前の終了より早い場合
        alert('午後の営業開始時間は、午前の終了時間と同じか、それより大きい時間を指定してください。');
        return false;
      }
    }else{//未選択
        alert('営業日か、定休日を選択してください。');
        return false;
    }
  }

  // 営業日クリックで時間表示
  function formSwitch() {
      hoge = document.getElementsByName('open')
      if (hoge[0].checked) {
          document.getElementById('from_to_date').style.display = "";
      } else if (hoge[1].checked) {
          document.getElementById('from_to_date').style.display = "none";
          var inputItem = document.getElementById("from_to_date").getElementsByTagName("input");
          for(var i=0; i<inputItem.length; i++){
          inputItem[i].checked = "";
          }
      } else {
          document.getElementById('from_to_date').style.display = "none";
          var inputItem = document.getElementById("from_to_date").getElementsByTagName("input");
          for(var i=0; i<selectItem.length; i++){
          inputItem[i].value = "";
          }
      }
  }
  window.addEventListener('load', formSwitch());
</script>

</body>
</html>
