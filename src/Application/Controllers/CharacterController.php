<?php

declare(strict_types=1);

namespace Starfall\Application\Controllers;

use Starfall\Core\Http\Request;
use Starfall\Core\Http\Response;
use Starfall\Domain\Character\CharacterService;

final class CharacterController
{
    public function __construct(private readonly CharacterService $characterService)
    {
    }

    public function list(Request $request): Response
    {
        $avatarUuid = trim((string)($request->query['avatar_uuid'] ?? ''));
        if ($avatarUuid === '') {
            return Response::json(['error' => 'avatar_uuid is required'], 422);
        }

        return Response::json([
            'characters' => $this->characterService->listCharacters($avatarUuid),
        ]);
    }
}
