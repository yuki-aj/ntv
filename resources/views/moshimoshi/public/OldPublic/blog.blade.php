@include('public/header')
<div class="w_90">
    <h1>更新ページ</h1>
    <table class="contact_table w_100">
            <tr>
                <th class="contact-item">image</th>
                <td class="">
                <input type="file" placeholder="" name="" class="form-text" required />
                </td>
            </tr>
            <tr>
                <th class="contact-item">category</th>
                <td class="contact-body">
                <input type="text" placeholder="" name="" class="form-text" required />
                </td>
            </tr>
            <tr>
                <th class="contact-item">name</th>
                <td class="contact-body">
                    <input type="text" placeholder="" name="" class="form-text" required />
                </td>
            </tr>
            <tr>
                <th class="contact-item">title</th>
                <td class="contact-body">
                    <input type="text" placeholder="" name="" class="form-text" required />
                </td>
            </tr>
            <tr>
                <th class="contact-item">lead</th>
                <td class="contact-body">
                    <textarea name="" placeholder="" class="form-textarea"></textarea>
                </td>
            </tr>
            <tr>
                <th class="contact-item">HTML</th>
                <td class="contact-body">
                <textarea name="" placeholder="" class="form-textarea h_long"></textarea>
                </td>
            </tr>
    </table>
    <div class="update">
        <a href="/blog">
            <p>登録</p>
        </a>
        </div>
</div>
@include('public/footer')