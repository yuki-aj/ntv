<?php
namespace App\Http\Requests;
use Session;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
class FilesRequest extends FormRequest{
  public function authorize(){
    return true;
  }
  public function rules(){
		$route = Route::getFacadeRoot()->current()->uri();
    $rules = array();
    switch ($route) {
      case 'edit_user':
      $rules['img_url'] = 'file|image|mimes:jpeg,jpg,png,gif|max:2048';// 2MB(2048)
      break;
      // case 'edit_textbook':
      // $rules['page_sound'] = 'file|mimes:mp3,wav|max:500000';
      // break;
    }
    return $rules;
  }
  public function messages(){
    // var_dump('hello');
    $locale = Session::get('locale');
    switch ($locale) {
			case 'en':
          $uploaded_error = 'The image size is too large. Please change the image and re-register.';
				break;
			case 'zh-CN':
          $uploaded_error = '图像尺寸太大。请更改图像并重新注册。';
				break;
			case 'zh-TW':
          $uploaded_error = '圖像尺寸太大。請更改圖像並重新註冊。';
				break;
			case 'ko':
          $uploaded_error = '이미지 크기가 너무 큽니다. 이미지를 변경하고 다시 등록하십시오.';
				break;
			case 'fr':
          $uploaded_error = "La taille de l'image est trop grande. Veuillez changer l'image et vous réinscrire.";
				break;
        default:
          return [
            $uploaded_error = '画像サイズが大きすぎます。画像を変更して再登録してください。',
          ];
        break;
    }
    return [
      'img_url.uploaded'    => $uploaded_error,
      'wechat_url.uploaded' => $uploaded_error,
      'line_url.uploaded'   => $uploaded_error,
    ];
  }
}