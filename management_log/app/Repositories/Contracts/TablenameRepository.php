<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:49
 */

namespace App\Repositories\Contracts;


use Prettus\Repository\Contracts\RepositoryInterface;

interface TablenameRepository extends RepositoryInterface
{
    public function getTableId();

    public function checkTableId($table_id);

    public function getTableIdToken($user_id);

    public function checkTableIdToken($table_id, $user_id);

}