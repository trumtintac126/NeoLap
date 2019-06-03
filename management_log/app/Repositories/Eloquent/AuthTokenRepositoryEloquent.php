<?php


namespace App\Repositories\Eloquent;


use App\Models\Auth_token;
use App\Repositories\Contracts\AuthTokenRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

class AuthTokenRepositoryEloquent extends BaseRepository implements AuthTokenRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Auth_token::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getUserByToken($token)
    {
        $user_id = $this->findWhere(['token' => $token], ['user_id'])->first();
        return $user_id;
    }
}