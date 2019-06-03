<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:33
 */

namespace App\Repositories\Eloquent;


use App\Models\Row_name;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Contracts\RownameRepository;

class RownameRepositoryEloquent extends BaseRepository implements RownameRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Row_name::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getTableIdFromRowName($row_name_value)
    {
        $arr_table = [];
        foreach ($row_name_value as $item) {
            $table_id_with_rowname = $this->findWhere(['row_name' => $item], ['*'])->first;
            array_push($arr_table, $table_id_with_rowname->table_name_id);
        }
        $id = [];
        foreach ($arr_table as $item) {
            array_push($id, $item->table_name_id);
        }
        return array_unique($id);
    }
}