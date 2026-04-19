<?php

declare(strict_types=1);

use Starfall\Core\Http\Request;

$router = require dirname(__DIR__) . '/src/bootstrap.php';
$request = Request::fromGlobals();
$response = $router->dispatch($request);
$response->send();
