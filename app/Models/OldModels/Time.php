<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Time extends Model{
  protected $table = 't_time';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  //public $incrementing = false;// auto-incrementing@var bool
  //public $timestamps   = false;// time-stamp@var bool
	protected $guarded = [];//更新ブラックリスト
  //protected $fillable = ['id','u_id','name','category','spec1','spec2','spec3','origin','memo'];
//   public static function getListByUid($u_id){
//     $prods = Product::where('u_id',$u_id)->get();
// 		$products = array();
// 		foreach($prods as $prod){
// 			$products[$prod->id] = $prod;
// 		}
//     return $products;
//   }
  /*init*/
  public function init(){
    // $this->id	      = 0;
    $this->s_id	      = '';//$store->id
    $this->open_time  = '';//開店時間
    $this->end_time   = '';//閉店時間
    $this->r_time     = '';//予約開始時間
    $this->r_end_time = '';//予約終了時間
    $this->priority   = "";//優先度。休日祝日優先
    $this->date       = '';//日付、祝日、曜日のどれかが入る
    $this->l_time     = "";//ランチ開始時間
    $this->l_end_time = "";//ランチ終了時間
    $this->d_time     = "";//ディナー開始時間
    $this->d_end_time = "";//ディナー終了時間
    $this->week       = '';//1(第一週)～4(第四週)
    $this->updated_by = 0;
    
    //以下後で削除
    $this->day     = '';//1(第一週)～4(第四週)
    $this->month     = '';//1(1月)～12(12月)
    $this->holiday     = '';//定休日 曜日0(日曜日)～6(土曜日)
  }
}