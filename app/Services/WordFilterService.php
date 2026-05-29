<?php

namespace App\Services;

use App\Models\BadWord;
use Illuminate\Support\Facades\Cache;

class WordFilterService
{
    /**
     * Filter bad words from the given text.
     * Censors detected bad words by keeping first and last letter,
     * replacing inner characters with asterisks.
     */
    public function filter(string $text): string
    {
        $badWords = Cache::remember('bad_words_list', 600, function () {
            return BadWord::pluck('word')->toArray();
        });

        if (empty($badWords)) {
            return $text;
        }

        foreach ($badWords as $word) {
            $censored = $this->censorWord($word);
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';
            $text = preg_replace($pattern, $censored, $text);
        }

        return $text;
    }

    /**
     * Censor a single word: keep first and last characters, mask inner chars.
     * Example: BANGSAT -> B*NG**T (each inner character replaced individually)
     */
    private function censorWord(string $word): string
    {
        $len = mb_strlen($word);
        if ($len <= 2) {
            return str_repeat('*', $len);
        }

        $first = mb_substr($word, 0, 1);
        $last  = mb_substr($word, -1, 1);
        $inner = mb_substr($word, 1, $len - 2);

        // Replace vowels with *, keep consonants
        $vowels  = ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'];
        $censored = '';
        for ($i = 0; $i < mb_strlen($inner); $i++) {
            $char = mb_substr($inner, $i, 1);
            $censored .= in_array($char, $vowels) ? '*' : $char;
        }

        return $first . $censored . $last;
    }
}
