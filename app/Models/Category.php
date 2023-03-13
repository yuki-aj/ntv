<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Category extends Model{
  protected $table = 't_category';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
  /*init*/
  public function init(){
    // $this->id	      = 0;
    $this->name	    = '';
  }

  public static function getList($locale){
    $cates = Category::get();//t_category
		$categories = array();//配列
    $last_d_id  = 0;
		$locale = str_replace('-', '', $locale);//-を空にする
    $division    = 'division_'.$locale;//$localeの前にdivision_をつける
    $subdivision = 'subdivision_'.$locale;//$localeの前にsubdivision_をつける
    foreach($cates as $cate){//t_categoryを回す
      $d_id = floor($cate->id/100);//$cate->idを100で割った物を$d_idに入れる
      $s_id = $cate->id - $d_id*100;//上で作った$d_idに100をかけたものを、$cate->idから引く
      if($last_d_id != $division){
        if(isset($cate->$division)){
          $categories[$d_id]['name'] = $cate->$division;
        }
      }
      $last_d_id = $d_id;
      if(isset($cate->$subdivision)){
        $categories[$d_id]['subdivision'][$s_id] = $cate->$subdivision;
      }
		}
    return $categories;
  }
  public static function ReturnCategoryNames($id,$locale){
    $category = Category::find($id);
    $locale = str_replace('-', '', $locale);
    $division    = 'division_'.$locale;
    $subdivision = 'subdivision_'.$locale;
    // $no_choice   = "@lang('messages.error')";
    // $array['division']    = $category->$division??$no_choice;
    // $array['subdivision'] = $category->$subdivision??$no_choice;
    $array['division']    = $category->$division??"";
    $array['subdivision'] = $category->$subdivision??"";
    return $array;
  }
}
