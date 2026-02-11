<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class ProjectImage
{
    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    public static function forProject(int $projectId): array
    {
        if ($projectId <= 0) return [];

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, project_id, image_path, alt_text, sort_order, created_at
             FROM project_images
             WHERE project_id = :pid
             ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([':pid' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, project_id, image_path, alt_text, sort_order, created_at
             FROM project_images
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(int $projectId, string $path, ?string $alt, int $sortOrder = 0): int
    {
        if ($projectId <= 0) throw new InvalidArgumentException('project_id invalide');
        if ($path === '') throw new InvalidArgumentException('image_path invalide');

        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'INSERT INTO project_images (project_id, image_path, alt_text, sort_order)
             VALUES (:pid, :path, :alt, :sort)'
        );
        $stmt->execute([
            ':pid'  => $projectId,
            ':path' => $path,
            ':alt'  => $alt,
            ':sort' => max(0, $sortOrder),
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function delete(int $id): void
    {
        if ($id <= 0) return;

        $pdo = self::pdo();
        $stmt = $pdo->prepare('DELETE FROM project_images WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
    }
}
