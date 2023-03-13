<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nice extends Model
{

        // ひとつの「いいね」は1人のユーザー、ひとつの投稿に紐づくので、UserモデルとPostモデルそれぞれに対して、belongsToメソッド
        protected $fillable = ['post_id','users_id'];

        public function users() {
            return $this->belongsTo('App\Models\Users');
        }
     
        public function post() {
            return $this->belongsTo('App\Models\Post');
        }
        use HasFactory;
}


