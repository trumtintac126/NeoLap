<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 13:45
 */

namespace App\Http\Controllers\Api;


use App\Repositories\Validators\Row_name\CreateRownameValidator;
use App\Repositories\Validators\Row_name\UpdateRownameValidator;
use App\Services\RownameService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;

class RownameController extends ApiController
{
    public function __construct(RownameService $rownameService,
                                CreateRownameValidator $createRownameValidator,
                                UpdateRownameValidator $updateRownameValidator,
                                TablenameController $controller)
    {
        $this->rownameService = $rownameService;
        $this->createRownameValidator = $createRownameValidator;
        $this->updateRownameValidator = $updateRownameValidator;
        $this->controller = $controller;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $data_user = auth()->user();

            $row_name = $request->data_row_name;
            if (isset($row_name)) {
                foreach ($row_name as $row) {
                    $data_row_name = [
                        'row_name' => $row['row_name'],
                        'type' => $row['type'],
                        'status' => \Constant::DB_FLG_STATUS_ON,
                        'table_name_id' => $request->table_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $data_row = $this->rownameService->create($data_row_name);
                }
            } else {
                return $this->error("Data error insert!");
            }

            DB::commit();
            return $this->success("Create table_name success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $table_name_id, $id)
    {
        try {

            $data_row_name = $this->findWhere($table_name_id, $id);

            if ($data_row_name == null) {
                return $this->error('Access deny');
            }

            $data_update = [
                'row_name' => $request->row_name ?? $data_row_name['row_name'],
                'type' => $request->type ?? $data_row_name['type'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $data_user = $this->rownameService->update($id, $data_update);

            return $this->success('Update success!');

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function findWhere($table_name_id, $id)
    {
        try {
            $data_user = auth()->user();

            $data_row_name = $this->rownameService->findWhere([
                'status' => 1,
                'id' => $id,
                'table_name_id' => $table_name_id
            ],
                ['*'])->first();

            return $data_row_name;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function paginate(Request $request, $table_name_id)
    {
        try {
            $data_user = auth()->user();
            $data_row_name = $this->rownameService->findWhere([
                'status' => 1,
                'table_name_id' => $table_name_id
            ], ['*']);

            return $this->success($data_row_name);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function delete($table_name_id, $id)
    {
        try {

            $data_row_name = $this->findWhere($table_name_id, $id);

            if ($data_row_name == null) {
                return $this->error("Access deny");
            }

            $data_update = [
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
            ];

            $table_name = $this->rownameService->update($id, $data_update);

            return $this->success("Delete success");

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getRowName($table_name_id)
    {
        try {
            $data = $this->rownameService->findWhere(
                ['table_id' => $table_name_id],
                ['row_name', 'id']
            );
    
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

}