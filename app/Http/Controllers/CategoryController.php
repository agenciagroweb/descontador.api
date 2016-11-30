<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Category;
use App\Http\Requests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var \App\Category
     */
    protected $category;

    /**
     * CategoryController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show', 'index']]);
        $this->category = $category;
    }

    /**
     * Display a listing of category
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->category->listCategory();

        return Controller::response($response, 200);
    }

    /**
     * Create a new category instance.
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

        $response = $this->category->pushCategory($request);

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

        $category = $this->category->pullCategory($id);
        $response = $category->first();

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

        $response = $this->category->updateCategory($request, $id);

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

        $response = $this->category->deleteCategory($id);

        return Controller::response($response, 204);
    }
}
