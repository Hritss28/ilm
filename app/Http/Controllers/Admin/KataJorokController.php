<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BadWord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class KataJorokController extends Controller
{
    /**
     * Display the list of bad words.
     */
    public function index(): View
    {
        $badWords = BadWord::orderBy('word')->get();
        return view('admin.kata-jorok', compact('badWords'));
    }

    /**
     * Store a new bad word.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'word' => ['required', 'string', 'max:100', 'unique:bad_words,word'],
        ], [
            'word.required' => 'Kata tidak boleh kosong.',
            'word.unique'   => 'Kata tersebut sudah ada dalam daftar.',
        ]);

        BadWord::create(['word' => strtolower(trim($request->input('word')))]);

        // Clear the cache so the filter picks up the new word immediately
        Cache::forget('bad_words_list');

        return back()->with('success', 'Kata berhasil ditambahkan.');
    }

    /**
     * Remove a bad word from the list.
     */
    public function destroy(BadWord $badWord): RedirectResponse
    {
        $badWord->delete();

        // Clear the cache
        Cache::forget('bad_words_list');

        return back()->with('success', 'Kata berhasil dihapus.');
    }
}
