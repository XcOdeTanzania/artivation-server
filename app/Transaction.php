<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;

class Transaction extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'tracking_id',
        'reference_no',
        'payment_method',
        'currency',
        'items_purchased'
    ];
    protected $hidden = ['items_purchased','tracking_id'];
    public function transactions(){
        return $this->hasMany(User::class);
    }
}
