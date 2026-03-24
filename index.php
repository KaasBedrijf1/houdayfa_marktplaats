<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

$isHomepage = true;

$q = trim((string)($_GET['q'] ?? ''));
$catSlug = trim((string)($_GET['category'] ?? ''));
$plaats = trim((string)($_GET['plaats'] ?? ''));
// afstand: voor later (geo); nu alleen in URL bewaard voor UX
$activeSlug = $catSlug !== '' ? $catSlug : null;

$minPrice = null;
$maxPrice = null;
if (isset($_GET['min_price']) && $_GET['min_price'] !== '' && is_numeric($_GET['min_price'])) {
    $minPrice = max(0.0, (float)$_GET['min_price']);
}
if (isset($_GET['max_price']) && $_GET['max_price'] !== '' && is_numeric($_GET['max_price'])) {
    $maxPrice = max(0.0, (float)$_GET['max_price']);
}

$hasActiveFilters = $q !== '' || $catSlug !== '' || $plaats !== '' || $minPrice !== null || $maxPrice !== null;

$pageTitle = $hasActiveFilters ? 'Zoekresultaten' : 'Tweedehands van particulieren';

$base = base_url();

$sql = 'SELECT l.id, l.title, l.price, l.city, l.image, l.created_at, c.name AS category_name, c.slug AS category_slug
        FROM listings l
        JOIN categories c ON c.id = l.category_id
        WHERE l.status = \'approved\'';
$params = [];

if ($catSlug !== '') {
    $sql .= ' AND c.slug = :slug';
    $params['slug'] = $catSlug;
}

if ($q !== '') {
    $sql .= ' AND (l.title LIKE :q OR l.description LIKE :q)';
    $params['q'] = '%' . $q . '%';
}

if ($plaats !== '') {
    $sql .= ' AND l.city LIKE :plaats';
    $params['plaats'] = '%' . $plaats . '%';
}

if ($minPrice !== null) {
    $sql .= ' AND l.price >= :pmin';
    $params['pmin'] = $minPrice;
}
if ($maxPrice !== null) {
    $sql .= ' AND l.price <= :pmax';
    $params['pmax'] = $maxPrice;
}

if ($hasActiveFilters) {
    $sql .= ' ORDER BY l.created_at DESC LIMIT 96';
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $listings = $stmt->fetchAll();
    $byCategory = [];
    $byCity = [];
    $featuredListings = [];
} else {
    $sqlAll = 'SELECT l.id, l.title, l.price, l.city, l.image, l.created_at, c.name AS category_name, c.slug AS category_slug
        FROM listings l
        JOIN categories c ON c.id = l.category_id
        WHERE l.status = \'approved\'
        ORDER BY l.created_at DESC LIMIT 220';
    $all = db()->query($sqlAll)->fetchAll();
    $listings = $all;

    $byCategory = [];
    foreach ($all as $row) {
        $slug = $row['category_slug'];
        if (!isset($byCategory[$slug])) {
            $byCategory[$slug] = [];
        }
        $byCategory[$slug][] = $row;
    }

    $byCity = [];
    foreach ($all as $row) {
        $city = $row['city'] !== '' ? $row['city'] : 'Onbekend';
        if (!isset($byCity[$city])) {
            $byCity[$city] = [];
        }
        $byCity[$city][] = $row;
    }
    ksort($byCity, SORT_NATURAL | SORT_FLAG_CASE);

    $featuredListings = array_slice($all, 0, min(8, count($all)));
}

$filterCategoryName = '';
if ($catSlug !== '') {
    foreach ($navCategories as $c) {
        if ($c['slug'] === $catSlug) {
            $filterCategoryName = $c['name'];
            break;
        }
    }
}

$totalListings = (int)db()->query("SELECT COUNT(*) FROM listings WHERE status = 'approved'")->fetchColumn();
$newThisWeek = (int)db()->query(
    "SELECT COUNT(*) FROM listings WHERE status = 'approved' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
)->fetchColumn();
$countBySlug = [];
$cntStmt = db()->query(
    "SELECT c.slug, COUNT(l.id) AS n FROM categories c
     LEFT JOIN listings l ON l.category_id = c.id AND l.status = 'approved'
     GROUP BY c.id, c.slug"
);
foreach ($cntStmt->fetchAll() as $cr) {
    $countBySlug[$cr['slug']] = (int)$cr['n'];
}

