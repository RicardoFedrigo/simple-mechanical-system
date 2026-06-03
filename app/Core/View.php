<?php

namespace App\Core;

class View
{
    public static function render(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        $file = __DIR__ . '/../Views/' . $template . '.php';
       
        if (!file_exists($file)) {
            throw new \RuntimeException('View not found: ' . $template);
        }

        ob_start();
        require $file;
        return ob_get_clean();
    }
}
