# ShiftKoro — Architecture & Plan

Online truck booking system, rebuilt from scratch with plain PHP 8 + simple OOP.
No framework, no Composer packages — just PHP, PDO (MySQL/MariaDB) and Bootstrap 5.

Priority: **Simple code > Clean OOP > Maintainability > Performance**.

---

## 1. What changed from the old project

| Old project | New project |
|---|---|
| Raw SQL with user input concatenated (SQL injection) | PDO prepared statements everywhere |
| Login stored in a plain cookie (`current_user`) | PHP session + `password_hash()` |
| `md5(sha1())` passwords | `password_hash()` / `password_verify()` |
| Customer typed the distance manually | Distance calculated from pickup/destination coordinates |
| Fare = distance × driver's tk (in the URL, editable by user!) | Fare calculated on the server by `FareCalculator` |
| Drivers registered trucks themselves | Trucks come from a Truck API (dummy now), synced into DB |
| Separate `personal_shifting` / `business_shifttin` tables | One `bookings` table with `shifting_type` |
| Payment never verified, booking saved before payment | SSLCommerz init → callback/IPN → validation API → booking confirmed |
| Admin password hardcoded in `index.php` | Admin is a user with `role = 'admin'` |
| Every page mixed HTML + SQL + logic | Controllers → Services/Models → Views |

The old code (`User-Interface/`, `Admin-interface/`) has been removed; it is still
available in git history (first commit).

---

## 2. Folder structure

```
shiftkoro/
├── public/                 ← web root (only this folder is public)
│   ├── index.php           ← front controller, every request comes here
│   ├── .htaccess           ← sends all requests to index.php
│   └── assets/             ← css, images
├── app/
│   ├── Core/               ← small "framework" classes
│   │   ├── Router.php        maps URL → controller method
│   │   ├── Database.php      one shared PDO connection
│   │   ├── Model.php         base model (find, create, update)
│   │   ├── Controller.php    base controller (view, redirect, auth checks)
│   │   ├── View.php          renders a PHP template inside a layout
│   │   ├── Session.php       session, flash messages, old input, CSRF
│   │   ├── Auth.php          login / logout / current user
│   │   ├── Validator.php     form validation rules
│   │   └── HttpException.php 404 / 403 errors
│   ├── Models/             ← one class per table
│   │   ├── User.php  Location.php  Truck.php  Booking.php  Payment.php
│   ├── Services/           ← business logic
│   │   ├── DistanceCalculator.php
│   │   ├── FareCalculator.php
│   │   ├── BookingService.php
│   │   ├── PaymentService.php
│   │   ├── SslCommerzGateway.php
│   │   ├── TruckSyncService.php
│   │   └── ReportService.php
│   ├── TruckApi/           ← where truck data comes from
│   │   ├── TruckProviderInterface.php
│   │   ├── DummyTruckProvider.php   (current — reads dummy JSON)
│   │   ├── HttpTruckProvider.php    (for a real REST API later)
│   │   └── dummy-trucks.json
│   ├── Controllers/        ← customer pages
│   │   └── Admin/          ← admin panel pages
│   ├── helpers.php         ← tiny global helpers: e(), url(), money() ...
│   ├── routes.php          ← all URLs in one place
│   └── bootstrap.php       ← autoloader + config + session start
├── views/                  ← HTML templates (layouts, partials, pages)
├── config/
│   ├── config.example.php  ← copy to config.php and fill in
│   └── config.php          ← real settings (git-ignored)
├── database/
│   ├── schema.sql          ← tables
│   └── seed.sql            ← locations + admin user
└── docs/ARCHITECTURE.md
```

### Layers (who talks to whom)

```
Request → public/index.php → Router → Controller
                                         │
                       ┌─────────────────┼──────────────────┐
                       ▼                 ▼                  ▼
                   Validator         Services            Models ──→ Database (PDO)
                                   (fare, booking,
                                    payment, sync)
                                         │
                              TruckProvider / SslCommerzGateway (external HTTP)
                                         │
Controller → View (views/*.php inside a layout) → HTML response
```

Rules kept on purpose:
- **Controllers** are thin: read input, validate, call a service/model, render or redirect.
- **Models** only know about their own table (SQL lives here).
- **Services** hold business rules that touch more than one model or an external API.
- **Views** only display data. Every output goes through `e()` (XSS safe).

