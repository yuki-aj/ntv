@include('manage/header')

    <h1 class="baskets">もしもしデリバリー管理画面</h1>
    
    <div class="admin_flex">
        <a class="update" href="/store_manage" rel="noopener noreferrer">店舗管理</a>
        <a class="update" href="/order_search" rel="noopener noreferrer">注文管理</a>
        <a class="update" href="/search_user" rel="noopener noreferrer">ユーザー管理</a>
        <a class="update" href="/coupon_list" rel="noopener noreferrer">クーポン管理</a>
        <a class="update" href="/paid_inventory" rel="noopener noreferrer">広告枠</a>
        <a class="update" href="/logout">ログアウト</a>
    </div>

    <!-- カテゴリー -->
    <div class="admin_page">
        <h2 class="bold">カテゴリー</h2>
        <table class="contact_table position">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex admin_gap">
                        <p>アイコン</p>
                        <p>カテゴリー名</p>
                    </div>
                </td>
                <th>
                    <a href="/admin_edit/0/0/0">
                        <div class="orange_btn">
                            <p>追加</p>
                        </div>
                    </a>
                </th>
            </tr>
            @foreach($customs as $key => $custom)
            @if($custom->type == 0)
                <tr>
                    <td class="contact-body">
                    <p>{{$custom->title}}</p>
                        <div class="admin_flex" style="flex-wrap: nowrap;">
                            <p><img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}"></p>
                            <p><img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}-2.{{isset($custom->extension)? $custom->extension : '0'}}"></p>
                        </div>
                    </td>
                    <th>
                        <div class="flex admin_gap">
                            <a href="/admin_edit/{{$custom->type}}/0/{{$custom->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                            <a href="/custom_delete/{{$custom->id}}" onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                        </div>
                    </th>
                </tr>
            @endif
            @endforeach
        </table>
    </div>

    <!-- お知らせ-->
    <div class="admin_page">
        <h2 class="bold">お知らせ</h2>
        <table class="contact_table position" style="table-layout: fixed;">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex admin_gap">
                        <p>タイトル</p>
                        <p>リンク先</p>
                    </div>
                </td>
                <th>
                    <a href="/admin_edit/1/0/0">
                        <div class="orange_btn">
                            <p>追加</p>
                        </div>
                    </a>
                </th>
            </tr>
            @foreach($customs as $key => $custom)
            @if($custom->type == 1 && $custom->s_id == 0 )
                <tr>
                    <td class="contact-body admin_box">
                        <div class="admin_gap">
                            <p class="over">{{$custom->title}}</p>
                            <p class="over">{{$custom->url}}</p>
                        </div>
                    </td>
                    <th>
                        <div class="flex admin_gap">
                            <a href="/admin_edit/{{$custom->type}}/0/{{$custom->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                            <a href="/custom_delete/{{$custom->id}}" onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                        </div>
                    </th>
                </tr>
            @endif
            @endforeach
        </table>
    </div>

    <!-- スライダー-->
    <div class="admin_page">
        <h2 class="bold">スライダー</h2>
        <table class="contact_table position" style="table-layout: fixed;">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex admin_gap">
                        <p>画像</p>
                        <p>リンク先</p>
                    </div>
                </td>
                <th>
                    <a href="/admin_edit/2/0/0">
                        <div class="orange_btn">
                            <p>追加</p>
                        </div>
                    </a>
                </th>
            </tr>
            @foreach($customs as $key => $custom)
            @if($custom->type == 2)
                <tr>
                    <td class="contact-body admin_box">
                        <div class="admin_flex admin_gap over">
                            <img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}">
                            <p class="over">{{$custom->url}}</p>
                        </div>
                    </td>
                    <th>
                        <div class="flex admin_gap">
                            <a href="/admin_edit/{{$custom->type}}/0/{{$custom->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                            <a href="/custom_delete/{{$custom->id}}" onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                        </div>
                    </th>
                </tr>
            @endif
            @endforeach
        </table>
    </div>

    <!-- プロモーション-->
    <div class="admin_page">
        <h2 class="bold">プロモーション</h2>
        <table class="contact_table position" style="table-layout: fixed;">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex" style="gap:5px">
                        <p>画像</p>
                        <p>広告名</p>
                        <p>掲載期間</p>
                        <p>リンク先</p>
                    </div>
                </td>
                <th>
                    <a href="/admin_edit/3/0/0">
                        <div class="orange_btn">
                            <p>追加</p>
                        </div>
                    </a>
                </th>
            </tr>
            @foreach($customs as $key => $custom)
            @if($custom->type == 3)
                <tr>
                    <td class="contact-body admin_box">
                        <div class="admin_flex admin_gap">
                        <img src="/storage/admin_img/{{isset($custom->type)? $custom->type : '0'}}-{{isset($custom->no)? $custom->no : '0'}}.{{isset($custom->extension)? $custom->extension : '0'}}">
                            <p>{{$custom->title}}</p>
                            <p>{{$custom->from_date}}～{{$custom->to_date}}</p>
                            <p class="over">{{$custom->url}}</p>
                        </div>
                    </td>
                    <th>
                        <div class="flex admin_gap">
                            <a href="/admin_edit/{{$custom->type}}/0/{{$custom->id}}" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                            <a href="/custom_delete/{{$custom->id}}" onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                        </div>
                    </th>
                </tr>
            @endif
            @endforeach
        </table>
    </div>

    <!-- PICKUP-->
    <div class="admin_page">
        <h2 class="bold">PICK UP</h2>
        <table class="contact_table position">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex">
                        <p>店名</p>
                    </div>
                </td>
                <th>
                    <a href="/admin_edit/4/0/0">
                        <div class="orange_btn">
                            <p>追加</p>
                        </div>
                    </a>
                </th>
            </tr>
            @foreach($pick_up as $key => $pick)
                <tr>
                    <td class="contact-body admin_box">
                        <div class="admin_flex">
                            <p>{{$pick->s_name}}</p>
                        </div>
                    </td>
                    <th>
                        <div class="flex admin_gap">
                            <a href="/custom_delete/{{$custom->id}}"  onclick="return really_delete();" class="btn btn--black btn--cubic btn--shadow">削除</a>
                        </div>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- SEO-->
    <div class="admin_page">
        <h2 class="bold">SEO</h2>
        <table class="contact_table position">
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex">
                        <p>共通タイトル:
                        @foreach($customs as $key => $custom)
                            @if($custom->type == 5 && $custom->no == 1)
                            {{$custom->title}}
                            @endif
                        @endforeach
                        </p>
                    </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/admin_edit/5/0/1" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                    </div>
                </th>
            </tr>
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex">
                        <p>ページ概要:
                        @foreach($customs as $key => $custom)
                            @if($custom->type == 5 && $custom->no == 2)
                            {{$custom->title}}
                            @endif
                        @endforeach
                        </p>
                    </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/admin_edit/5/0/2" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                    </div>
                </th>
            </tr>
            <tr>
                <td class="contact-body admin_box">
                    <div class="admin_flex">
                        <p>キーワード:
                        @foreach($customs as $key => $custom)
                            @if($custom->type == 5 && $custom->no == 3)
                            {{$custom->title}}
                            @endif
                        @endforeach
                        </p>
                    </div>
                </td>
                <th>
                    <div class="flex admin_gap">
                        <a href="/admin_edit/5/0/3" class="btn btn--orange btn--cubic btn--shadow">編集</a>
                    </div>
                </th>
            </tr>
        </table>
    </div>

    <script>
        function really_delete(){
            var result = confirm('本当に削除しますか？');
            if(result) {
                return true;
            } else {
                return false;
            }
        }
    </script>
</body>
</html>