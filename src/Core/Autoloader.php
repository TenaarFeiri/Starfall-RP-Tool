<?php

declare(strict_types=1);

namespace Starfall\Core;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

final class Autoloader
{
    private const NAMESPACE_TOKEN_TYPES = [T_STRING, T_NAME_QUALIFIED, T_NS_SEPARATOR];

    /** @var array<string,string> */
    private array $classMap = [];

    public function __construct(
        private readonly string $baseDirectory,
        private readonly string $rootNamespace = 'Starfall\\'
    ) {
    }

    public function register(): void
    {
        $this->buildClassMap();
        spl_autoload_register([$this, 'loadClass']);
    }

    public function loadClass(string $class): void
    {
        if (!str_starts_with($class, $this->rootNamespace)) {
            return;
        }

        $file = $this->classMap[$class] ?? null;
        if ($file === null) {
            return;
        }

        require_once $file;
    }

    private function buildClassMap(): void
    {
        if (!is_dir($this->baseDirectory)) {
            throw new RuntimeException('Autoloader base directory is missing: ' . $this->baseDirectory);
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->baseDirectory));
        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
                continue;
            }

            $classes = $this->extractClasses((string)$fileInfo->getPathname());
            foreach ($classes as $class) {
                $this->classMap[$class] = (string)$fileInfo->getPathname();
            }
        }
    }

    /** @return string[] */
    private function extractClasses(string $filePath): array
    {
        $source = file_get_contents($filePath) ?: '';
        $tokens = token_get_all($source);

        $namespace = '';
        $results = [];
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];
            if (!is_array($token)) {
                continue;
            }

            if ($token[0] === T_NAMESPACE) {
                $namespace = '';
                for ($j = $i + 1; $j < $count; $j++) {
                    $next = $tokens[$j];
                    if (is_string($next) && ($next === ';' || $next === '{')) {
                        break;
                    }
                    if (is_array($next) && in_array($next[0], self::NAMESPACE_TOKEN_TYPES, true)) {
                        $namespace .= $next[1];
                    }
                }
            }

            if (in_array($token[0], [T_CLASS, T_INTERFACE, T_TRAIT, T_ENUM], true)) {
                $nameToken = $tokens[$i + 2] ?? null;
                if (is_array($nameToken) && $nameToken[0] === T_STRING) {
                    $results[] = $namespace . '\\' . $nameToken[1];
                }
            }
        }

        return $results;
    }
}
