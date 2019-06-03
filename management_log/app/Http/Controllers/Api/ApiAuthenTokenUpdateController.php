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
            //get table_name
            $data_table_name = $this->tablenameService->findWhere(['user_id' => $user_id,
                'status' => 1,
                'id' => $table_id], ['*']);
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

}