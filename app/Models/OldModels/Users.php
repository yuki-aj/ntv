<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    public function posts() {
        return $this->hasMany('App\Models\Post');
    }
 
    public function nices() {
        return $this->hasMany('App\Models\Nice');
    }
    use HasFactory;
}
