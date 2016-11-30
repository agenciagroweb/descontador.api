<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'picture', 'about', 'social_facebook', 'social_twitter', 'social_youtube', 'social_www', 'is_active'];

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
    public function listStore()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new store from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushStore($request)
    {
        $input = $request->all();

        if (isset($input['name']))
            $input['slug'] = str_slug($input['name'], "-");

        $store = new Store;
        $store->fill($input);
        $store->save();

        return $store;
    }

    /**
     * Get a specific store by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullStore($id)
    {
        $store = $this->where('id', $id)->get();
        $store->load('coupons');

        $response = $store;

        return $response;
    }

    /**
     * Get a specific store by slug.
     *
     * @param $slug
     * @return mixed
     */
    public function pullStoreSlug($slug)
    {
        $response = $this->where('slug', $slug)->get();

        return $response;
    }

    /**
     *  Update store by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateStore($request, $id)
    {
        $input = $request->all();

        if (isset($input['name']))
            $input['slug'] = str_slug($input['name'], "-");

        $store = $this->find($id);

        if ($store instanceof Store) {
            $store->fill($input);
            $store->save();
        }

        return $store;
    }

    /**
     * Update a specific store profile picture by id.
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function updatePicture($id, $request)
    {
        $store = $this->find($id);

        if ($store instanceof Store) {

            $store->picture = $request['picture'];
            $store->save();

            return $store;
        }

        return false;
    }

    /**
    /**
     * Delete a specific store by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteStore($id)
    {
        $store = $this->find($id);

        if ($store instanceof Store) {
            return $store->delete();
        }

        return false;
    }

    /**
     * Delete a specific picture store picture by id.
     *
     * @param $id
     * @return mixed
     */
    public function deletePicture($id)
    {
        $store = $this->find($id);

        if ($store instanceof Store) {

            $store->picture = null;
            $store->save();

            return $store;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function coupons()
    {
        return $this->belongsToMany('App\Coupon', 'coupons_store', 'store_id', 'coupon_id');
    }

}
