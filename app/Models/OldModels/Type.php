<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Type extends Model{
  protected $table = 't_type';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
  /*init*/
  public static function getList($locale){
    $w_types = Type::get();//DBのt_typeをgetする
		$types = array();//$typesは配列
		$locale = str_replace('-', '', $locale);//$localeの中身の'-'を''(空)に置き変える
    $name = 'name_'.$locale;//$nameは、'name_' + $locale
		foreach($w_types as $w_type){//$w_typesを回す
      if(isset($w_type->$name)){//$name(name_jaなど)カラムがあったら
			$types[$w_type->id] = $w_type->$name;//$typesのidに$w_type->$nameカラムの項目（言葉）を入れる
      }
		}
    return $types;
  }
}