<?php

declare(strict_types=1);

use Starfall\Application\AppFactory;
use Starfall\Core\Autoloader;
use Starfall\Core\Config;

require_once __DIR__ . '/Core/Autoloader.php';

$autoloader = new Autoloader(__DIR__);
$autoloader->register();

$config = new Config(dirname(__DIR__) . '/config');

return AppFactory::makeRouter($config);
