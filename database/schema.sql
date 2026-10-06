-- ShiftKoro database schema
-- Run once:  mysql -u root < database/schema.sql

CREATE DATABASE IF NOT EXISTS shiftkoro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shiftkoro;

CREATE TABLE users (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name           VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL,
    phone          VARCHAR(20)  NOT NULL,
    password_hash  VARCHAR(255) NOT NULL,
    role           ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE locations (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)  NOT NULL,
    city        VARCHAR(60)   NOT NULL DEFAULT 'Dhaka',
    latitude    DECIMAL(10,7) NOT NULL,
    longitude   DECIMAL(10,7) NOT NULL,
    is_active   TINYINT(1)    NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_locations_name_city (name, city),
    KEY idx_locations_active (is_active)
) ENGINE=InnoDB;

CREATE TABLE trucks (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    external_id   VARCHAR(50)   NULL,
    name          VARCHAR(100)  NOT NULL,
    type          VARCHAR(30)   NOT NULL,
    size_ft       DECIMAL(5,1)  NOT NULL,
    capacity_ton  DECIMAL(5,2)  NOT NULL,
    description   TEXT          NULL,
    image_url     VARCHAR(255)  NULL,
    base_fare     DECIMAL(10,2) NOT NULL DEFAULT 0,
    per_km_rate   DECIMAL(10,2) NOT NULL,
    is_available  TINYINT(1)    NOT NULL DEFAULT 1,
    synced_at     TIMESTAMP NULL,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_trucks_external_id (external_id),
    KEY idx_trucks_type (type),
    KEY idx_trucks_available (is_available)
) ENGINE=InnoDB;

CREATE TABLE bookings (
    id                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_no           VARCHAR(20)   NOT NULL,
    user_id              BIGINT UNSIGNED NOT NULL,
    truck_id             BIGINT UNSIGNED NOT NULL,
    pickup_location_id   BIGINT UNSIGNED NOT NULL,
    dropoff_location_id  BIGINT UNSIGNED NOT NULL,
    pickup_address       VARCHAR(255)  NOT NULL,
    dropoff_address      VARCHAR(255)  NOT NULL,
    shifting_type        ENUM('personal', 'business') NOT NULL DEFAULT 'personal',
    shifting_date        DATE          NOT NULL,
    contact_name         VARCHAR(100)  NOT NULL,
    contact_phone        VARCHAR(20)   NOT NULL,
    notes                TEXT          NULL,
    distance_km          DECIMAL(8,2)  NOT NULL,
    base_fare            DECIMAL(10,2) NOT NULL,
    per_km_rate          DECIMAL(10,2) NOT NULL,
    total_fare           DECIMAL(10,2) NOT NULL,
    status               ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    confirmed_at         TIMESTAMP NULL,
    created_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_bookings_booking_no (booking_no),
    KEY idx_bookings_user (user_id),
    KEY idx_bookings_truck_date (truck_id, shifting_date),
    KEY idx_bookings_status (status),
    KEY idx_bookings_created (created_at),
    CONSTRAINT fk_bookings_user    FOREIGN KEY (user_id)             REFERENCES users (id),
    CONSTRAINT fk_bookings_truck   FOREIGN KEY (truck_id)            REFERENCES trucks (id),
    CONSTRAINT fk_bookings_pickup  FOREIGN KEY (pickup_location_id)  REFERENCES locations (id),
    CONSTRAINT fk_bookings_dropoff FOREIGN KEY (dropoff_location_id) REFERENCES locations (id)
) ENGINE=InnoDB;

CREATE TABLE payments (
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    booking_id        BIGINT UNSIGNED NOT NULL,
    tran_id           VARCHAR(50)   NOT NULL,
    amount            DECIMAL(10,2) NOT NULL,
    currency          CHAR(3)       NOT NULL DEFAULT 'BDT',
    status            ENUM('initiated', 'success', 'failed', 'cancelled') NOT NULL DEFAULT 'initiated',
    val_id            VARCHAR(100)  NULL,
    bank_tran_id      VARCHAR(100)  NULL,
    card_type         VARCHAR(50)   NULL,
    gateway_response  TEXT          NULL,
    paid_at           TIMESTAMP NULL,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_payments_tran_id (tran_id),
    KEY idx_payments_booking (booking_id),
    KEY idx_payments_status (status),
    CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings (id)
) ENGINE=InnoDB;
