<?php
use Illuminate\Support\Facades\Route;
//Public
Route::match(['get'       ],'/','PublicController@Top');// トップページ
Route::match(['get','post'],'/search','PublicController@Search');// 検索　
Route::match(['get','post'],'/search/{c_id}','PublicController@Search');// 検索　
Route::match(['get'       ],'/shop/{s_id}','PublicController@Store');// 店舗(公開)
Route::match(['post'      ],'/shop','PublicController@Store');// 店舗詳細(公開)
Route::match(['get'      ],'/product_detail','PublicController@ProductDetail');// 商品詳細
Route::match(['get'       ],'/update_apptdate/{apptdate}','PublicController@UpdateApptdate');
Route::match(['get'       ],'/update_favorite/{s_id}','PublicController@UpdateFavorite');
Route::match(['get','post'],'/contact','PublicController@Contact');//お問合せ
//Shopping
Route::match(['get'       ],'/cart','ShoppingController@Cart');// お買い物カゴ
Route::match(['get','post'],'/pay','ShoppingController@Pay');// ご注文手続き
Route::match(['get','post'],'/one_pay','ShoppingController@OnePay');// ご注文手続き
Route::match(['get'       ],'/confirm','ShoppingController@Confirm');//　決済　確認
Route::match(['get'       ],'/ordercompletion','ShoppingController@Ordercompletion');//　注文確定
Route::match(['get'       ],'/orderlist','ShoppingController@Orderlist');//　受注一覧リスト
Route::match(['get'       ],'/orderdetails','ShoppingController@Orderdetails');//　注文詳細
Route::match(['get'       ],'/orderedit','ShoppingController@Orderedit');//　注文詳細編集
//Ajax
Route::match([      'post'],'/add_modal','AjaxController@AddModal');//商品追加
Route::match([      'post'],'/line_id','AjaxController@LineId');//商品削除
//Cart
Route::match(['get'       ],'iteminfo/{s_id}/{p_id}','CartController@ItemInfo');//商品個別ページ
Route::match(['get','post'],'/add_cart','CartController@AddCart');//商品追加
Route::match([      'post'],'/change_quantity','CartController@ChangeQuantity');//商品追加
Route::match([      'post'],'/delete_cart','CartController@DeleteCart');//商品削除
//Account
Route::match(['get','post'],'/login','AccountController@Login');
Route::match(['get'       ],'/logout','AccountController@Logout');
Route::match(['get'       ],'/initial_email/{kind}','AccountController@InitialEmail');
Route::match(['get','post'],'/initial_email','AccountController@InitialEmail');
Route::match(['get'       ],'/initial_registration/{hash}','AccountController@InitialRegistration');
Route::match(['get','post'],'/reset_password','AccountController@ResetPassword');
Route::match(['get'       ],'/new_password/{hash}','AccountController@NewPassword');
Route::match([      'post'],'/new_password','AccountController@NewPassword');
Route::match(['get'       ],'/edit_user/{u_id}','AccountController@EditUser');
Route::match([      'post'],'/edit_user','AccountController@EditUser');
Route::match(['get','post'],'/add_store_user','AccountController@AddStoreUser');
//Member
Route::match(['get'       ],'/mypage','MemberController@Mypage');// マイページ
Route::match(['get','post'],'/password','MemberController@Password');// パスワード変更
Route::match(['get','post'],'/name','MemberController@Name');// 名前変更
Route::match(['get'       ],'/user_coupon','MemberController@UserCoupon');// ユーザー　クーポン
Route::match(['get'       ],'/shop_coupon/{s_id}','MemberController@StoreCoupon');// 店舗(公開)
Route::match(['get'       ],'/register','MemberController@Register');// マイページ会員登録 
Route::match(['get'       ],'/registration','MemberController@Registration');// 登録情報の確認
Route::match(['get'       ],'/unregistered_pay','MemberController@Unregisteredpay');// 未登録ユーザー決済画面
Route::match(['get'       ],'/unregistered_confirm','MemberController@Unregisteredconfirm');// 未登録ユーザー確認画面
Route::match(['get'       ],'/myprofile_payment','MemberController@MyProfilePayment');// お支払い方法変更
Route::match(['get'       ],'/new_payment','MemberController@NewPayment');// 新規お支払い方法追加
Route::match(['get'       ],'/payments','MemberController@Payments');// お支払い方法変更　
Route::match(['get'       ],'/favorite','MemberController@Favorite');// お気に入り
//Route::match(['get'       ],'/usericon','MemberController@Usericon');// ユーザーアイコン変更
// Route::match(['get'       ],'/orderstatus','MemberController@Orderstatus');// 注文状況　
// Route::match(['get'       ],'/orderhistory','MemberController@Orderhistory');// 注文履歴
// Route::match(['get'       ],'/orderhistorydetails','MemberController@Orderhistorydetails');// 注文履歴
// Route::match(['get'       ],'/info','MemberController@Info');// お客様情報一覧

