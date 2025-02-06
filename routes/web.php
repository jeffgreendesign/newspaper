<?php

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function (Request $request) {
    $articles = Article::all();

    // Search functionality
    if ($request->has('search')) {
        $query = strtolower($request->input('search'));
        $articles = $articles->filter(fn($article) => Str::contains(strtolower($article->title), $query) || Str::contains(strtolower($article->content), $query));
    }

    // Pagination logic
    $perPage = 5;
    $currentPage = request()->input('page', 1);
    $pagedArticles = new LengthAwarePaginator(
        $articles->forPage($currentPage, $perPage),
        $articles->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );

    return view('articles.index', ['articles' => $pagedArticles]);
});

Route::get('/article/{slug}', function ($slug) {
    $article = Article::find($slug);
    return $article ? view('articles.show', ['article' => $article]) : abort(404);
});

Route::get('/category/{category}', function ($category) {
    // Paginate articles directly from the database by category
    $articles = Article::where('category', 'like', '%' . $category . '%')  // Filter articles by category
                        ->orderBy('date', 'desc')  // Optional: order by date
                        ->paginate(10);  // Adjust the number of articles per page

    return view('articles.index', compact('articles'));
});