$priceBounds = db()->query("SELECT COALESCE(MIN(price), 0) AS mn, COALESCE(MAX(price), 0) AS mx FROM listings WHERE status = 'approved'")->fetch();
$pMn = (float)($priceBounds['mn'] ?? 0);
$pMx = (float)($priceBounds['mx'] ?? 0);
if ($pMx <= $pMn) {
    $pMx = $pMn + 1;
}

$mmQuery = static function (array $overrides) use ($base, $q, $catSlug, $plaats, $minPrice, $maxPrice): string {
    $p = [];
    if ($q !== '') {
        $p['q'] = $q;
    }
    if ($catSlug !== '') {
        $p['category'] = $catSlug;
    }
    if ($plaats !== '') {
        $p['plaats'] = $plaats;
    }
    if ($minPrice !== null) {
        $p['min_price'] = $minPrice;
    }
    if ($maxPrice !== null) {
        $p['max_price'] = $maxPrice;
    }
    foreach ($overrides as $k => $v) {
        if (array_key_exists($k, $overrides) && $v === null) {
            unset($p[$k]);
            continue;
        }
        if ($v === '') {
            unset($p[$k]);
            continue;
        }
        $p[$k] = $v;
    }
    $qs = http_build_query($p);
    return $base . '/index.php' . ($qs !== '' ? '?' . $qs : '');
};

require_once __DIR__ . '/includes/listing_card_ogani.php';

$mmShowFullHero = true;

require __DIR__ . '/includes/header.php';
?>

