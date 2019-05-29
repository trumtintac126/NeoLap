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
    
    /**
     * table_name belongsTo user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Table_name', 'user_id', 'id');
    }

    /**
     * table_name hasMany row_name
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function row_name()
    {
        return $this->hasMany('App\Models\Row_name', 'table_name_id', 'id');
    }
    
}