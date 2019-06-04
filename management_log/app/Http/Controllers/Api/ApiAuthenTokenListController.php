<?php


namespace App\Http\Controllers\Api;


use App\Services\AuthTokenService;
use App\Services\RownameService;
use App\Services\RowvalueService;
use App\Services\TablenameService;
use Illuminate\Http\Request;

class ApiAuthenTokenListController extends ApiController
{
    public function __construct(
        TablenameService $tablenameService,
        RownameService $rownameService,
        RowvalueService $rowvalueService,
        AuthTokenService $tokenService)
    {
        $this->tablenameService = $tablenameService;
        $this->rownameService = $rownameService;
        $this->rowvalueService = $rowvalueService;
        $this->tokenService = $tokenService;
    }

    public function listTablName(Request $request)
    {
        try {
            $token = $request->token;

            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            $list_table = $this->tablenameService->paginate(['*']);

            return $this->success($list_table);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function listRowName(Request $request, $table_id)
    {
        try {
            $token = $request->token;

            if (!$this->tableIdCheck($token, $table_id)) {
                return $this->error('Access deny');
            }

            $list_row_name_table = $this->rownameService->paginate(['*']);

            return $this->success($list_row_name_table);

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function listRowValueFromTable(Request $request, $table_id)
    {
        try {
            $token = $request->token;

            if (!$this->tableIdCheck($token, $table_id)) {
                return $this->error('Access deny');
            }

            $row_name_by_table_id = $this->rownameService->findWhere(['table_name_id' => $table_id], ['id', 'row_name']);

            $arr_rowname = $this->nameRowName($row_name_by_table_id);

            $arr_rowname_id = $this->idRowName($row_name_by_table_id);

            $arr_value = $this->valueRowName($arr_rowname_id);

            $arr_data_value = $this->dataValueRowName($arr_value);

            //sum row_name of table_id
            $sum_row_name = count($arr_rowname);
            //sum row_value of table_id
            $sum_row_value = count($arr_data_value);

            $count = $sum_row_value / $sum_row_name;

            $arr_result = [];

            for ($i = 0; $i < $count; $i++) {
                $data = [];
                foreach (range(0, $count - 1) as $index) {
                    $data["$arr_rowname[$index]"] = $arr_value[$index][$i]->value;
                }
                array_push($arr_result, $data);
            }

            return $this->success($arr_result);


        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function tableIdCheck($token, $table_id)
    {
        $user_id = $this->tokenService->getUserByToken($token)->user_id;

        $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

        return $table_id_check;
    }

    public function nameRowName($row_name_by_table_id)
    {
        $arr_rowname = [];

        foreach ($row_name_by_table_id as $item) {
            array_push($arr_rowname, $item->row_name);
        }

        return $arr_rowname;
    }

    public function idRowName($row_name_by_table_id)
    {
        $arr_rowname_id = [];

        foreach ($row_name_by_table_id as $item) {
            array_push($arr_rowname_id, $item->id);
        }

        return $arr_rowname_id;
    }

    public function valueRowName($arr_rowname_id)
    {
        $arr_value = [];
        foreach ($arr_rowname_id as $item) {
            $data_value = $this->rowvalueService->findWhere(['row_id' => $item], ['row_id', 'value']);
            array_push($arr_value, $data_value);

        }
        return $arr_value;
    }

    public function dataValueRowName($arr_value)
    {
        //get array data value
        $arr_data_value = [];
        foreach ($arr_value as $item) {
            foreach ($item as $data) {
                array_push($arr_data_value, $item);
            }
        }

        return $arr_data_value;
    }
}