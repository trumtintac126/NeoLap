<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:26
 */

namespace App\Services;

use App\Repositories\Contracts\UserRepository;
use App\Repositories\Validators\User\CreateUserValidator;
use App\Repositories\Validators\User\UpdateUserValidator;
use Illuminate\Support\Facades\Input;

class UserService extends AbstractService
{
    public function __construct(
        UserRepository $repository,
        CreateUserValidator $createValidator,
        UpdateUserValidator $updateValidator
    )
    {
        $this->repository = $repository;
        $this->createValidator = $createValidator;
        $this->updateValidator = $updateValidator;
//        $this->repository->abc();
    }
    
}