<?php

declare(strict_types=1);

namespace Starfall\Infrastructure\Database;

use Closure;
use PDO;
use PDOStatement;
use RuntimeException;
use Throwable;

final class Database
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /** @param array<string,mixed> $params @return array<string,mixed>|null */
    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->prepareAndExecute($sql, $params);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    /** @param array<string,mixed> $params @return list<array<string,mixed>> */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->prepareAndExecute($sql, $params);
        $rows = $stmt->fetchAll();

        return is_array($rows) ? $rows : [];
    }

    /** @param array<string,mixed> $params */
    public function fetchColumn(string $sql, array $params = [], int $column = 0): mixed
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->fetchColumn($column);
    }

    /** @param array<string,mixed> $params */
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->prepareAndExecute($sql, $params);

        return $stmt->rowCount();
    }

    /** @param array<string,mixed> $params */
    public function insert(string $sql, array $params = []): string
    {
        $this->prepareAndExecute($sql, $params);

        return $this->pdo->lastInsertId();
    }

    public function transaction(Closure $work): mixed
    {
        $this->pdo->beginTransaction();

        try {
            $result = $work($this);
            $this->pdo->commit();

            return $result;
        } catch (Throwable $throwable) {
            $this->pdo->rollBack();
            throw $throwable;
        }
    }

    /** @param array<string,mixed> $params */
    private function prepareAndExecute(string $sql, array $params): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        if ($statement === false) {
            throw new RuntimeException('Failed to prepare SQL statement.');
        }

        foreach ($params as $key => $value) {
            $param = is_string($key) && $key !== '' && $key[0] !== ':' ? ':' . $key : (string)$key;
            $statement->bindValue($param, $value, $this->detectPdoType($value));
        }

        $statement->execute();

        return $statement;
    }

    private function detectPdoType(mixed $value): int
    {
        return match (true) {
            is_int($value) => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            $value === null => PDO::PARAM_NULL,
            default => PDO::PARAM_STR,
        };
    }
}
