<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function users() {
        return $this->belongsTo('App\Models\Users');
    }
 
    public function nices() {
        return $this->hasMany('App\Models\Nice');
    }
    use HasFactory;
}
