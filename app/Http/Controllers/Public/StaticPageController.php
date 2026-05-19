<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Services\SeoService;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Display the About page.
     */
    public function about(): View
    {
        $page = StaticPage::where('slug', 'tentang-kami')->firstOrFail();
        $seo = $this->seoService->generateForPage($page->title, 'Tentang ' . config('news_portal.site.name'));

        return view('public.static.about', compact('page', 'seo'));
    }

    /**
     * Display the Redaksi page.
     */
    public function redaksi(): View
    {
        $page = StaticPage::where('slug', 'redaksi')->firstOrFail();
        $seo = $this->seoService->generateForPage($page->title, 'Susunan redaksi ' . config('news_portal.site.name'));

        return view('public.static.redaksi', compact('page', 'seo'));
    }
}
