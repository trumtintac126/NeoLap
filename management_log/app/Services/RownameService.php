<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:43
 */

namespace App\Services;


use App\Repositories\Contracts\RownameRepository;
use App\Repositories\Validators\Row_name\CreateRownameValidator;
use App\Repositories\Validators\Row_name\UpdateRownameValidator;

class RownameService extends AbstractService
{
    public function __construct(
        RownameRepository $repository,
        CreateRownameValidator $createRownameValidator,
        UpdateRownameValidator $updateRownameValidator
    )
    {
        $this->repository = $repository;
        $this->createValidator = $createRownameValidator;
        $this->updateValidator = $updateRownameValidator;
    }
}