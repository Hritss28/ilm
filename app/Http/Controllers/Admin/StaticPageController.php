<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Services\ContentSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function __construct(
        protected ContentSanitizer $contentSanitizer,
    ) {}
    /**
     * Display a listing of static pages.
     */
    public function index(): View
    {
        $pages = StaticPage::with('updater')->get();

        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     * Static pages are pre-seeded, so creation is not supported.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('admin.pages.index')
            ->with('error', 'Halaman statis tidak bisa ditambahkan secara manual.');
    }

    /**
     * Store is not supported for static pages.
     */
    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.pages.index')
            ->with('error', 'Halaman statis tidak bisa ditambahkan secara manual.');
    }

    /**
     * Display the specified page (redirects to edit).
     */
    public function show(StaticPage $page): RedirectResponse
    {
        return redirect()->route('admin.pages.edit', $page);
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(StaticPage $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page.
     */
    public function update(Request $request, StaticPage $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Sanitize rich text content
        $validated['content'] = $this->contentSanitizer->sanitize($validated['content']);
        $validated['updated_by'] = auth()->id();

        $page->update($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui.');
    }

    /**
     * Destroy is not supported for static pages.
     */
    public function destroy(StaticPage $page): RedirectResponse
    {
        return redirect()->route('admin.pages.index')
            ->with('error', 'Halaman statis tidak bisa dihapus.');
    }
}
