# Shade Studio — upgraded PHP / Laravel project

A complete student demonstration store built from the supplied rom&nd project.
The app is not affiliated with rom&nd. Foundation/cushion sample products are fictional.

## Requirements

- PHP **8.3.6 or newer** (8.3 or 8.4 recommended), with PDO, SQLite, mbstring, XML/DOM, tokenizer, fileinfo, ctype, iconv, curl and OpenSSL. Enable pdo_mysql when using MySQL.
- The included dependency lock targets PHP 8.3.6. PHP 8.2 from the old SQL export cannot run this Laravel version.
- Composer if restoring dependencies. The ZIP includes vendor for convenient local setup.
- No Node/Vite build is needed: UI assets are plain CSS and JavaScript.
- Internet for the optional MediaPipe camera model. Manual matching and shopping work without model download.

## Quick start (new demo database)

Extract the ZIP, open a terminal in the romand-v2 folder, then run:

Windows:

```bat
setup.bat
php artisan studio:admin your-email@example.com
php artisan serve
```

macOS / Linux:

```sh
bash setup.sh
php artisan studio:admin your-email@example.com
php artisan serve
```

The admin command asks for a password of at least 12 characters. There are no preset accounts or shared admin passwords. Register a separate customer in the browser.
Open http://127.0.0.1:8000. Run via localhost or HTTPS for camera permission; do not open Blade files directly.
If `php` is not found, add your PHP installation to PATH. Confirm the version with `php -v`.
The default database is SQLite, so MySQL is not needed for the first demonstration.
Do not run `migrate:fresh` on a database whose records you want to keep.

## Preserve the supplied lipsticks.sql IDs and stocks in a new installation

Instead of setup.bat/setup.sh, use an empty database and run these in order:

```sh
# Windows: copy .env.example .env
# macOS/Linux: cp .env.example .env
php -r "if (!file_exists('database/database.sqlite')) touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
php artisan studio:import-legacy database/fixtures/legacy-lipsticks.json
php artisan db:seed
php artisan studio:admin your-email@example.com
php artisan serve
```

The JSON fixture contains only the 23 product rows from your uploaded SQL. Its import requires an empty shade catalog. It keeps original IDs, names, prices, and stocks. Seeding afterwards adds missing demo items and does not reset stock.

## Upgrade a copy of an existing full Laravel database

1. Back up the **entire** old database and uploaded images first; lipsticks.sql alone does not include users/orders.
2. Use a separate project folder and a copied database for your first upgrade attempt.
3. Configure this project's .env to point to that copy (and preserve the existing APP_KEY when needed).
4. Copy old product image uploads into public/images/uploads. Do not copy old cached bootstrap files.
5. Run `php artisan migrate`, then inspect the catalog, order history, and IDs.
6. Optionally run `php artisan db:seed` for missing sample catalog data; it does not create/reset accounts or reset existing stock.

The new migration renames lipsticks to product_shades and keeps IDs, creates normalized products, and links each shade. The compatibility Lipstick model points to product_shades. Historical order items keep their product/shade/price snapshots. Old session carts should be cleared after migration because session cart keys changed.
A bare SQL table imported without Laravel migration history needs a deliberate migration baseline; use the supplied JSON import into a new database rather than importing lipsticks.sql over the new tables.

## MySQL / MariaDB instead of SQLite

