<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'author' => $this->faker->name,
            'source' => $this->faker->company,
            'category' => $this->faker->word,
            'published_at' => $this->faker->dateTime,
        ];
    }
}

