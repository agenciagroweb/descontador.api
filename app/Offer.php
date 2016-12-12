<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'offers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'url', 'price', 'about', 'is_active'];

    /**
     * Property to define a black-list:
     *
     * @var array
     */
    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    /**
     * Get a list of stores.
     *
     * @return mixed
     */
    public function listOffer()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new offer from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushOffer($request)
    {
        $input = $request->all();

        if (isset($input['title']))
            $input['slug'] = str_slug($input['title'], "-");

        $offer = new Offer;
        $offer->fill($input);
        $offer->save();

        return $offer;
    }

    /**
     * Get a specific offer by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullOffer($id)
    {
        $offer = $this->where('id', $id)->get();
        //$offer->load('store');

        $response = $offer;

        return $response;
    }

    /**
     *  Update offer by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateOffer($request, $id)
    {
        $input = $request->all();

        if (isset($input['title']))
            $input['slug'] = str_slug($input['title'], "-");
        
        $offer = $this->find($id);

        if ($offer instanceof Offer) {
            $offer->fill($input);
            $offer->save();
        }

        return $offer;
    }

    /**
     * Delete a specific offer by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteOffer($id)
    {
        $offer = $this->find($id);

        if ($offer instanceof Offer) {
            return $offer->delete();
        }

        return false;
    }

}
