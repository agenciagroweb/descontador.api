<?php

namespace App;

use Hash;
use Mail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fid', 'username', 'name', 'lastname', 'email', 'password', 'picture', 'social_facebook', 'social_twitter', 'social_youtube', 'social_www', 'birthday', 'address', 'city', 'state', 'county', 'zipcode', 'is_active'];

    /**
     * Property to define a black-list:
     *
     * @var array
     */
    protected $hidden = ['password', 'pivot', 'created_at', 'updated_at'];

    /**
     * Get a list of users.
     *
     * @return mixed
     */
    public function listUser()
    {
        $user = $this->all();

        return $user;
    }

    /**
     *  Create a new user from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushUser($request)
    {
        $input = $request->all();
        $secret = $input['password'];

        $input['password'] = Hash::make($input['password']);

        $user = new User;
        $user->fill($input);
        $user->save();

        $user->role()->attach(1);
        $user->load('role');

        //$this->sendWelcome($user, $secret);

        return $user;
    }

    /**
     * Get a specific user by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullUser($id)
    {
        $user = $this->where('id', $id)->get();
        //$user->load('role');

        $response = $this->filterUser($user);

        return $response;
    }

    /**
     * Get a specific user by e-mail.
     *
     * @param $mail
     * @return mixed
     */
    public function pullUserEmail($mail)
    {
        $user = $this->where('email', $mail)->get();
        //$user->load('role');

        $response = $this->filterUser($user);

        return $response;
    }

    /**
     *  Update user by id and set request data.
     *
     * @param $request input form data
     * @param $id user id
     * @return User
     */
    public function updateUser($request, $id)
    {
        $input = $request->all();

        $user = $this->find($id);

        if ($user instanceof User) {

            if (isset($input['password']) && isset($input['c_password'])) {

                if ( ! Hash::check($input['c_password'], $user->password)) {
                    return array('error' => 91);
                }

                $input['password']= Hash::make($input['password']);
            }

            $user->fill($input);
            $user->save();
        }

        return $user;
    }

    /**
     *  Update user password.
     *
     * @param $secret password
     * @param $id user id
     * @return User
     */
    public function updatePassword($secret, $id)
    {
        $user = $this->find($id);

        if ($user instanceof User) {

            $input['password'] = Hash::make($secret);

            if (isset($user->fid) && $user->fid !== NULL) {
                $input['fid'] = NULL;
            }

            $user->fill($input);
            $user->save();

            //$this->sendForgot($user, $secret);

            return true;
        }

        return false;
    }

    /**
     * Update a specific picture banner by id.
     *
     * @param $id
     * @param $request
     * @return mixed
     */
    public function updatePicture($id, $request)
    {
        $user = $this->find($id);

        if ($user instanceof User) {

            $user->picture = $request['picture'];
            $user->save();

            return $user;
        }

        return false;
    }

    /**
     * Delete a specific user by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteUser($id)
    {
        $user = $this->find($id);

        if ($user instanceof User) {
            return $user->delete();
        }

        return false;
    }

    /**
     * Delete a specific picture banner by id.
     *
     * @param $id
     * @return mixed
     */
    public function deletePicture($id)
    {
        $user = $this->find($id);

        if ($user instanceof User) {

            $user->picture = null;
            $user->save();

            return $user;
        }

        return false;
    }

    /**
     * Make relationship with Team
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role()
    {
        return $this->belongsToMany('App\Role', 'users_role', 'user_id', 'role_id');
    }

    /**
     * Filter pivot list.
     *
     * @param $user
     * @param $full
     * @param $address
     * @return mixed
     */
    public function filterUser($user, $full = true, $address = true, $role = true)
    {
        if ($user->count() > 0) {

            if ($full === true) {
                foreach ($user as $i => $item) {
                    unset($user[$i]->fid);
                    unset($user[$i]->is_active);
                }
            }

            if ($address === true) {
                foreach ($user as $i => $item) {
                    unset($user[$i]->address);
                    unset($user[$i]->birthday);
                }
            }

            if ($role === true) {
                foreach ($user->lists('role') as $i => $item) {
                    foreach ($item as $k => $role) {
                        unset($user[$i]->role[$k]->name);
                    }
                }
            } else {
                foreach ($user->lists('role') as $i => $item) {
                    unset($user[$i]->role);
                }
            }
        }

        return $user;
    }
}
