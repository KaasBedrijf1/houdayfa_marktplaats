<?php
declare(strict_types=1);

/**
 * Inline merk: groene ster + “Market” (rode M). Geen <img src="logo.svg"> met tekst.
 */
function mm_brand_logo_render(string $base, string $extraClass = ''): void
{
    $cls = trim('mm-brand-logo ' . $extraClass);
    ?>
    <a href="<?= e($base) ?>/index.php" class="<?= e($cls) ?>" aria-label="<?= e(SITE_NAME) ?> — home">
        <span class="mm-brand-logo__row">
            <span class="mm-brand-logo__mark" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 34" width="32" height="34" focusable="false">
                    <path fill="#006233" d="M16 0l4.9 12.1h13.1L21.8 19.6l4.9 12.1L16 24.2 5.3 31.7l4.9-12.1L0 12.1h13.1L16 0z"/>
                </svg>
            </span>
            <span class="mm-brand-logo__type">
                <span class="mm-brand-logo__letter">M</span><span class="mm-brand-logo__rest">arket</span>
            </span>
        </span>
        <span class="mm-brand-logo__rule" aria-hidden="true"></span>
    </a>
    <?php
}
