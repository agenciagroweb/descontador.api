<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'title', 'rules', 'is_active'];

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
    public function listCoupon()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new coupon from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushCoupon($request)
    {
        $input = $request->all();

        $coupon = new Coupon;
        $coupon->fill($input);
        $coupon->save();

        return $coupon;
    }

    /**
     * Get a specific coupon by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullCoupon($id)
    {
        $response = $this->where('id', $id)->get();

        return $response;
    }

    /**
     *  Update coupon by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateCoupon($request, $id)
    {
        $input = $request->all();

        $coupon = $this->find($id);

        if ($coupon instanceof Coupon) {
            $coupon->fill($input);
            $coupon->save();
        }

        return $coupon;
    }

    /**
    /**
     * Delete a specific coupon by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteCoupon($id)
    {
        $coupon = $this->find($id);

        if ($coupon instanceof Coupon) {
            return $coupon->delete();
        }

        return false;
    }

}
