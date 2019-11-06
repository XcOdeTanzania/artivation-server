<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piece extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'image', 
        'price',
        'title',
        'size',
        'desc',
        'rate',
        'artist_id',
        'category_id',
        'cart_status',
        'favorite_list',
        'purchased_by'
    ];

    public function cart(){
        return $this->hasOne(Cart::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
