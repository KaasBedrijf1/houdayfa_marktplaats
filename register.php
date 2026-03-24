<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (is_logged_in()) {
    header('Location: ' . base_url() . '/index.php');
    exit;
}

$pageTitle = 'Account aanmaken';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $email = trim((string)($_POST['email'] ?? ''));
    $displayName = trim((string)($_POST['display_name'] ?? ''));
    $pass = (string)($_POST['password'] ?? '');
    $pass2 = (string)($_POST['password2'] ?? '');

    if (!email_acceptable($email)) {
        $errors[] = 'Voer een geldig e-mailadres in.';
    }
    if ($displayName === '' || mb_strlen($displayName) < 2) {
        $errors[] = 'Je weergavenaam is te kort (minimaal 2 tekens).';
    }
    if (mb_strlen($pass) < 8) {
        $errors[] = 'Wachtwoord minimaal 8 tekens.';
    }
    if ($pass !== $pass2) {
        $errors[] = 'Wachtwoorden komen niet overeen.';
    }

    if ($errors === []) {
        $check = db()->prepare('SELECT id FROM users WHERE email = :e LIMIT 1');
        $check->execute(['e' => $email]);
        if ($check->fetch()) {
            $errors[] = 'Dit e-mailadres is al geregistreerd. Log in of gebruik een ander adres.';
        }
    }

    if ($errors === []) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = db()->prepare(
            'INSERT INTO users (email, display_name, password_hash, role) VALUES (:e, :n, :h, \'user\')'
        );
        $stmt->execute(['e' => $email, 'n' => $displayName, 'h' => $hash]);
        $id = (int)db()->lastInsertId();
        login_user_from_row([
            'id' => $id,
            'email' => $email,
            'display_name' => $displayName,
            'role' => 'user',
        ]);
        flash_set('success', 'Welkom! Je bent ingelogd. Je kunt nu een advertentie plaatsen (eerst ter goedkeuring).');
        header('Location: ' . base_url() . '/index.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="product spad mm-inner-page">
<div class="container" style="max-width: 520px;">
    <div class="page-intro">
        <h1>Gratis account</h1>
        <p class="muted">Nodig om te verkopen en om contactgegevens van verkopers te zien. <strong>Bekijken</strong> van advertenties kan zonder account.</p>
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
            Weergavenaam (zoals op advertenties)
            <input type="text" name="display_name" required maxlength="100" autocomplete="nickname" value="<?= e($_POST['display_name'] ?? '') ?>">
        </label>
        <label>
            Wachtwoord (min. 8 tekens)
            <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>
        <label>
            Wachtwoord herhalen
            <input type="password" name="password2" required minlength="8" autocomplete="new-password">
        </label>
        <button type="submit" class="btn btn-primary">Account aanmaken</button>
    </form>
    <p class="mt-3 muted small">Heb je al een account? <a href="<?= e(base_url()) ?>/login.php">Inloggen</a></p>
</div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
