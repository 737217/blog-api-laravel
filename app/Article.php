<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Gate;

class Article extends Model
{
    protected $fillable = ['id', 'articles_title', 'articles_description', 'articles_slug', 'articles_text', 'articles_active', 'articles_created_user', 'articles_categories'];

    public function category() {
        return $this->belongsToMany('App\Category', 'article_category', 'article_id', 'category_id');
    }

    static public function getArticles() {
        $articles = DB::table('articles')
            ->join('users', 'articles.articles_created_user', '=', 'users.id')
            ->select('articles.*', 'users.name', 'users.email', 'users.role')
            ->get();
        return response()->json($articles);
    }

    static public function saveArticle($request) {
        $user = Auth::user();
        $article = new Article;
        return $article->saveA($article, $request, $user);
    }

    static public function updateArticle($request) {
        $user = Auth::user();
        $article = Article::where('articles_slug', $request->old_slug)->first();
        if (Gate::forUser($user)->allows('updateArticles', $article)) {
            return $article->saveA($article, $request, $user);
        }
        return response()->json(['error' => 'У вас нет прав доступа'], 401);
    }

    static public function deleteArticle($id) {
        $user = Auth::user();
        $article = Article::findOrFail($id);
        if (Gate::forUser($user)->allows('updateArticles', $article)) {
            DB::table('article_category')->where('article_id', '=', $id)->delete();
            $article->delete();
            return 204;
        }
        return response()->json(['error' => 'У вас нет прав доступа']);
    }

    private function saveA($article, $request, $user) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'max:255',
            'active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $article->articles_title = $request->title;
        $article->articles_description = $request->description;
        $article->articles_text = $request->text;
        $article->articles_active = $request->active;
        $article->articles_slug = $request->slug;
        $article->articles_created_user = $user->id;
        $article->save();
        $category = DB::table('article_category')->where('article_id', '=', $article->id)->get();
        if(count($category)) {
            DB::table('article_category')->where('article_id', '=', $article->id)->delete();
        }
        if($request->categories) {
            foreach ($request->categories as $category) {
                DB::table('article_category')->insert(
                    [
                        'article_id' => $article->id,
                        'category_id' => $category
                    ]
                );
            }
        }
        return response()->json($article);
    }
}
