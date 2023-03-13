<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Option extends Model{
  // protected $table = 't_o_product';
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
    // $this->id	      = 0;
    // $this->s_id	    = '';//store_id
    // $this->p_id     = '';//product_id
    // $this->o_id    = '';//option_id
    // $this->name    = '';
    // $this->price    = '';
    // $this->note     = '';
    $this->s_id	    = 0;//store_id
    $this->p_id     = 0;//product_id
    $this->o_id    = 0;//option_id
    $this->name    = '';
    $this->price    = 0;
    $this->note     = '';
    // $this->grade    = 0;
    // $this->volume   = 0;
    // $this->category = 0;
    // $this->type     = 0;
    // $this->price    = '';
    // $this->p_unit   = 0;
    // $this->origin   = 0;
    // $this->note_ja   = '';
    // $this->note_en   = '';
    // $this->note_zhCN = '';
    // $this->note_zhTW = '';
    // $this->note_ko   = '';
    // $this->note_fr   = '';
    // $this->video_url = '';
    $this->updated_by = 0;
  }
}