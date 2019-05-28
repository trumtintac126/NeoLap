<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 16:22
 */

namespace App\Services;


use App\Repositories\Contracts\RowvalueRepository;
use App\Repositories\Validators\Row_value\CreateRowvalueValidator;
use App\Repositories\Validators\Row_value\UpdateRowvalueValidator;


class RowvalueService extends AbstractService
{
    public function __construct(RowvalueRepository $rowvalueRepository,
                                CreateRowvalueValidator $createRowvalueValidator,
                                UpdateRowvalueValidator $updateRowvalueValidator)
    {
        $this->rowvalueRepository = $rowvalueRepository;
        $this->createRowvalueValidator = $createRowvalueValidator;
        $this->updateRowvalueValidator = $updateRowvalueValidator;
    }
}