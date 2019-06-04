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
use App\Services\RowvalueService;
use App\Services\TablenameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Webpatser\Uuid\Uuid;


class ApiAuthenTokenCreateController extends ApiController
{
    public function __construct(
        AuthTokenService $tokenService,
        CreateAuthTokenValidator $createAuthTokenValidator,
        UpdateAuthTokenValidator $updateAuthTokenValidator,
        TablenameService $tablenameService,
        CreateTablenameValidator $createTablenameValidator,
        RownameService $rownameService, RowvalueService $rowvalueService)
    {
        $this->tokenService = $tokenService;
        $this->createAuthTokenValidator = $createAuthTokenValidator;
        $this->updateAuthTokenValidator = $updateAuthTokenValidator;
        $this->tablenameService = $tablenameService;
        $this->createTablenameValidator = $createTablenameValidator;
        $this->rownameService = $rownameService;
        $this->rowvalueService = $rowvalueService;
    }

    public
    function createTable(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->token;
            $user_id = $this->tokenService->getUserByToken($token);
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

            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            $table_id = $this->tablenameService->checkTableIdToken($table_id, $user_id);

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

    public function createRowValue(Request $request)
    {
        try {
            DB::beginTransaction();
            $token = $request->token;
            $table_id = $request->table_name_id;
            $user_id = $this->tokenService->getUserByToken($token)->user_id;
            $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

            if (!$table_id_check) {
                return $this->error("Access deny");
            }

            $data_row_name = $request->data_row_name;

            $id = $this->rownameService->getTableIdFromRowName($data_row_name, $table_id);

            if (count($id) == null) {
                return $this->error("Error");
            }

            foreach ($data_row_name as $item) {

                $find_id_table = $this->rownameService->findWhere(
                    ['row_name' => $item, 'table_name_id' => $table_id],
                    ['id'])->first()->id;

                $data_insert = $this->insertDataRowValue($find_id_table, $request->$item, $request->hash);
            }
            DB::commit();
            return $this->success("Create success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function insertDataRowValue($row_id, $value, $hash)
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

}