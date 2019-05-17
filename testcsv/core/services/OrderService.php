<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 16/05/2019
 * Time: 14:06
 */

namespace Core\Services;


use Core\Repositories\OrderRepository;

class OrderService implements ServiceInterface
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function paginate()
    {
        // TODO: Implement paginate() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($data)
    {
        return $this->repository->store($data);
    }

    public function revenueByMonth($year)
    {
        return $this->repository->revenueByMonth($year);
    }

    public function listYearOrder()
    {
        return $this->repository->listYearOrder();
    }

    public function topBySell($year,$month)
    {
        return $this->repository->topBySell($year,$month);
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}