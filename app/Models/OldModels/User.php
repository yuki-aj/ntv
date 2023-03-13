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
		//$this->id				= 0;//オートインクリメントでは不要
		$this->kind				= '';
		$this->name				= '';
		$this->kana				= '';
		$this->birthday	     	= '';
		$this->s_id    	        = 0;
		$this->user_status    	= 0;
		$this->corporation_flag = 0;
		$this->password			= '';
		$this->hash				= '';
		$this->email			= '';
		$this->email_status	    = 0;
		$this->line_name        = '';
		$this->line_id          = '';
		$this->tel       	    = '';
		$this->postcode    	    = '';
		$this->address     	    = '';
		$this->d_name       = '';
		$this->d_postcode       = '';
		$this->d_tel       	    = '';
		$this->d_address        = '';
		$this->d_name2       = '';
		$this->d_postcode2       = '';
		$this->d_tel2     	    = '';
		$this->d_address2        = '';
		$this->stripe_id   	    = '';
		$this->favorite   	    = '';
		$this->coupon_stock   	= '';
		$this->coupon_used   	= '';
		// $this->trial_ends_at    = 0;
		// $this->ends_at     	    = 0;

		// $this->company			= '';
		// $this->establish		= 0;
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
		$this->updated_by		= 0;
	}
	public function posts() {
        return $this->hasMany('App\Models\Post');
    }
 
    public function nices() {
        return $this->hasMany('App\Models\Nice');
    }
}

