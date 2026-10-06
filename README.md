# ShiftKoro — Online Truck Booking System

Customers pick a pickup and destination, see available trucks with the exact fare,
book, pay with SSLCommerz and get an instant confirmation. Admins manage trucks,
locations, bookings and payments, and see basic reports.

Plain PHP 8 + MySQL/MariaDB + Bootstrap 5. No framework and no Composer packages.
Architecture, database design and all flows: **[docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)**.

> The old code (`User-Interface/`, `Admin-interface/`) was removed. It is still in git
> history (first commit) if you ever need to look at it.

## Requirements
- PHP 8.1+ with `pdo_mysql`, `curl`, `openssl` enabled
- MySQL 5.7+ / MariaDB 10.4+ (XAMPP works)

XAMPP: in `C:\xampp\php\php.ini` remove the `;` in front of
`extension=pdo_mysql`, `extension=openssl` and `extension=mbstring`, then restart Apache.

## Setup

```bash
# 1. Database
mysql -u root < database/schema.sql
mysql -u root shiftkoro < database/seed.sql

# 2. Config
cp config/config.example.php config/config.php
#    edit config/config.php: app.url, db, sslcommerz store id/password

# 3. Import trucks from the (dummy) Truck API
php scripts/sync-trucks.php
#    or later: Admin → Trucks → "Sync from Truck API"
```

### Run

**Option A — PHP built-in server** (`app.url` = `http://localhost:8000`)
```bash
php -S localhost:8000 -t public public/index.php
```

**Option B — XAMPP Apache** (`app.url` = `http://localhost/shiftkoro`)
Put the project in `C:\xampp\htdocs\shiftkoro` and open http://localhost/shiftkoro.
The root `.htaccess` forwards everything into `public/` (needs `mod_rewrite`, on by default).

### Default admin
`admin@shiftkoro.com` / `Admin@123` — change the password from the Profile page after first login.

## SSLCommerz

1. Create a sandbox store at https://developer.sslcommerz.com/registration/ and put the
   store id / password in `config/config.php` (`sandbox => true`).
2. Sandbox test card: `4111 1111 1111 1111`, any future expiry, CVV `111`. Mobile banking OTP: `111111`.
3. **IPN**: SSLCommerz cannot reach `localhost`. To test the IPN locally expose the app with
   a tunnel (e.g. ngrok) and set `app.url` to the tunnel URL. The success URL also verifies the
   payment, so bookings are confirmed even without IPN while testing locally.
4. `cURL error: unable to get local issuer certificate` on XAMPP → download
   https://curl.se/ca/cacert.pem and set `curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"` in
   `php.ini`. (Only as a temporary local workaround: `'verify_ssl' => false` in config.)
5. Going live: real store credentials, `sandbox => false`, HTTPS site, `verify_ssl => true`,
   `app.debug => false`.

## Common changes

| I want to… | Change |
|---|---|
| Change a truck's price | Admin → Trucks → Edit (or in the Truck API, then Sync) |
| Change minimum fare / rounding | `config.php` → `fare` |
| Make distance closer to real road distance | `config.php` → `distance.road_factor` |
| Add pickup/destination areas | Admin → Locations |
| Use the real Truck API | `config.php` → `truck_api.driver = 'http'`, `base_url`, `api_key`. If its JSON fields differ, edit only `HttpTruckProvider::mapTruck()` |
| Use Google Maps distance | Replace the body of `DistanceCalculator::between()` |

## Code tour (where to look)

| Folder | What is inside |
|---|---|
| `app/routes.php` | every URL → controller method |
| `app/Controllers` | read request, validate, call model/service, show view |
| `app/Models` | one class per table, all SQL for that table |
| `app/Services` | business rules: distance, fare, booking, payment, sync, reports |
| `app/TruckApi` | where truck data comes from (dummy now, HTTP later) |
| `app/Core` | small helpers: router, database, base model/controller, session, validator |
| `views` | HTML templates (`layouts/`, `partials/`, one folder per page group) |
