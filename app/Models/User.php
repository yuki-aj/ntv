<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable{
	use HasFactory, Notifiable;
	protected $table = 't_user';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		// $this->id				= 0;//オートインクリメントでは不要
		$this->kind				= 0;
		$this->user_status				= 0;
		$this->full_name				= '';
		$this->user_name				= '';
		$this->invest_status				= 0;
		$this->email	     	= '';
		$this->referral_code    	        = '';
		$this->wallet_address    	= '';
		$this->password = '';
		$this->hash				= '';
		$this->invest = '';
		$this->balance = '';
		$this->earning = '';
		// $this->hash				= '';
		$this->updated_at		= 0;
		// $this->created_at		= 0;
	}
}

