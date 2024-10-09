<?php

namespace App\Services\NewsAggregator;

class NewsAggregatorService
{
    protected $sources = [];

    public function __construct()
    {
        $this->sources['newsapi'] = new NewsAPIService();
        $this->sources['guardianapi'] = new GuardianService();
        $this->sources['nyt'] = new NewYorkTimesService();
    }

    public function fetchFromSource($source)
    {
        if (!isset($this->sources[$source])) {
            throw new \Exception("Source not supported");
        }

        $this->sources[$source]->fetchArticles();
    }

    public function fetchFromAllSources()
    {
        foreach ($this->sources as $source) {
            $source->fetchArticles();
        }
    }
}
