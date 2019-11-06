<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'token',
        'discount_percent',
        'registered_by',
        'expire_on',
        'acquired_by',
    ];

    protected $hidden = [
      //'token'
    ];

    public function user(){
        return $this->belongsTo(User::class,'acquired_by');
    }
}
