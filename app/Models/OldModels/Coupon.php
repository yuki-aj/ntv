<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Coupon extends Model{
  protected $table = 't_coupon';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  // public $incrementing = false;// auto-incrementing@var bool
  public $timestamps   = false;// time-stamp@var bool
	protected $guarded = [];//更新ブラックリスト
  /*init*/
  public function init(){
    $this->id	       = 0;
    $this->p_flag	   = '';
    $this->s_id	     = 0;
    $this->p_id	     = 0;
    $this->title     = '';
    $this->discount	 = '';
    $this->extension	= '';
    $this->from_date = '2099-01-01 00:00:00';
    $this->to_date   = '2099-01-01 00:00:00';
  }
}