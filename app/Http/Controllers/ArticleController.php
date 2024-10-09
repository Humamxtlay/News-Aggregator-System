<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Get all articles with pagination
    public function index()
    {
        $articles = Article::paginate(10);
        return response()->json($articles);
    }

    public function search(Request $request)
    {
        $query = Article::query();

        if ($request->has('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%')
                  ->orWhere('body', 'like', '%' . $request->keyword . '%');
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        $articles = $query->paginate(10);

        return response()->json($articles);
    }

    public function show($id)
    {
        $article = Article::find($id);

        if (! $article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}
