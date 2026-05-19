<?php

namespace App\Services;

class ContentSanitizer
{
    protected \HTMLPurifier $purifier;

    public function __construct()
    {
        $config = \HTMLPurifier_Config::createDefault();

        // Allow common HTML elements for rich text content
        // Only include elements that HTMLPurifier natively supports
        $config->set('HTML.Allowed', implode(',', [
            'p[style|class]',
            'br',
            'strong',
            'b',
            'em',
            'i',
            'u',
            'a[href|target|rel|title]',
            'ul',
            'ol',
            'li',
            'h1[class]',
            'h2[class]',
            'h3[class]',
            'h4[class]',
            'h5[class]',
            'h6[class]',
            'blockquote[class]',
            'pre',
            'code',
            'img[src|alt|title|width|height|class]',
            'table[class]',
            'thead',
            'tbody',
            'tr',
            'th[colspan|rowspan]',
            'td[colspan|rowspan]',
            'div[class]',
            'span[class|style]',
        ]));

        // Allow YouTube/Vimeo embeds via iframe
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');

        // Allow data URIs for images
        $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'data' => true]);

        // Cache directory
        $cacheDir = storage_path('app/purifier');
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $config->set('Cache.SerializerPath', $cacheDir);

        // Add custom elements via HTML definition
        $config->set('HTML.DefinitionID', 'news-portal-custom');
        $config->set('HTML.DefinitionRev', 3);

        if ($def = $config->maybeGetRawHTMLDefinition()) {
            // Add 'loading' attribute to img element
            $def->addAttribute('img', 'loading', 'Enum#lazy,eager');

            // Add figure element (block-level, can contain flow content)
            $def->addElement('figure', 'Block', 'Flow', 'Common', [
                'class' => 'Text',
            ]);

            // Add figcaption element (block-level, can contain flow content)
            $def->addElement('figcaption', 'Block', 'Flow', 'Common', [
                'class' => 'Text',
            ]);

            // Add video element
            $def->addElement('video', 'Block', 'Optional: source | Flow', 'Common', [
                'src' => 'URI',
                'controls' => 'Bool',
                'width' => 'Length',
                'height' => 'Length',
                'class' => 'Text',
            ]);

            // Add source element
            $def->addElement('source', 'Block', 'Empty', 'Common', [
                'src' => 'URI',
                'type' => 'Text',
            ]);
        }

        $this->purifier = new \HTMLPurifier($config);
    }

    /**
     * Sanitize HTML content using HTMLPurifier.
     *
     * @param string|null $content The raw HTML content
     * @return string The sanitized HTML content
     */
    public function sanitize(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        return $this->purifier->purify($content);
    }
}
