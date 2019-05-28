<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:45
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Table_name extends Model
{
    protected $table = "table_names";
    public $timestamps = false;
    protected $fillable = [
        'table_name',
        'user_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden = [
    ];
}