<?php


namespace App\Http\Controllers\Api;


use App\Repositories\Validators\Row_name\UpdateRownameValidator;
use App\Repositories\Validators\Table_name\UpdateTablenameValidator;
use App\Services\AuthTokenService;
use App\Services\RownameService;
use App\Services\TablenameService;
use Illuminate\Http\Request;
use Prettus\Validator\Exceptions\ValidatorException;

class ApiAuthenTokenUpdateController extends ApiController
{
    public function __construct(
        TablenameService $tablenameService,
        RownameService $rownameService,
        AuthTokenService $tokenService,
        UpdateTablenameValidator $updateTablenameValidator,
        UpdateRownameValidator $updateRownameValidator)
    {
        $this->tablenameService = $tablenameService;
        $this->rownameService = $rownameService;
        $this->updateTablenameValidator = $updateTablenameValidator;
        $this->updateRownameValidator = $updateRownameValidator;
        $this->tokenService = $tokenService;
    }

    public function updateTableName(Request $request, $table_id)
    {
        try {
            $token = $request->token;
            $table_name = $request->table_name;
            //get user_id with token
            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            //if table_id and user_id true -> return true else retun false
            $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

            if (!$table_id_check) {
                return $this->error("Access deny");
            }

            $data_table_name = $this->findTableName($table_id, $user_id);
            //check table_name exits?
            $check_table_name = $this->tablenameService->findWhere(['table_name' => $table_name, 'user_id' => $user_id], ['*']);

            if (count($check_table_name) > 0) {
                return $this->error("Table name exits");
            }

            $data_update = [
                'table_name' => $request->table_name ?? $data_table_name['table_name'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $data_user = $this->tablenameService->update($table_id, $data_update);

            return $this->success('Update success!');

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function updateRowName(Request $request, $table_id, $row_name_id)
    {
        try {
            $token = $request->token;

            $row_name = $request->row_name;
            //get user_id with token
            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            //if table_id and user_id true -> return true else retun false
            $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

            if (!$table_id_check) {
                return $this->error('Access deny');
            }
            //get row_name in table_id after check table_id
            $data_row_name = $this->findRowName($table_id);

            $data_row_name_check = $this->getRowNameFromTableId($data_row_name);

            if (in_array($row_name, $data_row_name_check)) {
                return $this->error("Row name exits in table");
            }

            $data_update = [
                'row_name' => $request->row_name ?? $data_row_name['row_name'],
                'updated_at' => date('Y-m-d H:i:s'),
                'type' => 'string'
            ];

            $data_row_name = $this->rownameService->update($row_name_id, $data_update);

            return $this->success('Update success!');

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getRowNameFromTableId($data_row_name)
    {
        try {
            $arr_row_name = [];
            foreach ($data_row_name as $item) {
                array_push($arr_row_name, $item->row_name);
            }

            return $arr_row_name;
        } catch (\Exception $e) {
            return $this->error("Error");
        }
    }

    public function findTableName($id, $token)
    {
        try {
            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            $data_table_name = $this->tablenameService->findWhere([
                'user_id' => $user_id,
                'status' => 1,
                'id' => $id
            ],
                ['*'])->first();

            return $data_table_name;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function findRowName($table_id)
    {
        try {
            return $this->rownameService->findWhere(['table_name_id' => $table_id], ['row_name']);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}