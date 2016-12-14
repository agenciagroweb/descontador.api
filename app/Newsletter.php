<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'newsletters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'is_active'];

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
    public function listNewsletter()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new newsletter from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushNewsletter($request)
    {
        $input = $request->all();

        $newsletter = new Newsletter;
        $newsletter->fill($input);
        $newsletter->save();

        return $newsletter;
    }

    /**
     * Get a specific newsletter by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullNewsletter($id)
    {
        $newsletter = $this->where('id', $id)->get();

        $response = $newsletter;

        return $response;
    }

    /**
     * Get a specific offer by slug.
     *
     * @param $email
     * @return mixed
     */
    public function pullNewsletterEmail($email)
    {
        $response = $this->where('email', $email)->get();

        return $response;
    }

    /**
     * Check that email already exists.
     *
     * @param string $mail
     * @return mixed
     */
    public function isNewsletter($mail)
    {
        $user = $this->where('email', $mail)->count();

        if ($user > 0)
            return true;

        return false;
    }

    /**
     *  Update newsletter by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateNewsletter($request, $id)
    {
        $input = $request->all();

        $newsletter = $this->find($id);

        if ($newsletter instanceof Newsletter) {
            $newsletter->fill($input);
            $newsletter->save();
        }

        return $newsletter;
    }

    /**
     * Delete a specific newsletter by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteNewsletter($id)
    {
        $newsletter = $this->find($id);

        if ($newsletter instanceof Newsletter) {
            return $newsletter->delete();
        }

        return false;
    }

}
