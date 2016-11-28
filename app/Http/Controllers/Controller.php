<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected static $statusCode = array(
        '1' => 'success',
        '13' => 'unauthorized user',
        '14' => 'could not create token',
        '15' => 'token not provide',
        '21' => 'user already exists',
        '22' => 'e-mail or password is incorrect',
        '34' => 'sorry, that page does not exist',
        '38' => 'query parameter is missing',
        '61' => 'user not found',
        '64' => 'data not found',
        '71' => 'invalid file format upload attempt',
        '91' => 'old password is incorrect',
        '112' => 'match already confirmed'
    );

    /**
     * Validate required arguments.
     *
     * @param $request
     * @param $required
     * @return bool
     */
    protected static function validator($request, $required)
    {
        $validator = Validator::make($request->all(), $required);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    /**
     * Send output or error.
     *
     * @param $output
     * @param $code
     * @return mixed
     */
    public static function response($output, $code)
    {
        if (Request::isMethod('get') && strpos(Request::route()->getName(), 'index') !== false) {
            return Response::json($output, $code);
        }

        if (empty($output) || collect($output)->isEmpty()) {

            if ($code === 304) {
                return Response::json($output, $code);
            }

            return Response::json(self::error(64), 404);
        }

        if ($output === false or $output === 0)
            return Response::json(self::error(64), 404);

        return Response::json($output, $code);
    }

    /**
     * Receive user role from JWT token and check if as a supreme user.
     *
     * @param $data
     * @return bool
     */
    public function supreme($data)
    {
        foreach ($data as $role) {

            if ($role->id !== 1)
                return true;
        }

        return false;
    }

    /**
     * Make json to send an error.
     * @param $code int
     * @return array
     */
    protected static function error($code)
    {
        return [
            'error' => [
                'status' => $code,
                'message' => self::$statusCode[$code]
            ]
        ];
    }

    /**
     * Make json to send an error.
     * @param $code int
     * @return array
     */
    protected static function success($code)
    {
        return [
            'success' => [
                'status' => $code,
                'message' => self::$statusCode[$code]
            ]
        ];
    }
}
