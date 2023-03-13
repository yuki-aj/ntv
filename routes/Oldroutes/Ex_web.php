<?php
use Illuminate\Support\Facades\Route;
//Site
Route::match(['get'       ],'/','SiteController@Top');// トップページ
Route::match(['get','post'],'/search','SiteController@Search');// 入力検索　
Route::match(['get'       ],'/shop/{s_id}','SiteController@Shop');// 店舗詳細
Route::match(['get'       ],'/cart','SiteController@Cart');// お買い物カゴ
Route::match(['get'       ],'/pay','SiteController@Pay');// ご注文手続き
Route::match(['get'       ],'/register','SiteController@Register');// マイページ会員登録 
Route::match(['get'       ],'/favorite','SiteController@Favorite');// お気に入り

Route::match(['get'       ],'/mypage','SiteController@Mypage');// マイページ
Route::match(['get'       ],'/registration','SiteController@Registration');// 登録情報の確認
Route::match(['get'       ],'/unregistered_pay','SiteController@Unregisteredpay');// 未登録ユーザー決済画面
Route::match(['get'       ],'/unregistered_confirm','SiteController@Unregisteredconfirm');// 未登録ユーザー確認画面
Route::match(['get'       ],'/mail','SiteController@Mail');// メールアドレス変更
Route::match(['get'       ],'/address','SiteController@Address');// 住所変更
Route::match(['get'       ],'/tel','SiteController@Tel');// 電話番号変更
Route::match(['get'       ],'/payments','SiteController@Payments');// お支払い方法変更　
Route::match(['get'       ],'/password','SiteController@Password');// パスワード変更
Route::match(['get'       ],'/usericon','SiteController@Usericon');// ユーザーアイコン変更
Route::match(['get'       ],'/name','SiteController@Name');// 名前変更
Route::match(['get'       ],'/subname','SiteController@Subname');// フリガナ変更　
Route::match(['get'       ],'/orderstatus','SiteController@Orderstatus');// 注文状況　
Route::match(['get'       ],'/orderhistory','SiteController@Orderhistory');// 注文履歴
Route::match(['get'       ],'/orderhistorydetails','SiteController@Orderhistorydetails');// 注文履歴
Route::match(['get'       ],'/info','SiteController@Info');// お客様情報一覧

Route::match(['get'       ],'/management','SiteController@management');// もしもし管理　
Route::match(['get'       ],'/moshidelimanagement','SiteController@Moshidelimanagement');// もしもし店舗管理　
Route::match(['get'       ],'/store_management','SiteController@Shopmanagement');// 店舗情報
Route::match(['get'       ],'/shop_edit','SiteController@Shopedit');// 商品編集　
Route::match(['get'       ],'/shop_edit1','SiteController@Shopedit1');// オプション編集　
Route::match(['get'       ],'/shop_edit2','SiteController@Shopedit2');// 店舗スライダー写真　編集　
Route::match(['get'       ],'/shop_edit3','SiteController@Shopedit3');// スタッフからのメッセージ　編集　
Route::match(['get'       ],'/shop_edit4','SiteController@Shopedit4');// 店舗クーポン　編集　
Route::match(['get'       ],'/edit0','SiteController@Edit0');// ヘッダー　編集　
Route::match(['get'       ],'/edit','SiteController@Edit');// カテゴリー　編集　
Route::match(['get'       ],'/edit1','SiteController@Edit1');// お知らせ　編集　
Route::match(['get'       ],'/edit2','SiteController@Edit2');// スライダー ON/OFF　編集　
Route::match(['get'       ],'/edit2_1','SiteController@Edit2_1');// スライダー 画像　編集　
Route::match(['get'       ],'/edit3','SiteController@Edit3');// フリースペース　編集　
Route::match(['get'       ],'/edit4','SiteController@Edit4');// 広告　編集　
Route::match(['get'       ],'/edit5','SiteController@Edit5');// もしデリ推し店　編集　
Route::match(['get'       ],'/edit6','SiteController@Edit6');// 有料広告枠　編集　
Route::match(['get'       ],'/edit7','SiteController@Edit7');// 新メニュー　編集　
Route::match(['get'       ],'/edit8','SiteController@Edit8');// もしデリクーポン　編集　
Route::match(['get'       ],'/edit9','SiteController@Edit9');// どんなものが食べたい気分？　編集
Route::match(['get'       ],'/edit10','SiteController@Edit10');//　タイトル　編集
Route::match(['get'       ],'/moshideli_edit','SiteController@Moshideliedit');//　店舗追加　　編集
Route::match(['get'       ],'/product_edit','SiteController@Productedit');//　もしもし商品情報　編集
Route::match(['get'       ],'/blog','SiteController@Blog');//　ブログ
Route::match(['get'       ],'/blog_list','SiteController@Bloglist');//　ブログリスト
Route::match(['get'       ],'/freespace','SiteController@Freespace');//　フリースペース
Route::match(['get'       ],'/freespace_list','SiteController@Freespacelist');//　フリースペースリスト
Route::match(['get'       ],'/confirm','SiteController@Confirm');//　決済　確認
Route::match(['get'       ],'/ordercompletion','SiteController@Ordercompletion');//　注文確定
Route::match(['get'       ],'/orderlist','SiteController@Orderlist');//　受注一覧リスト
Route::match(['get'       ],'/orderdetails','SiteController@Orderdetails');//　注文詳細
Route::match(['get'       ],'/orderedit','SiteController@Orderedit');//　注文詳細編集


