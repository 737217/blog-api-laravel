<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Article;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Gate;

class CategoryController extends Controller {

    public function index() {
        return Category::all();
    }

    public function show($slug) {
        $category = Category::where('categories_slug', $slug)->first();
        return response()->json($category);
    }

    public function getCategoryById($id) {
        $category = Category::find($id);
        return response()->json($category);
    }

    // use x-www-form-urlencoded
    public function store(Request $request) {
        return Category::saveCategory($request);
    }

    // use x-www-form-urlencoded
    public function update(Request $request) {
        return Category::updateCategory($request);
    }

    public function delete($id) {
        return Category::deleteCategory($id);
    }
}
