<?php

declare(strict_types=1);

namespace Starfall\Core\Http;

use Closure;

final class Router
{
    /** @var array<string,array<string,Closure(Request):Response>> */
    private array $routes = [];

    /** @param Closure(Request):Response $handler */
    public function add(string $method, string $path, Closure $handler): void
    {
        $this->routes[strtoupper($method)][$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $handler = $this->routes[$request->method][$request->path] ?? null;
        if ($handler === null) {
            return Response::json([
                'error' => 'Route not found',
                'method' => $request->method,
                'path' => $request->path,
            ], 404);
        }

        return $handler($request);
    }
}
