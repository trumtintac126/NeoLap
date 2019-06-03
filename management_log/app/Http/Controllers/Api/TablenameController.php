<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 28/05/2019
 * Time: 08:58
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TablenameService;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Validators\Table_name\CreateTablenameValidator;
use App\Repositories\Validators\Table_name\UpdateTablenameValidator;

class TablenameController extends ApiController
{
    public function __construct(TablenameService $tablenameService,
                                CreateTablenameValidator $createTablenameValidator,
                                UpdateTablenameValidator $updateTablenameValidator)
    {
        $this->tablenameService = $tablenameService;
        $this->createTablenameValidator = $createTablenameValidator;
        $this->updateTablenameValidator = $updateTablenameValidator;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $data_user = auth()->user();
            $data_table_name = [
                'table_name' => $request->table_name,
                'user_id' => $data_user->id,
                'status' => \Constant::DB_FLG_STATUS_ON,
                'created_at' => date('Y-m-d H:i:s'),

            ];
            $table_name = $this->tablenameService->create($data_table_name);
            DB::commit();
            return $this->success("Create table_name success");

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $data_table_name = $this->findWhere($id);

            if ($data_table_name == null) {
                return $this->error('Access deny');
            }

            $data_update = [
                'table_name' => $request->table_name ? $request->table_name : $data_table_name['table_name'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $data_user = $this->tablenameService->update($id, $data_update);

            return $this->success('Update success!');

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function findWhere($id)
    {
        try {
            $data_user = auth()->user();

            $data_table_name = $this->tablenameService->findWhere([
                'user_id' => $data_user->id,
                'status' => 1,
                'id' => $id
            ],
                ['*'])->first();

            return $data_table_name;

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function finnById($id)
    {
        try {

            $data_table_name = $this->findWhere($id);

            return $this->success($data_table_name);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {

            $data_table_name = $this->findWhere($id);

            if ($data_table_name == null) {
                return $this->error("Access deny");
            }

            $data_update = [
                'status' => 0,
                'deleted_at' => date('Y-m-d H:i:s'),
            ];

            $table_name = $this->tablenameService->update($id, $data_update);

            return $this->success("Delete success");

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function all()
    {
        try {
            $data_user = auth()->user();
            $data = $this->tablenameService->findWhere(
                ['user_id' => $data_user->id],
                ['*']
            );

            return $this->success($data);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getTableName()
    {
        try {
            $data_user = auth()->user();
            $data = $this->tablenameService->findWhere(
                ['user_id' => $data_user->id],
                ['table_name', 'id']
            );

            return $this->success($data);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * check table_id of user before list all row value
     * @param $table_id
     * @return bool|void
     */
//    public function checkTableId($table_id)
//    {
//        $table_id_check = $this->getTableId();
//        $arr_table_id = [];
//
//        foreach ($table_id_check as $item) {
//            array_push($arr_table_id, $item->id);
//        }
//
//        if (in_array($table_id, $arr_table_id)) {
//            return true;
//        } else {
//            return false;
//        }
//    }
//
//    public function getTableId()
//    {
//        $data_user = auth()->user();
//        $table_id_check = $this->tablenameService->findWhere(
//            ['user_id' => $data_user->id],
//            ['id']
//        );
//        return $table_id_check;
//    }

}