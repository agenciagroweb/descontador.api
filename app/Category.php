<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'is_active'];

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
    public function listCategory()
    {
        $response = $this->all();

        return $response;
    }

    /**
     *  Create a new category from request.
     *
     * @param $request input form data
     * @return User
     */
    public function pushCategory($request)
    {
        $input = $request->all();

        if (isset($input['name']))
            $input['slug'] = str_slug($input['name'], "-");

        $category = new Category;
        $category->fill($input);
        $category->save();

        return $category;
    }

    /**
     * Get a specific category by id.
     *
     * @param $id
     * @return mixed
     */
    public function pullCategory($id)
    {
        $category = $this->where('id', $id)->get();
        //$category->load('store');

        $response = $category;

        return $response;
    }

    /**
     *  Update category by id and set request data.
     *
     * @param $request input form data
     * @param $id store id
     * @return User
     */
    public function updateCategory($request, $id)
    {
        $input = $request->all();

        if (isset($input['name']))
            $input['slug'] = str_slug($input['name'], "-");
        
        $category = $this->find($id);

        if ($category instanceof Category) {
            $category->fill($input);
            $category->save();
        }

        return $category;
    }

    /**
     * Delete a specific category by id.
     *
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id)
    {
        $category = $this->find($id);

        if ($category instanceof Category) {
            return $category->delete();
        }

        return false;
    }

}
