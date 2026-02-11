<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class Project
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

        return $pdo->query(
            'SELECT id, title, subtitle, description, created_at
             FROM projects
             ORDER BY id DESC'
        )->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(int $id): ?array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT id, title, subtitle, description, created_at
             FROM projects
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

        $title = trim((string)($data['title'] ?? ''));
        $subtitle = trim((string)($data['subtitle'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));

        if ($title === '' || mb_strlen($title) < 2) {
            throw new InvalidArgumentException('invalid title');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO projects (title, subtitle, description)
             VALUES (:title, :subtitle, :description)'
        );
        $stmt->execute([
            ':title'       => $title,
            ':subtitle'    => ($subtitle !== '' ? $subtitle : null),
            ':description' => ($description !== '' ? $description : null),
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = self::pdo();

        $title = trim((string)($data['title'] ?? ''));
        $subtitle = trim((string)($data['subtitle'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));

        if ($title === '' || mb_strlen($title) < 2) {
            throw new InvalidArgumentException('invalid title');
        }

        $stmt = $pdo->prepare(
            'UPDATE projects
             SET title = :title,
                 subtitle = :subtitle,
                 description = :description
             WHERE id = :id'
        );
        $stmt->execute([
            ':id'          => $id,
            ':title'       => $title,
            ':subtitle'    => ($subtitle !== '' ? $subtitle : null),
            ':description' => ($description !== '' ? $description : null),
        ]);

        if ($stmt->rowCount() === 0) {
            
        }
    }

    public static function delete(int $id): void
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare('DELETE FROM projects WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            
        }
    }

    public static function findWithImages(int $id): ?array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT id, title, subtitle, description, created_at
             FROM projects
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$project) {
            return null;
        }

        $stmt2 = $pdo->prepare(
            'SELECT id, image_path, alt_text, sort_order
             FROM project_images
             WHERE project_id = :project_id
             ORDER BY sort_order ASC, id ASC'
        );
        $stmt2->execute([':project_id' => $id]);

        $project['images'] = $stmt2->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $project;
    }
}