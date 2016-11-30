<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Coupon;
use App\Http\Requests;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * @var \App\Coupon
     */
    protected $coupon;

    /**
     * CouponController constructor.
     * @param Coupon $coupon
     */
    public function __construct(Coupon $coupon)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'show']]);
        $this->coupon = $coupon;
    }

    /**
     * Display a listing of coupons
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = $this->coupon->listCoupon();

        return Controller::response($response, 200);
    }

    /**
     * Create a new coupon instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ( ! JWTAuth::parseToken()->authenticate())
            return Controller::response(Controller::error(13), 401);

        $validate = [
            'code' => 'required',
            'title' => 'required'
        ];

        $validator = Controller::validator($request, $validate);

        if ($validator !== true)
            return Controller::response(Controller::error(38), 400);

        $response = $this->coupon->pushCoupon($request);

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

        $coupon = $this->coupon->pullCoupon($id);
//        $user->load('teams', 'games');
//
//        $user = $this->user->filterTeam($user);
//        $user = $this->user->filterGame($user);

        $response = $coupon->first();

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

        $response = $this->coupon->updateCoupon($request, $id);

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

        $response = $this->coupon->deleteCoupon($id);

        return Controller::response($response, 204);
    }
}