Only **one interface** exists: `TruckProviderInterface`, because swapping the
dummy API with a real one is an explicit requirement. Nothing else is abstracted.

---

## 3. Database design

All tables: `InnoDB`, `utf8mb4`, `snake_case` plural names, `id` BIGINT primary key,
`created_at` / `updated_at` timestamps.

```
users 1───* bookings *───1 trucks
              │  │
              │  └──*───1 locations (pickup_location_id, dropoff_location_id)
              │
              1───* payments
```

### users
| column | type | notes |
|---|---|---|
| id | BIGINT PK | |
| name | VARCHAR(100) | |
| email | VARCHAR(150) | UNIQUE |
| phone | VARCHAR(20) | UNIQUE, BD format `01XXXXXXXXX` |
| password_hash | VARCHAR(255) | `password_hash()` |
| role | ENUM('customer','admin') | default customer |
| created_at / updated_at | TIMESTAMP | |

### locations
Pickup/destination areas with coordinates (used for distance).
| column | type | notes |
|---|---|---|
| id | BIGINT PK | |
| name | VARCHAR(100) | e.g. Mirpur 10 |
| city | VARCHAR(60) | e.g. Dhaka |
| latitude / longitude | DECIMAL(10,7) | |
| is_active | TINYINT(1) | index |
| UNIQUE(name, city) | | |

### trucks
Local copy of trucks from the Truck API (synced). Admin can also add/edit trucks.
| column | type | notes |
|---|---|---|
| id | BIGINT PK | |
| external_id | VARCHAR(50) NULL | UNIQUE — id from the truck API, NULL for manually added |
| name | VARCHAR(100) | |
| type | VARCHAR(30) | pickup / medium / large / covered_van … (index) |
| size_ft | DECIMAL(5,1) | |
| capacity_ton | DECIMAL(5,2) | |
| description | TEXT | |
| image_url | VARCHAR(255) | |
| base_fare | DECIMAL(10,2) | fixed starting charge |
| per_km_rate | DECIMAL(10,2) | charge per km |
| is_available | TINYINT(1) | index |
| synced_at | TIMESTAMP NULL | last time updated from API |

### bookings
| column | type | notes |
|---|---|---|
| id | BIGINT PK | |
| booking_no | VARCHAR(20) | UNIQUE, e.g. `TB-20261006-4F2A` |
| user_id | FK → users | index |
| truck_id | FK → trucks | |
| pickup_location_id / dropoff_location_id | FK → locations | |
| pickup_address / dropoff_address | VARCHAR(255) | house / road details |
| shifting_type | ENUM('personal','business') | from old project |
| shifting_date | DATE | |
| contact_name / contact_phone | | |
| notes | TEXT NULL | |
| distance_km | DECIMAL(8,2) | **snapshot** at booking time |
| base_fare / per_km_rate / total_fare | DECIMAL(10,2) | **snapshot** (rates may change later) |
| status | ENUM('pending','confirmed','completed','cancelled') | index |
| confirmed_at | TIMESTAMP NULL | |
| INDEX(truck_id, shifting_date) | | availability check |

### payments
One booking can have several payment attempts (fail → retry).
| column | type | notes |
|---|---|---|
| id | BIGINT PK | |
| booking_id | FK → bookings | index |
| tran_id | VARCHAR(50) | UNIQUE — our transaction id sent to SSLCommerz |
| amount | DECIMAL(10,2) | |
| currency | CHAR(3) | BDT |
| status | ENUM('initiated','success','failed','cancelled') | index |
| val_id / bank_tran_id / card_type | VARCHAR NULL | from SSLCommerz |
| gateway_response | TEXT NULL | raw JSON from validation API (for audit) |
| paid_at | TIMESTAMP NULL | |

### Status rules
- Booking is created as **pending**.
- Only a **verified** SSLCommerz payment sets payment → `success` and booking → `confirmed` (one DB transaction).
- Failed/cancelled payment → payment row `failed`/`cancelled`, booking **stays pending** (customer can retry).
- Customer can cancel a *pending* booking. Admin can mark *confirmed* → `completed`, or cancel.
- A truck is **not available** on a date if it has a `confirmed` booking that day,
  or a `pending` booking created in the last 30 minutes (someone is paying right now).

---

## 4. Main flows

