<?php

namespace App\Http\Controllers;

use File;
use JWTAuth;
use Validator;
use Auth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthenticateController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * AuthenticateController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password', 'fid');
        $token = null;

        try {

            if ( ! $token = JWTAuth::attempt($credentials))
                return response()->json(['error' => 'invalid_credentials'], 401);

            if ( ! empty($request->input('fid'))) {
                $user = $this->user->authFacebook($request);
            }

            if ( ! empty($request->input('email'))) {
                $user = $this->user->authEmail($request);
            }

            $hash = compact('token');
            $user['token'] = $hash['token'];

            return Controller::response($user, 200);

        } catch (JWTException $e) {

            return response()->json(['error' => 'could_not_create_token'], 500);
        }
    }
}
