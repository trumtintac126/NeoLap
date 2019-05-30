<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 16:26
 */

namespace App\Http\Controllers\Api;


use App\Repositories\Validators\Row_value\CreateRowvalueValidator;
use App\Repositories\Validators\Row_value\UpdateRowvalueValidator;
use App\Services\RownameService;
use App\Services\RowvalueService;

use App\Services\TablenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Webpatser\Uuid\Uuid;

class RowvalueController extends ApiController
{
    public function __construct(RowvalueService $rowvalueService,
                                CreateRowvalueValidator $createRowvalueValidator,
                                UpdateRowvalueValidator $updateRowvalueValidator,
                                RownameController $controller,
                                RownameService $rownameService,
                                TablenameController $tablenameController)
    {
        $this->rowvalueService = $rowvalueService;
        $this->createRowvalueValidator = $createRowvalueValidator;
        $this->updateRowvalueValidator = $updateRowvalueValidator;
        $this->rownameService = $rownameService;
        $this->controller = $controller;
        $this->tablenameController = $tablenameController;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $data_user = auth()->user();
            $hash = (string)Uuid::generate();

            $id_ip = $this->controller->findIdCreat("ip");
            $id_method = $this->controller->findIdCreat("method");
            $id_body = $this->controller->findIdCreat("body");
            $id_header = $this->controller->findIdCreat("header");

            $hash = $request->hash;
            $ip = $request->ip;
            $method = $request->methods;
            $body = $request->body;
            $header = $request->header;

            $data_insert_ip = $this->insertData($id_ip, $ip, $hash);
            $data_insert_method = $this->insertData($id_method, $method, $hash);
            $data_insert_body = $this->insertData($id_body, $body, $hash);
            $data_insert_header = $this->insertData($id_header, $header, $hash);

            DB::commit();
            return $this->success("Create success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function insertData($row_id, $value, $hash)
    {
        try {
            $data_insert = [
                'row_id' => $row_id,
                'value' => $value,
                'hash' => $hash,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $data = $this->rowvalueService->create($data_insert);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


    public function delete($id)
    {
        try {
            $data = $this->rowvalueService->delete($id);
            return $this->success("Delete success");
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {
            $table_id = $request->table_id;
            $this->tablenameController->checkTableId($table_id);
            $data_header = $this->getHeader($table_id);
            $data_body = $this->getBody($table_id);
            $data_method = $this->getMethod($table_id);
            $data_ip = $this->getIp($table_id);

            $array_data = [];

            for ($i = 0; $i < count($data_header); $i++) {
                $data = [
                    "hearder" => $data_header[$i],
                    "body" => $data_body[$i],
                    "method" => $data_method[$i],
                    "ip" => $data_ip[$i]
                ];

                array_push($array_data, $data);
            }

            return $this->success($array_data);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

//    /**
//     * check table_id of user before list all row value
//     * @param $table_id
//     * @return bool|void
//     */
//    public function checkTableId($table_id)
//    {
//        $data_user = auth()->user();
//        $table_id_check = $this->tablenameService->findWhere(
//            ['user_id' => $data_user->id],
//            ['id']
//        );
//        $arr_table_id = [];
//
//        foreach ($table_id_check as $item) {
//            array_push($arr_table_id, $item->id);
//        }
//
//        if (in_array($table_id, $arr_table_id)) {
//            return true;
//        } else {
//            return;
//        }
//    }

    public function getHeader($table_id)
    {
        $arr_header = [];
        $id_header = $this->controller->findId("header", $table_id);
        $data_header = $this->rowvalueService->findWhere(['row_id' => $id_header], ['value']);
        foreach ($data_header as $item) {
            array_push($arr_header, $item->value);
        }
        return $arr_header;
    }

    public function getBody($table_id)
    {
        $arr_body = [];
        $id_body = $this->controller->findId("body", $table_id);
        $data_body = $this->rowvalueService->findWhere(['row_id' => $id_body], ['value']);
        foreach ($data_body as $item) {
            array_push($arr_body, $item->value);
        }
        return $arr_body;
    }

    public function getMethod($table_id)
    {
        $arr_method = [];
        $id_method = $this->controller->findId("method", $table_id);
        $data_method = $this->rowvalueService->findWhere(['row_id' => $id_method], ['value']);
        foreach ($data_method as $item) {
            array_push($arr_method, $item->value);
        }
        return $arr_method;
    }

    public function getIp($table_id)
    {
        $arr_ip = [];
        $id_ip = $this->controller->findId("ip", $table_id);
        $data_ip = $this->rowvalueService->findWhere(['row_id' => $id_ip], ['value']);
        foreach ($data_ip as $item) {
            array_push($arr_ip, $item->value);
        }
        return $arr_ip;
    }
}