<?php


namespace App\Http\Controllers\Api;


use App\Services\AuthTokenService;
use App\Services\RownameService;
use App\Services\RowvalueService;
use App\Services\TablenameService;
use Illuminate\Http\Request;

class ApiAuthenTokenDeleteController extends ApiController
{
    public function __construct(
        TablenameService $tablenameService,
        RownameService $rownameService,
        RowvalueService $rowvalueService,
        AuthTokenService $tokenService
    )
    {
        $this->tablenameService = $tablenameService;
        $this->rownameService = $rownameService;
        $this->rowvalueService = $rowvalueService;
        $this->tokenService = $tokenService;
    }

    public function deleteTable(Request $request, $table_id)
    {
        try {
            $token = $request->token;

            if (!$this->tableIdCheck($token, $table_id)) {
                return $this->error('Access deny');
            }

            $data_row_name = $this->getRowName($table_id);

            if ($data_row_name == null) {
                return $this->error("With table_id : " . $table_id . "have row_name !");
            }

            $data_update = [
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
            ];

            $table_name = $this->tablenameService->update($table_id, $data_update);

            return $this->success("Delete success");

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function deleteRowName(Request $request, $table_id, $row_name_id)
    {
        try {
            $token = $request->token;

            if (!$this->tableIdCheck($token, $table_id)) {
                return $this->error('Access deny');
            }

            $data_row_name = $this->getRowName($table_id);

            $arr_row_name_id = [];

            foreach ($data_row_name as $item) {
                array_push($arr_row_name_id, $item->id);
            }

            if (!in_array($row_name_id,$arr_row_name_id)) {
                return $this->error("Row name id not exits in table : " . $table_id);
            }

            $data_row_value = $this->getRowValue($row_name_id);

            if (count($data_row_value) > 0) {
                return $this->error("With row_name: " . $row_name_id . " exits row value !");
            }

            $data_delete = $this->rownameService->delete($row_name_id);

            return $this->success("Delete success");


        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function tableIdCheck($token, $table_id)
    {
        $user_id = $this->tokenService->getUserByToken($token)->user_id;

        $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

        return $table_id_check;
    }

    public function getRowName($table_id)
    {
        try {
            $data = $this->rownameService->findWhere(['table_name_id' => $table_id], ['*']);

            return $data;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getRowValue($row_name_id)
    {
        $data = $this->rowvalueService->findWhere(['row_id' => $row_name_id], ['value']);

        return $data;
    }
}