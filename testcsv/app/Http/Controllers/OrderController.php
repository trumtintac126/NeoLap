<?php

namespace App\Http\Controllers;


use Core\Services\OrderService;
use Illuminate\Http\Request;

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

                        if($row == 1){ $row++; continue; }

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

}