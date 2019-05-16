<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 16/05/2019
 * Time: 13:54
 */

namespace Core\Repositories;


use App\Order;

class OrderRepository implements RepositoryInterface
{

    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function store($data)
    {
        $result = [
            'order_date' => $data['order_date'],
            'category_order'=> $data['category_order'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'total_detail' => $data['total_detail']
        ];

        return $this->model->create($result);
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function paginate()
    {
        // TODO: Implement paginate() method.
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