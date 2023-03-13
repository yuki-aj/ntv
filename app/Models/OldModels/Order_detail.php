<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Order_detail extends Model{
  protected $table = 't_order_detail';
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
    $this->order_id      = '';
    $this->order_flag    = 1;
    $this->s_id          = '';
    $this->product_id    = '';
    $this->option_1      = '';
    $this->option_2      = '';
    $this->option_3      = '';
    $this->option_4      = '';
    $this->price         = '';
    $this->quantity      = '';
    $this->updated_at    = 0;
    $this->created_at    = 0;
    $this->updated_by    = 0;
  }
}