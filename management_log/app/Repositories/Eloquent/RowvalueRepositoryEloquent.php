<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 16:18
 */

namespace App\Repositories\Eloquent;


use App\Models\Row_value;
use App\Repositories\Contracts\RowvalueRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class RowvalueRepositoryEloquent extends BaseRepository implements RowvalueRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Row_value::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}