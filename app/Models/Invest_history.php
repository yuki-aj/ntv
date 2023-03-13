<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Invest_history extends Authenticatable{
	use HasFactory, Notifiable;
	protected $table = 't_invest_history';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		// $this->id				= 0;//オートインクリメントでは不要
		$this->user_id			= '';
		$this->user_status			= 0;
		$this->plan_name		= '';
		$this->amount			= '';
		$this->invest_date		= '';
		$this->mature_date		= '';
		$this->status			= '';
		$this->updated_at		= 0;
		$this->created_at		= 0;
	}
}

