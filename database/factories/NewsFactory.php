<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'slug' => fake()->unique()->slug(4),
            'content' => '<p>' . fake()->paragraphs(3, true) . '</p>',
            'excerpt' => fake()->paragraph(),
            'thumbnail' => null,
            'category_id' => Category::factory(),
            'author_id' => User::factory(),
            'status' => 'published',
            'is_featured' => false,
            'is_breaking_news' => false,
            'breaking_news_until' => null,
            'views' => fake()->numberBetween(0, 1000),
            'published_at' => now(),
            'seo_title' => null,
            'seo_description' => null,
            'seo_keywords' => null,
        ];
    }

    /**
     * Indicate that the news is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the news is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the news is breaking news.
     */
    public function breaking(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_breaking_news' => true,
            'breaking_news_until' => now()->addHours(24),
        ]);
    }
}
