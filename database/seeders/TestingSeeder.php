<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Seed the database with representative testing data.
     */
    public function run(): void
    {
        // Create users for each role
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);

        $redaktur = User::factory()->create([
            'name' => 'Redaktur User',
            'email' => 'redaktur@test.com',
            'role' => 'redaktur',
        ]);

        $author = User::factory()->create([
            'name' => 'Author User',
            'email' => 'author@test.com',
            'role' => 'author',
        ]);

        // Create categories
        $categories = [
            ['name' => 'Headline', 'slug' => 'headline', 'order' => 1],
            ['name' => 'Nasional', 'slug' => 'nasional', 'order' => 2],
            ['name' => 'Regional', 'slug' => 'regional', 'order' => 3],
            ['name' => 'Hukum', 'slug' => 'hukum', 'order' => 4],
            ['name' => 'Politik', 'slug' => 'politik', 'order' => 5],
            ['name' => 'Lalu Lintas', 'slug' => 'lalu-lintas', 'order' => 6],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'order' => 7],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi', 'order' => 8],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create published news for each category
            News::factory()->count(5)->create([
                'category_id' => $category->id,
                'author_id' => $admin->id,
                'status' => 'published',
            ]);

            // Create draft news
            News::factory()->count(2)->draft()->create([
                'category_id' => $category->id,
                'author_id' => $author->id,
            ]);
        }

        // Create featured news
        $headlineCategory = Category::where('slug', 'headline')->first();
        News::factory()->count(3)->featured()->create([
            'category_id' => $headlineCategory->id,
            'author_id' => $redaktur->id,
            'status' => 'published',
        ]);

        // Create breaking news
        News::factory()->count(2)->breaking()->create([
            'category_id' => $headlineCategory->id,
            'author_id' => $admin->id,
            'status' => 'published',
        ]);
    }
}
