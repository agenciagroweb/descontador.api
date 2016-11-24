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

}
