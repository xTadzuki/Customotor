<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class Review
{
    private const STATUS_PENDING  = 'pending';
    private const STATUS_APPROVED = 'approved';
    private const STATUS_REJECTED = 'rejected';

    private const ALLOWED_STATUS = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    // page publique: avis approuvés
    public static function publicApproved(): array
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT r.id, r.rating, r.comment, r.created_at,
                    u.firstname, u.lastname
             FROM reviews r
             JOIN users u ON u.id = r.user_id
             WHERE r.status = :st
             ORDER BY r.id DESC'
        );
        $stmt->execute([':st' => self::STATUS_APPROVED]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    
    public static function allPending(): array
    {
        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT r.id, r.rating, r.comment, r.status, r.created_at,
                    u.firstname, u.lastname, u.email
             FROM reviews r
             JOIN users u ON u.id = r.user_id
             WHERE r.status = :st
             ORDER BY r.id DESC'
        );
        $stmt->execute([':st' => self::STATUS_PENDING]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function latestApproved(int $limit = 3): array
    {
        $limit = max(1, min(50, (int)$limit));

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT r.rating, r.comment, r.created_at,
                    u.firstname, u.lastname
             FROM reviews r
             JOIN users u ON u.id = r.user_id
             WHERE r.status = :st
             ORDER BY r.id DESC
             LIMIT :lim'
        );
        $stmt->bindValue(':st', self::STATUS_APPROVED, PDO::PARAM_STR);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findAdmin(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT r.id, r.user_id, r.rating, r.comment, r.status, r.created_at,
                    u.firstname, u.lastname, u.email
             FROM reviews r
             JOIN users u ON u.id = r.user_id
             WHERE r.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = self::pdo();

        $userId  = (int)($data['user_id'] ?? 0);
        $rating  = (int)($data['rating'] ?? 0);
        $comment = trim((string)($data['comment'] ?? ''));

        if ($userId <= 0) {
            throw new InvalidArgumentException('user_id invalide');
        }
        if ($rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('rating invalide');
        }
        if ($comment === '' || mb_strlen($comment) < 10 || mb_strlen($comment) > 800) {
            throw new InvalidArgumentException('comment invalide');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO reviews (user_id, rating, comment, status)
             VALUES (:user_id, :rating, :comment, :status)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':rating'  => $rating,
            ':comment' => $comment,
            ':status'  => self::STATUS_PENDING,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function updateStatus(int $id, string $status): void
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('id invalide');
        }
        if (!in_array($status, self::ALLOWED_STATUS, true)) {
            throw new InvalidArgumentException('invalid status');
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'UPDATE reviews
             SET status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('review not found');
        }
    }
}