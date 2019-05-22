<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 21/05/2019
 * Time: 13:37
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comments";
    public $timestamps = false;

    protected $fillable = [
        'content',
        'author',
    ];

    protected $hidden = [
    ];

}