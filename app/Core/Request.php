<?php

namespace App\Core;

class Request
{
    public function uri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    /**
     * Return all query string parameters from the current GET request.
     *
     * @return array<string, mixed>
     */
    public function query(): array
    {
        return $_GET ?? [];
    }

    /**
     * Alias for query() to match common request helper patterns.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->query();
    }
}
