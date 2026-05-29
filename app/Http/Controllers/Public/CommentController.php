<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\WordFilterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(
        protected WordFilterService $wordFilter
    ) {}

    /**
     * Store a newly created comment for a news article.
     */
    public function store(Request $request, News $news): RedirectResponse
    {
        $request->validate([
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ], [
            'body.required' => 'Komentar tidak boleh kosong.',
            'body.min'      => 'Komentar minimal 3 karakter.',
            'body.max'      => 'Komentar maksimal 1000 karakter.',
        ]);

        $filteredBody = $this->wordFilter->filter($request->input('body'));

        $news->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $filteredBody,
        ]);

        return redirect()
            ->route('news.show', $news->slug)
            ->with('comment_success', 'Komentar berhasil ditambahkan.');
    }
}
