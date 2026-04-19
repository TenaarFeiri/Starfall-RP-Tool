<?php

declare(strict_types=1);

require __DIR__ . '/../src/Core/Autoloader.php';

$autoloader = new \Starfall\Core\Autoloader(__DIR__ . '/../src');
$autoloader->register();

$router = new \Starfall\Core\Http\Router();
$router->add('GET', '/health', fn (\Starfall\Core\Http\Request $request): \Starfall\Core\Http\Response => \Starfall\Core\Http\Response::json([
    'ok' => true,
    'method' => $request->method,
]));

$request = new \Starfall\Core\Http\Request('GET', '/health', [], [], []);
$response = $router->dispatch($request);

if (!$response instanceof \Starfall\Core\Http\Response) {
    fwrite(STDERR, "Router dispatch failed\n");
    exit(1);
}

echo "Smoke test passed\n";
