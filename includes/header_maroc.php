<?php
declare(strict_types=1);
$pageTitle = $pageTitle ?? SITE_NAME;
$base = base_url();
$isHomepage = $isHomepage ?? false;
$activeSlug = $activeSlug ?? null;
$getQ = isset($_GET['q']) ? (string)$_GET['q'] : '';
$getPlaats = isset($_GET['plaats']) ? (string)$_GET['plaats'] : '';
$getCategory = isset($_GET['category']) ? (string)$_GET['category'] : '';
$getAfstand = isset($_GET['afstand']) ? (string)$_GET['afstand'] : '';
require_once __DIR__ . '/category_icons.php';
?>
<!DOCTYPE html>
<html lang="nl" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(SITE_NAME) ?> — lokaal tweedehands kopen en verkopen. Overzichtelijke zoekfuncties en duidelijke categorieën.">
    <meta name="theme-color" content="#084149">
    <meta name="color-scheme" content="light">
    <title><?= e($pageTitle) ?> — <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@500;600;700&family=Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e($base) ?>/assets/css/style.css">
</head>
<body class="<?= $isHomepage ? 'is-home' : '' ?>">
<a class="skip-link" href="#main-content">Naar inhoud</a>
<div class="header-shell" id="header-shell">
<div class="top-bar">
    <div class="wrap top-bar-inner">
        <div class="top-bar-links">
            <button type="button" class="top-bar-link">Help &amp; info</button>
            <button type="button" class="top-bar-link">Voorwaarden</button>
            <button type="button" class="top-bar-link">Veilig handelen</button>
        </div>
        <div class="top-bar-actions">
            <button type="button" class="top-icon-link" title="Berichten (binnenkort)"><span class="visually-hidden">Berichten</span>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            </button>
            <button type="button" class="top-icon-link" title="Meldingen (binnenkort)"><span class="visually-hidden">Meldingen</span>
                <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
            </button>
            <button type="button" class="top-login">Inloggen</button>
        </div>
    </div>
</div>

<header class="site-header">
    <div class="header-ornament" aria-hidden="true"></div>
    <div class="wrap masthead">
        <?php if ($isHomepage): ?>
        <button type="button" class="sidebar-toggle" id="sidebar-open" aria-expanded="false" aria-controls="sidebar-menu" title="Menu">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
            <span class="sidebar-toggle__label">Menu</span>
        </button>
        <?php endif; ?>
        <a class="logo" href="<?= e($base) ?>/index.php"><?php
            $parts = explode(' ', SITE_NAME, 2);
            if (isset($parts[1])) {
                echo e($parts[0]) . ' <span>' . e($parts[1]) . '</span>';
            } else {
                echo e(SITE_NAME);
            }
        ?></a>

        <?php if (!$isHomepage): ?>
        <form class="header-search header-search--compact" method="get" action="<?= e($base) ?>/index.php">
            <input type="search" name="q" placeholder="Waar ben je naar op zoek?" value="<?= e($getQ) ?>">
            <button type="submit">Zoek</button>
        </form>
        <?php endif; ?>

        <div class="masthead-actions">
            <a class="btn btn-cta" href="<?= e($base) ?>/place.php" title="Plaats advertentie">
                <svg class="btn-icon" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <span class="btn-cta__label">Plaats advertentie</span>
            </a>
        </div>
    </div>

    <?php if ($isHomepage): ?>
    <div class="wrap search-panel">
        <form class="search-row" method="get" action="<?= e($base) ?>/index.php">
            <label class="sr-only" for="sq">Zoeken</label>
            <input id="sq" class="search-row__q" type="search" name="q" placeholder="Wat zoek je? Bijv. laptop, bank, fiets…" value="<?= e($getQ) ?>">
            <label class="sr-only" for="scat">Categorie</label>
            <select id="scat" class="search-row__select" name="category">
                <option value="">Alle categorieën</option>
                <?php foreach ($navCategories as $c): ?>
                    <option value="<?= e($c['slug']) ?>" <?= ($getCategory === $c['slug']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <label class="sr-only" for="splaats">Plaats</label>
            <input id="splaats" class="search-row__place" type="text" name="plaats" placeholder="Plaats" value="<?= e($getPlaats) ?>" autocomplete="address-level2">
            <label class="sr-only" for="sdist">Afstand</label>
            <select id="sdist" class="search-row__select search-row__select--narrow" name="afstand">
                <?php
                /* Keys bewust met prefix: anders maakt PHP van '10' een int-key en breekt strict e() */
                $distOpts = ['' => 'Heel Marokko', 'k10' => '+ 10 km', 'k25' => '+ 25 km', 'k50' => '+ 50 km', 'k100' => '+ 100 km'];
                $distVals = ['k10' => '10', 'k25' => '25', 'k50' => '50', 'k100' => '100'];
                foreach ($distOpts as $key => $label):
                    $val = $key === '' ? '' : ($distVals[$key] ?? $key);
                ?>
                    <option value="<?= e($val) ?>" <?= ($getAfstand === (string)$val) ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="search-row__submit">Zoeken</button>
        </form>
    </div>

    <div class="wrap category-icons" aria-label="Snel naar categorie">
        <?php
        $shown = 0;
        foreach ($navCategories as $c):
            if ($shown >= 8) {
                break;
            }
            $shown++;
        ?>
            <a class="cat-icon-tile" href="<?= e($base) ?>/index.php?category=<?= e($c['slug']) ?>">
                <span class="cat-icon-tile__icon"><?= category_icon_markup($c['slug']) ?></span>
                <span class="cat-icon-tile__label"><?= e($c['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="header-zellige" aria-hidden="true"></div>
</header>
</div><!-- .header-shell -->

<?php if (!$isHomepage): ?>
<nav class="category-nav" aria-label="Categorieën">
    <div class="wrap">
        <ul>
            <li><a href="<?= e($base) ?>/index.php">Alle</a></li>
            <?php foreach ($navCategories as $c): ?>
                <li>
                    <a href="<?= e($base) ?>/index.php?category=<?= e($c['slug']) ?>"
                       class="<?= ($activeSlug === $c['slug']) ? 'active' : '' ?>">
                        <?= e($c['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
<?php endif; ?>

<main id="main-content" class="wrap <?= $isHomepage ? 'wrap-wide' : '' ?> main-content<?= $isHomepage ? ' main-content--home' : '' ?>" tabindex="-1">
