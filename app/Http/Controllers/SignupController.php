<?php

namespace App\Http\Controllers;

use File;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class SignupController extends Controller
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
        $validate = [
            'username' => 'required',
            'email'    => 'required|email',
            'password' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $isValid = $this->isValid($request);

        if ($isValid !== true)
            return Controller::response(Controller::error(21), 400);

        if ($request->hasFile('upload')) {

            $file = $request->file('upload');

            $name = uniqid(rand(), true) . "." . $file->getClientOriginalExtension();
            $file->move('storage/', $name);

            $request['picture'] = url('/storage') . "/" . $name;
        }

        $response = $this->user->pushUser($request);

        return Controller::response($response, 200);
    }

    /**
     * @param $request
     * @return bool
     */
    private function isValid($request)
    {
        $validated = true;

        if ($this->pullUsername($request->input('username'))) {
            $validated = false;
        }

        if ($this->pullEmail($request->input('email'))) {
            $validated = false;
        }

        if ( ! empty($request->input('fid'))) {

            if ($this->pullFacebook($request->input('fid'))) {
                $validated = false;
            }
        }

        return $validated;
    }

    /**
     * @param $mail
     * @return mixed
     */
    private function pullEmail($mail)
    {
        return $this->user->isUserEmail($mail);
    }

    /**
     * @param $fid
     * @return mixed
     */
    private function pullFacebook($fid)
    {
        return $this->user->isUserFacebook($fid);
    }

    /**
     * @param $username
     * @return mixed
     */
    private function pullUsername($username)
    {
        return $this->user->isUsernameAlready($username);
    }
}
