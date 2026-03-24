<?php
declare(strict_types=1);

/**
 * Simpele SVG-iconen per categorie-slug (homepage-snelkoppelingen).
 */
function category_icon_markup(string $slug): string
{
    $icons = [
        'autos' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>',
        'elektronica' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20 18c1.1 0 1.99-.9 1.99-2L22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/></svg>',
        'huis-inrichting' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8h5z"/></svg>',
        'kleding' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2l-4 4h8l-4-4zm8 6H4l-2 4v2h20v-2l-2-4zM6 14v8h12v-8H6z"/></svg>',
        'sport-hobby' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M15.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM5 8c-2.8 0-5 2.2-5 5v7h2v-7c0-1.7 1.3-3 3-3h4v10h2V8H5zm14 0h-4v10h2v-3h2c2.2 0 4-1.8 4-4s-1.8-4-4-4z"/></svg>',
        'zakelijk' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/></svg>',
    ];
    $fallback = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 2l2 7h7l-5.5 4 2 7L12 16l-5.5 5 2-7L3 9h7l2-7z"/></svg>';
    return $icons[$slug] ?? $fallback;
}
