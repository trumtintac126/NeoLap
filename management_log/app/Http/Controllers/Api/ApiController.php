<?php

/**
 * Created by PhpStorm.
 * User: trinm
 * Date: 23/05/2019
 * Time: 16:39
 */
namespace App\Http\Controllers\Api;

class ApiController extends \App\Http\Controllers\Controller
{
    protected $servire;

    /**
     * Response success
     * @param mixed $data
     * @return object
     */
    protected function success($data)
    {
        return response()
            ->json([
                'status' => true,
                'message' => 'Success!',
                'data' => $data
            ], 200)
            ->withHeaders([
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => 'Origin'
            ]);
    }
    /**
     * Response error
     * @param string $message
     * @param int $code
     * @return object
     */
    protected function error($message, $code = 400)
    {
        return response()
            ->json([
                'status' => false,
                'message' => $message,
                'data' => null
            ], $code)
            ->withHeaders([
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => 'Origin'
            ]);
    }
}