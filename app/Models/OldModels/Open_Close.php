<?php
namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Open_Close extends Authenticatable{
	use HasFactory, Notifiable;
	protected $table = 't_open_close';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		//$this->id				= 0;//オートインクリメントでは不要
		// $this->kind				= 1;
		// $this->store_status	    = '1';
		// $this->feature_status	= '0';
		// $this->stripe_user_id   = '';
		// $this->password			= '';
		// $this->hash				= '';
		// $this->name				= '';
		$this->s_id				= '0';
		$this->open				= '0';
		$this->day				= '';
		$this->time				= '';

		// $this->c_id			    = '0';//カテゴリー名
		// $this->catch_copy       = '';
		// $this->note             = '';
		// $this->postcode         = '';
		// $this->address          = '';
		// $this->access           = '';
		// $this->parking          = '0';
		// $this->tel              = '';
		// $this->map_url          = '';
		// $this->email            = '';
		// $this->email_status	    = 0;
		// $this->pay_id			= '0';
		// $this->url  			= '';
		// $this->line  			= '';
		// $this->instagram		= '';
		// $this->twitter			= '';
		// $this->facebook			= '';
		// $this->stripe_s_id		= '';
		// $this->employee			= 0;
		// $this->postcode			= '';
		// $this->address			= '';
		// $this->department		= '';
		// $this->facebook		    = '';
		// $this->site_url			= '';
		// $this->note_ja			= '';
		// $this->note_en			= '';
		// $this->note_zhCN		= '';
		// $this->note_zhTW		= '';
		// $this->note_ko			= '';
		// $this->note_fr			= '';
		// $this->updated_by		= 0;
	}
}
