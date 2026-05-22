<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Headline',
            'Nasional',
            'Regional',
            'Hukum',
            'Politik',
            'Dikbud',
            'Pariwisata',
            'Tekno',
            'Ekonomi',
            'Kuliner',
            'Olahraga',
            'Info Pelayanan Public',
            'Literasi',
        ];

        foreach ($categories as $index => $name) {
            DB::table('categories')->insert([
                'name' => $name,
                'slug' => Str::slug($name),
                'order' => $index + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
