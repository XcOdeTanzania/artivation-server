<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;
    protected $fillable =['piece_id','user_id'];
    protected $dates = ['deleted_at'];
    
     public function users(){
        return $this->hasMany(User::class);
    }
}
