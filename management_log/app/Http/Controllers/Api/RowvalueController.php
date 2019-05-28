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
                                RownameController $controller)
    {
        $this->rowvalueService = $rowvalueService;
        $this->createRowvalueValidator = $createRowvalueValidator;
        $this->updateRowvalueValidator = $updateRowvalueValidator;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $data_value = $request->data_value;
            $hash = (string) Uuid::generate();
            dd($hash);
            foreach ($data_value as $data) {
                $data_row_value = [
                    "row_id" => $data['row_id'],
                    "value" => $data['value'],
                    "hash" => '',
                    'created_at' => date('Y-m-d H:i:s'),

                ];
            }

            DB::commit();
            return $this->success("Create success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}