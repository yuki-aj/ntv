<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Store extends Authenticatable{
	use HasFactory, Notifiable;
	protected $table = 't_store';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		//$this->id				= 0;//オートインクリメントでは不要
		$this->kind				= 1;
		$this->store_status	    = '1';
		$this->stripe_user_id   = '';
		$this->name				= '';
		$this->c_id			    = '0';//カテゴリー名
		$this->catch_copy       = '';
		$this->schedule_memo       = '';
		$this->note             = '';
		$this->postcode         = '';
		$this->address          = '';
		$this->email            = '';
		$this->access           = '';
		$this->parking          = '0';
		$this->tel              = '';
		$this->map_url          = '';
		$this->url  			= '';
		$this->instagram		= '';
		$this->twitter			= '';
		$this->facebook			= '';
		$this->updated_by		= 0;
	}
}
