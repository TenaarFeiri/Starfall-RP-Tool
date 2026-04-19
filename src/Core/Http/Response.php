<?php

declare(strict_types=1);

namespace Starfall\Core\Http;

final class Response
{
    /** @param array<string,mixed> $payload */
    public function __construct(
        private readonly int $statusCode,
        private readonly array $payload,
    ) {
    }

    public static function json(array $payload, int $statusCode = 200): self
    {
        return new self($statusCode, $payload);
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
