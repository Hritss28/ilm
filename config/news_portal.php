<?php

return [

    /*
    |--------------------------------------------------------------------------
    | News Portal Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration values specific to the Info Lantas
    | Mojokerto news portal application.
    |
    */

    'site' => [
        'name' => env('NEWS_PORTAL_SITE_NAME', 'Info Lantas Mojokerto'),
        'description' => env('NEWS_PORTAL_SITE_DESCRIPTION', 'Portal Berita Terkini Wilayah Mojokerto (Kabupaten & Kota)'),
        'contact_email' => env('NEWS_PORTAL_CONTACT_EMAIL', 'redaksi@infolantasmojokerto.com'),
        'contact_phone' => env('NEWS_PORTAL_CONTACT_PHONE', '+62-321-123456'),
    ],

    'images' => [
        'max_thumbnail_size' => env('MAX_THUMBNAIL_SIZE', 2048), // KB
        'max_gallery_image_size' => env('MAX_GALLERY_IMAGE_SIZE', 5120), // KB
        'max_advertisement_size' => env('MAX_ADVERTISEMENT_SIZE', 5120), // KB
        'quality' => env('IMAGE_QUALITY', 85),
    ],

    'cache_ttl' => [
        'breaking_news' => env('CACHE_TTL_BREAKING_NEWS', 5), // minutes
        'featured_news' => env('CACHE_TTL_FEATURED_NEWS', 10), // minutes
        'popular_news' => env('CACHE_TTL_POPULAR_NEWS', 30), // minutes
        'recent_news' => env('CACHE_TTL_RECENT_NEWS', 10), // minutes
        'advertisements' => env('CACHE_TTL_ADVERTISEMENTS', 15), // minutes
        'categories' => env('CACHE_TTL_CATEGORIES', 60), // minutes
        'static_pages' => env('CACHE_TTL_STATIC_PAGES', 60), // minutes
    ],

    'pagination' => [
        'news_per_page' => env('NEWS_PER_PAGE', 15),
        'admin_news_per_page' => env('ADMIN_NEWS_PER_PAGE', 20),
    ],

    'breaking_news' => [
        'duration_hours' => env('BREAKING_NEWS_DURATION_HOURS', 24),
        'max_display' => env('MAX_BREAKING_NEWS_DISPLAY', 5),
    ],

    'seo' => [
        'default_keywords' => env('DEFAULT_SEO_KEYWORDS', 'berita mojokerto, info lantas, berita terkini, mojokerto news'),
    ],

    'social_media' => [
        'facebook' => env('SOCIAL_MEDIA_FACEBOOK', 'https://facebook.com/infolantasmojokerto'),
        'twitter' => env('SOCIAL_MEDIA_TWITTER', 'https://twitter.com/infolantasmjk'),
        'instagram' => env('SOCIAL_MEDIA_INSTAGRAM', 'https://instagram.com/infolantasmojokerto'),
        'youtube' => env('SOCIAL_MEDIA_YOUTUBE', 'https://youtube.com/@infolantasmojokerto'),
    ],

    'firebase' => [
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID'),
        'private_key' => env('FIREBASE_PRIVATE_KEY'),
        'client_email' => env('FIREBASE_CLIENT_EMAIL'),
        'client_id' => env('FIREBASE_CLIENT_ID'),
        'auth_uri' => env('FIREBASE_AUTH_URI', 'https://accounts.google.com/o/oauth2/auth'),
        'token_uri' => env('FIREBASE_TOKEN_URI', 'https://oauth2.googleapis.com/token'),
    ],

];