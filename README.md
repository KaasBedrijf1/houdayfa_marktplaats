# Houdayfa Marktplaats — PHP demo (Marktplaats-achtig)

Educatief voorbeeld: zoeken, categorieën, advertentie bekijken, **accounts**, **moderatie** en plaatsen met PHP + MySQL (XAMPP).

> Geen officiële Marktplaats; geen logo of merknaam van Marktplaats.

## Wat is dit?

Een **marktplaats-achtige site**: **iedereen mag gratis rondkijken** (geen account). Om **te verkopen** of **contact** (e-mail verkoper) te zien is **inloggen** nodig. Nieuwe advertenties komen op **pending** en zijn pas **openbaar na goedkeuring** door een beheerder (eenvoudige fraude-/spamrem).

Configureer de sitenaam en tagline in `config.php` (`SITE_NAME`, `SITE_TAGLINE`, `SITE_META_DESCRIPTION`).

## Toegang & rollen

| Actie | Account |
|--------|---------|
| Zoeken, advertenties bekijken (lijst + detail zonder e-mail verkoper) | Niet nodig |
| Advertentie plaatsen | Registreren + inloggen |
| E-mailadres verkoper / mailto | Ingelogd |
| Goedkeuren / afwijzen advertenties | Admin |

**Standaardbeheerder** (na schone import van `sql/schema.sql` of na `sql/migration_auth_moderation.sql`):

- **E-mail:** `admin@localhost`
- **Wachtwoord:** `AdminWijzigDit123` — **direct wijzigen** na installatie.

Beheer: `http://localhost/houdayfa_marktplaats/admin/index.php` (of `/admin/listings.php`).

## Frontend: Ogani-template (`marktplaatsnew`)

De lay-out komt uit het project **`marktplaatsnew`** (Ogani / Colorlib-achtige e-commerce HTML-template):

- **Assets:** `assets/ogani/src/` (Bootstrap, jQuery, Owl Carousel, CSS, enz.)
- **Aanpassingen:** `assets/ogani/mm-overrides.css`, `assets/ogani/mm-main.js`
- **Logo:** `assets/ogani/src/images/logo.svg`
- **`assets/ogani/mm-forms-detail.css`** wordt o.a. op `place.php`, `ad.php`, `login.php`, `register.php`, `my-listings.php` geladen.

### Optioneel: aparte “marketplace”-database (winkelwagen)

Zie `sql/optional_marketplace_cart_db.sql` — niet aangesloten op deze listings-flow.

## Vereisten

- XAMPP (Apache + PHP + MySQL)
- PHP 8.0+ (gebruikt o.a. `match`)

## Installatie (nieuwe database)

1. Map onder `htdocs/houdayfa_marktplaats`.

2. Maak database:

   ```sql
   CREATE DATABASE marktmaroc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Importeer **`sql/schema.sql`** (categorieën, **users**, **listings** met moderatievelden, standaard-admin).

4. Optioneel: importeer **`sql/seed_demo_products.sql`** (±50 demo-advertenties; `TRUNCATE listings`).

5. Pas **`config.php`** aan (`DB_USER` / `DB_PASS` indien nodig).

6. Map **`assets/uploads/`** schrijfbaar voor uploads.

7. Open: `http://localhost/houdayfa_marktplaats/`

## Bestaande database upgraden (oude schema zonder users/moderatie)

Eenmalig in phpMyAdmin (database `marktmaroc`):

```text
sql/migration_auth_moderation.sql
```

Daarna bestaan o.a. tabel **`users`** en kolommen **`listings.status`**, **`listings.user_id`**, enz. Bestaande advertenties krijgen status **approved** zodat ze zichtbaar blijven.

## Bestanden (kern)

| Bestand | Functie |
|--------|---------|
| `index.php` | Overzicht + zoeken (alleen **goedgekeurde** advertenties) |
| `ad.php` | Detail; contact alleen ingelogd; pending/rejected alleen verkoper/admin |
| `place.php` | Nieuwe advertentie (alleen ingelogd → status **pending**) |
| `register.php` / `login.php` / `logout.php` | Accounts |
| `my-listings.php` | Eigen advertenties + status |
| `admin/index.php`, `admin/listings.php` | Moderatiewachtrij |
| `includes/auth.php` | Sessie, CSRF, rollen |
| `sql/schema.sql` | Volledig schema |
| `sql/migration_auth_moderation.sql` | Upgrade van oude installaties |

## Uitbreidingen (ideeën)

- E-mailverificatie, rate limits, CAPTCHA op plaatsen
- Meerdere foto’s per advertentie
- Paginatie op zoekresultaten
- Berichten in de site i.p.v. alleen mailto
