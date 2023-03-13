@include('public/header')
<div class="w_90">
    <h1><i class="bi bi-cart3"></i>　注文詳細画面　<i class="bi bi-cart3"></i></h1>
   <h2 class="backpage"> <a href="/orderlist">戻る</a></h2>

    <div class="orderflex w_90m">
        <div class="w_100 t_center">
            <table id="table" class="orderlist">
                <tbody>
                    <h2>注文情報</h2>
                    <tr>
                        <th>注文番号</th>
                        <td>492</td>
                    </tr>
                    <tr>
                        <th>注文日時</th>
                        <td>2022/06/15（水）16：15</td>
                    </tr>
                    <tr>
                        <th>配送希望日時</th>
                        <td>2022/06/16（木）18：00</td>
                    </tr>
                    <tr>
                        <th>決済方法</th>
                        <td>クレジットカード</td>
                    </tr>
                    <tr>
                        <th>決済状態</th>
                        <td>完了</td>
                    </tr>
                    <tr>
                        <th>配送状況</th>
                        <td>準備中</td>
                    </tr>
                        
                    
                </tbody>
            </table>
        </div>

        <div class="w_100 t_center">
            <table id="table" class="orderlist">
                <tbody>
                    <h2>商品情報</h2>
                    <tr>
                        <th>店名</th>
                        <td>ナマステキッチン</td>
                    </tr>
                    <tr>
                        <th>商品名</th>
                        <td>ガパオライス</td>
                    </tr>
                    <tr>
                        <th>注文数</th>
                        <td>1</td>
                    </tr>
                    <tr>
                        <th>オプション</th>
                        <td>ナンロール</td>
                    </tr>
                    <tr>
                        <th>商品基本価格</th>
                        <td>1050円</td>
                    </tr>
                    <tr>
                        <th>オプション価格</th>
                        <td>550円</td>
                    </tr>
                    <tr>
                        <th>配送料</th>
                        <td>420円</td>
                    </tr>
                    <tr>
                        <th>合計</th>
                        <td>2020円</td>
                    </tr>
                        
                    
                </tbody>
            </table>
        </div>
        <div class="w_100 t_center">
            <table id="table" class="orderlist">
                <tbody>
                    <h2>配送先情報</h2>
                    <tr>
                        <th>氏名</th>
                        <td>もしもし　花子</td>
                    </tr>
                    <tr>
                        <th>フリガナ</th>
                        <td>モシモシ　ハナコ</td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td>多摩市落合2丁目38番地103号</td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>03-1234-5678</td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td>moshimoshi@delivery@com</td>
                    </tr>
                    <tr>
                        <th>注文メモ</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>法人名</th>
                        <td></td>
                    </tr>
                        
                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="cancel">
        <h2><a href="/orderedit"><input class="p_10" type= "submit" value="変更する"></a></h2>
        <h2><input class="p_10" type= "submit" value="注文をキャンセルする"></h2>
    </div>
</div>

<div class="m_b">
</div>