<?php

declare(strict_types = 1)
;
// BASE_URL
if (!defined('BASE_URL')) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    define('BASE_URL', $base === '' ? '' : $base);
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
/**
 * Racine de l’app
 */
define('APP_ROOT', dirname(__DIR__));

/**
 * Config globale
 */
$config = require APP_ROOT . '/app/config/config.php';
$GLOBALS['config'] = $config; // ✅ permet aux helpers (mailer, etc.) d’y accéder

/**
 * Session
 */
$sessionName = $config['security']['session_name'] ?? 'customotor_session';
session_name($sessionName);

// cookies de session plus sûrs
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax',
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    require_once APP_ROOT . '/app/helpers/security.php';    security_headers(false); // true en prod https
}

/**
 * CSRF : génération du token 
 */
if (!empty($config['security']['csrf'])) {
    require_once APP_ROOT . '/app/helpers/csrf.php';
    csrf_token();
}

/**
 * Routes + Router
 */
$routes = require APP_ROOT . '/app/config/routes.php';
require APP_ROOT . '/app/core/router.php';

/**
 * Dispatch
 */
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

$path = parse_url($uri, PHP_URL_PATH) ?: '/';

/**
 * Base path auto:
 */
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$base = rtrim($base, '/');

// cas particulier si dirname() renvoie "/" ou "."
if ($base === '/' || $base === '.') {
    $base = '';
}

// retire le préfixe s'il est au début
if ($base !== '' && strpos($path, $base) === 0) {
    $path = substr($path, strlen($base)) ?: '/';
}

// normalise
$path = '/' . trim($path, '/');
$path = $path === '//' ? '/' : $path;

$router = new Router($routes);
$router->dispatch($method, $path);