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

            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

            if (!$table_id_check) {
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

            $user_id = $this->tokenService->getUserByToken($token)->user_id;

            $table_id_check = $this->tablenameService->checkTableIdToken($table_id, $user_id);

            $row_name_by_table_id = $this->rownameService->findWhere(['table_name_id' => $table_id], ['id', 'row_name']);

            $arr_rowname = [];

            $arr_rowname_id = [];

            foreach ($row_name_by_table_id as $item) {
                array_push($arr_rowname, $item->row_name);
            }

            foreach ($row_name_by_table_id as $item) {
                array_push($arr_rowname_id, $item->id);
            }


            $arr_value = [];

            foreach ($arr_rowname_id as $item) {
                $data_value = $this->rowvalueService->findWhere(['row_id' => $item], ['row_id', 'value']);
                array_push($arr_value, $data_value);

            }

            foreach ($arr_value as $item) {
                print $item;
            }
            exit;
            dd(count($arr_value));
            $sum_row_name = count($arr_rowname);
            $sum_row_value = count($arr_value);
            dd($sum_row_value);
            dd(($sum_row_value / $sum_row_name));
            for ($i = 1; $i <= ($sum_row_value / $sum_row_name); $i++) {
                print "trumtintac";
            }
            exit;
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}