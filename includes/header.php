<?php
declare(strict_types=1);

require_once __DIR__ . '/mm_brand_logo.php';

$pageTitle = $pageTitle ?? SITE_NAME;
$base = base_url();
$ogani = $base . '/assets/ogani/src';
$isHomepage = $isHomepage ?? false;
$activeSlug = $activeSlug ?? null;
$getQ = isset($_GET['q']) ? (string)$_GET['q'] : '';
$getPlaats = isset($_GET['plaats']) ? (string)$_GET['plaats'] : '';
$getCategory = isset($_GET['category']) ? (string)$_GET['category'] : '';
$getAfstand = isset($_GET['afstand']) ? (string)$_GET['afstand'] : '';
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$loadMmFormsDetail = in_array($currentPage, ['place.php', 'ad.php', 'login.php', 'register.php', 'my-listings.php'], true);
$mmUser = auth_user();

/** Alleen index.php zet dit op true — grote hero elders = te veel scrollen */
$mmShowFullHero = $mmShowFullHero ?? false;
$mmScriptPath = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
$mmInAdmin = str_contains($mmScriptPath, '/admin/');
$mmHeroLayout = $mmShowFullHero ? 'full' : ($mmInAdmin ? 'none' : 'compact');
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(SITE_META_DESCRIPTION) ?>">
    <meta name="theme-color" content="#006233">
    <title><?= e($pageTitle) ?> — <?= e(SITE_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="<?= e($ogani) ?>/css/style.css" type="text/css">
    <link rel="stylesheet" href="<?= e($base) ?>/assets/ogani/mm-overrides.css" type="text/css">
    <?php if ($loadMmFormsDetail): ?>
    <link rel="stylesheet" href="<?= e($base) ?>/assets/ogani/mm-forms-detail.css" type="text/css">
    <?php endif; ?>
</head>
<body class="<?= $isHomepage ? 'is-home' : '' ?> ogani-theme mm-hero-layout-<?= e($mmHeroLayout) ?>">

<a class="skip-link" href="#main-content">Naar inhoud</a>

<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <?php mm_brand_logo_render($base, 'mm-brand-logo--compact'); ?>
    </div>
    <div class="humberger__menu__cart">
        <ul>
            <li><a href="<?= e($base) ?>/index.php"><i class="fa fa-search"></i> <span>Zoeken</span></a></li>
            <li><a href="<?= e($base) ?>/place.php"><i class="fa fa-tag"></i> <span>Verkopen</span></a></li>
        </ul>
        <div class="header__cart__price"><?= e(SITE_NAME) ?></div>
    </div>
    <div class="humberger__menu__widget">
        <div class="header__top__right__auth">
            <?php if ($mmUser): ?>
                <a href="<?= e($base) ?>/my-listings.php"><i class="fa fa-user"></i> <?= e($mmUser['display_name']) ?></a>
                <a href="<?= e($base) ?>/place.php"><i class="fa fa-camera"></i> Verkopen</a>
                <?php if ($mmUser['role'] === 'admin'): ?>
                    <a href="<?= e($base) ?>/admin/index.php"><i class="fa fa-shield-alt"></i> Beheer</a>
                <?php endif; ?>
                <a href="<?= e($base) ?>/logout.php"><i class="fa fa-sign-out-alt"></i> Uitloggen</a>
            <?php else: ?>
                <a href="<?= e($base) ?>/login.php"><i class="fa fa-sign-in-alt"></i> Inloggen</a>
                <a href="<?= e($base) ?>/register.php"><i class="fa fa-user-plus"></i> Registreren</a>
                <a href="<?= e($base) ?>/place.php"><i class="fa fa-camera"></i> Verkopen</a>
            <?php endif; ?>
        </div>
    </div>
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li><a href="<?= e($base) ?>/index.php" class="<?= ($currentPage === 'index.php') ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= e($base) ?>/place.php" class="<?= ($currentPage === 'place.php') ? 'active' : '' ?>">Iets verkopen</a></li>
            <?php foreach ($navCategories as $c): ?>
                <li><a href="<?= e($base) ?>/index.php?category=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-users"></i> Particulieren — geen winkel, gewoon tweedehands</li>
        </ul>
    </div>
</div>

<header class="header">
    <div class="header__top mm-header-topstrip">
        <div class="container">
            <div class="row align-items-center no-gutters mm-header-topstrip__row">
                <div class="col mm-header-topstrip__left">
                    <p class="mm-header-topstrip__tagline mb-0"><?= e(SITE_TAGLINE) ?></p>
                </div>
                <div class="col-auto mm-header-topstrip__right">
                    <div class="header__top__right__auth mm-header-auth mm-header-auth--inline">
                            <?php if ($mmUser): ?>
                                <a href="<?= e($base) ?>/my-listings.php">Mijn advertenties</a>
                                <a href="<?= e($base) ?>/place.php">Verkopen</a>
                                <?php if ($mmUser['role'] === 'admin'): ?>
                                    <a href="<?= e($base) ?>/admin/index.php">Beheer</a>
                                <?php endif; ?>
                                <a href="<?= e($base) ?>/logout.php">Uitloggen</a>
                            <?php else: ?>
                                <a href="<?= e($base) ?>/login.php">Inloggen</a>
                                <a href="<?= e($base) ?>/register.php">Registreren</a>
                                <a href="<?= e($base) ?>/place.php">Verkopen</a>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mm-header-main-wrap">
        <div class="row align-items-center mm-header-main justify-content-between justify-content-lg-start">
            <div class="col-auto mm-header-brand">
                <div class="header__logo">
                    <?php mm_brand_logo_render($base); ?>
                </div>
            </div>
            <div class="col mm-header-menu-wrap d-none d-lg-block">
                <nav class="header__menu mm-header-menu" aria-label="Hoofdmenu">
                    <ul>
                        <li><a href="<?= e($base) ?>/index.php" class="<?= ($currentPage === 'index.php' && $getCategory === '') ? 'active' : '' ?>">Home</a></li>
                        <li><a href="<?= e($base) ?>/place.php" class="<?= ($currentPage === 'place.php') ? 'active' : '' ?>">Verkopen</a></li>
                        <?php
                        $mmNavPrimary = array_slice($navCategories, 0, 5);
                        $mmNavMore = array_slice($navCategories, 5);
                        $mmMoreActive = false;
                        foreach ($mmNavMore as $mc) {
                            if ($activeSlug === $mc['slug'] || $getCategory === $mc['slug']) {
                                $mmMoreActive = true;
                                break;
                            }
                        }
                        foreach ($mmNavPrimary as $c): ?>
                            <li>
                                <a href="<?= e($base) ?>/index.php?category=<?= e($c['slug']) ?>"
                                   class="<?= ($activeSlug === $c['slug'] || $getCategory === $c['slug']) ? 'active' : '' ?>"><?= e($c['name']) ?></a>
                            </li>
                        <?php endforeach; ?>
                        <?php if ($mmNavMore !== []): ?>
                            <li class="mm-nav-more">
                                <span class="mm-nav-more__label <?= $mmMoreActive ? 'is-active' : '' ?>" tabindex="0">Meer <i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                <ul class="mm-nav-more__dropdown">
                                    <?php foreach ($mmNavMore as $c): ?>
                                        <li>
                                            <a href="<?= e($base) ?>/index.php?category=<?= e($c['slug']) ?>"
                                               class="<?= ($activeSlug === $c['slug'] || $getCategory === $c['slug']) ? 'active' : '' ?>"><?= e($c['name']) ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
            <div class="col-auto mm-header-actions text-right">
                <div class="header__cart mm-header-quick" aria-label="Zoeken en verkopen">
                    <ul>
                        <li><a href="<?= e($base) ?>/index.php" title="Zoeken"><i class="fa fa-search"></i></a></li>
                        <li><a href="<?= e($base) ?>/place.php" title="Iets verkopen"><i class="fa fa-tag"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>

<?php if ($mmHeroLayout === 'full'): ?>
<section class="hero hero-normal mm-hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Categorieën</span>
                    </div>
                    <ul>
                        <li><a href="<?= e($base) ?>/index.php">Alle advertenties</a></li>
                        <?php foreach ($navCategories as $category): ?>
                            <li>
                                <a href="<?= e($base) ?>/index.php?category=<?= e($category['slug']) ?>"><?= e($category['name']) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form method="get" action="<?= e($base) ?>/index.php">
                            <?php if ($getCategory !== ''): ?><input type="hidden" name="category" value="<?= e($getCategory) ?>"><?php endif; ?>
                            <?php if ($getPlaats !== ''): ?><input type="hidden" name="plaats" value="<?= e($getPlaats) ?>"><?php endif; ?>
                            <?php if ($getAfstand !== ''): ?><input type="hidden" name="afstand" value="<?= e($getAfstand) ?>"><?php endif; ?>
                            <div class="hero__search__categories">Zoeken</div>
                            <input type="text" name="q" placeholder="Zoek bij andere particulieren…" value="<?= e($getQ) ?>" autocomplete="off">
                            <button type="submit" class="site-btn">ZOEKEN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php elseif ($mmHeroLayout === 'compact'): ?>
<section class="mm-compact-search" aria-label="Snel zoeken">
    <div class="container">
        <div class="mm-compact-search__row">
            <form class="mm-compact-search__form" method="get" action="<?= e($base) ?>/index.php">
                <?php if ($getCategory !== ''): ?><input type="hidden" name="category" value="<?= e($getCategory) ?>"><?php endif; ?>
                <?php if ($getPlaats !== ''): ?><input type="hidden" name="plaats" value="<?= e($getPlaats) ?>"><?php endif; ?>
                <?php if ($getAfstand !== ''): ?><input type="hidden" name="afstand" value="<?= e($getAfstand) ?>"><?php endif; ?>
                <label class="sr-only" for="mm-compact-q">Zoek advertenties</label>
                <input id="mm-compact-q" class="mm-compact-search__input" type="search" name="q" placeholder="Zoek advertenties…" value="<?= e($getQ) ?>" autocomplete="off">
                <button type="submit" class="mm-compact-search__btn site-btn">Zoeken</button>
            </form>
            <a class="mm-compact-search__tohome" href="<?= e($base) ?>/index.php"><i class="fa fa-th-large" aria-hidden="true"></i> Overzicht</a>
        </div>
    </div>
</section>
<?php endif; ?>

<main id="main-content" class="mm-ogani-main" tabindex="-1">
<?php
$mmFlash = flash_take();
if ($mmFlash !== null):
    $mmFlashClass = ($mmFlash['type'] ?? '') === 'error' ? 'alert-danger' : 'alert-success';
    ?>
<div class="container pt-3">
    <div class="alert <?= e($mmFlashClass) ?> alert-dismissible fade show" role="alert">
        <?= e((string)($mmFlash['message'] ?? '')) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Sluiten"><span aria-hidden="true">&times;</span></button>
    </div>
</div>
<?php endif; ?>
