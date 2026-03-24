<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

require_login();

$pageTitle = 'Mijn advertenties';
$u = auth_user();

$stmt = db()->prepare(
    'SELECT l.*, c.name AS category_name
     FROM listings l
     JOIN categories c ON c.id = l.category_id
     WHERE l.user_id = :uid
     ORDER BY l.created_at DESC'
);
$stmt->execute(['uid' => $u['id']]);
$mine = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="product spad mm-inner-page">
<div class="container">
    <div class="page-intro">
        <h1>Mijn advertenties</h1>
        <p class="muted">Nieuwe advertenties zijn eerst <strong>in afwachting van goedkeuring</strong>. Na controle zijn ze zichtbaar voor iedereen.</p>
    </div>

    <?php if ($mine === []): ?>
        <p class="empty">Je hebt nog geen advertenties. <a href="<?= e(base_url()) ?>/place.php">Plaats er een</a>.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered bg-white">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Categorie</th>
                        <th>Prijs</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mine as $row): ?>
                        <?php
                        $st = (string)$row['status'];
                        $label = match ($st) {
                            'approved' => 'Live',
                            'pending' => 'Wacht op goedkeuring',
                            'rejected' => 'Afgewezen',
                            default => $st,
                        };
                        $badge = match ($st) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'secondary',
                        };
                        ?>
                        <tr>
                            <td><?= e($row['title']) ?></td>
                            <td><?= e($row['category_name']) ?></td>
                            <td>€ <?= number_format((float)$row['price'], 2, ',', '.') ?></td>
                            <td><span class="badge badge-<?= e($badge) ?>"><?= e($label) ?></span></td>
                            <td><?= e(date('d-m-Y', strtotime((string)$row['created_at']))) ?></td>
                            <td><a href="<?= e(base_url()) ?>/ad.php?id=<?= (int)$row['id'] ?>">Bekijken</a></td>
                        </tr>
                        <?php if ($st === 'rejected' && !empty($row['reject_reason'])): ?>
                            <tr>
                                <td colspan="6" class="small text-muted"><?= e((string)$row['reject_reason']) ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
