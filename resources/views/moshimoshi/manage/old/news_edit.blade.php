@include('public/header')
<div class="management">
    <table class="contact_table">
        <h1>お知らせ</h1>
        <tr>
            <th class="contact-item">日付</th>
            <td class="contact-body">
                <input type="date" placeholder="" name="date1" class="form-text" required />
                ～
                <input type="date" placeholder="" name="date2" class="form-text" required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">タイトル</th>
            <td class="contact-body">
                <input type="text" placeholder="" name="title" class="form-text" required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">リンク先</th>
            <td class="contact-body">
                <input type="url" placeholder="" name="url" class="form-text" required />
            </td>
        </tr>
        <!-- <tr>
            <th class="contact-item">フォント</th>
            <td class="contact-body">
                <input type="text" placeholder="" name="" class="form-text" required />
            </td>
        </tr> -->
    </table>
    <!-- <div class="update">
        <a href="/management">
            <p>更新する</p>
        </a>
    </div> -->
            <div class="t_center">
                <input class="addition" type= "submit" value="更新する">
            </div>
<div class="mb">
</div>
</div>
<!-- <div class="m_btm30">
</div> -->

@include('public/footer')

