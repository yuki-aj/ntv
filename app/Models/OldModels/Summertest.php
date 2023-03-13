<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Summertest extends Model
{
    use HasFactory;
	protected $table = 't_summertest';
  //protected $primaryKey = 'user_id';//デフォルトid
	//public $incrementing = false;//オートインクリメント
	//public $timestamps = false;//タイムスタンプ(updated_at,created_at)無効
	//protected $fillable = ['id',];//更新ホワイトリスト
	protected $guarded = [];//更新ブラックリスト
	public function init(){
		$this->summernote = '';
		// $this->timestamp  = '';
    }
}
