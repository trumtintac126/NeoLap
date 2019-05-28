<?php

namespace App\Services;

use App\Services\Concerns\HasAttributeHooks;
use App\Services\Contracts\ServiceInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Validator\Contracts\ValidatorInterface;

abstract class AbstractService implements ServiceInterface
{
    use HasAttributeHooks;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var ValidatorInterface
     */
    protected $createValidator;

    /**
     * @var ValidatorInterface
     */
    protected $updateValidator;

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        if (!is_null($this->createValidator)) {
            $this->createValidator->with($attributes)->passesOrFail();
        }

        $attributes = $this->beforeCreate($attributes);

        return $this->repository->create($attributes);
    }

    /**
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes)
    {
        if (!is_null($this->updateValidator)) {
            $this->updateValidator->with($attributes)->passesOrFail();
        }

        $attributes = $this->beforeUpdate($attributes);

        return $this->repository->update($attributes, $id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function all(array $options = [])
    {
        return $this->repository->scopeQuery(function ($query) {
            return $query->orderBy('id', 'desc');
        })->all();
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function paginate(array $options = [])
    {
        $limit = $options['limit'] ?? 10;
       return $this->repository->paginate($limit ,$options);
    }

    public function findWhere(array $where, array $attributes)
    {
        return $this->repository->findWhere($where, $attributes);
    }

}