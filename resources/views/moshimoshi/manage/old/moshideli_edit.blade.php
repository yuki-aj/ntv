@include('public/header')
<div class="management">
    <table class="contact_table">
        <h1>店舗追加</h1>
        <tr>
            <th class="contact-item">店舗画像</th>
            <td class="">
                <input type="file" placeholder="" name="" class="form-text" required />
            </td>
        </tr>
        <tr>
            <th class="contact-item">店舗名</th>
            <td class="contact-body">
                <input type="text" placeholder="" name="" class="form-text" required />
            </td>
        </tr>
    </table>
    <div class="update">
        <a href="/management">
            <p>更新する</p>
        </a>
    </div>
</div>
@include('public/footer')

