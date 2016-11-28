<?php

namespace App\Http\Controllers;

use File;
use JWTAuth;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserController extends Controller
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
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show']]);
        $this->user = $user;
    }

    /**
     * Display a listing of teams
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $user = $this->user->listUser();
        $response = $user;

        return Controller::response($response, 200);
    }

    /**
     * Create a new user instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $validate = [
            'username' => 'required',
            'email'    => 'required|email',
            'password' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

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
     * Display the specified resource.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        if ( ! is_numeric($id))
            return Controller::response(Controller::error(38), 400);

        $user = $this->user->pullUser($id);
//        $user->load('teams', 'games');
//
//        $user = $this->user->filterTeam($user);
//        $user = $this->user->filterGame($user);

        $response = $user->first();

        return Controller::response($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! JWTAuth::parseToken()->authenticate())
            return Controller::response(Controller::error(13), 401);

        if ( $user->id != $id && ! Controller::supreme($user->role)) {
            return Controller::response(Controller::error(13), 401);
        }

        if ( ! is_numeric($id))
            return Controller::response(Controller::error(38), 400);

        if (empty($request->all()))
            return Controller::response($request, 304);

        $response = $this->user->updateUser($request, $id);

        if (isset($response['error']))
            return Controller::response(Controller::error($response['error']), 400);

        return Controller::response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $user = json_decode($this->user->pullUser($id));

        if ( ! empty($user) || ! collect($user)->isEmpty()) {

            if ($user[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $user[0]->picture);

                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        $response = $this->user->deleteUser($id);

        return Controller::response($response, 204);
    }
}
