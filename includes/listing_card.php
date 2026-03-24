<?php
declare(strict_types=1);

function render_listing_card(array $row, string $base): void
{
    $id = (int)$row['id'];
    $ts = strtotime($row['created_at'] ?? 'now');
    ?>
<article class="card listing-card" data-listing-id="<?= $id ?>" data-city="<?= e($row['city']) ?>" data-date="<?= (int)$ts ?>">
    <div class="card-top">
        <a href="<?= e($base) ?>/ad.php?id=<?= $id ?>" class="card-media" tabindex="-1" aria-hidden="true">
            <div class="thumb">
                <?php if (!empty($row['image'])): ?>
                    <img src="<?= e($base) ?>/assets/uploads/<?= e($row['image']) ?>" alt="" loading="lazy">
                <?php else: ?>
                    <span class="no-img">Geen foto</span>
                <?php endif; ?>
            </div>
        </a>
        <button type="button" class="fav-btn" data-fav="<?= $id ?>" aria-label="Bewaar advertentie" title="Bewaren">
            <span class="fav-icon" aria-hidden="true">♡</span>
        </button>
    </div>
    <a href="<?= e($base) ?>/ad.php?id=<?= $id ?>" class="card-body-link">
        <div class="card-body">
            <h2><?= e($row['title']) ?></h2>
            <p class="price">€ <?= number_format((float)$row['price'], 2, ',', '.') ?></p>
            <p class="meta muted"><?= e($row['city']) ?> · <?= e($row['category_name']) ?></p>
        </div>
    </a>
</article>
    <?php
}
