<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK BERITA (NEWS) ===\n";
$nullNews = \App\Models\News::whereNull('thumbnail')->count();
$notNullNews = \App\Models\News::whereNotNull('thumbnail')->count();
echo "News - NULL: $nullNews, Not NULL: $notNullNews\n";

echo "\n=== CEK GALERI (GALLERIES) ===\n";
$nullGallery = \App\Models\Gallery::whereNull('thumbnail')->count();
$notNullGallery = \App\Models\Gallery::whereNotNull('thumbnail')->count();
echo "Gallery - NULL: $nullGallery, Not NULL: $notNullGallery\n";

echo "\n=== CEK VIDEO ===\n";
$nullVideo = \App\Models\Video::whereNull('thumbnail')->count();
$notNullVideo = \App\Models\Video::whereNotNull('thumbnail')->count();
echo "Video - NULL: $nullVideo, Not NULL: $notNullVideo\n";
