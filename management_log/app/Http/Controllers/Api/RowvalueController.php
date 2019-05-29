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

            $id_ip = $this->controller->findId("ip");
            $id_method = $this->controller->findId("method");
            $id_body = $this->controller->findId("body");
            $id_header = $this->controller->findId("header");

            $hash = $request->hash;
            $ip = $request->ip;
            $method = $request->method;
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

    public function all()
    {
        try {
            $this->getHeader();
            $data_name = $this->getRowName(1);
            $row_id = [];
            $row_name = [];
            foreach ($data_name as $data) {
                array_push($row_id, $data->id);
                array_push($row_name, $data->row_name);
            }

            $data_header = $this->getHeader();
            $data_body = $this->getBody();
            $data_method = $this->getMethod();
            $data_ip = $this->getIp();

            foreach ($row_name as $item)
            {
                print $item;
            }

            $value = $this->getValue($row_id);

            $data = [
                "row_name" => $row_name,
                "value" => $value
            ];

            return $this->success($data);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getHeader()
    {
        $id_header = $this->controller->findId("header");
        $data_header = $this->rowvalueService->findWhere(['row_id' => $id_header], ['value']);
        return $data_header;
    }

    public function getBody()
    {
        $id_body = $this->controller->findId("body");
        $data_body = $this->rowvalueService->findWhere(['row_id' => $id_body], ['value']);
        return $data_body;
    }

    public function getMethod()
    {
        $id_method = $this->controller->findId("method");
        $data_method = $this->rowvalueService->findWhere(['row_id' => $id_method], ['value']);
        return $data_method;
    }

    public function getIp()
    {
        $id_ip = $this->controller->findId("ip");
        $data_ip = $this->rowvalueService->findWhere(['row_id' => $id_ip], ['value']);
        return $data_ip;
    }

    public function getValue(array $row_id)
    {
        try {
            $value = [];
            $hash = $this->getHash($row_id);
            foreach ($hash as $item) {
                $data = $this->rowvalueService->findWhere(['hash' => $item], ['value']);
                foreach ($data as $item) {
                    array_push($value, $item->value);
                }
            }
            return $value;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getHash(array $row_id)
    {
        try {
            $hash = [];
            foreach ($row_id as $id) {
                $data = $this->rowvalueService->findWhere(['row_id' => $id], ['value', 'hash']);
                foreach ($data as $item) {
                    array_push($hash, $item->hash);
                }
            }
            $hash = array_unique($hash);
            return $hash;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


    public function getRowName($table_id)
    {
        try {
            return $this->rownameService->findWhere(
                ['table_name_id' => $table_id],
                ['row_name', 'id']
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}