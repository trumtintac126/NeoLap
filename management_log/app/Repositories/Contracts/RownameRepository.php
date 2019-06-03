<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:32
 */

namespace App\Repositories\Contracts;


use Prettus\Repository\Contracts\RepositoryInterface;

interface RownameRepository extends RepositoryInterface
{
    public function getTableIdFromRowName($row_name_value);
}