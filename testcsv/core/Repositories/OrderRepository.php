<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 16/05/2019
 * Time: 13:54
 */

namespace Core\Repositories;


use App\Order;
use Illuminate\Support\Facades\DB;

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
            'category_order' => $data['category_order'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'total_detail' => $data['total_detail']
        ];

        return $this->model->create($result);
    }

    public function revenueByMonth($year)
    {
        $data = DB::table('order_customers')
            ->select(DB::raw('SUM(total_detail) as total_order_by_month'),
                DB::raw('extract(month from order_date) as month_order_by_year'))
            ->groupBy('month_order_by_year')
            ->whereYear('order_date', '=', $year)
            ->get();
        return $data;
    }

    public function listYearOrder()
    {
        $data = DB::table('order_customers')
            ->select(
                DB::raw('extract(year from order_date) as order_year')
            )
            ->groupBy('order_year')
            ->get();
        return $data;
    }

    public function topBySell($year, $month)
    {
        $data = DB::table('order_customers')
            ->select(DB::raw('SUM(quantity) as sum_quantity'),
                'category_order'
            )
            ->groupBy('category_order')
            ->orderByDesc('sum_quantity')
            ->whereMonth('order_date', '=', $month)
            ->whereYear('order_date', '=', $year)
            ->limit(3)
            ->get();
        return $data;
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function paginate()
    {
        $data = DB::table('order_customers')
            ->select('id', 'order_date', 'category_order', 'price', 'quantity', 'total_detail')
            ->orderByDesc('id');
        return $data->simplePaginate(15);
    }

    public function getAll()
    {
        return $this->model->all()->toArray();
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