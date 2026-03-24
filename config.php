<?php
/**
 * Pas aan voor jouw XAMPP MySQL
 */
declare(strict_types=1);

/** Weergavenaam — header toont ★ + Market met rode M */
const SITE_NAME = 'Market';

/**
 * Gebruikt in meta, headers, uitlegteksten.
 */
const SITE_TAGLINE = 'Van particulier tot particulier';

/** Meta description (SEO / social) */
const SITE_META_DESCRIPTION = 'Market: gratis rondkijken, veilig gemodereerd tweedehandsaanbod. Account om te verkopen of contact op te nemen.';

/**
 * Database: lokaal (XAMPP) via defaults; op Render/via Docker zet je env DB_HOST, DB_NAME, DB_USER, DB_PASS.
 */
function mm_env(string $key, string $default): string
{
    $v = getenv($key);
    return $v !== false ? $v : $default;
}

define('DB_HOST', mm_env('DB_HOST', '127.0.0.1'));
define('DB_NAME', mm_env('DB_NAME', 'marktmaroc'));
define('DB_USER', mm_env('DB_USER', 'root'));
define('DB_PASS', mm_env('DB_PASS', ''));
define('DB_CHARSET', mm_env('DB_CHARSET', 'utf8mb4'));

/** Basis-URL van de site (zonder trailing slash), bv. http://localhost/houdayfa_marktplaats — DB: marktmaroc */
function base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $script = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $dir = str_replace('\\', '/', dirname($script));
    if ($dir === '/' || $dir === '\\') {
        $dir = '';
    }
    return rtrim($scheme . '://' . $host . $dir, '/');
}

/** HTML-escape; accepteert ook int/float (bijv. PHP-arraykeys '10' → int) */
function e(string|int|float|null $s): string
{
    if ($s === null) {
        return '';
    }
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * E-mail voor login/registratie.
 * PHP's FILTER_VALIDATE_EMAIL wijst o.a. admin@localhost af — die staat wel in het schema als demo-admin.
 */
function email_acceptable(string $email): bool
{
    $email = trim($email);
    if ($email === '' || strlen($email) > 254) {
        return false;
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
        return true;
    }
    // Lokale / test-adressen zoals user@localhost
    return (bool) preg_match('/^[^\s@]+@[^\s@]+$/u', $email);
}
