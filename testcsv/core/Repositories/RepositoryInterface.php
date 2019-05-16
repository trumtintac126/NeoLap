<?php

namespace Core\Repositories;

interface RepositoryInterface
{
    public function store($data);

    public function find($id);

    public function paginate();

    public function update($id,$data);

    public function destroy($id);
}