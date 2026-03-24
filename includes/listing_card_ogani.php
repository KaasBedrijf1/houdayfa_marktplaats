<?php
declare(strict_types=1);

/**
 * @param array<string, mixed> $opts Optioneel: fresh_badge (bool), extra_class (string)
 */
function render_listing_card_ogani(array $row, string $base, array $opts = []): void
{
    $id = (int)$row['id'];
    $img = !empty($row['image']) ? $base . '/assets/uploads/' . rawurlencode((string)$row['image']) : '';
    $freshBadge = (bool)($opts['fresh_badge'] ?? true);
    $extraClass = trim((string)($opts['extra_class'] ?? ''));

    $created = strtotime((string)($row['created_at']));
    $ageLabel = '';
    $isNew = false;
    if ($created !== false) {
        $days = (int) floor((time() - $created) / 86400);
        if ($days < 0) {
            $days = 0;
        }
        if ($days === 0) {
            $ageLabel = 'Vandaag';
        } elseif ($days === 1) {
            $ageLabel = 'Gisteren';
        } elseif ($days < 7) {
            $ageLabel = $days . ' d. geleden';
        } else {
            $ageLabel = date('d-m-Y', $created);
        }
        $isNew = $days < 4;
    }

    $colClass = 'col-lg-4 col-md-6 col-sm-6 mm-listing-card';
    if ($extraClass !== '') {
        $colClass .= ' ' . $extraClass;
    }
    ?>
    <div class="<?= e($colClass) ?>">
        <article class="product__discount__item mm-card-article">
            <div class="product__discount__item__pic mm-pic">
                <?php if ($freshBadge && $isNew): ?>
                    <span class="mm-card-badge" aria-hidden="true">Nieuw</span>
                <?php endif; ?>
                <a href="<?= e($base) ?>/ad.php?id=<?= $id ?>" tabindex="-1" aria-hidden="true">
                    <?php if ($img !== ''): ?>
                        <img src="<?= e($img) ?>" alt="" loading="lazy">
                    <?php else: ?>
                        <span class="mm-no-photo"><i class="fa fa-image" aria-hidden="true"></i> Geen foto</span>
                    <?php endif; ?>
                </a>
                <ul class="product__item__pic__hover">
                    <li><a href="<?= e($base) ?>/ad.php?id=<?= $id ?>" title="Bekijken"><i class="fa fa-eye"></i></a></li>
                    <li><a href="<?= e($base) ?>/place.php" title="Plaats advertentie"><i class="fa fa-plus"></i></a></li>
                </ul>
            </div>
            <div class="product__discount__item__text">
                <span class="mm-card-cat"><?= e($row['category_name']) ?></span>
                <h5><a href="<?= e($base) ?>/ad.php?id=<?= $id ?>"><?= e($row['title']) ?></a></h5>
                <div class="mm-card-meta">
                    <span class="mm-card-city"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> <?= e($row['city']) ?></span>
                    <?php if ($ageLabel !== ''): ?>
                        <span class="mm-card-age" title="Geplaatst"><?= e($ageLabel) ?></span>
                    <?php endif; ?>
                </div>
                <div class="mm-card-footer">
                    <div class="product__item__price">€ <?= number_format((float)$row['price'], 2, ',', '.') ?></div>
                    <a class="mm-card-cta" href="<?= e($base) ?>/ad.php?id=<?= $id ?>">Bekijken</a>
                </div>
            </div>
        </article>
    </div>
    <?php
}
