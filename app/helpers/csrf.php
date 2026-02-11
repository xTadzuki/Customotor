<?php



function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        // si jamais un fichier oublie session_start()
        session_start();
    }

    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_regenerate(): void
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_verify(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $sessionToken = (string)($_SESSION['csrf_token'] ?? '');

    // token envoyé par form POST
    $postedToken  = (string)($_POST['csrf_token'] ?? '');

    // optionnel: header (AJAX)
    if ($postedToken === '' && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $postedToken = (string)$_SERVER['HTTP_X_CSRF_TOKEN'];
    }

    if ($sessionToken === '' || $postedToken === '' || !hash_equals($sessionToken, $postedToken)) {
        http_response_code(419);
        exit('419');
    }
}