<?php
declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

require_login();

$pageTitle = 'Verkoop je tweedehands spullen';
$errors = [];
$u = auth_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $title = trim((string)($_POST['title'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $priceRaw = str_replace(',', '.', trim((string)($_POST['price'] ?? '0')));
    $city = trim((string)($_POST['city'] ?? ''));
    $sellerName = trim((string)($_POST['seller_name'] ?? ''));
    $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;

    if ($title === '' || mb_strlen($title) < 3) {
        $errors[] = 'Titel is verplicht (minimaal 3 tekens).';
    }
    if ($description === '' || mb_strlen($description) < 10) {
        $errors[] = 'Omschrijving is te kort (minimaal 10 tekens).';
    }
    if (!is_numeric($priceRaw) || (float)$priceRaw < 0) {
        $errors[] = 'Voer een geldige prijs in.';
    }
    if ($city === '') {
        $errors[] = 'Plaats is verplicht.';
    }
    if ($sellerName === '') {
        $errors[] = 'Je naam op de advertentie is verplicht.';
    }

    $validCat = false;
    foreach ($navCategories as $c) {
        if ((int)$c['id'] === $categoryId) {
            $validCat = true;
            break;
        }
    }
    if (!$validCat) {
        $errors[] = 'Kies een categorie.';
    }

    $imageName = null;
    if (!empty($_FILES['image']['tmp_name']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES['image']['tmp_name']);
        $map = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!isset($map[$mime])) {
            $errors[] = 'Alleen JPG, PNG of WEBP toegestaan.';
        } elseif ($_FILES['image']['size'] > 3 * 1024 * 1024) {
            $errors[] = 'Afbeelding maximaal 3 MB.';
        } else {
            $imageName = bin2hex(random_bytes(8)) . '.' . $map[$mime];
        }
    }

    if ($errors === [] && $imageName !== null) {
        $uploadDir = __DIR__ . '/assets/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName)) {
            $errors[] = 'Upload mislukt. Controleer maprechten op assets/uploads.';
            $imageName = null;
        }
    }

    if ($errors === []) {
        $stmt = db()->prepare(
            'INSERT INTO listings (user_id, category_id, title, description, price, city, seller_name, seller_email, image, status)
             VALUES (:uid, :cid, :title, :descr, :price, :city, :sname, :semail, :img, \'pending\')'
        );
        $stmt->execute([
            'uid' => $u['id'],
            'cid' => $categoryId,
            'title' => $title,
            'descr' => $description,
            'price' => round((float)$priceRaw, 2),
            'city' => $city,
            'sname' => $sellerName,
            'semail' => $u['email'],
            'img' => $imageName,
        ]);
        flash_set('success', 'Je advertentie is ingediend. Na goedkeuring door een beheerder is hij zichtbaar voor iedereen. Je vindt de status onder “Mijn advertenties”.');
        header('Location: ' . base_url() . '/my-listings.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="product spad mm-inner-page">
<div class="container">
<div class="page-intro">
    <h1>Zet je tweedehands spullen te koop</h1>
    <p class="muted">Je bent ingelogd als <strong><?= e($u['display_name']) ?></strong>. Contactmail voor kopers is je accountmail (<strong><?= e($u['email']) ?></strong>). Elke nieuwe advertentie wordt eerst <strong>gecontroleerd</strong> voordat hij live gaat.</p>
</div>

<?php if ($errors !== []): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form class="form-place" method="post" enctype="multipart/form-data" novalidate>
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <label>
        Categorie
        <select name="category_id" required>
            <option value="">— kies —</option>
            <?php foreach ($navCategories as $c): ?>
                <option value="<?= (int)$c['id'] ?>"><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        Titel
        <input type="text" name="title" maxlength="160" required value="<?= e($_POST['title'] ?? '') ?>">
    </label>
    <label>
        Prijs (€)
        <input type="text" name="price" inputmode="decimal" placeholder="0 of 49,95" required value="<?= e($_POST['price'] ?? '') ?>">
    </label>
    <label>
        Plaats / regio
        <input type="text" name="city" maxlength="100" required value="<?= e($_POST['city'] ?? '') ?>">
    </label>
    <label>
        Omschrijving
        <textarea name="description" rows="8" required><?= e($_POST['description'] ?? '') ?></textarea>
    </label>
    <label>
        Foto (JPG/PNG/WEBP, max 3 MB) — optioneel
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
    </label>
    <fieldset class="seller-fieldset">
        <legend>Naam op de advertentie</legend>
        <label>
            Weergavenaam voor kopers
            <input type="text" name="seller_name" maxlength="100" required value="<?= e($_POST['seller_name'] ?? $u['display_name']) ?>">
        </label>
    </fieldset>
    <button type="submit" class="btn btn-primary">Advertentie indienen ter goedkeuring</button>
</form>
</div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
