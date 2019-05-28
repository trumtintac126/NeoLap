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
    
}