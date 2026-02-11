<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class Service
{
    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    public static function allActiveWithCategory(): array
    {
        $pdo = self::pdo();
        $stmt = $pdo->query(
            'SELECT s.id, s.name, s.description, s.price_from, s.sort_order,
                    c.name AS category_name
             FROM services s
             LEFT JOIN service_categories c ON c.id = s.category_id
             WHERE s.is_active = 1
             ORDER BY c.sort_order ASC, s.sort_order ASC, s.name ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function groupedByCategory(): array
    {
        $pdo = self::pdo();

        $rows = $pdo->query(
            'SELECT 
                s.id,
                s.name,
                s.description,
                s.price_from,
                s.sort_order,
                c.id   AS category_id,
                c.name AS category_name
             FROM services s
             LEFT JOIN service_categories c ON c.id = s.category_id
             WHERE s.is_active = 1
             ORDER BY c.sort_order ASC, s.sort_order ASC, s.name ASC'
        )->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $grouped = [];

        foreach ($rows as $row) {
            $catId = (int)($row['category_id'] ?? 0);
            $catName = (string)($row['category_name'] ?? 'autres services');

            if (!isset($grouped[$catId])) {
                $grouped[$catId] = [
                    'category' => $catName,
                    'services' => [],
                ];
            }

            $grouped[$catId]['services'][] = $row;
        }

        return $grouped;
    }

    public static function find(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT s.*,
                    c.name AS category_name
             FROM services s
             LEFT JOIN service_categories c ON c.id = s.category_id
             WHERE s.id = :id AND s.is_active = 1
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = self::pdo();

        $categoryId = $data['category_id'] ?? null;
        $categoryId = ($categoryId === '' || $categoryId === null) ? null : (int)$categoryId;

        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $description = $description !== '' ? $description : null;

        $priceFromRaw = $data['price_from'] ?? null;
        $priceFrom = null;
        if ($priceFromRaw !== null && $priceFromRaw !== '') {
            $priceFrom = (float)$priceFromRaw;
            if ($priceFrom < 0) $priceFrom = 0;
        }

        $isActive = (int)($data['is_active'] ?? 1);
        $isActive = ($isActive === 0) ? 0 : 1;

        $sortOrder = (int)($data['sort_order'] ?? 0);
        if ($sortOrder < 0) $sortOrder = 0;

        if ($name === '' || mb_strlen($name) < 2) {
            throw new InvalidArgumentException('name invalide');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO services (category_id, name, description, price_from, is_active, sort_order)
             VALUES (:category_id, :name, :description, :price_from, :is_active, :sort_order)'
        );
        $stmt->execute([
            ':category_id' => $categoryId,
            ':name'        => $name,
            ':description' => $description,
            ':price_from'  => $priceFrom,
            ':is_active'   => $isActive,
            ':sort_order'  => $sortOrder,
        ]);

        return (int)$pdo->lastInsertId();
    }

    // =========================
    // ADMIN
    // =========================

    public static function allForAdminWithCategory(): array
    {
        $pdo = self::pdo();

        $stmt = $pdo->query(
            'SELECT s.id, s.name, s.description, s.price_from, s.is_active, s.sort_order,
                    s.category_id,
                    c.name AS category_name
             FROM services s
             LEFT JOIN service_categories c ON c.id = s.category_id
             ORDER BY c.sort_order ASC, s.sort_order ASC, s.name ASC'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findForAdmin(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare(
            'SELECT s.*,
                    c.name AS category_name
             FROM services s
             LEFT JOIN service_categories c ON c.id = s.category_id
             WHERE s.id = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function update(int $id, array $data): void
    {
        if ($id <= 0) throw new InvalidArgumentException('id invalide');

        $pdo = self::pdo();

        $categoryId = $data['category_id'] ?? null;
        $categoryId = ($categoryId === '' || $categoryId === null) ? null : (int)$categoryId;

        $name = trim((string)($data['name'] ?? ''));
        $description = trim((string)($data['description'] ?? ''));
        $description = $description !== '' ? $description : null;

        $priceFromRaw = $data['price_from'] ?? null;
        $priceFrom = null;
        if ($priceFromRaw !== null && $priceFromRaw !== '') {
            $priceFrom = (float)$priceFromRaw;
            if ($priceFrom < 0) $priceFrom = 0;
        }

        $isActive = (int)($data['is_active'] ?? 1);
        $isActive = ($isActive === 0) ? 0 : 1;

        $sortOrder = (int)($data['sort_order'] ?? 0);
        if ($sortOrder < 0) $sortOrder = 0;

        if ($name === '' || mb_strlen($name) < 2) {
            throw new InvalidArgumentException('name invalide');
        }

        $stmt = $pdo->prepare(
            'UPDATE services
             SET category_id = :category_id,
                 name = :name,
                 description = :description,
                 price_from = :price_from,
                 is_active = :is_active,
                 sort_order = :sort_order
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute([
            ':category_id' => $categoryId,
            ':name'        => $name,
            ':description' => $description,
            ':price_from'  => $priceFrom,
            ':is_active'   => $isActive,
            ':sort_order'  => $sortOrder,
            ':id'          => $id,
        ]);
    }

    public static function softDelete(int $id): void
    {
        if ($id <= 0) return;

        $pdo = self::pdo();
        $stmt = $pdo->prepare('UPDATE services SET is_active = 0 WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
    }
    public static function countActive(): int
{
    $pdo = self::pdo();
    $stmt = $pdo->query('SELECT COUNT(*) FROM services WHERE is_active = 1');
    return (int)$stmt->fetchColumn();
}

}
