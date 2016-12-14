<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Offer;
use App\Http\Requests;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * @var \App\Offer
     */
    protected $offer;

    /**
     * OfferController constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show']]);
        $this->offer = $offer;
    }

    /**
     * Display a listing of offer
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ( ! Controller::supreme($user->role))
            return Controller::response(Controller::error(13), 401);

        $response = $this->offer->listOffer();

        return Controller::response($response, 200);
    }

    /**
     * Create a new offer instance.
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
            'title' => 'required',
            'url' => 'required',
            'price' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $response = $this->offer->pushOffer($request);

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
            $offer = $this->offer->pullOfferSlug($id);
        } else {
            $offer = $this->offer->pullOffer($id);
        }

        $response = $offer->first();

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

        $response = $this->offer->updateOffer($request, $id);

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

        $response = $this->offer->deleteOffer($id);

        return Controller::response($response, 204);
    }
}
