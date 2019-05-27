<?php

namespace App\Services\Contracts;

interface ServiceInterface
{
    public function find($id, array $columns = ['*']);

    public function create(array $attributes);

    public function update($id, array $attributes);

    public function delete($id);

    public function all(array $options = []);

    public function paginate(array $options = []);
}