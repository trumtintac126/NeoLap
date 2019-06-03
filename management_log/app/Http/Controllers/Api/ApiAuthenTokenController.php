<?php


namespace App\Http\Controllers\Api;


use App\Repositories\Validators\Auth_token\CreateAuthTokenValidator;
use App\Repositories\Validators\Auth_token\UpdateAuthTokenValidator;
use App\Repositories\Validators\Row_name\CreateRownameValidator;
use App\Repositories\Validators\Row_name\UpdateRownameValidator;
use App\Repositories\Validators\Table_name\CreateTablenameValidator;
use App\Repositories\Validators\Table_name\UpdateTablenameValidator;
use App\Services\AuthTokenService;
use App\Services\RownameService;
use App\Services\TablenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Webpatser\Uuid\Uuid;


class ApiAuthenTokenController extends ApiController
{
    public function __construct(AuthTokenService $tokenService,
                                CreateAuthTokenValidator $createAuthTokenValidator,
                                UpdateAuthTokenValidator $updateAuthTokenValidator,
                                TablenameService $tablenameService,
                                CreateTablenameValidator $createTablenameValidator,
                                UpdateTablenameValidator $updateTablenameValidator,
                                RownameService $rownameService,
                                CreateRownameValidator $createRownameValidator,
                                UpdateRownameValidator $updateRownameValidator,
                                RownameController $rownameController,
                                RowvalueController $rowvalueController)
    {
        $this->tokenService = $tokenService;
        $this->createAuthTokenValidator = $createAuthTokenValidator;
        $this->updateAuthTokenValidator = $updateAuthTokenValidator;
        $this->tablenameService = $tablenameService;
        $this->createTablenameValidator = $createTablenameValidator;
        $this->updateTablenameValidator = $updateTablenameValidator;
        $this->rownameService = $rownameService;
        $this->createRownameValidator = $createRownameValidator;
        $this->updateRownameValidator = $updateRownameValidator;
        $this->rownameController = $rownameController;
        $this->rowvalueController = $rowvalueController;
    }

    public
    function createTable(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->token;
            $user_id = $this->getUserByToken($token);

            $check = $this->checkTableName($request->table_name, $user_id->user_id);

            if (!$check) {
                return $this->error("Table name exits");
            }

            $data_table_name = [
                'table_name' => $request->table_name,
                'user_id' => $user_id->user_id,
                'status' => \Constant::DB_FLG_STATUS_ON,
                'created_at' => date('Y-m-d H:i:s'),

            ];

            $table_name = $this->tablenameService->create($data_table_name);
            DB::commit();
            $table = $this->tablenameService->all(['*'])->first();
            $message = "Create table_name " . $table->table_name . " success with table_id = " . $table->id;
            return $this->success($message);

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function checkTableName($table_name, $user_id)
    {
        try {
            $table_name_check = $this->tablenameService->findWhere(
                ['table_name' => $table_name, 'user_id' => $user_id],
                ['*']);

            if (count($table_name_check) > 0) {
                return false;
            }
            return true;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public
    function creatRowName(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->token;
            $table_id = $request->table_name_id;

            $user_id = $this->getUserByToken($token)->user_id;
            $table_id = $this->checkTableIdToken($table_id, $user_id);

            if (!$table_id) {
                return $this->error("Access deny");
            }
            $row_name = $request->data_row_name;
            if (isset($row_name)) {
                foreach ($row_name as $row) {
                    $data_row_name = [
                        'row_name' => $row['row_name'],
                        'type' => $row['type'],
                        'status' => \Constant::DB_FLG_STATUS_ON,
                        'table_name_id' => $request->table_name_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $data_row = $this->rownameService->create($data_row_name);
                }
            }

            DB::commit();
            return $this->success("Create row name success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

//    public function createRowValueRequestLog(Request $request)
//    {
//        try {
//            DB::beginTransaction();
//            $token = $request->token;
//            $table_id = $request->table_name_id;
//            $user_id = $this->getUserByToken($token)->user_id;
//            $table_id_check = $this->checkTableIdToken($table_id, $user_id);
//
//            if (!$table_id_check) {
//                return $this->error("Access deny");
//            }
//
//            $hash = (string)Uuid::generate();
//            $id_ip = $this->rownameController->findId("ip", $table_id);
//            $id_method = $this->rownameController->findId("method", $table_id);
//            $id_body = $this->rownameController->findId("body", $table_id);
//            $id_header = $this->rownameController->findId("header", $table_id);
//
//            $hash = $request->hash;
//            $ip = $request->ip;
//            $method = $request->methods;
//            $body = $request->body;
//            $header = $request->header;
//
//            $data_insert_ip = $this->rowvalueController->insertData($id_ip, $ip, $hash);
//            $data_insert_method = $this->rowvalueController->insertData($id_method, $method, $hash);
//            $data_insert_body = $this->rowvalueController->insertData($id_body, $body, $hash);
//            $data_insert_header = $this->rowvalueController->insertData($id_header, $header, $hash);
//
//            DB::commit();
//            return $this->success("Create success");
//
//        } catch (ValidatorException $ex) {
//            return $this->error($ex->getMessageBag());
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return $this->error($e->getMessage());
//        }
//    }

    public function createRowValue(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->token;
            $table_id = $request->table_name_id;
            $user_id = $this->getUserByToken($token)->user_id;
            $table_id_check = $this->checkTableIdToken($table_id, $user_id);

            if (!$table_id_check) {
                return $this->error("Access deny");
            }

            $data_row_name = $request->row_name_table;

            $id = $this->getTableIdFromRowName($data_row_name);

            //check table_id request === table_id with row_name

            if (!in_array($table_id, $id)) {
                return $this->error("Error");
            }

            foreach ($data_row_name as $item) {
                $find_id_table = $this->rownameController->findId($item, $table_id);
                $data_insert = $this->rowvalueController->insertData($find_id_table, $request->$item, $request->hash);
            }
            DB::commit();
            return $this->success("Create success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }


    public function getTableIdFromRowName($row_name_value)
    {
        $arr_table = [];
        foreach ($row_name_value as $item) {
            $table_id_with_rowname = $this->rownameService->findWhere(['row_name' => $item], ['*'])->first;
            array_push($arr_table, $table_id_with_rowname->table_name_id);
        }
        $id = [];
        foreach ($arr_table as $item) {
            array_push($id, $item->table_name_id);
        }
        return array_unique($id);
    }

    public
    function getUserByToken($token)
    {
        try {
            $user_id = $this->tokenService->findWhere(['token' => $token], ['user_id'])->first();
            return $user_id;
        } catch (\Exception $e) {
            $this->error("Token Invalid");
        }
    }

    public
    function checkTableIdToken($table_id, $user_id)
    {
        $table_id_check = $this->getTableIdToken($user_id);
        $arr_table_id = [];
        foreach ($table_id_check as $item) {
            array_push($arr_table_id, $item->id);
        }

        if (in_array($table_id, $arr_table_id)) {
            return true;
        } else {
            return false;
        }
    }

    public
    function getTableIdToken($user_id)
    {
        $table_id_check = $this->tablenameService->findWhere(
            ['user_id' => $user_id],
            ['id']
        );
        return $table_id_check;
    }
}