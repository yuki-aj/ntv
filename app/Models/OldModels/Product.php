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
  // protected $fillable = ['id','s_id','explanation','name','size1','size2','size3','unit','grade','category','type','price','p_unit','origin','note_ja','note_en','note_ko','note_zhCN','note_zhTW','note_fr','note_url','updeted_by','updeted_at','created_at','spec1','spec2','spec3','memo'];
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
    $this->s_id	    = 0;
    $this->c_id     = 0;// カテゴリー名
    $this->sc_id     = 0;// 
    $this->p_status = 1;
    $this->name     = '';// 商品名
    $this->price    = 0;// 金額
    $this->note     = '';// 商品説明
    $this->hashtag     = '';// ハッシュタグ
    $this->extension     = '';// 拡張子
    $this->o_ids    = '';// オプション
    $this->updated_by = 1;
  }
}