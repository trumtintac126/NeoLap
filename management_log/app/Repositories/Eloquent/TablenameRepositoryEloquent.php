<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:50
 */

namespace App\Repositories\Eloquent;


use App\Models\Table_name;
use App\Repositories\Contracts\TablenameRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;

class TablenameRepositoryEloquent extends BaseRepository implements TablenameRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Table_name::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    //get tableid
    public function checkTableId($table_id)
    {
        return $this->checkTableId($table_id);
    }

    public function getTableId()
    {
        $data_user = auth()->user();
        $table_id_check = $this->findWhere(
            ['user_id' => $data_user->id],
            ['id']
        );
        return $table_id_check;
    }

    //get tableid with token
    public function getTableIdToken($user_id)
    {
        $table_id_check = $this->findWhere(
            ['user_id' => $user_id,
                'status' => 1],
            ['id']
        );
        return $table_id_check;
    }

    public function checkTableIdToken($table_id, $user_id)
    {
        return $this > $this->checkTableIdToken($table_id, $user_id);
    }
}