<?php

namespace App\Http\Controllers;

use File;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @var \App\User
     */
    protected $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $isUser = $this->isValid($request);

        if ($isUser === false)
            return Controller::response(Controller::error(22), 401);

        $request->session()->push('user', $isUser);

        return Controller::response($isUser, 200);
    }

    /**
     * @param $request
     * @return mixed
     */
    private function isValid($request)
    {
        if ( ! empty($request->input('fid'))) {
            return $this->user->authFacebook($request);
        }

        if ( ! empty($request->input('email'))) {
            return $this->user->authEmail($request);
        }

        return false;
    }
}
