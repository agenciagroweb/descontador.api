<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * @var \App\Redirect
     */
    protected $redirect;

    /**
     * RedirectController constructor.
     * @param Redirect $redirect
     */
    public function __construct(Redirect $redirect)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show']]);
        $this->redirect = $redirect;
    }

    /**
     * Display a listing of redirect
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $response = $this->redirect->listRedirect();

        return Controller::response($response, 200);
    }

    /**
     * Create a new redirect instance.
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
            'to' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true || filter_var($request->input('to'), FILTER_VALIDATE_URL) === false)
            return Controller::response(Controller::error(38), 400);

        $response = $this->redirect->pushRedirect($request);

        return Controller::response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $code
     * @return mixed
     */
    public function show($code)
    {
        $redirect = $this->redirect->pullRedirect($code);
        $response = $redirect->first();

        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $response['to'])) {

            $this->redirect->updateCounter($response['code']);
            return redirect()->away($response['to']);
        }

        return redirect("/");

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

        $response = $this->redirect->updateRedirect($request, $id);

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

        $response = $this->redirect->deleteRedirect($id);

        return Controller::response($response, 204);
    }
}
