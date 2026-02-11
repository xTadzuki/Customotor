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

    public static function allForProject(int $projectId): array
    {
        if ($projectId <= 0) {
            return [];
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, image_path, alt_text, sort_order, created_at
             FROM project_images
             WHERE project_id = :project_id
             ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([':project_id' => $projectId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function create(array $data): int
    {
        $pdo = self::pdo();

        $projectId = (int)($data['project_id'] ?? 0);
        if ($projectId <= 0) {
            throw new InvalidArgumentException('project_id invalide');
        }

        $imagePath = trim((string)($data['image_path'] ?? ''));
        if ($imagePath === '') {
            throw new InvalidArgumentException('image_path requis');
        }

        $alt = trim((string)($data['alt_text'] ?? ''));
        $alt = ($alt !== '' ? $alt : null);

        $sort = (int)($data['sort_order'] ?? 0);

        $stmt = $pdo->prepare(
            'INSERT INTO project_images (project_id, image_path, alt_text, sort_order)
             VALUES (:project_id, :image_path, :alt_text, :sort_order)'
        );
        $stmt->execute([
            ':project_id' => $projectId,
            ':image_path' => $imagePath,
            ':alt_text'   => $alt,
            ':sort_order' => $sort,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function delete(int $id): void
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare('DELETE FROM project_images WHERE id = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('image introuvable');
        }
    }
}