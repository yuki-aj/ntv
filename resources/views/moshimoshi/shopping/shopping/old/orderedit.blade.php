@include('public/header')
<div class="w_90">
    <h1><i class="bi bi-cart3"></i>　注文詳細編集画面　<i class="bi bi-cart3"></i></h1>
   <h2 class="backpage"> <a href="/orderdetails">戻る</a></h2>

    <div class="orderflex w_90m">
        <div class="w_100 t_center">
            <table id="table" class="orderlist">
                <tbody>
                    <h2>注文情報</h2>
                    <tr>
                        <th>注文番号</th>
                        <td><input type="number"></td>
                    </tr>
                    <tr>
                        <th>注文日時</th>
                        <td><input type="datetime-local"></td>
                    </tr>
                    <tr>
                        <th>配送希望日時</th>
                        <td><input type="datetime-local"></td>
                    </tr>
                    <tr>
                        <th>決済方法</th>
                        <td>
                            <select name="">
                                <option value="1">
                                    クレジットカード
                                </option>
                                <option value="2">
                                    au pay
                                </option>
                            </select>
                        </td>
                             
                    </tr>
                    <tr>
                        <th>決済状態</th>
                        <td>
                            <select name="">
                                <option value="1">
                                    完了
                                </option>
                                <option value="2">
                                    処理中
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>配送状況</th>
                        <td>
                        <select name="">
                                <option value="1">
                                    準備中
                                </option>
                                <option value="2">
                                    配送中
                                </option>
                                <option value="2">
                                    配送完了
                                </option>
                            </select>
                        </td>
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
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>商品名</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>注文数</th>
                        <td>
                            <select name="">
                                <option value="1">
                                    1
                                </option>
                                <option value="2">
                                    2
                                </option>
                                <option value="3">
                                    3
                                </option>
                                <option value="4">
                                    4
                                </option>
                                <option value="5">
                                    5
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>オプション</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>商品基本価格</th>
                        <td><input type="number"></td>
                    </tr>
                    <tr>
                        <th>オプション価格</th>
                        <td><input type="number"></td>
                    </tr>
                    <tr>
                        <th>配送料</th>
                        <td><input type="number"></td>
                    </tr>
                    <tr>
                        <th>合計</th>
                        <td><input type="number"></td>
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
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>フリガナ</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><input type="tel"></td>
                    </tr>
                    <tr>
                        <th>メールアドレス</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>注文メモ</th>
                        <td><input type="text"></td>
                    </tr>
                    <tr>
                        <th>法人名</th>
                        <td><input type="text"></td>
                    </tr>
                        
                    
                </tbody>
            </table>
        </div>
    </div>

    <div class="cancel">
        <h2><a href="/orderdetails"><input class="p_10" type= "submit" value="更新する"></a></h2>
    </div>
</div>