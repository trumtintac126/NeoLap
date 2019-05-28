<?php
/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:38
 */
namespace App\Http\Controllers\Api;


use App\Repositories\Validators\User\CreateUserValidator;
use App\Repositories\Validators\User\UpdateUserValidator;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Validator\Exceptions\ValidatorException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Webpatser\Uuid\Uuid;
use Tymon\JWTAuth\Exceptions\JWTException;

//use JWTAuth;

class UserController extends ApiController
{
    public function __construct(UserService $userService,
                                CreateUserValidator $createUserValidator,
                                UpdateUserValidator $updateUserValidator)
    {
        $this->userService = $userService;
        $this->createUserValidator = $createUserValidator;
        $this->updateUserValidator = $updateUserValidator;
    }

    /**
     * @return listUser
     */
    public function getAll()
    {
        try {
            $data = $this->userService->all();
            return $this->success($data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return token
     */
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $data_user = [
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "role" => 1,
                'status' => \Constant::DB_FLG_STATUS_ON,
                'created_at' => date('Y-m-d H:i:s'),

            ];
            $user = $this->userService->create($data_user);
            $token = JWTAuth::fromUser($user);

            DB::commit();
            return $this->success($token);

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

            $data_user = auth()->user();

            $data_update = [
                "first_name" => $request->first_name ? $request->first_name : $data_user['first_name'],
                "last_name" => $request->last_name ? $request->last_name : $data_user['last_name'],
            ];
            
            $data_user = $this->userService->update($id, $data_update);

            return $this->success('Update success!');

        } catch (ValidatorException $ex) {
            return $this->error($ex->getMessageBag());
        } catch (\Exception $e) {
            dd($e);
            return $this->error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->token);
            return $this->success('You have successfully logged out!');
        } catch (JWTException $e) {
            return $this->error('Failed to logout, please try again.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!($token = JWTAuth::attempt($credentials))) {

                return $this->error('Email or password incorrect');
            }

            return $this->success($token);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

}