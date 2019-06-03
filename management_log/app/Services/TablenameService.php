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

    public function checkTableId($table_id)
    {
        $table_id_check = $this->repository->getTableId();
        $arr_table_id = [];

        foreach ($table_id_check as $item) {
            array_push($arr_table_id, $item->id);
        }

        if (in_array($table_id, $arr_table_id)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkTableIdToken($table_id, $user_id)
    {
        $table_id_check = $this->repository->getTableIdToken($user_id);
        $arr_table_id = [];
        foreach ($table_id_check as $item) {
            array_push($arr_table_id, $item->id);
        }

        if (in_array($table_id, $arr_table_id)) {
            return true;
        } else {
            return false;
        }
    }

}