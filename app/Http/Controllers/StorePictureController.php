<?php

namespace App\Http\Controllers;

use JWTAuth;
use File;
use App\Store;
use App\Http\Requests;
use Illuminate\Http\Request;

class StorePictureController extends Controller
{
    /**
     * @var \App\Store
     */
    protected $store;

    /**
     * UserController constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
        $this->store = $store;
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

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

//        $validate = [
//            'upload' => 'required|mimes:jpeg,png'
//        ];
//
//        $validator = Controller::validator($request, $validate);
//
//        if ($validator !== true)
//            return Controller::response(Controller::error(71), 400);

        $store = json_decode($this->store->pullStore($id));

        if ( ! empty($store) || ! collect($store)->isEmpty()) {

            if ($store[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $store[0]->picture);

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

        $response = $this->store->updatePicture($id, $request);

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

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $store = json_decode($this->store->pullStore($id));

        if ( ! empty($store) || ! collect($store)->isEmpty()) {

            if ($store[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $store[0]->picture);

                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        $response = $this->store->deletePicture($id);

        return Controller::response($response, 204);
    }
}
