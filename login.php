<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (is_logged_in()) {
    header('Location: ' . base_url() . '/index.php');
    exit;
}

$pageTitle = 'Inloggen';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $email = trim((string)($_POST['email'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');

    if (!email_acceptable($email)) {
        $errors[] = 'Voer een geldig e-mailadres in.';
    }
    if ($pass === '') {
        $errors[] = 'Voer je wachtwoord in.';
    }

    if ($errors === []) {
        $stmt = db()->prepare('SELECT id, email, display_name, role, password_hash FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($pass, (string)$row['password_hash'])) {
            $errors[] = 'Onjuiste combinatie van e-mail en wachtwoord.';
        } else {
            login_user_from_row([
                'id' => (int)$row['id'],
                'email' => (string)$row['email'],
                'display_name' => (string)$row['display_name'],
                'role' => (string)$row['role'],
            ]);
            $next = (string)($_SESSION['redirect_after_login'] ?? '');
            unset($_SESSION['redirect_after_login']);
            if ($next !== '' && str_starts_with($next, '/') && !str_starts_with($next, '//')) {
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                header('Location: ' . $scheme . '://' . $host . $next);
                exit;
            }
            header('Location: ' . base_url() . '/index.php');
            exit;
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="product spad mm-inner-page">
<div class="container" style="max-width: 480px;">
    <div class="page-intro">
        <h1>Inloggen</h1>
        <p class="muted">Log in om te verkopen, je advertenties te zien of contact op te nemen met een verkoper.</p>
    </div>

    <?php if ($errors !== []): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="form-place" method="post" novalidate>
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <label>
            E-mail
            <input type="email" name="email" required maxlength="160" autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>">
        </label>
        <label>
            Wachtwoord
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit" class="btn btn-primary">Inloggen</button>
    </form>
    <p class="mt-3 muted small">Nog geen account? <a href="<?= e(base_url()) ?>/register.php">Gratis registreren</a></p>
</div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
