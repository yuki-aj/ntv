<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Custom extends Model{
  protected $table = 't_custom';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  // public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
	protected $guarded = [];//更新ブラックリスト
  //protected $fillable = ['id','u_id','name','category','spec1','spec2','spec3','origin','memo'];
  // public static function getListByUid($u_id){
  //   $prods = Product::where('u_id',$u_id)->get();
	// 	$products = array();
	// 	foreach($prods as $prod){
	// 		$products[$prod->id] = $prod;
	// 	}
  //   return $products;
  // }
  /*init*/
  public function init(){
    $this->id	       = 0;
    $this->type	     = 0;
    $this->no        = 0;
    $this->s_id      = 0;
    $this->title     = '';
    $this->url	     = '';
    $this->extension = '';
    $this->from_date = '2000-01-01';
    $this->to_date   = '2000-01-01';
    $this->updated_at   = '';
    $this->created_at   = '';
  }
}