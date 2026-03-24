# Marktplaats-demo op Render (Docker + PHP)

Zelfde werkwijze als je rijschool-project: code naar **GitHub**, daarna **Render → Blueprint** (`render.yaml`).

## Belangrijk: MySQL

Deze app gebruikt **MySQL** (PDO). Render zelf levert **geen** gratis MySQL in één klik. Je hebt twee opties:

### A — Gratis MySQL elders (aanbevolen voor demo)

1. Maak een gratis MySQL-database, bijvoorbeeld bij **PlanetScale**, **Aiven**, of een **Railway MySQL**-plugin.
2. Maak een database + gebruiker en importeer je schema: `sql/schema.sql` (en eventueel `sql/seed_demo_products.sql`).
3. In **Render** → je web service → **Environment** → voeg toe:

| Key       | Voorbeeld        |
|-----------|------------------|
| `DB_HOST` | host van je DB   |
| `DB_NAME` | databasenaam     |
| `DB_USER` | gebruiker        |
| `DB_PASS` | wachtwoord       |

4. **Redeploy** de service.

Zonder deze variabelen blijft de app op de oude defaults (`127.0.0.1` / `root`) — dat werkt **niet** op Render.

### B — Alleen testen dat de container start

Dan zie je de site nog niet volledig; database-queries falen tot `DB_*` goed staan.

## Lokaal met Docker

```bash
docker build -t marktplaats-demo .
docker run --rm -p 8080:80 -e PORT=80 marktplaats-demo
```

Open `http://localhost:8080` (MySQL nog steeds lokaal nodig, tenzij je `-e DB_HOST=...` meegeeft).

## Git push

```powershell
cd C:\xampp\htdocs\houdayfa_marktplaats
git remote set-url origin https://github.com/JOUW-USER/JOUW-REPO.git
git add .
git commit -m "Docker + Render"
git push -u origin main
```

Daarna op Render: **New → Blueprint** → deze repo.
