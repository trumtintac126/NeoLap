<?php

namespace App\Http\Controllers;


use App\Events\MessagePosted;
use Core\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Mockery\CountValidator\Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        try {
            $file = $request->file('file_csv');

            $file_name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $file_size = $file->getSize();

            $valid_extension = array("csv");

            $maxFileSize = 2097152;

            if (in_array(strtolower($extension), $valid_extension)) {

                if ($file_size <= $maxFileSize) {

                    $location = public_path() . "/uploads/";
                    if (!is_dir($location)) {
                        mkdir($location);
                    }

                    $file->move($location, $file_name);

                    $filepath = public_path("/uploads/" . $file_name);

                    $file = fopen($filepath, "r");

                    $importData_arr = array();
                    $i = 0;

                    $row = 1;

                    while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                        $num = count($filedata);

                        if ($row == 1) {
                            $row++;
                            continue;
                        }

                        for ($c = 0; $c < $num; $c++) {

                            $importData_arr[$i][] = $filedata [$c];
                        }
                        $i++;
                    }

                    fclose($file);

                    $this->storeOrder($importData_arr);

                    $message = "Success";
                    $code = 200;
                    $data = "Success";
                } else {
                    throw new \Exception("File too large. File must be less than 2MB.!!!", 2);
                }
            } else {
                throw new \Exception("Invalid File Extension!!!", 3);
            }

        } catch (\Exception $e) {
            dd($e);
            if ($e->getCode() === 2 || $e->getCode() === 3) {
                $message = $e->getMessage();
            } else {
                $message = "Something error";
            }
            $code = 400;
            $data = null;
        }
        return response()
            ->json([
                "result_code" => $code,
                "result_message" => $message,
                "data" => $data
            ], $code);
    }


    /**
     * @param array $importData_arr
     * @return bool
     */
    public function storeOrder(array $importData_arr)
    {
        foreach ($importData_arr as $importData) {
            $order_data = [
                "order_date" => date('Y-m-d', strtotime($importData[0])),
                "category_order" => $importData[1],
                "price" => $importData[2],
                "quantity" => $importData[3],
                "total_detail" => (double)$importData[4],
            ];
            $this->orderService->store($order_data);
        }

        return true;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function revenueByMonth(Request $request)
    {
        try {

            $revenue_month_by_year = $this->orderService->revenueByMonth($request->year);
            $message = "Success";
            $code = 200;
            $data = [
                "revenue_month" => $revenue_month_by_year,
                "year_order" => $this->getListYearOrder()
            ];

        } catch (\Exception $e) {
            $message = $e->getMessage();
            $code = 400;
            $data = null;
        }
        return response()
            ->json([
                "result_code" => $code,
                "result_message" => $message,
                "data" => $data
            ], $code);
    }

    /**
     * @return array|null
     */
    public function getListYearOrder()
    {
        try {
            $data_year_order = $this->orderService->listYearOrder();

            $year = [];

            foreach ($data_year_order as $data) {
                array_push($year, $data->order_year);
            }

            $data = $year;

        } catch (\Exception $e) {
            $data = null;
        }
        return $data;
    }

    public function topBySell(Request $request)
    {
        try {

            $topBySell = $this->orderService->topBySell($request->year, $request->month);

            $message = "Success";
            $code = 200;
            $data = $topBySell;

        } catch (\Exception $e) {
            dd($e);
            $message = $e->getMessage();
            $code = 400;
            $data = null;
        }
        return response()
            ->json([
                "result_code" => $code,
                "result_message" => $message,
                "data" => $data
            ], $code);
    }

    public function getAll()
    {
        try {
//            $data_order =  Redis::$this->orderService->paginate();
//
//            $count = Redis::count($this->orderService->getAll()) / 15;

            $data_order =  $this->orderService->paginate();

            $count = count($this->orderService->getAll()) / 15;

            if ($count <= 1) {
                $total = 1;
            } else {
                $total = ceil($count);
            }

            $message = "Success";
            $code = 200;
            $data = [
                'data_orders' => $data_order,
                'total_pages' => $total
            ];

        } catch (\Exception $e) {
            dd($e);
            $message = $e->getMessage();
            $code = 400;
            $data = null;
        }
        return response()
            ->json([
                "result_code" => $code,
                "result_message" => $message,
                "data" => $data
            ], $code)
            ->withHeaders([
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => 'Origin'
            ]);
    }

    public function store(Request $request)
    {
        try {
            $data_order = [
                "order_date" => date("Y-m-d"),
                "category_order" => $request->category_order,
                "price" => $request->price,
                "quantity" => $request->quantity,
                "total_detail" => $request->total_detail,
            ];

            $order = $this->orderService->store($data_order);

            $data = broadcast(new MessagePosted($order));

            $code = 200;
            $message = "Success";
            $data = "Create order success";

        } catch (\Exception $e) {
            dd($e);
            $message = $e->getMessage();
            $code = 400;
            $data = null;
        }
        return response()
            ->json([
                "result_code" => $code,
                "result_message" => $message,
                "data" => $data
            ], $code);
    }

}