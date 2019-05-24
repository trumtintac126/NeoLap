<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:24
 */

namespace App\Repositories;


use App\Models\User;
use App\Validations\UserValidation;
use Illuminate\Support\Facades\Input;

class UserRepository implements RepositoryInterface
{
    protected $_model;
    protected $validator;

    public function __construct(User $user, UserValidation $validator)
    {
        $this->_model = $user;
        $this->validator = $validator;
    }

    public function paginate()
    {
        // TODO: Implement paginate() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function create($data)
    {
//        dd($data);
        
        $this->validator->with($data)->passesOrFail();

        return $this->_model->create($data);
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function listAll()
    {
        return $this->_model->all()->toArray();
    }
}