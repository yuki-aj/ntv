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
		// $this->kind				= 1;
		$this->store_status	    = 0;
		$this->stripe_user_id   = '';
		$this->password			= '';
		$this->hash				= '';
		$this->name				= '';// 店舗名
		$this->c_id			    = '';//カテゴリー名
		$this->catch_copy       = '';// キャッチコピー
		$this->note             = '';// 店舗説明
		$this->postcode         = '';// 郵便番号
		$this->address          = '';// 住所
		$this->access           = '';// 駅から徒歩
		$this->parking          = '';// 駐車場
		$this->tel              = '';// 電話番号
		$this->map_url          = '';

		$this->email            = '';// メール
		$this->email_status	    = 0;
		$this->pay_id			= '';
		$this->url  			= '';// ホームページ
		$this->line  			= '';
		$this->instagram		= '';
		$this->twitter			= '';
		$this->facebook			= '';
		$this->stripe_s_id		= '';
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
}
