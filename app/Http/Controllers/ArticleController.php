<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller {

    public function index() {
        return Article::getArticles();
    }

    public function show($slug) {
        $article = Article::where('articles_slug', $slug)->first();
        $article_category = Article::where('articles_slug', $slug)->first()->category;

        return response()->json([
            'article' => $article,
            'article_category' => $article_category
        ]);
    }

    public function showCategory($slug) {
        $article = Article::where('slug', $slug)->first();
        return response()->json($article);
    }

    // use x-www-form-urlencoded
    public function store(Request $request) {
        return Article::saveArticle($request);
    }

    // use x-www-form-urlencoded
    public function update(Request $request) {
        return Article::updateArticle($request);
    }

    public function delete(Request $request, $id) {
        return Article::deleteArticle($request, $id);
    }
}
