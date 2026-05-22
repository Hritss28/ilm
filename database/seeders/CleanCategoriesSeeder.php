<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use Illuminate\Database\Seeder;

class CleanCategoriesSeeder extends Seeder
{
    public function run()
    {
        $nasional = Category::where('name', 'Nasional')->first();
        if ($nasional) {
            News::whereIn('category_id', Category::whereIn('name', ['Lalu Lintas', 'Potret', 'Potret Kelana Kota'])->pluck('id'))
                ->update(['category_id' => $nasional->id]);
        }
        Category::whereIn('name', ['Lalu Lintas', 'Potret', 'Potret Kelana Kota'])->delete();
    }
}
