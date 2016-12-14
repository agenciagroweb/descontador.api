<?php

namespace App\Http\Controllers;

use JWTAuth;
use File;
use App\Offer;
use App\Http\Requests;
use Illuminate\Http\Request;

class OfferPictureController extends Controller
{
    /**
     * @var \App\Offer
     */
    protected $offer;

    /**
     * UserController constructor.
     * @param Offer $offer
     */
    public function __construct(Offer $offer)
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate']]);
        $this->offer = $offer;
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

        $offer = json_decode($this->offer->pullOffer($id));

        if ( ! empty($offer) || ! collect($offer)->isEmpty()) {

            if ($offer[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $offer[0]->picture);

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

        $response = $this->offer->updatePicture($id, $request);

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

        $offer = json_decode($this->offer->pullOffer($id));

        if ( ! empty($offer) || ! collect($offer)->isEmpty()) {

            if ($offer[0]->picture) {
                $picture = str_replace(url('/storage') . "/", "", $offer[0]->picture);

                $target = public_path('storage/') . $picture;

                @chmod($target, 0777 & ~umask());
                File::delete($target);
            }
        }

        $response = $this->offer->deletePicture($id);

        return Controller::response($response, 204);
    }
}