### 4.1 Customer booking flow
```
Home: choose Pickup + Destination + Date
   → GET /trucks?pickup=&dropoff=&date=
     DistanceCalculator: distance (km)
     list available trucks, each with FareCalculator estimate
   → GET /trucks/{id}?pickup=&dropoff=&date=      (truck details + fare breakdown)
   → GET /bookings/create?truck=&pickup=&dropoff=&date=   (login required)
     form: contact name/phone, addresses, shifting type, notes
   → POST /bookings
     BookingService: validate → recalc distance & fare on server → check availability
     → insert booking (status = pending)
   → GET /bookings/{id}   (details + "Pay Now")
```

### 4.2 Payment flow (SSLCommerz)
```
POST /bookings/{id}/pay
  PaymentService::start()
    - insert payments row (status = initiated, unique tran_id)
    - SslCommerzGateway::createSession() → GatewayPageURL
  redirect customer → SSLCommerz hosted page

SSLCommerz → POST /payment/success   (browser redirect)
           → POST /payment/fail
           → POST /payment/cancel
           → POST /payment/ipn       (server-to-server, may come before/after success)

success + ipn  → PaymentService::verify(post)
                   - find payment by tran_id
                   - SslCommerzGateway::validate(val_id)   (calls SSLCommerz validation API)
                   - check status VALID/VALIDATED, tran_id, amount, currency
                   - DB transaction: payment = success, booking = confirmed
                   - idempotent: already success → do nothing
fail / cancel  → payment = failed / cancelled (only if still "initiated")
Every browser callback ends with a redirect to GET /bookings/{id}.
```
Callbacks are cross-site POSTs, so they do not use the session or CSRF token;
the booking is found by `tran_id` and trusted **only after** the validation API says so.

### 4.3 Truck data flow
```
TruckProviderInterface::fetchTrucks(): array of plain truck arrays
   DummyTruckProvider → reads app/TruckApi/dummy-trucks.json   (now)
   HttpTruckProvider  → GET {truck_api.base_url}/trucks         (later)

TruckSyncService::sync()
   for each truck from the provider → insert or update `trucks` by external_id
Run by: Admin → Trucks → "Sync from API"
```
To switch to a real API: set `'truck_api' => ['driver' => 'http', 'base_url' => ...]`
in `config/config.php`. If the real API uses different field names, only
`HttpTruckProvider::mapTruck()` needs to change.

A public dummy endpoint `GET /api/dummy/trucks` returns the same JSON so the API shape
can be seen/tested in a browser.

### 4.4 Distance & fare
- `DistanceCalculator` uses the Haversine formula (straight-line km between two
  coordinates) × `road_factor` (default 1.3, because roads are not straight).
  To use Google Distance Matrix later, change only this class.
- `FareCalculator`: `fare = base_fare + distance_km × per_km_rate`, never less than
  `minimum_fare`, rounded to `round_to` taka. All numbers are in `config.php → fare`
  and per-truck rates are editable in Admin → Trucks.

---

## 5. URL map

| Method | URL | Purpose |
|---|---|---|
| GET | `/` | Home + search form |
| GET/POST | `/register`, `/login` | Auth |
| POST | `/logout` | |
| GET/POST | `/profile`, `/profile/password` | Profile |
| GET | `/trucks`, `/trucks/{id}` | Truck listing & details |
| GET | `/bookings` | Booking history |
| GET/POST | `/bookings/create`, `/bookings` | New booking |
| GET | `/bookings/{id}` | Booking details & status |
| POST | `/bookings/{id}/pay`, `/bookings/{id}/cancel` | |
| POST | `/payment/success`, `/payment/fail`, `/payment/cancel`, `/payment/ipn` | SSLCommerz |
| GET | `/api/dummy/trucks` | Dummy truck API |
| GET | `/admin` | Dashboard |
| GET/POST | `/admin/trucks…` | Truck management + sync |
| GET/POST | `/admin/locations…` | Location management |
| GET/POST | `/admin/bookings…` | Booking management |
| GET | `/admin/payments` | Payment management |
| GET | `/admin/reports` | Basic reports |

---

## 6. Security checklist
- PDO prepared statements only
- `password_hash` / `password_verify`, `session_regenerate_id()` on login
- CSRF token on every POST form (except SSLCommerz callbacks)
- All output escaped with `e()`
- Fare/amount always calculated on the server — never trusted from the browser
- Payment trusted only after SSLCommerz validation API + amount check
- Secrets in `config/config.php` (git-ignored)
