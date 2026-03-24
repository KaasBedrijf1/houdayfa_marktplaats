<?php
declare(strict_types=1);

/**
 * Sessie-gebaseerde auth (na config.php / base_url).
 */
function auth_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    return [
        'id' => (int)$_SESSION['user_id'],
        'email' => (string)($_SESSION['user_email'] ?? ''),
        'display_name' => (string)($_SESSION['user_display_name'] ?? ''),
        'role' => (string)($_SESSION['user_role'] ?? 'user'),
    ];
}

function is_logged_in(): bool
{
    return auth_user() !== null;
}

function is_admin(): bool
{
    $u = auth_user();
    return $u !== null && $u['role'] === 'admin';
}

function require_login(): void
{
    if (!is_logged_in()) {
        $uri = $_SERVER['REQUEST_URI'] ?? '/index.php';
        $_SESSION['redirect_after_login'] = $uri;
        header('Location: ' . base_url() . '/login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        exit('Geen toegang tot beheer.');
    }
}

function login_user_from_row(array $row): void
{
    $_SESSION['user_id'] = (int)$row['id'];
    $_SESSION['user_email'] = (string)$row['email'];
    $_SESSION['user_display_name'] = (string)$row['display_name'];
    $_SESSION['user_role'] = (string)$row['role'];
}

function logout_user(): void
{
    unset(
        $_SESSION['user_id'],
        $_SESSION['user_email'],
        $_SESSION['user_display_name'],
        $_SESSION['user_role'],
        $_SESSION['redirect_after_login']
    );
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** @return array{type: string, message: string}|null */
function flash_take(): ?array
{
    if (empty($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        return null;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_verify(): void
{
    $sent = (string)($_POST['_csrf'] ?? '');
    $ok = isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $sent);
    if (!$ok) {
        http_response_code(400);
        exit('Ongeldig of verlopen formulier. Vernieuw de pagina en probeer opnieuw.');
    }
}
