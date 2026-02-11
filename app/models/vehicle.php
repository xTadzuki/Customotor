<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class Vehicle
{
    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    private static function normalizePlate(?string $plate): ?string
    {
        $p = trim((string)$plate);
        if ($p === '') return null;

        // normalisation légère
        $p = mb_strtoupper($p);
        $p = preg_replace('/\s+/', ' ', $p); // espaces multiples -> 1
        return $p ?: null;
    }

    public static function allForUser(int $userId): array
    {
        if ($userId <= 0) return [];

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, brand, model, year, plate, created_at
             FROM vehicles
             WHERE user_id = :user_id
             ORDER BY id DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findForUser(int $id, int $userId): ?array
    {
        if ($id <= 0 || $userId <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT id, user_id, brand, model, year, plate, created_at
             FROM vehicles
             WHERE id = :id AND user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute([
            ':id'      => $id,
            ':user_id' => $userId,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $userId = (int)($data['user_id'] ?? 0);
        if ($userId <= 0) {
            throw new InvalidArgumentException('user_id invalide');
        }

        $brand = trim((string)($data['brand'] ?? ''));
        $model = trim((string)($data['model'] ?? ''));

        if ($brand === '' || mb_strlen($brand) < 2 || mb_strlen($brand) > 60) {
            throw new InvalidArgumentException('marque invalide');
        }
        if ($model === '' || mb_strlen($model) < 1 || mb_strlen($model) > 60) {
            throw new InvalidArgumentException('modèle invalide');
        }

        $year = null;
        if (array_key_exists('year', $data) && $data['year'] !== '' && $data['year'] !== null) {
            $y = (int)$data['year'];
            $maxYear = (int)date('Y') + 1;
            if ($y < 1950 || $y > $maxYear) {
                throw new InvalidArgumentException('année invalide');
            }
            $year = $y;
        }

        $plate = null;
        if (array_key_exists('plate', $data)) {
            $plate = self::normalizePlate((string)$data['plate']);
            if ($plate !== null && mb_strlen($plate) > 20) {
                throw new InvalidArgumentException('plaque invalide');
            }
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO vehicles (user_id, brand, model, year, plate)
             VALUES (:user_id, :brand, :model, :year, :plate)'
        );
        $stmt->execute([
            ':user_id' => $userId,
            ':brand'   => $brand,
            ':model'   => $model,
            ':year'    => $year,
            ':plate'   => $plate,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function updateForUser(int $id, int $userId, array $data): void
    {
        if ($id <= 0 || $userId <= 0) {
            throw new InvalidArgumentException('id/user_id invalide');
        }

        $brand = trim((string)($data['brand'] ?? ''));
        $model = trim((string)($data['model'] ?? ''));

        if ($brand === '' || mb_strlen($brand) < 2 || mb_strlen($brand) > 60) {
            throw new InvalidArgumentException('marque invalide');
        }
        if ($model === '' || mb_strlen($model) < 1 || mb_strlen($model) > 60) {
            throw new InvalidArgumentException('modèle invalide');
        }

        $year = null;
        if (array_key_exists('year', $data) && $data['year'] !== '' && $data['year'] !== null) {
            $y = (int)$data['year'];
            $maxYear = (int)date('Y') + 1;
            if ($y < 1950 || $y > $maxYear) {
                throw new InvalidArgumentException('année invalide');
            }
            $year = $y;
        }

        $plate = null;
        if (array_key_exists('plate', $data)) {
            $plate = self::normalizePlate((string)$data['plate']);
            if ($plate !== null && mb_strlen($plate) > 20) {
                throw new InvalidArgumentException('plaque invalide');
            }
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'UPDATE vehicles
             SET brand = :brand, model = :model, year = :year, plate = :plate
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'      => $id,
            ':user_id' => $userId,
            ':brand'   => $brand,
            ':model'   => $model,
            ':year'    => $year,
            ':plate'   => $plate,
        ]);

        if ($stmt->rowCount() === 0) {
            // distingue "introuvable" vs "inchangé"
            $exists = self::findForUser($id, $userId);
            if (!$exists) {
                throw new RuntimeException('véhicule introuvable');
            }
            throw new RuntimeException('véhicule inchangé');
        }
    }

    public static function deleteForUser(int $id, int $userId): void
    {
        if ($id <= 0 || $userId <= 0) {
            throw new InvalidArgumentException('id/user_id invalide');
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'DELETE FROM vehicles
             WHERE id = :id AND user_id = :user_id'
        );
        $stmt->execute([
            ':id'      => $id,
            ':user_id' => $userId,
        ]);

        if ($stmt->rowCount() === 0) {
            throw new RuntimeException('véhicule introuvable');
        }
    }
}