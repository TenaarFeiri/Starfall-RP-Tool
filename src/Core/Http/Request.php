<?php

declare(strict_types=1);

namespace Starfall\Core\Http;

final class Request
{
    /** @param array<string,mixed> $query @param array<string,mixed> $body @param array<string,mixed> $server */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public readonly array $query,
        public readonly array $body,
        public readonly array $server,
    ) {
    }

    public static function fromGlobals(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        $raw = file_get_contents('php://input') ?: '';
        $decoded = json_decode($raw, true);
        $jsonBody = is_array($decoded) ? $decoded : [];

        return new self(
            method: $method,
            path: $path,
            query: $_GET,
            body: array_merge($_POST, $jsonBody),
            server: $_SERVER,
        );
    }
}
