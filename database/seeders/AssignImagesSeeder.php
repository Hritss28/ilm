<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\News;
use App\Models\Video;
use Illuminate\Database\Seeder;

class AssignImagesSeeder extends Seeder
{
    public function run(): void
    {
        // Assign thumbnails to news articles
        $newsItems = News::orderBy('id')->get();
        $totalImages = 20;

        foreach ($newsItems as $index => $news) {
            $imgNum = str_pad(($index % $totalImages) + 1, 2, '0', STR_PAD_LEFT);
            $news->update(['thumbnail' => "thumbnails/2026/05/news_{$imgNum}.jpg"]);
        }

        // Assign images to advertisements
        $ads = Advertisement::orderBy('id')->get();
        foreach ($ads as $index => $ad) {
            $imgNum = str_pad(($index % 3) + 1, 2, '0', STR_PAD_LEFT);
            $ad->update(['image_url' => "advertisements/ad_{$imgNum}.jpg"]);
        }

        // Assign thumbnails to videos
        $videos = Video::orderBy('id')->get();
        foreach ($videos as $index => $video) {
            $imgNum = str_pad(($index % 8) + 1, 2, '0', STR_PAD_LEFT);
            $video->update(['thumbnail' => "videos/vid_{$imgNum}.jpg"]);
        }

        $this->command->info('Images assigned to all records successfully!');
    }
}
