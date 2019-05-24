<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:38
 */
namespace App\Http\Controllers\Api;


use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends ApiController
{
    public function __construct(UserService $userService)
    {
        $this->_userService = $userService;
    }

    /**
     * @return listUser
     */
    public function getAll()
    {
        try {
            $data = $this->_userService->listAll();
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $data_user = $this->_userService->data_create_user($request->user, $request->password);

            $user = $this->_userService->create($data_user);

            DB::commit();

            return $this->success('Create success!');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}