<?php
declare(strict_types=1);
/**
 * @var string $base
 * @var array $navCategories
 * @var string|null $activeSlug
 * @var string $q
 * @var string $plaats
 * @var float|null $minPrice
 * @var float|null $maxPrice
 * @var array<string,int> $countBySlug
 * @var int $totalListings
 * @var int $newThisWeek
 * @var Closure $mmQuery fn(array $overrides): string — null waarden verwijderen query-keys
 */
?>
<button type="button" class="sidebar-backdrop" id="sidebar-backdrop" hidden aria-label="Menu sluiten"></button>

<aside class="sidebar sidebar-pro" id="sidebar-menu" aria-label="Menu & filters">
    <div class="sidebar-pro__inner">
        <div class="sidebar-pro__head">
            <div class="sidebar-brand">
                <span class="sidebar-brand__title"><?= e(SITE_NAME) ?></span>
                <span class="sidebar-brand__sub">Menu &amp; snelkiezers</span>
            </div>
            <button type="button" class="sidebar-close" id="sidebar-close" aria-label="Menu sluiten">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
            </button>
        </div>

        <div class="sidebar-statbar">
            <div class="sidebar-stat">
                <strong><?= (int)$totalListings ?></strong>
                <span>advertenties</span>
            </div>
            <div class="sidebar-stat sidebar-stat--accent">
                <strong>+<?= (int)$newThisWeek ?></strong>
                <span>deze week</span>
            </div>
        </div>

        <a class="sidebar-cta" href="<?= e($base) ?>/place.php">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Plaats advertentie
        </a>

        <nav class="sidebar-section" aria-labelledby="nav-quick-cities">
            <h3 class="sidebar-heading" id="nav-quick-cities">Populaire steden</h3>
            <ul class="sidebar-chips">
                <?php
                $cities = ['Casablanca', 'Rabat', 'Marrakech', 'Tanger', 'Fès', 'Amsterdam', 'Rotterdam', 'Utrecht'];
                foreach ($cities as $city):
                    $active = ($plaats !== '' && strcasecmp(trim($plaats), $city) === 0);
                ?>
                    <li>
                        <a href="<?= e($mmQuery(['plaats' => $city])) ?>"
                           class="sidebar-chip<?= $active ? ' is-active' : '' ?>"><?= e($city) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <nav class="sidebar-section" aria-labelledby="nav-price">
            <h3 class="sidebar-heading" id="nav-price">Prijsklasse</h3>
            <ul class="sidebar-links">
                <?php
                $ranges = [
                    ['label' => 'Tot € 25', 'min' => null, 'max' => 25.0],
                    ['label' => '€ 25 – € 100', 'min' => 25.0, 'max' => 100.0],
                    ['label' => '€ 100 – € 500', 'min' => 100.0, 'max' => 500.0],
                    ['label' => '€ 500 – € 2.000', 'min' => 500.0, 'max' => 2000.0],
                    ['label' => 'Boven € 2.000', 'min' => 2000.0, 'max' => null],
                ];
                foreach ($ranges as $r):
                    $over = ['min_price' => $r['min'], 'max_price' => $r['max']];
                    $href = $mmQuery($over);
                    $minMatch = ($r['min'] === null && $minPrice === null)
                        || ($r['min'] !== null && $minPrice !== null && abs($r['min'] - $minPrice) < 0.02);
                    $maxMatch = ($r['max'] === null && $maxPrice === null)
                        || ($r['max'] !== null && $maxPrice !== null && abs($r['max'] - $maxPrice) < 0.02);
                    $isRowActive = $minMatch && $maxMatch;
                ?>
                    <li>
                        <a href="<?= e($href) ?>" class="sidebar-link-row<?= $isRowActive ? ' is-active' : '' ?>">
                            <?= e($r['label']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="<?= e($mmQuery(['min_price' => null, 'max_price' => null])) ?>" class="sidebar-link-row sidebar-link-row--muted">Alle prijzen</a>
                </li>
            </ul>
        </nav>

        <nav class="sidebar-section" aria-labelledby="nav-cats">
            <h3 class="sidebar-heading" id="nav-cats">Categorieën</h3>
            <ul class="sidebar-cats sidebar-cats--pro">
                <li>
                    <a href="<?= e($mmQuery(['category' => null])) ?>" class="sidebar-cat<?= ($activeSlug === null || $activeSlug === '') ? ' is-active' : '' ?>">
                        <span class="sidebar-cat__name">Alles tonen</span>
                        <span class="sidebar-cat__badge"><?= (int)$totalListings ?></span>
                    </a>
                </li>
                <?php foreach ($navCategories as $c):
                    $slug = $c['slug'];
                    $cnt = $countBySlug[$slug] ?? 0;
                    $isAct = ($activeSlug === $slug);
                ?>
                    <li>
                        <a href="<?= e($mmQuery(['category' => $slug])) ?>" class="sidebar-cat<?= $isAct ? ' is-active' : '' ?>">
                            <span class="sidebar-cat__icon" aria-hidden="true"><?= category_icon_markup($slug) ?></span>
                            <span class="sidebar-cat__name"><?= e($c['name']) ?></span>
                            <span class="sidebar-cat__badge"><?= (int)$cnt ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <section class="sidebar-section sidebar-section--tips">
            <h3 class="sidebar-heading">Pro-tip</h3>
            <ul class="sidebar-tips">
                <li>
                    <span class="sidebar-tip-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    </span>
                    <div><strong>Combineer filters</strong><span class="muted small">Stad + prijs + categorie voor scherpere resultaten.</span></div>
                </li>
                <li>
                    <span class="sidebar-tip-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                    </span>
                    <div><strong>Veilig afspreken</strong><span class="muted small">Kies liever een openbare plek voor de eerste ontmoeting.</span></div>
                </li>
                <li>
                    <span class="sidebar-tip-ico" aria-hidden="true">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </span>
                    <div><strong>Bewaar favorieten</strong><span class="muted small">Klik op het hart — opgeslagen op dit apparaat.</span></div>
                </li>
            </ul>
        </section>
    </div>
</aside>
