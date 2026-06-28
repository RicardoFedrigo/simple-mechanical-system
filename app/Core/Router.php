<?php

namespace App\Core;

class Router
{
    /** @var array<int, array{method: string, path: string, handler: callable|string, middleware: array<int, string>}> */
    private array $routes = [];

    public function get(string $path, callable|string|array $handler, array $middleware = []): void
    {
        $this->routes[] = ['method' => 'GET', 'path' => $path, 'handler' => $handler, 'middleware' => $middleware];
    }

    public function post(string $path, callable|string|array $handler, array $middleware = []): void
    {
        $this->routes[] = ['method' => 'POST', 'path' => $path, 'handler' => $handler, 'middleware' => $middleware];
    }

    public function dispatch(Request $request): mixed
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $request->method() && $this->matchPath($route['path'], $request->uri())) {
                foreach ($route['middleware'] as $middlewareClass) {
                    $middleware = new $middlewareClass();
                    if (!$middleware->handle($request)) {
                        return null;
                    }
                }

                return $this->callHandler($route['handler'], $request);
            }
        }

        http_response_code(404);
        echo View::render('404', ['title' => 'Page Not Found']);
        return null;
    }

    private function matchPath(string $pattern, string $uri): bool
    {
        $pattern = preg_quote($pattern, '/');
        $pattern = str_replace('\*', '.*', $pattern);
        return (bool) preg_match('#^' . $pattern . '$#', $uri);
    }

    private function callHandler(callable|string|array $handler, Request $request): mixed
    {
        if (is_string($handler)) {
            [$controller, $method] = explode('@', $handler, 2);
            $controllerInstance = new $controller();
            return $controllerInstance->$method($request);
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;
            return $controller->$method($request);
        }

        return $handler($request);
    }
}
