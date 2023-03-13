<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Product extends Model{
  protected $table = 't_product';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  //public $incrementing = false;// auto-incrementing@var bool
  //public $timestamps   = false;// time-stamp@var bool
	protected $guarded = [];//更新ブラックリスト
  // protected $fillable = ['id','u_id','name','size1','size2','size3','unit','grade','category','type','price','p_unit','origin','note_ja','note_en','note_ko','note_zhCN','note_zhTW','note_fr','note_url','updeted_by','updeted_at','created_at','spec1','spec2','spec3','memo'];
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
    $this->s_id	    = 0;
    $this->c_id	    = 0;
    $this->p_status = '';
    $this->name     = '';
    $this->price    = '';
    $this->note     = '';
    $this->o_id     = '';
    // $this->grade    = 0;
    // $this->volume   = 0;
    // $this->category = 0;
    // $this->type     = 0;
    // $this->price    = 0;
    // $this->p_unit   = 0;
    // $this->origin   = 0;
    // $this->note_ja   = '';
    // $this->note_en   = '';
    // $this->note_zhCN = '';
    // $this->note_zhTW = '';
    // $this->note_ko   = '';
    // $this->note_fr   = '';
    // $this->video_url = '';
    // $this->updated_at = '';
    $this->updated_by = '';
  }
}