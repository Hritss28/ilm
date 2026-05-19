<?php

namespace App\Http\Middleware;

use App\Models\News;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IncrementViewCount
{
    /**
     * Handle an incoming request.
     *
     * Increments the view counter for a news article using session
     * to avoid duplicate counts from the same session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');

        if ($slug) {
            $news = News::where('slug', $slug)->first();

            if ($news) {
                $sessionKey = "viewed_news_{$news->id}";

                if (!$request->session()->has($sessionKey)) {
                    $news->increment('views');
                    $request->session()->put($sessionKey, true);
                }
            }
        }

        return $next($request);
    }
}
