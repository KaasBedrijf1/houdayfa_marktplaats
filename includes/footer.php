</main>
<?php
require_once __DIR__ . '/mm_brand_logo.php';
$baseFt = base_url();
$oganiFt = $baseFt . '/assets/ogani/src';
?>
<footer class="footer spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <div class="footer__about__logo">
                        <?php mm_brand_logo_render($baseFt, 'mm-brand-logo--footer'); ?>
                    </div>
                    <ul>
                        <li><?= e(SITE_TAGLINE) ?></li>
                        <li>Particulieren verkopen en kopen tweedehands — zonder tussenhandel.</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                <div class="footer__widget">
                    <h6>Categorieën</h6>
                    <ul>
                        <?php foreach (array_slice($navCategories ?? [], 0, 4) as $c): ?>
                            <li><a href="<?= e($baseFt) ?>/index.php?category=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <ul>
                        <li><a href="<?= e($baseFt) ?>/index.php">Alle advertenties</a></li>
                        <li><a href="<?= e($baseFt) ?>/place.php">Iets verkopen</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="footer__widget">
                    <h6>Tips voor verkopers</h6>
                    <p>Goede foto’s, een eerlijke prijs en duidelijke omschrijving — dan vindt een koper je sneller.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="<?= e($oganiFt) ?>/js/jquery-3.3.1.min.js"></script>
<script src="<?= e($oganiFt) ?>/js/bootstrap.min.js"></script>
<script src="<?= e($oganiFt) ?>/js/jquery.nice-select.min.js"></script>
<script src="<?= e($oganiFt) ?>/js/jquery-ui.min.js"></script>
<script src="<?= e($oganiFt) ?>/js/jquery.slicknav.js"></script>
<script src="<?= e($oganiFt) ?>/js/owl.carousel.min.js"></script>
<script src="<?= e($baseFt) ?>/assets/ogani/mm-main.js"></script>
<script src="<?= e($baseFt) ?>/assets/js/layout.js" defer></script>
</body>
</html>
