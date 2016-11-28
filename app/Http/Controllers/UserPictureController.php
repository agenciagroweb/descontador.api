<?php

namespace App\Http\Controllers;

use JWTAuth;
use File;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserPictureController extends Controller
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
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
        $this->user = $user;
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

//        $validate = [
//            'upload' => 'required|mimes:jpeg,png'
//        ];
//
//        $validator = Controller::validator($request, $validate);
//
//        if ($validator !== true)
//            return Controller::response(Controller::error(71), 400);

        $user = json_decode($this->user->pullUser($id));

        if ( ! empty($user) || ! collect($user)->isEmpty()) {

            if ($user[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $user[0]->picture);

                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        if ($request->hasFile('upload')) {

            $file = $request->file('upload');

            $name = uniqid(rand(), true) . "." . $file->getClientOriginalExtension();
            $file->move('storage/', $name);

            $request['picture'] = url('/storage') . "/" . $name;
        }

        $response = $this->user->updatePicture($id, $request);

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

        if ( ! JWTAuth::parseToken()->authenticate())
            return Controller::response(Controller::error(13), 401);

        if ( $user->id != $id && ! Controller::supreme($user->role)) {
            return Controller::response(Controller::error(13), 401);
        }

        $user = json_decode($this->user->pullUser($id));

        if ( ! empty($user) || ! collect($user)->isEmpty()) {

            if ($user[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $user[0]->picture);

                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        $response = $this->user->deletePicture($id);

        return Controller::response($response, 204);
    }
}
