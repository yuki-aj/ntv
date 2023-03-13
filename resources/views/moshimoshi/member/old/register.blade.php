<!-- マイページ登録画面 -->
@include('public/header')

<h1 class="baskets">マイページ新規会員登録</h1>
<div class="desc_w60">  
    <table class="contact_table w_60">
        <tr>
            <th class="contact-item">名前</th>
            <td class="contact-bodys">
                <input type="text" placeholder="例）山田 太郎" name="名前" class="
                " required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">フリガナ</th>
            <td class="contact-bodys">
                <input type="text" placeholder="例）ヤマダ タロウ" name="フリガナ" class="
                " required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">電話番号</th>
            <td class="contact-bodys">
                <input type="tel" placeholder="例）090-〇〇〇〇-〇〇〇〇" name="電話" class="
                " required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">メールアドレス</th>
            <td class="contact-bodys">
                <input type="email" placeholder="例）moshimoshi@delivery.com" name="メール" class="
                " required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">郵便番号
            </th>
            <td class="contact-bodys">
                <input type="text" placeholder="例）2060033" name="郵便番号" class="
                " required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">市</th>
            <td class="contact-bodys">
                <select name="" class="form_select">
                    <option>多摩市</option>
                    <option>八王子別所</option>
                    <option>八王子松木</option>
                    <option>八王子松が谷</option>
                    <option>八王子鹿島</option>
                    <option>八王子堀之内</option>
                    <option>八王子越野</option>
                </select>
            </td>
        </tr>
        <tr>
            <th class="contact-item">番地以降</th>
            <td class="contact-bodys">
                <input type="text" placeholder="例) 落合２丁目３８番地" name="住所" class="form-text" required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">パスワード</th>
            <td class="contact-bodys">
                <input type="text" placeholder="例) moshideli" name="パスワード" class="form-text" required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">パスワード確認用</th>
            <td class="contact-bodys">
                <input type="text" placeholder="例）moshideli" name="パスワード確認" class="form-text" required />
            </td>
        </tr>
    </table>
    <div class="confirm">
        <a href="/mypage">
            <input class="contact_submit" type="submit" value="マイページを更新する" />
        </a>
    </div>
</div>

<div class="m_b">
</div>


@include('public/footer')