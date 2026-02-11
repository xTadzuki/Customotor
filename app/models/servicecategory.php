<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class ServiceCategory
{
    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    public static function all(): array
    {
        $pdo = self::pdo();

        $stmt = $pdo->query(
            'SELECT id, name, sort_order
             FROM service_categories
             ORDER BY sort_order ASC, name ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, name, sort_order
             FROM service_categories
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = self::pdo();

        $name = trim((string)($data['name'] ?? ''));
        $sortOrder = (int)($data['sort_order'] ?? 0);

        if ($name === '' || mb_strlen($name) < 2) {
            throw new InvalidArgumentException('nom de catégorie invalide');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO service_categories (name, sort_order)
             VALUES (:name, :sort_order)'
        );
        $stmt->execute([
            ':name' => $name,
            ':sort_order' => $sortOrder,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('id invalide');
        }

        $pdo = self::pdo();

        $name = trim((string)($data['name'] ?? ''));
        $sortOrder = (int)($data['sort_order'] ?? 0);

        if ($name === '' || mb_strlen($name) < 2) {
            throw new InvalidArgumentException('nom de catégorie invalide');
        }

        $stmt = $pdo->prepare(
            'UPDATE service_categories
             SET name = :name,
                 sort_order = :sort_order
             WHERE id = :id'
        );
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':sort_order' => $sortOrder,
        ]);

        
    }

    public static function delete(int $id): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('id invalide');
        }

        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'DELETE FROM service_categories
             WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('catégorie introuvable');
        }
    }
}