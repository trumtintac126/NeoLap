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

    /**
     * row_name belongsTo table_name
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function table_name()
    {
        return $this->belongsTo('App\Models\Row_name', 'table_name_id', 'id');
    }

    /**
     * row_name hasMany row_value
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function row_value()
    {
        return $this->hasMany('App\Models\Row_value', 'row_id', 'id');
    }
}