<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Gate;

class Category extends Model
{
    protected $fillable = ['id', 'categories_title', 'categories_description', 'categories_slug', 'categories_text', 'categories_active', 'categories_created_user', 'categories_parent_id'];

    public function articles() {
        return $this->belongsToMany('App\Article', 'article_category', 'category_id', 'article_id');
    }

    static public function saveCategory($request) {
        $user = Auth::user();
        $category = new Category;
        $category->categories_created_user = $user->id;
        $category->categories_parent_id = $request->parent_id;
        return $category->saveC($category, $request, $user);
    }

    static public function updateCategory($request) {
        $user = Auth::user();
        $category = Category::where('categories_slug', $request->slug)->first();
        $category->categories_parent_id = $request->parent_id;
        if (Gate::forUser($user)->allows('updateCategory', $category)) {
            return $category->saveC($category, $request, $user);
        }
        return response()->json(['error' => 'У вас нет прав доступа'], 401);
    }

    static public function deleteCategory($id) {
        $user = Auth::user();
        $category = Category::findOrFail($id);
        $res = Gate::forUser($user)->allows('updateCategory', $category);
        if (Gate::forUser($user)->allows('updateCategory', $category)) {
            DB::table('article_category')->where('category_id', '=', $id)->delete();
            $category->delete();
            return 204;
        }
        return response()->json(['error' => 'У вас нет прав доступа']);
    }

    private function saveC($category, $request, $user) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'max:255',
            'active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $category->categories_title = $request->title;
        $category->categories_description = $request->description;
        $category->categories_active = $request->active;
        $category->categories_slug = $request->slug;
        $category->save();
        return response()->json($category);
    }

}
