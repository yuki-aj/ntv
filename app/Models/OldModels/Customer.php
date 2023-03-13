<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Customer extends Model{
  protected $table = 't_customer';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  //public $incrementing = false;// auto-incrementing@var bool
  //public $timestamps   = false;// time-stamp@var bool
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
    $this->id	       = '';//stripeの$customer->id
    $this->stripe_id = '';
  }
}