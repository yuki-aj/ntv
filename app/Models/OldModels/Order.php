<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order extends Model{
  protected $table = 't_order';
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
    $prods = Product::where('u_id',$u_id)->get();
		$products = array();
		foreach($prods as $prod){
			$products[$prod->id] = $prod;
		}
    return $products;
  }
  /*init*/
  public function init(){
    // $this->id	      = 0;
    $this->o_id             = '';
    $this->c_flag           = '';
    $this->s_id             = '';
    $this->u_name           = '';
    $this->nominate_list    = '';
    $this->d_staff_id       = '';
    $this->s_hash           = '';
    $this->hash             = '';
    $this->u_id             = '';
    $this->order_flag       = '';
    $this->corporation_flag = '';
    $this->delivery_date    = '';
    $this->delivery_time    = '';
    $this->catch_time       = '';
    $this->d_postcode       = '';
    $this->d_address        = '';
    $this->d_tel            = '';
    $this->d_name           = '';
    $this->o_postcode       = '';
    $this->o_address        = '';
    $this->o_tel            = '';
    $this->note             = '';
    $this->status_time      = ',,';
    $this->updated_by     = 0;
  }
}