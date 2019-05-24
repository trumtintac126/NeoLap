<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:26
 */

namespace App\Services;

use App\Repositories\UserRepository;
use Webpatser\Uuid\Uuid;

class UserService implements ServiceInterface
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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
        return $this->repository->create($data);
    }

    public function data_create_user($input, $password)
    {
        return array(
            'email'         => $input['email'],
            'password'      => bcrypt($password),
            'first_name'    => $input['first_name'],
            'last_name'     => $input['last_name'],
            'status'        => \Constant::DB_FLG_STATUS_ON,
            'created_at'     => date('Y-m-d H:i:s'),
            'remember_token'=> (string) Uuid::generate(),
            'role'          => 1
        );
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
        return $this->repository->listAll();
    }
}