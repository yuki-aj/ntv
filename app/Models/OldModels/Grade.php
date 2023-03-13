<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Grade extends Model{
  protected $table = 't_grade';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
  /*init*/
  public static function getList($locale){
    $w_grades = Grade::get();
		$grades = array();
		$locale = str_replace('-', '', $locale);
    $name = 'name_'.$locale;
		foreach($w_grades as $w_grade){
      if(isset($w_grade->$name)){
			$grades[$w_grade->id]['name']    = $w_grade->$name;
			$grades[$w_grade->id]['checked'] = '';
      }
		}
    return $grades;
  }
}