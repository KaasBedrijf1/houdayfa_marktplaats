<?php
declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
require_admin();

$pending = (int)db()->query("SELECT COUNT(*) FROM listings WHERE status = 'pending'")->fetchColumn();

$pageTitle = 'Beheer';
require __DIR__ . '/../includes/header.php';
?>

<section class="product spad mm-inner-page">
<div class="container" style="max-width: 640px;">
    <h1>Beheer</h1>
    <ul class="list-unstyled">
        <li class="mb-3">
            <a class="btn btn-primary" href="<?= e(base_url()) ?>/admin/listings.php">
                Advertenties modereren
                <?php if ($pending > 0): ?>
                    <span class="badge badge-light ml-1"><?= $pending ?> wachtend</span>
                <?php endif; ?>
            </a>
        </li>
        <li><a href="<?= e(base_url()) ?>/index.php">Terug naar de site</a></li>
    </ul>
</div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
