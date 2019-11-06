<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'number_of_pieces',
        'number_of_pieces_bought',
        'number_of_likes',
        'ratings',
        'folder'];

    public function pieces(){
        return  $this->hasMany(Piece::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
