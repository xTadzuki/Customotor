<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class ContactRequest
{
    private const ALLOWED_STATUS = ['new', 'in_progress', 'done'];

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

        $userId = $data['user_id'] ?? null;

        $firstname = trim((string)($data['firstname'] ?? ''));
        $lastname  = trim((string)($data['lastname'] ?? ''));
        $email     = strtolower(trim((string)($data['email'] ?? '')));
        $message   = trim((string)($data['message'] ?? ''));

        $phone = trim((string)($data['phone'] ?? ''));
        $brand = trim((string)($data['brand'] ?? ''));
        $model = trim((string)($data['model'] ?? ''));
        $yearRaw = $data['year'] ?? null;

        if ($firstname === '' || $lastname === '') {
            throw new InvalidArgumentException('firstname/lastname required');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('invalid email');
        }
        if ($message === '') {
            throw new InvalidArgumentException('message required');
        }

        $year = null;
        if ($yearRaw !== null && $yearRaw !== '') {
            $year = (int)$yearRaw;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO contact_requests
              (user_id, firstname, lastname, email, phone, brand, model, year, message, status)
             VALUES
              (:user_id, :firstname, :lastname, :email, :phone, :brand, :model, :year, :message, "new")'
        );

        $stmt->execute([
            ':user_id'   => ($userId !== null ? (int)$userId : null),
            ':firstname' => $firstname,
            ':lastname'  => $lastname,
            ':email'     => $email,
            ':phone'     => ($phone !== '' ? $phone : null),
            ':brand'     => ($brand !== '' ? $brand : null),
            ':model'     => ($model !== '' ? $model : null),
            ':year'      => $year,
            ':message'   => $message,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function all(): array
    {
        $pdo = self::pdo();

        return $pdo->query(
            'SELECT id, user_id, firstname, lastname, email, phone, brand, model, year, status, created_at
             FROM contact_requests
             ORDER BY id DESC'
        )->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function allForUser(int $userId): array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT id, user_id, firstname, lastname, email, phone, brand, model, year, status, created_at
             FROM contact_requests
             WHERE user_id = :user_id
             ORDER BY id DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(int $id): ?array
    {
        $pdo = self::pdo();

        $stmt = $pdo->prepare(
            'SELECT id, user_id, firstname, lastname, email, phone, brand, model, year, message, status, created_at
             FROM contact_requests
             WHERE id = :id
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
            'UPDATE contact_requests
             SET status = :status
             WHERE id = :id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id'     => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('request not found');
        }
    }
}