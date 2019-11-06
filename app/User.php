<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Notifications\ResetPassword;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','photo_url', 'email', 'password','phone','sex'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function artists(){
        return  $this->hasOne(Artist::class);
    }   

    public function likes()
    {
        return $this->belongsToMany(Image::class, 'likes', 'user_id', 'piece_id');
    }
    
    public function transactions(){
        return  $this->hasMany(Transaction::class);
    }

    // Custom Reset Password
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    //Relation to coupon
    public function coupon(){
        return $this->hasOne(Coupon::class,'acquired_by');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }


    public function cartPieces()
    {
        return $this->belongsToMany(Piece::class,'carts');
    }

    public function role(){
        return $this->belongsTo(Role::class)->first();
    }
}
