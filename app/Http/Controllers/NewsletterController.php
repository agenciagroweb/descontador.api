<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Newsletter;
use App\Http\Requests;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * @var \App\Newsletter
     */
    protected $newsletter;

    /**
     * NewsletterController constructor.
     * @param Newsletter $newsletter
     */
    public function __construct(Newsletter $newsletter)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show', 'store']]);
        $this->newsletter = $newsletter;
    }

    /**
     * Display a listing of newsletter
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $response = $this->newsletter->listNewsletter();

        return Controller::response($response, 200);
    }

    /**
     * Create a new newsletter instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'email' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $isValid = $this->isValid($request);

        if ($isValid !== true)
            return Controller::response(Controller::error(21), 400);

        $response = $this->newsletter->pushNewsletter($request);

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
            $newsletter = $this->newsletter->pullNewsletterEmail($id);
        } else {
            $newsletter = $this->newsletter->pullNewsletter($id);
        }

        $response = $newsletter->first();

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

        $response = $this->newsletter->updateNewsletter($request, $id);

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

        $response = $this->newsletter->deleteNewsletter($id);

        return Controller::response($response, 204);
    }

    /**
     * @param $request
     * @return bool
     */
    private function isValid($request)
    {
        $validated = true;

        if ($this->newsletter->isNewsletter($request->input('email'))) {
            $validated = false;
        }

        return $validated;
    }

}
