<?php

declare(strict_types=1);

require_once APP_ROOT . '/app/models/database.php';

class User
{
    private const ROLES = ['client', 'admin'];

    private static function pdo(): PDO
    {
        return method_exists('Database', 'getConnection')
            ? Database::getConnection()
            : Database::pdo();
    }

    public static function findByEmail(string $email): ?array
    {
        $email = strtolower(trim($email));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function findById(int $id): ?array
    {
        if ($id <= 0) return null;

        $pdo = self::pdo();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public static function create(array $data): array
    {
        $pdo = self::pdo();

        $role = (string)($data['role'] ?? 'client');
        if (!in_array($role, self::ROLES, true)) {
            $role = 'client';
        }

        $firstname = trim((string)($data['firstname'] ?? ''));
        $lastname  = trim((string)($data['lastname'] ?? ''));
        $email     = strtolower(trim((string)($data['email'] ?? '')));
        $hash      = (string)($data['password_hash'] ?? '');

        if ($firstname === '' || mb_strlen($firstname) < 2) {
            throw new InvalidArgumentException('firstname invalide');
        }
        if ($lastname === '' || mb_strlen($lastname) < 2) {
            throw new InvalidArgumentException('lastname invalide');
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('email invalide');
        }
        if ($hash === '') {
            throw new InvalidArgumentException('password_hash requis');
        }

        // Empêche doublon email 
        if (self::findByEmail($email)) {
            throw new RuntimeException('email déjà utilisé');
        }

        $stmt = $pdo->prepare(
            'INSERT INTO users (role, firstname, lastname, email, password_hash)
             VALUES (:role, :firstname, :lastname, :email, :password_hash)'
        );

        $stmt->execute([
            ':role'          => $role,
            ':firstname'     => $firstname,
            ':lastname'      => $lastname,
            ':email'         => $email,
            ':password_hash' => $hash,
        ]);

        $id = (int)$pdo->lastInsertId();
        $user = self::findById($id);

        if (!$user) {
            throw new RuntimeException('user creation failed');
        }

        return $user;
    }

    public static function ensureAdminSeed(): void
    {
        
        $env = strtolower((string)($_ENV['APP_ENV'] ?? 'local'));
        if (in_array($env, ['prod', 'production'], true)) {
            return;
        }

        $adminEmail = strtolower((string)($_ENV['ADMIN_EMAIL'] ?? 'admin@customotor.local'));
        $adminPass  = (string)($_ENV['ADMIN_PASSWORD'] ?? 'admin1234');

        
        if (mb_strlen($adminPass) < 8) {
            $adminPass = 'admin1234';
        }

        $existing = self::findByEmail($adminEmail);
        if ($existing) return;

        self::create([
            'role'          => 'admin',
            'firstname'     => 'admin',
            'lastname'      => 'customotor',
            'email'         => $adminEmail,
            'password_hash' => password_hash($adminPass, PASSWORD_DEFAULT),
        ]);
    }
}