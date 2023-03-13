<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Derivery_staff extends Authenticatable{
	use HasFactory, Notifiable;
	protected $table = 't_derivery_staff';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		//$this->id				= 0;//オートインクリメントでは不要
		$this->d_status   = '';
		$this->line_id    = '';
		$this->name	      = '';
		$this->tel        = '';
		$this->address    = '';
		$this->updated_by = 0;
	}
}

