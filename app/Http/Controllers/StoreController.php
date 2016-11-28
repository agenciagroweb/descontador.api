<?php

namespace App\Http\Controllers;

use File;
use JWTAuth;
use App\Store;
use App\Http\Requests;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * @var \App\Store
     */
    protected $store;

    /**
     * StoreController constructor.
     * @param Store $store
     */
    public function __construct(Store $store)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show']]);
        $this->store = $store;
    }

    /**
     * Display a listing of teams
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->store->listStore();

        return Controller::response($response, 200);
    }

    /**
     * Create a new store instance.
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
            'name' => 'required'
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

        $response = $this->store->pushStore($request);

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
        if ( ! is_numeric($id)) {
            $store = $this->store->pullStoreSlug($id);
        } else {
            $store = $this->store->pullStore($id);
        }

        $response = $store->first();

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

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        if ( ! is_numeric($id))
            return Controller::response(Controller::error(38), 400);

        if (empty($request->all()))
            return Controller::response($request, 304);

        $response = $this->store->updateStore($request, $id);

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

        $store = json_decode($this->store->pullStore($id));

        if ( ! empty($store) || ! collect($store)->isEmpty()) {

            if ($store[0]->picture) {

                $picture = str_replace(url('/storage') . "/", "", $store[0]->picture);
                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        $response = $this->store->deleteStore($id);

        return Controller::response($response, 204);
    }
}
