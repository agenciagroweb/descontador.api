<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'redirects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'to', 'counter', 'is_active'];

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
    public function listRedirect()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new redirect from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushRedirect($request)
    {
        $input = $request->all();

        $input['code'] = substr(uniqid(rand(), true), 0, 12);

        $redirect = new Redirect;
        $redirect->fill($input);
        $redirect->save();

        return $redirect;
    }

    /**
     * Get a specific redirect by $code.
     *
     * @param $code
     * @return mixed
     */
    public function pullRedirect($code)
    {
        $redirect = $this->where('code', $code)->get();
        $response = $redirect;

        return $response;
    }

    /**
     *  Update redirect by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateRedirect($request, $id)
    {
        $input = $request->all();

        $redirect = $this->find($id);

        if ($redirect instanceof Redirect) {
            $redirect->fill($input);
            $redirect->save();
        }

        return $redirect;
    }

    /**
     *  Update redirect counter.
     *
     * @param $code redirect code
     * @return User
     */
    public function updateCounter($code)
    {
        $redirect = $this->where('code', $code)->get();
        $response = $redirect->first();

        $this->where('code', $code)->update(['counter' => $response['counter'] + 1]);

        return $redirect;
    }

    /**
     * Delete a specific redirect by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteRedirect($id)
    {
        $redirect = $this->find($id);

        if ($redirect instanceof Redirect) {
            return $redirect->delete();
        }

        return false;
    }

}
