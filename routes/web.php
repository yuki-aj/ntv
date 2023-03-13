<?php
use Illuminate\Support\Facades\Route;
//Public
Route::match(['get','post'],'/','PublicController@Index');// トップページ
Route::match(['get','post'],'/login','PublicController@Login');// ログイン
Route::match(['get'       ],'/logout','PublicController@Logout');// ログアウト
Route::match(['get','post'],'/registration','PublicController@Registration');// 新規登録
Route::match(['get','post'],'/reset_password','PublicController@ResetPassword');// パスワードリセット
Route::match(['get'       ],'/new_password/{hash}','PublicController@NewPassword');
Route::match([      'post'],'/new_password','PublicController@NewPassword');

//Member
Route::match(['get'       ],'/dashboard','MemberController@Dashboard');// Dashboard
Route::match(['get'       ],'/invest','MemberController@Invest');// Invest
Route::match(['get'       ],'/withdraw','MemberController@Withdraw');// Withdraw
Route::match(['get'       ],'/invest_history','MemberController@InvestHistory');// InvestHistory
Route::match(['get'       ],'/earning_history','MemberController@EarningHistory');// EarningHistory
Route::match(['get'       ],'/reference_history','MemberController@ReferenceHistory');// ReferenceHistory
Route::match(['get'       ],'/withdraw_history','MemberController@WithdrawHistory');// WithdrawHistory

//Admin
Route::match(['get'       ],'/user_list','AdminController@UserList');// ユーザーリスト
Route::match(['get','post'],'/user_detail/{user_id}','AdminController@UserDetail');// ユーザー詳細
Route::match([      'post'],'/invest_history/{user_id}','AdminController@InvestHistory');// インベストヒストリー
// Route::match([      'post'],'/user_detail','AdminController@UserDetail');// ユーザー更新
Route::match(['get'       ],'/user_delete/{user_id}','AdminController@UserDelete');//ユーザー削除
Route::match(['get'       ],'/invest_delete/{user_id}/{invest_id}','AdminController@InvestDelete');//invest削除
Route::match(['get','post'],'/password','AdminController@Password');//パスワード変更



// HTTP ステータスコードを引数に、該当するエラーページを表示させる
// Route::get('error/{code}', function ($code) {
//     abort($code);
// });