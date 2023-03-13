@include('public/header')
<div class="w_90">
    <h1><i class="bi bi-cart3"></i>　受注一覧　<i class="bi bi-cart3"></i></h1>
    <table class="orderlist w_90">
        <tbody>
            <tr>
                <th>総累計</th>
                <th>月累計</th>
                <th>注文番号</th>
                <th>注文先店舗</th>
                <th>注文者名</th>
                <th>注文日時</th>
                <th>配送希望日時</th>
                <th>決済状態</th>
                <th>配送状況</th>
                <th></th>
            </tr>
            <tr>
                <td>100</td>
                <td>1</td>
                <td>492</td>
                <td>ナマステキッチン</td>
                <td>もしもし　花子</td>
                <td>2022/06/15（水）16：15</td>
                <td>2022/06/16（木）18：00</td>
                <td>完了</td>
                <td>準備中</td>
                <td><a href="/orderdetails"><input class="detail" type="button" value="詳細"></a></td>
            </tr>
            <tr>
                <td>101</td>
                <td>2</td>
                <td>554</td>
                <td>おむすびカフェ　くさびや</td>
                <td>花子 もしもし</td>
                <td>2022/06/16（木）17：20</td>
                <td>2022/06/17（木）19：30</td>
                <td>完了</td>
                <td>準備中</td>
                <td><a href="/orderdetails"><input type="button" class="detail" value="詳細"></a></td>
            </tr>
            <tr>
                <td>102</td>
                <td>3</td>
                <td>125</td>
                <td>ナマステキッチン</td>
                <td>もしもし　太郎</td>
                <td>2022/06/16（水）14：34</td>
                <td>2022/06/18（木）20：00</td>
                <td>完了</td>
                <td>準備中</td>
                <td><a href="/orderdetails"><input class="detail" type="button" value="詳細"></a></td>
            </tr>
            <tr>
                <td>103</td>
                <td>4</td>
                <td>755</td>
                <td>おむすびカフェ　くさびや</td>
                <td>太郎　もしもし</td>
                <td>2022/06/17（水）16：04</td>
                <td>2022/06/19（木）19：00</td>
                <td>完了</td>
                <td>準備中</td>
                <td><a href="/orderdetails"><input class="detail" type="button" value="詳細"></a></td>
            </tr>
        </tbody>
    </table>
</div>