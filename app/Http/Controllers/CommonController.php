<?php
namespace App\Http\Controllers;
use Session,Log,App;//Facades
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
class CommonController extends Controller{
	/** AccessLog (static) **/
	public static function AccessLog(){
		$atcion = Route::currentRouteAction();
		if (!$u_id = Session::get('u_id')) {
			$u_id = 'none';
		}
		Log::info("{$atcion}:u_id={$u_id}");
	}
	/** Bicrypt (static) **/
	public static function Bicrypt($project_name,$before, $direction=TRUE){
		$key = $project_name.' Project';
		if ($direction) {
			$after = '$'.openssl_encrypt($before, 'AES-128-ECB', $key);	//暗号化
		} else {
			switch( substr($before, 0, 1) ) {//beforeの0番目の1文字目を取得してswitch
			case '$':		//暗号データ、$だったら
				$after = openssl_decrypt(substr($before,1), 'AES-128-ECB', $key);	//復号化
				break;
			default:
				$after = $before;
				break;
			}
		}
		return $after;
	}
	/** Language (Get) **/
	public function Language(Request $request){
		switch ($request->locale) {
			case 'ja':
			case 'en':
			case 'zh-CN':
			case 'zh-TW':
			case 'ko':
			case 'fr':
			$locale = $request->locale;
			break;
			default:
				$locale = 'ja';
			break;
		}
		Session::put('locale', $locale);
		return back();
	}
}/* EOF */