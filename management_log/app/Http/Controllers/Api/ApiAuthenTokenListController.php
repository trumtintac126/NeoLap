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
}