Create an empty database, then set .env:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=romand_studio
DB_USERNAME=root
DB_PASSWORD=
```

Remove any SQLite DB_DATABASE value before adding the MySQL settings. Run migrations and seeders as above. SQLite is the runtime-tested path in this environment; MySQL setup is provided but was not integration-tested here.

## Features

- Database-driven collection with lipstick, foundation, and cushion filters/search.
- Product and shade records, per-shade stock/price, explicit source and reviewed status.
- Admin product/shade editor, swatch sampling by clicking a real swatch image, editable HEX.
- Manual Shade Finder with user-confirmed depth, undertone, color family, finish, and coverage.
- Optional local webcam cheek sampling with face detection, clipping and uneven-light checks; estimated depth requires confirmation. It deliberately does not infer undertone from red/blue thresholds.
- Category-specific ranking: foundation filters distant depth groups/opposite known undertones; lipstick ranks preference tags. At most three in-stock reviewed shades, with explanations and a no-match state.
- Comparison of shortlist cards; links to the exact product/shade and direct add to cart.
- Optional account profile storage/deletion and recommendation feedback; feedback does not train a model.
- Existing registration, login, cart, checkout, orders, notifications, promotions, customer management, and dashboard.
- Transactional checkout locks stock, refreshes prices from the DB, creates order snapshots, then decrements stock.
- KPay screenshot submission is manual proof only, **not an integrated payment gateway**. Configure STUDIO_PAYMENT_PHONE only for your own intended testing. Do not send money during a demo.

## Real catalog versus demo data

The original catalog is imported as unreviewed. Its shade names and colors came from your original files and have not been verified against the brand. Review and correct them in Admin before marking eligible.
`Studio Demo Foundation`, `Studio Demo Cushion`, and `Studio Demo Lipstick` are fictional example products under the brand `Illustrative sample`. Their labels, depth groups, undertone tags and display colors are hand-authored to exercise the app. They are never described as official rom&nd shades.
To use actual foundation products, add verified real product/shade information and sources in Admin, then remove or set stock to zero for the demonstration shades.

## Matching limits you should explain at the project show

- This is an explainable rule-based recommendation prototype, not a trained skin-color classifier or professional color diagnosis.
- Foundation matching uses confirmed coarse depth/undertone tags, not calibrated Delta-E measurements. No accuracy percentage is claimed.
- Camera lightness thresholds are illustrative and unvalidated. Lighting, camera processing, makeup and skin variations affect results. Capture quality gates do not prove calibration.
- Face landmarks locate cheeks; they do not establish undertone. The user selects undertone or Not sure.
- Photos remain in browser memory/canvas and are not uploaded or stored. Opening the camera downloads model assets from Google/jsDelivr. Saved profile fields go to the app database only if a logged-in customer opts in.
- Reference foundation text is a personal comparison note, not automatic cross-brand conversion.
- Compare physical swatches before buying foundation. A narrow real-brand shade range can legitimately return no match.

## Demo sequence

1. Browse Lips / Foundation / Cushion.
2. Open Find My Shade, choose foundation, depth 4, neutral, natural, medium.
3. Optionally open camera; capture both cheeks and confirm or correct the suggested depth.
4. Inspect the explained shortlist and demo labels; choose a shade.
5. Add to cart, register/sign in as a customer, place a COD demonstration order.
6. Sign in as admin in a different browser session, update order status.
7. Check customer order history and notifications; edit a catalog shade and inspect stock.
8. Show a no-match case by setting a product's relevant shades to zero stock.

## Tests and code map

```sh
php vendor/bin/phpunit
php artisan route:list
php artisan view:cache
```

- app/Services/ShadeMatcher.php — ranking and foundation guards.
- app/Http/Controllers/ShadeFinderController.php — validation, profile, feedback.
- app/Http/Controllers/AdminCatalogController.php — normalized admin catalog.
- app/Http/Controllers/CartController.php — server-side price/stock and atomic checkout.
- database/migrations/2026_10_30_000001_upgrade_catalog.php — ID-preserving catalog migration.
- public/js/shade-camera.js — optional capture and sampling.
- resources/views/v2 — new customer/admin UI.
- tests/Feature/StudioTest.php — recommendation, access, profile, checkout, seeding tests.

For public deployment, set APP_DEBUG=false, use HTTPS, protect backups, replace demo policy/contact text, configure your own payment/contact data, and perform deployment-specific testing. This package is delivered as a local student demo, not a live storefront.
