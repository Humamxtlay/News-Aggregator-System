<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/articles",
     *      operationId="getArticles",
     *      tags={"Articles"},
     *      summary="Get list of articles",
     *      description="Returns a list of articles with pagination",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      )
     * )
     */
    public function index()
    {
        $articles = Article::paginate(10);
        return response()->json($articles);
    }

    /**
     * @OA\Get(
     *      path="/api/articles/search",
     *      operationId="searchArticles",
     *      tags={"Articles"},
     *      summary="Search articles",
     *      description="Search articles by keyword, category, source, or date",
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          description="Search by keyword",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="category",
     *          in="query",
     *          description="Filter by category",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="source",
     *          in="query",
     *          description="Filter by source",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      )
     * )
     */
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

    /**
     * @OA\Get(
     *      path="/api/articles/{id}",
     *      operationId="getArticleById",
     *      tags={"Articles"},
     *      summary="Get article by ID",
     *      description="Returns a single article by ID",
     *      @OA\Parameter(
     *          name="id",
     *          description="Article ID",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Article not found"
     *      )
     * )
     */
    public function show($id)
    {
        $article = Article::find($id);

        if (! $article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return response()->json($article);
    }
}