//  WYSIWYG エディタ
Route::match(['get'       ],'/wysiwyg', 'FormController@Wysiwyg');
Route::match([      'post'],'/wysiwyg','FormController@Upload');
// 投稿一覧
Route::match(['get'       ],'/postlist','FormController@Postlist');
// 投稿詳細
Route::match(['get'       ],'/show/{id}','FormController@Show');
// Route::match([      'post'],'/show/{id}','FormController@Shows');

// 商品追加
Route::match([      'post'],'/store_management', 'EditController@EditProduct');
// オプション登録
Route::match([      'post'],'/product_edit', 'EditController@EditOption');
// 店舗登録
Route::match([      'post'],'/moshidelimanagement', 'EditController@EditStore');

// お店削除
// Route::post('/destroy{id}', [EditController::class, 'destroy'])->name('moshidelimanagement.destroy');

// いいねボタン
// Route::match([      'post'],'/shop/{s_id}','SiteController@Shop');// 店舗詳細
Route::get('/reply/nice/{post}', 'NiceController@nice')->name('nice');
Route::get('/reply/unnice/{post}', 'NiceController@unnice')->name('unnice');


//stripe
Route::match(['get'       ],'/top2','HomeController@Top2');
Route::match(['get','post'],'/cart2','HomeController@Cart2');
// Route::match([      'post'],'/onecharge','ChargeController@Onecharge');
Route::match([      'post'],'/complete','ChargeController@Onecharge');
Route::match(['get'       ],'/stripe','HomeController@Stripe');
Route::match([      'post'],'/charge','ChargeController@Charge');
Route::match([      'post'],'/subscribe_process','HomeController@subscribe_process');
Route::match(['get'       ],'/connect','HomeController@Connect');
Route::match([      'post'],'/stripe/webhook','WebhookController@HandleWebhook');

//カード登録
Route::match(['get'       ],'/cardform','PaymentController@CardForm');
Route::match([      'post'],'store','PaymentController@Store');
Route::match(['get'       ],'payment','PaymentController@Payment');
Route::match([      'post'],'addnewcard','PaymentController@AddNewCard');//カード情報が全くない時の登録
Route::match([      'post'],'addcard','PaymentController@AddCard');//既カードが登録されていて、追加する場合
Route::match([      'post'],'switchcard','PaymentController@SwitchCard');//デフォルトカードの変更
Route::match([      'post'],'deletecard','PaymentController@DeleteCard');//カード削除

//Ajax
Route::match([      'post'],'/add_modal','AjaxController@AddModal');//商品追加
// Route::match([      'post'],'/mdlcart','AjaxController@MdlCart');//商品追加
Route::match([      'post'],'/deletecart','AjaxController@DeleteCart');//商品削除

