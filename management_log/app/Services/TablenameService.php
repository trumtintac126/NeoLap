<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:57
 */

namespace App\Services;


use App\Repositories\Contracts\TablenameRepository;
use App\Repositories\Validators\Table_name\CreateTablenameValidator;
use App\Repositories\Validators\Table_name\UpdateTablenameValidator;

class TablenameService extends AbstractService
{
    public function __construct(
        TablenameRepository $repository,
        CreateTablenameValidator $createTablenameValidator,
        UpdateTablenameValidator $updateTablenameValidator
    )
    {
        $this->repository = $repository;
        $this->createValidator = $createTablenameValidator;
        $this->updateValidator = $updateTablenameValidator;
    }

}