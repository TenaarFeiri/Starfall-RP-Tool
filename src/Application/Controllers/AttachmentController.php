<?php

declare(strict_types=1);

namespace Starfall\Application\Controllers;

use Starfall\Core\Http\Request;
use Starfall\Core\Http\Response;
use Starfall\Domain\Attachment\AttachmentService;

final class AttachmentController
{
    public function __construct(private readonly AttachmentService $attachmentService)
    {
    }

    public function sync(Request $request): Response
    {
        $avatarUuid = trim((string)($request->body['avatar_uuid'] ?? ''));
        $states = is_array($request->body['attachments'] ?? null) ? $request->body['attachments'] : [];

        if ($avatarUuid === '') {
            return Response::json(['error' => 'avatar_uuid is required'], 422);
        }

        return Response::json($this->attachmentService->sync($avatarUuid, $states));
    }
}
