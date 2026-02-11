<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Alias pour compat avec controllers 
     */
    public static function getConnection(): PDO
    {
        return self::pdo();
    }

    /**
     * Connexion PDO singleton
     */
    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $host    = (string)($_ENV['DB_HOST'] ?? '127.0.0.1');
        $db      = (string)($_ENV['DB_NAME'] ?? 'customotor');
        $user    = (string)($_ENV['DB_USER'] ?? 'root');
        $pass    = (string)($_ENV['DB_PASS'] ?? '');
        $charset = (string)($_ENV['DB_CHARSET'] ?? 'utf8mb4');

        
        $port   = (string)($_ENV['DB_PORT'] ?? '');
        $socket = (string)($_ENV['DB_SOCKET'] ?? '');

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        if ($port !== '') {
            $dsn .= ";port={$port}";
        }
        if ($socket !== '') {
            $dsn .= ";unix_socket={$socket}";
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        
        if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
            $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES {$charset}";
        }

        try {
            self::$pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            
            http_response_code(500);
            exit('Erreur de connexion à la base de données.');
        }

        return self::$pdo;
    }
}