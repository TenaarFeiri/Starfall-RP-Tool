<?php

declare(strict_types=1);

namespace Starfall\Application\Controllers;

use Starfall\Core\Http\Request;
use Starfall\Core\Http\Response;
use Starfall\Domain\Environment\EnvironmentZoneRepository;

final class EnvironmentController
{
    public function __construct(private readonly EnvironmentZoneRepository $environmentZoneRepository)
    {
    }

    public function query(Request $request): Response
    {
        $x = (float)($request->query['x'] ?? 0);
        $y = (float)($request->query['y'] ?? 0);
        $z = (float)($request->query['z'] ?? 0);

        return Response::json([
            'zones' => $this->environmentZoneRepository->findByCoordinate($x, $y, $z),
        ]);
    }
}
