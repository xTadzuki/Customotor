<?php



function auth_user(): ?array
{
    $u = $_SESSION['user'] ?? null;
    return is_array($u) ? $u : null;
}

function auth_check(): bool
{
    return auth_user() !== null;
}

function auth_is_admin(): bool
{
    $user = auth_user();
    return $user !== null && (($user['role'] ?? '') === 'admin');
}

function auth_login(array $user): void
{
    // anti session fixation
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    // régénère un CSRF propre après login
    unset($_SESSION['csrf_token']);

    // on stocke uniquement ce qui est utile
    $_SESSION['user'] = [
        'id'        => (int)($user['id'] ?? 0),
        'firstname' => (string)($user['firstname'] ?? ''),
        'lastname'  => (string)($user['lastname'] ?? ''),
        'email'     => (string)($user['email'] ?? ''),
        'role'      => (string)($user['role'] ?? 'client'),
    ];
}

function auth_logout(): void
{
    // retire l'utilisateur
    unset($_SESSION['user'], $_SESSION['csrf_token']);

    // détruit la session complètement
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
        }

        session_destroy();
    }
}