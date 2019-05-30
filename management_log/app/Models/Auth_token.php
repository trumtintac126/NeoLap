<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth_token extends Authenticatable implements JWTSubject
{
    protected $table = "auth_tokens";
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'token',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden = [
        'token'
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}