<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 16:15
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Row_value extends Model
{
    protected $table = "row_values";
    public $timestamps = false;
    protected $fillable = [
        'row_id',
        'value',
        'hash',
        'created_at',
        'updated_at'
    ];
    protected $hidden = [
    ];
}