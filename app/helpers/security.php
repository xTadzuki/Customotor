<?php



/**
 * Escape HTML safely.
 */
function e(mixed $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}


function security_headers(bool $isProd = false): void
{
    // clickjacking
    header('X-Frame-Options: DENY');

    // mime sniffing
    header('X-Content-Type-Options: nosniff');

    // referrer
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // legacy / misc hardening
    header('X-Permitted-Cross-Domain-Policies: none');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

    // HSTS (ONLY if HTTPS + prod)
    if ($isProd) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
    }

    // CSP 
    $csp = [
        "default-src 'self'",
        "base-uri 'self'",
        "form-action 'self'",
        "frame-ancestors 'none'",
        "img-src 'self' data:",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
        "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
        "script-src 'self'",
        "connect-src 'self'", // Pour fetch/XHR éventuels
        "object-src 'none'",
    ];

    header('Content-Security-Policy: ' . implode('; ', $csp) . ';');
}

/**
 * Redirect helper (internal paths only).
 */
function redirect(string $path): void
{
    // force chemin interne
    $path = '/' . ltrim(trim($path), '/');

    
    $path = preg_replace('#/+#', '/', $path) ?: '/';

    header('Location: ' . $path, true, 302);
    exit;
}

/**
 * Random token helper.
 */
function random_token(int $bytes = 32): string
{
    return bin2hex(random_bytes($bytes));
}

/**
 * Very small rate limiter (session-based)
 */
function rate_limit(string $key, int $max = 5, int $windowSeconds = 60): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $now = time();
    $bucketKey = 'rl_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);

    if (!isset($_SESSION[$bucketKey]) || !is_array($_SESSION[$bucketKey])) {
        $_SESSION[$bucketKey] = [
            'count' => 0,
            'reset' => $now + $windowSeconds,
        ];
    }

    if ($now > (int)($_SESSION[$bucketKey]['reset'] ?? 0)) {
        $_SESSION[$bucketKey] = [
            'count' => 0,
            'reset' => $now + $windowSeconds,
        ];
    }

    $_SESSION[$bucketKey]['count'] = (int)($_SESSION[$bucketKey]['count'] ?? 0) + 1;

    return $_SESSION[$bucketKey]['count'] <= $max;
}