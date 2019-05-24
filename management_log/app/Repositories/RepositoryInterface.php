<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:22
 */
namespace App\Repositories;

interface RepositoryInterface
{
    public function paginate();

    public function find($id);

    public function create($data);

    public function update($id, $data);

    public function destroy($id);

    public function listAll();
}