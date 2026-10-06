<?php

namespace App\Core;

/**
 * Renders PHP templates from the views/ folder.
 */
class View
{
    private const VIEWS_PATH = __DIR__ . '/../../views/';

    /**
     * Render a page template inside a layout.
     * Template and layout share variables, so a page can set $title for the layout.
     */
    public static function render(string $template, array $data = [], string $layout = 'layouts/main'): void
    {
        extract($data);
        ob_start();
        require self::path($template);
        $content = ob_get_clean();

        require self::path($layout);
    }

    /** Render a small piece (e.g. a partial) and return it as a string. */
    public static function capture(string $template, array $data = []): string
    {
        extract($data);
        ob_start();
        require self::path($template);
        return ob_get_clean();
    }

    private static function path(string $template): string
    {
        $file = self::VIEWS_PATH . $template . '.php';
        if (!is_file($file)) {
            throw new \Exception("View not found: $template");
        }
        return $file;
    }
}