//cart
Route::match(['get'       ],'iteminfo/{s_id}/{p_id}','CartController@ItemInfo');//商品個別ページ
Route::match([      'post'],'/add_cart','CartController@AddCart');//商品追加
// Route::match([      'post'],'deletecart','CartController@DeleteCart');//商品削除

//もしもし注文管理画面
Route::match(['get','post'],'/ordersearch','ManageOrderController@OrderSearch');
Route::match([      'post'],'/orderdetail','ManageOrderController@OrderDetail');
Route::match(['get','post'],'/staff_list/{id}','ManageOrderController@StaffList');
//店舗注文管理画面
Route::match(['get','post'],'/shopordersearch/{s_id}','ManageOrderController@ShopOrderSearch');//店舗による注文確定画面
Route::match(['get'       ],'/receive_shop/{s_hash}','ManageOrderController@ReceiveShop');//店舗による注文確定画面
//配達員管理画面
Route::match(['get'       ],'/manage_products/{hash}','ManageOrderController@ManageProducts');//配達員の商品配達管理画面
Route::match([      'post'],'/manage_products','ManageOrderController@ManageProducts');//配達員の商品配達管理画面
// Route::match(['get'       ],'/receive_products/{hash}','ManageOrderController@ReceiveProducts');//受取完了画面
// Route::match(['get'       ],'/post_products/{hash}','ManageOrderController@PostProducts');//配達完了画面

//LINEapi
Route::match([      'post'],'/lineapi','LineMessengerController@Line');
// LINE メッセージ送信用
Route::match([      'post'],'/derivery_list','LineMessengerController@DeriveryList');
Route::match([      'post'],'/derivery_post','LineMessengerController@DeriveryPost');
// LINE メッセージ受信
Route::match([      'post'],'/line/webhook', 'LineMessengerController@webhook')->name('line.webhook');
// LINE メッセージ送信用
Route::match(['get'       ],'/line/message', 'LineMessengerController@message');

//Account
Route::match(['get','post'],'/login','AccountController@Login');
Route::match(['get'       ],'/logout','AccountController@Logout');

// //Public
// Route::match(['get'       ],'/','PublicController@Top');
// Route::match(['get'       ],'/search_supplier/{division}','PublicController@SearchSupplier');
// Route::match(['get','post'],'/search_supplier',           'PublicController@SearchSupplier');
// Route::match(['get','post'],'/search_buyer','PublicController@SearchBuyer');
// Route::match(['get'       ],'/user_individual/{u_id}/{p_id}','PublicController@UserIndividual');
// Route::match(['get'       ],'/about_us','PublicController@AboutUs');
// Route::match(['get'       ],'/privacy_policy','PublicController@PrivacyPolicy');
// Route::match(['get','post'],'/contact_us','PublicController@ContactUs');
// //Account
// Route::match(['get','post'],'/login','AccountController@Login');
// Route::match(['get'       ],'/logout','AccountController@Logout');
// Route::match(['get'       ],'/initial_email/{kind}','AccountController@InitialEmail');
// Route::match(['get','post'],'/initial_email','AccountController@InitialEmail');
// Route::match(['get'       ],'/initial_registration/{hash}','AccountController@InitialRegistration');
// Route::match(['get','post'],'/reset_password','AccountController@ResetPassword');
// Route::match(['get'       ],'/new_password/{hash}','AccountController@NewPassword');
// Route::match([      'post'],'/new_password',       'AccountController@NewPassword');
// //Edit
// Route::match(['get'       ],'/edit_user/{u_id}','EditController@EditUser');
// Route::match([      'post'],'/edit_user',       'EditController@EditUser');
// Route::match(['get'       ],'/edit_product/{u_id}/{p_id}','EditController@EditProduct');
// Route::match([      'post'],'/edit_product',              'EditController@EditProduct');
// Route::match(['get'       ],'/delete_product/{p_id}','EditController@DeleteProduct');
// //Common
// Route::match(['get'       ],'/language/{locale}','CommonController@Language');
// //admin
// Route::match(['get','post'],'/search_user','AdminController@SearchUser');
// Route::match(['get'       ],'/switch_status/{u_id}','AdminController@SwitchStatus');
// //Api
// Route::match([      'post'],'/contact','ApiController@Contact');
// Route::match([      'post'],'/translate','ApiController@Translate');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');