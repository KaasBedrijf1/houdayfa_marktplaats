</main>
<?php
$baseFt = base_url();
$navFt = $navCategories ?? [];
?>
<script src="<?= e($baseFt) ?>/assets/js/layout.js" defer></script>
<script src="<?= e($baseFt) ?>/assets/js/header-scroll.js" defer></script>
<footer class="site-footer">
    <div class="wrap footer-inner">
        <div class="footer-brand">
            <a class="footer-logo" href="<?= e($baseFt) ?>/index.php"><?= e(SITE_NAME) ?></a>
            <p class="footer-tagline">Lokaal aanbod, duidelijke prijzen — gebouwd voor overzicht en vertrouwen.</p>
        </div>
        <nav class="footer-col" aria-label="Snel naar categorie">
            <h2 class="footer-heading">Categorieën</h2>
            <ul class="footer-links">
                <li><a href="<?= e($baseFt) ?>/index.php">Alle advertenties</a></li>
                <?php foreach (array_slice($navFt, 0, 6) as $c): ?>
                    <li><a href="<?= e($baseFt) ?>/index.php?category=<?= e($c['slug']) ?>"><?= e($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <nav class="footer-col" aria-label="Diensten">
            <h2 class="footer-heading">Diensten</h2>
            <ul class="footer-links">
                <li><a href="<?= e($baseFt) ?>/place.php">Advertentie plaatsen</a></li>
                <li><a href="<?= e($baseFt) ?>/index.php">Zoeken</a></li>
            </ul>
        </nav>
        <div class="footer-col">
            <h2 class="footer-heading">Over deze site</h2>
            <ul class="footer-links">
                <li><span class="footer-note">Demo / educatief PHP-project. Geen officiële Marktplaats.</span></li>
                <li><a href="<?= e($baseFt) ?>/index.php">Home</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bar">
        <div class="wrap footer-bar-inner">
            <p class="footer-copy">© <?= date('Y') ?> <?= e(SITE_NAME) ?>. Alle rechten voorbehouden aan de respectieve eigenaren.</p>
        </div>
    </div>
</footer>
</body>
</html>
