<?php

declare(strict_types=1);

namespace Starfall\Application\Controllers;

use Starfall\Core\Http\Request;
use Starfall\Core\Http\Response;
use Starfall\Domain\Attachment\AttachmentService;
use Starfall\Domain\Auth\HudSessionRepository;
use Starfall\Domain\Character\CharacterService;

final class AuthController
{
    public function __construct(
        private readonly CharacterService $characterService,
        private readonly AttachmentService $attachmentService,
        private readonly HudSessionRepository $hudSessionRepository,
        private readonly string $appSecret,
    ) {
    }

    public function login(Request $request): Response
    {
        $avatarUuid = trim((string)($request->body['avatar_uuid'] ?? ''));
        if ($avatarUuid === '') {
            return Response::json(['error' => 'avatar_uuid is required'], 422);
        }

        $states = is_array($request->body['attachments'] ?? null) ? $request->body['attachments'] : [];
        $attachmentSync = $this->attachmentService->sync($avatarUuid, $states);
        $this->attachmentService->cleanupStalePending();

        $character = $this->characterService->getOrCreateLastCharacter($avatarUuid);
        $nonce = bin2hex(random_bytes(16));
        $token = hash_hmac('sha256', $avatarUuid . '|' . $nonce, $this->appSecret);
        $this->hudSessionRepository->create($avatarUuid, hash('sha256', $token));

        return Response::json([
            'token' => $token,
            'avatar_uuid' => $avatarUuid,
            'character' => $character,
            'attachment_sync' => $attachmentSync,
        ]);
    }
}
