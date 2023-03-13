<form action="/confirm" method="GET">
    <div>
        <label>お名前：</label>
        <input name="name" type="text" value="{{ old('name') }}">
    </div>
    <div>
        <label>電話番号：</label>
        <input name="tel" type="text"value="{{ old('tel') }}">
    </div>
    <button type="submit">送信</button>
</form>