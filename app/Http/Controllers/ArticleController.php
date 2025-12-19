<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
   // TAMBAHKAN METHOD INI
public function home()
{
    // Ambil 3 artikel terbaru untuk homepage
    $articles = Article::where('status', 'published')
        ->where('published_at', '<=', now())
        ->with(['category', 'author'])
        ->latest('published_at')
        ->take(3)
        ->get();
    
    return view('landing.index', compact('articles'));
}
    // List semua artikel
    public function index(Request $request)
    {
        $query = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['category', 'author', 'tags']);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest('published_at')->paginate(9);

        $categories = ArticleCategory::where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->where('status', 'published')
                      ->where('published_at', '<=', now());
            }])
            ->get();

        $featuredArticles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('landing.artikel.artikel', compact('articles', 'categories', 'featuredArticles'));
    }

    // Detail artikel
    public function show($slug)
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->with(['category', 'author', 'tags'])
            ->firstOrFail();

        // Increment views
        $article->increment('views');

        // Related articles (same category)
        $relatedArticles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('landing.artikel.artikel-detail', compact('article', 'relatedArticles'));
    }

    // Filter by category
    public function category($slug)
    {
        $category = ArticleCategory::where('slug', $slug)->firstOrFail();
        
        $articles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('category_id', $category->id)
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        $categories = ArticleCategory::where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->where('status', 'published')
                      ->where('published_at', '<=', now());
            }])
            ->get();

        return view('landing.artikel.artikel', compact('articles', 'categories', 'category'));
    }

    // Filter by tag
    public function tag($slug)
    {
        $tag = ArticleTag::where('slug', $slug)->firstOrFail();
        
        $articles = Article::where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('article_tags.id', $tag->id);
            })
            ->with(['category', 'author', 'tags'])
            ->latest('published_at')
            ->paginate(9);

        $categories = ArticleCategory::where('is_active', true)
            ->withCount(['articles' => function($query) {
                $query->where('status', 'published')
                      ->where('published_at', '<=', now());
            }])
            ->get();

        return view('landing.artikel.artikel', compact('articles', 'categories', 'tag'));
    }
}