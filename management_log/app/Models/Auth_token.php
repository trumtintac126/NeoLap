<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Auth_token extends Model
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
    ];

}