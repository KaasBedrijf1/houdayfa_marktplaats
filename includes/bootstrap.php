<?php
declare(strict_types=1);

/* Publiek bekijken; sessie voor inloggen en moderatie. */
if (session_status() === PHP_SESSION_NONE) {
    // Stabiele cookie over hele site (XAMPP / submap); voorkomt “ingelogd maar direct weer uit”
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/auth.php';

function load_categories(): array
{
    $stmt = db()->query('SELECT id, name, slug FROM categories ORDER BY name');
    return $stmt->fetchAll();
}

$navCategories = load_categories();
