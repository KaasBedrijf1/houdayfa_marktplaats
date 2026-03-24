<?php
declare(strict_types=1);

require __DIR__ . '/../includes/bootstrap.php';
require_admin();

$admin = auth_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = (string)($_POST['action'] ?? '');
    $id = isset($_POST['listing_id']) ? (int)$_POST['listing_id'] : 0;
    if ($id < 1) {
        flash_set('error', 'Ongeldige advertentie.');
        header('Location: ' . base_url() . '/admin/listings.php');
        exit;
    }

    if ($action === 'approve') {
        $stmt = db()->prepare(
            "UPDATE listings SET status = 'approved', moderated_at = NOW(), moderated_by = :aid, reject_reason = NULL WHERE id = :id AND status = 'pending'"
        );
        $stmt->execute(['aid' => $admin['id'], 'id' => $id]);
        flash_set('success', 'Advertentie goedgekeurd.');
    } elseif ($action === 'reject') {
        $reason = trim((string)($_POST['reject_reason'] ?? ''));
        if ($reason === '') {
            $reason = 'Niet voldaan aan de richtlijnen (geen details).';
        }
        $stmt = db()->prepare(
            "UPDATE listings SET status = 'rejected', moderated_at = NOW(), moderated_by = :aid, reject_reason = :r WHERE id = :id AND status = 'pending'"
        );
        $stmt->execute(['aid' => $admin['id'], 'r' => mb_substr($reason, 0, 500), 'id' => $id]);
        flash_set('success', 'Advertentie afgewezen.');
    }
    header('Location: ' . base_url() . '/admin/listings.php');
    exit;
}

$pageTitle = 'Moderatie — advertenties';

$pendingStmt = db()->query(
    "SELECT l.*, c.name AS category_name, u.email AS owner_email
     FROM listings l
     JOIN categories c ON c.id = l.category_id
     LEFT JOIN users u ON u.id = l.user_id
     WHERE l.status = 'pending'
     ORDER BY l.created_at ASC"
);
$queue = $pendingStmt->fetchAll();

$recentStmt = db()->query(
    "SELECT l.id, l.title, l.status, l.created_at, l.moderated_at
     FROM listings l
     WHERE l.status IN ('approved','rejected') AND l.moderated_at IS NOT NULL
     ORDER BY l.moderated_at DESC
     LIMIT 15"
);
$recent = $recentStmt->fetchAll();

require __DIR__ . '/../includes/header.php';
$base = base_url();
?>

<section class="product spad mm-inner-page">
<div class="container">
    <nav class="breadcrumb muted mb-3">
        <a href="<?= e($base) ?>/admin/index.php">Beheer</a> · Moderatie
    </nav>
    <h1>Wachtrij (goedkeuring)</h1>
    <p class="text-muted">Keur advertenties goed voordat ze op de homepage verschijnen. Zo houden we oplichting en rommel buiten de deur.</p>

    <?php if ($queue === []): ?>
        <p class="alert alert-info">Geen advertenties in afwachting.</p>
    <?php else: ?>
        <?php foreach ($queue as $row): ?>
            <article class="card mb-4">
                <div class="card-body">
                    <h2 class="h5"><?= e($row['title']) ?></h2>
                    <p class="small text-muted mb-2">
                        <?= e($row['category_name']) ?> · € <?= number_format((float)$row['price'], 2, ',', '.') ?> · <?= e($row['city']) ?>
                        · <?= e(date('d-m-Y H:i', strtotime((string)$row['created_at']))) ?>
                    </p>
                    <?php if (!empty($row['image'])): ?>
                        <p><img src="<?= e($base) ?>/assets/uploads/<?= e($row['image']) ?>" alt="" style="max-height:160px;width:auto;"></p>
                    <?php endif; ?>
                    <div class="prose small mb-3"><?= nl2br(e(mb_substr((string)$row['description'], 0, 800))) ?><?= mb_strlen((string)$row['description']) > 800 ? '…' : '' ?></div>
                    <p class="small"><strong>Verkoper (op advertentie):</strong> <?= e($row['seller_name']) ?> · <?= e($row['seller_email']) ?></p>
                    <?php if (!empty($row['owner_email'])): ?>
                        <p class="small text-muted">Account: <?= e($row['owner_email']) ?></p>
                    <?php endif; ?>
                    <div class="d-flex flex-wrap align-items-start">
                        <form method="post" class="d-inline mr-2 mb-2">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="listing_id" value="<?= (int)$row['id'] ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="btn btn-success btn-sm">Goedkeuren</button>
                        </form>
                        <form method="post" class="d-inline-flex align-items-start flex-wrap mb-2">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <input type="hidden" name="listing_id" value="<?= (int)$row['id'] ?>">
                            <input type="hidden" name="action" value="reject">
                            <input type="text" name="reject_reason" class="form-control form-control-sm mr-2 mb-2" style="min-width:220px;" placeholder="Reden (optioneel)">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Afwijzen</button>
                        </form>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <hr class="my-5">
    <h2 class="h4">Recent gemodereerd</h2>
    <?php if ($recent === []): ?>
        <p class="text-muted small">Nog geen geschiedenis.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($recent as $r): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><?= e($r['title']) ?> <span class="badge badge-<?= $r['status'] === 'approved' ? 'success' : 'secondary' ?>"><?= e($r['status']) ?></span></span>
                    <a class="small" href="<?= e($base) ?>/ad.php?id=<?= (int)$r['id'] ?>">Bekijk</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
