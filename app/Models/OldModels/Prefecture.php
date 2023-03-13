<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Prefecture extends Model{
  protected $table = 't_prefecture';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
  /*init*/
  public static function getList($locale){
    $prefs = Prefecture::get();
		$prefectures = array();
    if($locale != 'ja'){
      $locale = 'en';
    }
    $name = 'name_'.$locale;
    foreach($prefs as $pref){
			$prefectures[$pref->id] = $pref->$name;
		}
    return $prefectures;
  }
}