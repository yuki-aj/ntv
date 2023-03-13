@include('manage/header')
<style>
    @media  screen and (max-width: 600px) {
        .w_60 {
            width:90%;
        }
    }
</style>
<h1 class="baskets">注文管理</h1>
    <!-- <div class="admin_page">
        <div class="addition gap m_t3" style="border-radius: 50px;">
        <a href="/order_search">
            <p>管理画面へ</p>
        </a>
        </div>
    </div> -->
    <div class="flexbox">
        <form id="all_staff" name="all_staff" class="staff_form form-table" action="/staff_list/{{$id}}" method="Post">
            @csrf
            <h1 style="text-align: center; margin:20px 0 0; color:#000;">配達員選択</h1>
            @if(isset($msg) && $msg != '')
            <div class="w_60">
                <div class="t_center text-success margin10" style="padding:5px 10px;">{{$msg}}</div>
            </div>
            @endif
            <table class="w_60 t_center info-table2">
                @foreach($staff_lists as $key => $staff)
                @if($staff_lists[$key] == $staff_lists[0])
                <tr>
                    <th colspan="4">配達員名</th>
                    <th colspan="4">ID</th>
                    <th colspan="4">リスト<br>
                        <label><input id="checkAll" type="checkbox" name="coupon" value="checkall"></label><br>
                    </th>
                    <th colspan="4">決定ボタン</th>
                </tr>
                @endif
                <tr>
                    <td colspan="4">{{$staff->name}}</td>
                    <td colspan="4">{{$staff->id}}</td>
                    <td colspan="4"><input type="checkbox" class="checks" name="staff_id[]" value="{{$staff->id}}" {{$staff['checked']}}></td>
                    @if($staff['checked'] != '')
                        @if($staff['radiochecked'] == '' && $order->d_staff_id == 0)
                        <td colspan="4">
                            <input  form="d_staff_id_form_{{$staff->id}}" type="hidden" name="d_staff_id" value="{{$staff->id}}" {{$staff['radiochecked']}}>
                            <button form="d_staff_id_form_{{$staff->id}}" type="submit" class="orange_btn" style="border:none;"  value="{{$staff->id}}">決定</button>
                        </td>
                        @elseif($staff['radiochecked'] == '' && $order->d_staff_id != 0)
                        <td colspan="4"></td>
                        @else
                        <td colspan="4" style="background: #f4a125;">
                            決定
                        </td>
                        @endif
                    @endif
                </tr>
                @endforeach
            </table>
            <div class="t_center" style="margin:20px 0;">
                <input type="hidden" name="order__id" value="{{$id}}">
                <input form="all_staff" type="submit" value="配送依頼をする" class="addition" onClick="return all_staff_form();">
            </div>
        </form>
        @foreach($staff_lists as $key => $staff)
        <form name="d_id_form" id="d_staff_id_form_{{$staff->id}}" action="/staff_list/{{$id}}" method="POST">
            @csrf
        </form>
        @endforeach
    </div>


<script>
    //モーダルの中のラジオボタンの処理(1つのみ選択)
    var radio_val;
    $('.d_staff_id').on('click',function(){
    if($(this).val() == radio_val) {
        $(this).prop('checked', false);
        radio_val = null;
    } else {
        radio_val = $(this).val();
    }
    });
    function all_staff_form(){//チェックボタンチェックフォーム
        if($('input[name="staff_id[]"]').is(':checked')){//チェックボックスが選択されていたら
        }else{//チェックボックスが選択されていなかったら
            alert("配達員リストが選択されていません。");
            return false;
        }
    }
    function check_form(){//ラジオボタンチェックフォーム
        if($('input[name="d_staff_id"]').is(':checked')){//チェックボックスが選択されていたら
        }else{//チェックボックスが選択されていなかったら
            alert("配達員が選択されていません。");
            return false;
        }
    }
     //「全て選択」のチェックボックス
    let checkAll = document.getElementById("checkAll");
    //「全て選択」以外のチェックボックス
    let el = document.getElementsByClassName("checks");

    //全てのチェックボックスをON/OFFする
    const funcCheckAll = (bool) => {
        for (let i = 0; i < el.length; i++) {
            el[i].checked = bool;
        }
    }

    //「checks」のclassを持つ要素のチェック状態で「全て選択」のチェック状態をON/OFFする
    const funcCheck = () => {
        let count = 0;
        for (let i = 0; i < el.length; i++) {
            if (el[i].checked) {
                count += 1;
            }
        }
        if (el.length === count) {
            checkAll.checked = true;
        } else {
            checkAll.checked = false;
        }
    };

    //「全て選択」のチェックボックスをクリックした時
    checkAll.addEventListener("click",() => {
        funcCheckAll(checkAll.checked);
    },false);

    //「全て選択」以外のチェックボックスをクリックした時
    for (let i = 0; i < el.length; i++) {
        el[i].addEventListener("click", funcCheck, false);
    }
</script>

</body>
</html>
