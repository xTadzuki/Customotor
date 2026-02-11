<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class Appointment
{
    private const ALLOWED_STATUS = ['pending', 'confirmed', 'cancelled'];

    private static function pdo(): PDO
    {
        
        if (method_exists('Database', 'getConnection')) {
            return Database::getConnection();
        }
        return Database::pdo();
    }

    public static function create(array $data): int
    {
        $pdo = self::pdo();

        $userId      = (int)($data['user_id'] ?? 0);
        $requestedAt = trim((string)($data['requested_at'] ?? ''));
        $note        = trim((string)($data['note'] ?? ''));

        if ($userId <= 0) {
            throw new InvalidArgumentException('invalid user_id');
        }
        if ($requestedAt === '') {
            throw new InvalidArgumentException('requested_at required');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO appointments (user_id, requested_at, note, status)
             VALUES (:user_id, :requested_at, :note, "pending")'
        );

        $stmt->execute([
            ':user_id'      => $userId,
            ':requested_at' => $requestedAt,
            ':note'         => ($note !== '' ? $note : null),
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function allForUser(int $userId): array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT id, requested_at, note, status, created_at
             FROM appointments
             WHERE user_id = :user_id
             ORDER BY requested_at DESC, id DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function allAdmin(): array
    {
        $pdo = self::pdo();

        $stmt = $pdo->query(
            'SELECT a.id, a.requested_at, a.note, a.status, a.created_at,
                    u.firstname, u.lastname, u.email
             FROM appointments a
             JOIN users u ON u.id = a.user_id
             ORDER BY a.requested_at DESC, a.id DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findAdmin(int $id): ?array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT a.id, a.user_id, a.requested_at, a.note, a.status, a.created_at,
                    u.firstname, u.lastname, u.email
             FROM appointments a
             JOIN users u ON u.id = a.user_id
             WHERE a.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, self::ALLOWED_STATUS, true)) {
            throw new InvalidArgumentException('invalid status');
        }

        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'UPDATE appointments
             SET status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('appointment not found');
        }
    }
}