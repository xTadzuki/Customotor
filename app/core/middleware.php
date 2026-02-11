<?php



require_once APP_ROOT . '/app/helpers/auth.php';

final class Middleware
{
    public static function requireAuth(): void
    {
        if (!auth_check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();

        if (!auth_is_admin()) {
            http_response_code(403);
            exit('403 - accès refusé');
        }
    }
}