<section class="product spad">
    <div class="container">
        <?php if ($hasActiveFilters): ?>
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Zoekresultaten</h2>
                    </div>
                    <div class="mm-results-toolbar">
                        <p class="mm-results-count mb-0">
                            <strong><?= count($listings) ?></strong> advertent<?= count($listings) === 1 ? 'ie' : 'ies' ?> gevonden
                            <?php if ($totalListings > 0): ?>
                                <span class="text-muted font-weight-normal"> · <?= $totalListings ?> totaal live</span>
                            <?php endif; ?>
                        </p>
                        <a href="<?= e($base) ?>/index.php" class="mm-text-link">Wis filters</a>
                    </div>
                    <p class="text-muted">
                        <?php if ($catSlug !== ''): ?>
                            <span>Categorie: <strong><?= e($filterCategoryName !== '' ? $filterCategoryName : $catSlug) ?></strong></span>
                        <?php endif; ?>
                        <?php if ($q !== ''): ?>
                            <span><?= $catSlug !== '' ? ' · ' : '' ?>Zoekterm: <strong><?= e($q) ?></strong></span>
                        <?php endif; ?>
                        <?php if ($plaats !== ''): ?>
                            <span><?= ($catSlug !== '' || $q !== '') ? ' · ' : '' ?>Plaats: <strong><?= e($plaats) ?></strong></span>
                        <?php endif; ?>
                        <?php if ($minPrice !== null || $maxPrice !== null): ?>
                            <span><?= ($catSlug !== '' || $q !== '' || $plaats !== '') ? ' · ' : '' ?>Prijs:
                                <strong><?php
                                if ($minPrice !== null && $maxPrice !== null) {
                                    echo '€ ' . number_format($minPrice, 0, ',', '.') . ' – € ' . number_format($maxPrice, 0, ',', '.');
                                } elseif ($maxPrice !== null) {
                                    echo 'tot € ' . number_format($maxPrice, 0, ',', '.');
                                } else {
                                    echo 'vanaf € ' . number_format((float)$minPrice, 0, ',', '.');
                                }
                                ?></strong>
                            </span>
                        <?php endif; ?>
                        <span> — <a href="<?= e($base) ?>/index.php">Alles tonen</a></span>
                    </p>
                </div>
            </div>
            <div class="row mm-product-grid">
                <?php if (count($listings) === 0): ?>
                    <div class="col-12">
                        <div class="mm-empty-state mm-empty-state--wide">
                            <div class="mm-empty-state__icon" aria-hidden="true"><i class="fa fa-search"></i></div>
                            <p>Geen advertenties met deze filters. <a href="<?= e($base) ?>/index.php">Bekijk alles</a> of <a href="<?= e($base) ?>/place.php">plaats zelf een advertentie</a>.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($listings as $row): ?>
                        <?php render_listing_card_ogani($row, $base); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php else: ?>

            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Categorieën</h4>
                            <ul>
                                <li><a href="<?= e($base) ?>/index.php">Alle tweedehands advertenties</a></li>
                                <?php foreach ($navCategories as $category): ?>
                                    <li>
                                        <a href="<?= e($base) ?>/index.php?category=<?= e($category['slug']) ?>"><?= e($category['name']) ?></a>
                                        <span class="text-muted"> (<?= (int)($countBySlug[$category['slug']] ?? 0) ?>)</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="sidebar__item">
                            <h4>Prijs (€)</h4>
                            <p class="small text-muted mb-2">Van €<?= number_format($pMn, 0, ',', '.') ?> tot €<?= number_format($pMx, 0, ',', '.') ?> in de database.</p>
                            <form method="get" action="<?= e($base) ?>/index.php">
                                <?php if ($q !== ''): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
                                <?php if ($catSlug !== ''): ?><input type="hidden" name="category" value="<?= e($catSlug) ?>"><?php endif; ?>
                                <?php if ($plaats !== ''): ?><input type="hidden" name="plaats" value="<?= e($plaats) ?>"><?php endif; ?>
                                <div class="form-group mb-2">
                                    <label class="small d-block">Minimum</label>
                                    <input class="form-control form-control-sm" type="number" name="min_price" step="1" min="0"
                                           value="<?= $minPrice !== null ? e((string)(int)$minPrice) : e((string)(int)$pMn) ?>">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="small d-block">Maximum</label>
                                    <input class="form-control form-control-sm" type="number" name="max_price" step="1" min="0"
                                           value="<?= $maxPrice !== null ? e((string)(int)$maxPrice) : e((string)(int)$pMx) ?>">
                                </div>
                                <button type="submit" class="site-btn w-100">Filter</button>
                            </form>
                        </div>
                        <div class="sidebar__item">
                            <h4>Aanbod</h4>
                            <p class="mb-1"><strong><?= $totalListings ?></strong> advertenties van verkopers</p>
                            <p class="mb-0 text-muted small"><?= $newThisWeek ?> nieuw door particulieren (7 dagen)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="mm-trust-strip">
                        <div class="mm-trust-strip__inner">
                            <div class="mm-trust-item">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <div>
                                    <strong>Gratis rondkijken</strong>
                                    <small>Geen account nodig om te zoeken en advertenties te bekijken.</small>
                                </div>
                            </div>
                            <div class="mm-trust-item">
                                <i class="fa fa-shield-alt" aria-hidden="true"></i>
                                <div>
                                    <strong>Gecontroleerd aanbod</strong>
                                    <small>Nieuwe advertenties worden eerst gemodereerd.</small>
                                </div>
                            </div>
                            <div class="mm-trust-item">
                                <i class="fa fa-handshake" aria-hidden="true"></i>
                                <div>
                                    <strong>Particulieren</strong>
                                    <small>Contact met verkoper na inloggen — rechtstreeks en transparant.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mm-cat-chips-wrap">
                        <span class="mm-cat-chips-label">Snel naar</span>
                        <?php foreach (array_slice($navCategories, 0, 8) as $chipCat): ?>
                            <a class="mm-cat-chip" href="<?= e($base) ?>/index.php?category=<?= e($chipCat['slug']) ?>"><?= e($chipCat['name']) ?></a>
                        <?php endforeach; ?>
                    </div>

                    <div class="mm-hero-banner mm-hero-banner--store">
                        <div class="mm-hero-banner__copy">
                            <h3><span class="mm-hero-title-m">M</span>arket — jouw marktplaats</h3>
                            <p class="mm-hero-lead">Ontdek wat particulieren in de buurt verkopen. <strong>Verkopen</strong> of <strong>contact opnemen</strong> doe je met een gratis account; wij houden het platform schoon met moderatie.</p>
                            <div class="mm-hero-actions">
                                <a href="<?= e($base) ?>/place.php" class="site-btn">Iets verkopen</a>
                                <a href="#aanbod" class="mm-btn-ghost">Bekijk aanbod</a>
                                <a href="<?= e($base) ?>/register.php" class="mm-btn-ghost">Account aanmaken</a>
                            </div>
                        </div>
                        <div class="mm-hero-stats" aria-label="Aanbod in cijfers">
                            <div class="mm-hero-stat">
                                <span class="mm-hero-stat__n"><?= $totalListings ?></span>
                                <span class="mm-hero-stat__l">Advertenties live</span>
                            </div>
                            <div class="mm-hero-stat">
                                <span class="mm-hero-stat__n"><?= $newThisWeek ?></span>
                                <span class="mm-hero-stat__l">Nieuw (7 dagen)</span>
                            </div>
                            <div class="mm-hero-stat">
                                <span class="mm-hero-stat__n"><?= count($navCategories) ?></span>
                                <span class="mm-hero-stat__l">Categorieën</span>
                            </div>
                        </div>
                    </div>

                    <?php if (count($listings) === 0): ?>
                        <div class="mm-empty-state">
                            <div class="mm-empty-state__icon" aria-hidden="true"><i class="fa fa-store"></i></div>
                            <p>Nog geen advertenties. <a href="<?= e($base) ?>/place.php">Wees de eerste verkoper</a> — na goedkeuring verschijn je hier.</p>
                        </div>
                    <?php else: ?>
                        <?php if (count($featuredListings) > 0): ?>
                            <section class="mm-featured-block mb-5" id="aanbod">
                                <div class="mm-section-head">
                                    <div>
                                        <h2>Nieuw binnen</h2>
                                        <p class="mm-section-sub">Recent toegevoegd en goedgekeurd</p>
                                    </div>
                                    <?php
                                    $exploreCat = $navCategories[0]['slug'] ?? '';
                                    $exploreUrl = $base . '/index.php' . ($exploreCat !== '' ? '?category=' . rawurlencode($exploreCat) : '');
                                    ?>
                                    <a class="mm-text-link" href="<?= e($exploreUrl) ?>">Ontdek categorieën →</a>
                                </div>
                                <div class="row mm-product-grid">
                                    <?php foreach ($featuredListings as $row): ?>
                                        <?php render_listing_card_ogani($row, $base, ['fresh_badge' => true]); ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        <?php else: ?>
                            <div id="aanbod" class="mb-4"></div>
                        <?php endif; ?>
                        <?php foreach ($navCategories as $cat): ?>
                            <?php
                            $slug = $cat['slug'];
                            if (empty($byCategory[$slug])) {
                                continue;
                            }
                            $chunk = $byCategory[$slug];
                            ?>
                            <div class="product__discount mb-5">
                                <div class="section-title product__discount__title">
                                    <h2><?= e($cat['name']) ?></h2>
                                    <a href="<?= e($base) ?>/index.php?category=<?= e($slug) ?>">Alles in <?= e($cat['name']) ?> →</a>
                                </div>
                                <div class="row">
                                    <div class="product__discount__slider owl-carousel">
                                        <?php foreach ($chunk as $row): ?>
                                            <?php render_listing_card_ogani($row, $base); ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <hr class="my-5">
                        <div class="section-title">
                            <h2>Op plaats</h2>
                        </div>
                        <?php foreach ($byCity as $city => $rows): ?>
                            <div class="mb-5">
                                <div class="mm-city-head">
                                    <h4><?= e($city) ?></h4>
                                    <a href="<?= e($base) ?>/index.php?plaats=<?= e(urlencode($city)) ?>">Filter op deze plaats</a>
                                </div>
                                <div class="row mm-product-grid">
                                    <?php foreach ($rows as $row): ?>
                                        <?php render_listing_card_ogani($row, $base); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
