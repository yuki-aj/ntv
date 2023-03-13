<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Unit extends Model{
  protected $table = 't_unit';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
  /*init*/
  public static function getList($locale){
    $w_units = Unit::get();
		$units = array();
    // if($locale != 'ja'){
    //   $locale = 'en';
    // }
    $locale = str_replace('-','',$locale);
    $name = 'name_'.$locale;
		foreach($w_units as $w_unit){
      if(isset($w_unit->$name)){
			$units[$w_unit->id] = $w_unit->$name;
      }
		}
    return $units;
  }
}