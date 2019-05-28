<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:31
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Row_name extends Model
{

    protected $table = "row_names";
    public $timestamps = false;
    protected $fillable = [
        'row_name',
        'type',
        'table_name_id',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden = [
    ];

}