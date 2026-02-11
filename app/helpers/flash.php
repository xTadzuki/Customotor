<?php



function flash_set(string $key, string $message): void
{
    // on suppose que la session est déjà démarrée par public/index.php
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    // nettoyage si vide
    if (empty($_SESSION['flash'])) {
        unset($_SESSION['flash']);
    }

    return $msg;
}