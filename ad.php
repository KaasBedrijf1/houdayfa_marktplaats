<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id < 1) {
    http_response_code(404);
    $pageTitle = 'Niet gevonden';
    require __DIR__ . '/includes/header.php';
    echo '<section class="product spad"><div class="container"><p class="empty">Ongeldige advertentie.</p></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$stmt = db()->prepare(
    'SELECT l.*, c.name AS category_name, c.slug AS category_slug
     FROM listings l
     JOIN categories c ON c.id = l.category_id
     WHERE l.id = :id LIMIT 1'
);
$stmt->execute(['id' => $id]);
$ad = $stmt->fetch();

if (!$ad) {
    http_response_code(404);
    $pageTitle = 'Niet gevonden';
    require __DIR__ . '/includes/header.php';
    echo '<section class="product spad"><div class="container"><p class="empty">Deze advertentie bestaat niet (meer).</p></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$viewer = auth_user();
$status = (string)($ad['status'] ?? 'approved');
$ownerId = isset($ad['user_id']) ? (int)$ad['user_id'] : 0;
$isOwner = $viewer !== null && $ownerId > 0 && $ownerId === (int)$viewer['id'];
$isApproved = ($status === 'approved');
$canView = $isApproved || $isOwner || is_admin();

if (!$canView) {
    http_response_code(404);
    $pageTitle = 'Niet gevonden';
    require __DIR__ . '/includes/header.php';
    echo '<section class="product spad"><div class="container"><p class="empty">Deze advertentie is (nog) niet openbaar.</p></div></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$showContact = $isApproved && is_logged_in();

$pageTitle = $ad['title'];
$activeSlug = $ad['category_slug'];

require __DIR__ . '/includes/header.php';
?>
<section class="product spad mm-inner-page">
<div class="container">
<article class="ad-detail">
    <?php if ($status === 'pending'): ?>
        <div class="alert alert-warning"><?= $isOwner ? 'Deze advertentie wacht op goedkeuring. Alleen jij en beheerders zien deze pagina zo.' : 'Concept / wacht op goedkeuring.' ?></div>
    <?php elseif ($status === 'rejected'): ?>
        <div class="alert alert-danger">
            Afgewezen<?= $isOwner || is_admin() ? '' : '.' ?>
            <?php if (($isOwner || is_admin()) && !empty($ad['reject_reason'])): ?>
                <br><strong>Reden:</strong> <?= e((string)$ad['reject_reason']) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <nav class="breadcrumb muted">
        <a href="<?= e(base_url()) ?>/index.php">Home</a>
        ·
        <a href="<?= e(base_url()) ?>/index.php?category=<?= e($ad['category_slug']) ?>"><?= e($ad['category_name']) ?></a>
    </nav>
    <div class="ad-layout">
        <div class="ad-gallery">
            <?php if (!empty($ad['image'])): ?>
                <img src="<?= e(base_url()) ?>/assets/uploads/<?= e($ad['image']) ?>" alt="<?= e($ad['title']) ?>">
            <?php else: ?>
                <div class="no-img large">Geen foto bij deze advertentie</div>
            <?php endif; ?>
        </div>
        <div class="ad-info">
            <h1><?= e($ad['title']) ?></h1>
            <p class="ad-price">€ <?= number_format((float)$ad['price'], 2, ',', '.') ?></p>
            <ul class="facts">
                <li><strong>Locatie</strong> <?= e($ad['city']) ?></li>
                <li><strong>Categorie</strong> <?= e($ad['category_name']) ?></li>
                <li><strong>Geplaatst</strong> <?= e(date('d-m-Y H:i', strtotime($ad['created_at']))) ?></li>
            </ul>
            <div class="seller-box">
                <h2>Verkoper</h2>
                <p><strong><?= e($ad['seller_name']) ?></strong> biedt dit tweedehands aan.</p>
                <?php if ($showContact): ?>
                    <p class="muted small">Neem rechtstreeks contact op. E-mail:
                        <a href="mailto:<?= e($ad['seller_email']) ?>"><?= e($ad['seller_email']) ?></a>
                    </p>
                <?php elseif ($isApproved): ?>
                    <p class="muted small">
                        <a href="<?= e(base_url()) ?>/login.php">Log in</a> of
                        <a href="<?= e(base_url()) ?>/register.php">maak een gratis account</a>
                        om het e-mailadres van de verkoper te zien en contact op te nemen.
                    </p>
                <?php else: ?>
                    <p class="muted small">Contactgegevens zijn zichtbaar zodra de advertentie is goedgekeurd.</p>
                <?php endif; ?>
            </div>
            <a class="btn btn-secondary" href="<?= e(base_url()) ?>/index.php?category=<?= e($ad['category_slug']) ?>">Meer in <?= e($ad['category_name']) ?></a>
        </div>
    </div>
    <section class="ad-description">
        <h2>Omschrijving</h2>
        <div class="prose"><?= nl2br(e($ad['description'])) ?></div>
    </section>
</article>
</div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