//Manage
Route::match(['get'       ],'/admin_manage','ManageController@AdminManage');// もしもし管理　
Route::match(['get','post'],'/store_manage','ManageController@StoreManage');// もしもし店舗管理　
Route::match(['get'       ],'/product_list','ManageController@ProductList');// 店舗情報
Route::match(['get'       ],'/product_list/{s_id}','ManageController@ProductList');// 店舗情報
Route::match(['get'       ],'/option_list','ManageController@OptionList');// オプション一覧　
Route::match(['get'       ],'/option_list/{s_id}','ManageController@OptionList');// オプション一覧　
Route::match(['get'       ],'/option_edit/{s_id}/{o_id}','ManageController@OptionEdit');// オプション編集　
Route::match([      'post'],'/option_edit','ManageController@OptionEdit');// オプション編集　
Route::match(['get'       ],'/option_delete/{o_id}','ManageController@OptionDelete');
Route::match(['get'       ],'/category_edit/{c_id}','ManageController@CategoryEdit');// admin　カテゴリー
Route::match([      'post'],'/category_edit','ManageController@CategoryEdit');
Route::match(['get'       ],'/admin_edit/{type}/{id}','ManageController@AdminEdit');//admin編集　
Route::match([      'post'],'/admin_edit','ManageController@AdminEdit');//admin編集　
Route::match(['get'       ],'/coupon_list','ManageController@CouponList');// クーポン一覧
Route::match(['get'       ],'/coupon_edit/{c_id}','ManageController@CouponEdit');// クーポン編集
Route::match([      'post'],'/coupon_edit','ManageController@CouponEdit');// クーポン編集
Route::match(['get'       ],'/store_information','ManageController@StoreInformation');
Route::match(['get'       ],'/store_information/{s_id}','ManageController@StoreInformation');
Route::match([      'post'],'/shop_update','ManageController@ShopUpdate');
Route::match([      'post'],'/store_update','ManageController@StoreUpdate');
Route::match(['get'       ],'/store_delete/{s_id}','ManageController@Storedelete');// 店舗削除
Route::match(['get'       ],'/product_edit/{p_id}','ManageController@ProductEdit');
Route::match([      'post'],'/product_edit','ManageController@ProductEdit');
Route::match(['get'       ],'/product_delete/{p_id}','ManageController@Productdelete');// 商品削除
Route::match(['get'       ],'/custom_delete/{c_id}','ManageController@CustomDelete');// カスタム　削除
Route::match(['get'       ],'/coupon_delete/{c_id}','ManageController@CouponDelete');// 　クーポン削除
Route::match(['get','post'],'/calendar/{s_id}','ManageController@Calendar');// カレンダー機能
Route::match(['get'       ],'/calendar_delete/{c_id}','ManageController@Calendardelete');
Route::match(['get','post'],'/product_category/{s_id}','ManageController@ProductCategory');
Route::match(['get'       ],'/product_category_delete/{c_id}','ManageController@ProductCategoryDelete');
Route::match(['get'       ],'/paid_inventory','ManageController@PaidInventory');//有料広告枠 基本情報
Route::match(['get'       ],'/paid_inventory_detail/{type}/{id}','ManageController@PaidInventoryDetail');//有料広告枠　詳細情報
Route::match([      'post'],'/paid_inventory_detail','ManageController@PaidInventoryDetail');//有料広告枠　詳細情報
//Route::match(['get'       ],'/shop_edit','SiteController@Shopedit');// 商品編集

//Order
Route::match(['get','post'],'/order_search','OrderController@OrderSearch');//もしもし管理画面
Route::match([      'post'],'/order_detail','OrderController@OrderDetail');
Route::match(['get','post'],'/staff_list/{id}','OrderController@StaffList');
Route::match(['get','post'],'/search_user','OrderController@SearchUser');
Route::match(['get','post'],'/add_coupon','OrderController@AddCoupon');
Route::match(['get'       ],'/add_coupon/{c_hash}','OrderController@AddCoupon');
Route::match(['get','post'],'/shop_order_search/{s_id}','OrderController@ShopOrderSearch');//店舗による注文確定画面
Route::match([      'post'],'/shop_order_detail','OrderController@ShopOrderDetail');//店舗による注文確定画面
Route::match(['get'       ],'/receive_shop/{s_hash}','OrderController@ReceiveShop');//店舗による注文確定画面
Route::match(['get'       ],'/manage_products/{hash}','OrderController@ManageProducts');//配達員の商品配達管理画面
Route::match([      'post'],'/manage_products','OrderController@ManageProducts');//配達員の商品配達管理画面
Route::match(['get'       ],'/myorder/{id}','OrderController@MyOrder');//ユーザーの注文管理
Route::match([      'post'],'/myorder_detail','OrderController@MyOrderDetail');//ユーザーの注文詳細

//Charge
Route::match([      'post'],'/charge','ChargeController@Charge');
Route::match([      'post'],'/ordercompletion','ChargeController@Onecharge');
//Payment
Route::match(['get'       ],'/cardform','PaymentController@CardForm');//カード登録
Route::match([      'post'],'store','PaymentController@Store');
Route::match(['get'       ],'payment','PaymentController@Payment');
Route::match([      'post'],'addnewcard','PaymentController@AddNewCard');//カード情報が全くない時の登録
Route::match([      'post'],'addcard','PaymentController@AddCard');//既カードが登録されていて、追加する場合
Route::match([      'post'],'switchcard','PaymentController@SwitchCard');//デフォルトカードの変更
Route::match([      'post'],'deletecard','PaymentController@DeleteCard');//カード削除
//LINE
Route::match([      'post'],'/lineapi','LineMessengerController@Line');//LINEapi
Route::match([      'post'],'/delivery_list','LineMessengerController@DeliveryList');// LINE メッセージ送信用
Route::match([      'post'],'/delivery_post','LineMessengerController@DeliveryPost');
Route::match([      'post'],'/line/webhook', 'LineMessengerController@webhook')->name('line.webhook');// LINE メッセージ受信
Route::match(['get'       ],'/line/message', 'LineMessengerController@message');// LINE メッセージ送信用

// Route::match(['get'       ],'/top2','HomeController@Top2');
// Route::match(['get','post'],'/cart2','HomeController@Cart2');
// Route::match(['get'       ],'/stripe','HomeController@Stripe');
// Route::match([      'post'],'/subscribe_process','HomeController@subscribe_process');
// Route::match(['get'       ],'/connect','HomeController@Connect');
// Route::match([      'post'],'/stripe/webhook','WebhookController@HandleWebhook');
