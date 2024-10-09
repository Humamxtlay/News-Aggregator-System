<?php

namespace App\Services\NewsAggregator;

use GuzzleHttp\Client;
use App\Models\Article;
use Carbon\Carbon;

class NewsAPIService implements NewsSourceInterface
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('NEWS_API_KEY');  // Make sure to add this to your .env
    }

    public function fetchArticles()
    {
        $response = $this->client->get('https://newsapi.org/v2/top-headlines', [
            'query' => [
                'apiKey' => $this->apiKey,
                'country' => 'us',
                'category' => 'technology'
            ]
        ]);
    
        $data = json_decode($response->getBody()->getContents(), true);
    
        foreach ($data['articles'] as $articleData) {
            $publishedAt = Carbon::parse($articleData['publishedAt'])->format('Y-m-d H:i:s');
    
            Article::updateOrCreate([
                'title' => $articleData['title'],
            ], [
                'body' => $articleData['description'],
                'author' => $articleData['author'],
                'source' => 'NewsAPI',
                'category' => $articleData['category'] ?? 'general',
                'published_at' => $publishedAt,
            ]);
        }
    }
}
