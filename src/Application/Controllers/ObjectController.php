<?php

declare(strict_types=1);

namespace Starfall\Application\Controllers;

use Starfall\Core\Http\Request;
use Starfall\Core\Http\Response;
use Starfall\Domain\ObjectRegistry\ObjectRegistryRepository;

final class ObjectController
{
    public function __construct(private readonly ObjectRegistryRepository $objectRegistryRepository)
    {
    }

    public function register(Request $request): Response
    {
        $objectUuid = trim((string)($request->body['object_uuid'] ?? ''));
        if ($objectUuid === '') {
            return Response::json(['error' => 'object_uuid is required'], 422);
        }

        $payload = $request->body;
        $payload['object_uuid'] = $objectUuid;
        $this->objectRegistryRepository->register($payload);

        return Response::json(['status' => 'registered', 'object_uuid' => $objectUuid]);
    }

    public function command(Request $request): Response
    {
        $objectUuid = trim((string)($request->body['object_uuid'] ?? ''));
        $command = trim((string)($request->body['command'] ?? ''));
        $payload = is_array($request->body['payload'] ?? null) ? $request->body['payload'] : [];

        if ($objectUuid === '' || $command === '') {
            return Response::json(['error' => 'object_uuid and command are required'], 422);
        }

        $commandId = $this->objectRegistryRepository->enqueueCommand($objectUuid, $command, $payload);

        return Response::json(['status' => 'queued', 'command_id' => $commandId]);
    }
}
