<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Option extends Model{
  protected $table = 't_option';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  //public $incrementing = false;// auto-incrementing@var bool
  //public $timestamps   = false;// time-stamp@var bool
	protected $guarded = [];//更新ブラックリスト
  //protected $fillable = ['id','u_id','name','category','spec1','spec2','spec3','origin','memo'];
  public static function getListByUid($u_id){
    // $prods = Product::where('u_id',$u_id)->get();
    $prods = Option::where('u_id',$u_id)->get();
		$products = array();
		foreach($prods as $prod){
			$products[$prod->id] = $prod;
		}
    return $products;
  }
  /*init*/
  public function init(){
    $this->s_id	      = '0';//store_id
    $this->o_id       = '0';//option_id
    $this->o_name     = '';
    $this->name       = '';
    $this->price      = '0';
    $this->updated_by = 0;
  }
}