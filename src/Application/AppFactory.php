<?php

declare(strict_types=1);

namespace Starfall\Application;

use Starfall\Application\Controllers\AttachmentController;
use Starfall\Application\Controllers\AuthController;
use Starfall\Application\Controllers\CharacterController;
use Starfall\Application\Controllers\EnvironmentController;
use Starfall\Application\Controllers\ObjectController;
use Starfall\Core\Config;
use Starfall\Core\Http\Router;
use Starfall\Domain\Attachment\AttachmentRepository;
use Starfall\Domain\Attachment\AttachmentService;
use Starfall\Domain\Character\CharacterRepository;
use Starfall\Domain\Character\CharacterService;
use Starfall\Domain\Character\FileMappedLegacyCharacterImporter;
use Starfall\Domain\Environment\EnvironmentZoneRepository;
use Starfall\Domain\ObjectRegistry\ObjectRegistryRepository;
use Starfall\Infrastructure\Database\Database;
use Starfall\Infrastructure\Database\PdoConnectionFactory;

final class AppFactory
{
    public static function makeRouter(Config $config): Router
    {
        $database = new Database(PdoConnectionFactory::make($config->get('database')));

        $legacyConfig = $config->get('legacy_import');
        $characterService = new CharacterService(
            new CharacterRepository($database),
            new FileMappedLegacyCharacterImporter(
                legacyDirectory: (string)$legacyConfig['directory'],
                fieldMap: $legacyConfig['field_map']
            )
        );

        $attachmentService = new AttachmentService(new AttachmentRepository($database));
        $objectRegistryRepository = new ObjectRegistryRepository($database);
        $environmentZoneRepository = new EnvironmentZoneRepository($database);

        $authController = new AuthController(
            characterService: $characterService,
            attachmentService: $attachmentService,
            appSecret: (string)$config->get('app')['secret']
        );
        $characterController = new CharacterController($characterService);
        $objectController = new ObjectController($objectRegistryRepository);
        $environmentController = new EnvironmentController($environmentZoneRepository);
        $attachmentController = new AttachmentController($attachmentService);

        $router = new Router();
        $router->add('POST', '/api/hud/login', $authController->login(...));
        $router->add('GET', '/api/characters', $characterController->list(...));
        $router->add('POST', '/api/objects/register', $objectController->register(...));
        $router->add('POST', '/api/objects/command', $objectController->command(...));
        $router->add('GET', '/api/environment/query', $environmentController->query(...));
        $router->add('POST', '/api/attachments/sync', $attachmentController->sync(...));

        return $router;
    }
}
