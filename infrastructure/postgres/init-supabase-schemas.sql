-- =============================================================================
-- SIDAGAS Microservice Schema Initialization for Supabase PostgreSQL
-- =============================================================================
-- Jalankan script ini di Supabase SQL Editor (Dashboard → SQL Editor → New Query)
--
-- Script ini akan:
--   1. Membuat 14 schema untuk setiap bounded context / microservice
--   2. Membuat 14 database role terisolasi
--   3. Mengatur permission sehingga setiap role hanya bisa akses schema-nya
--   4. Menginstal extension yang diperlukan
--   5. Membuat tabel migrations di setiap schema
--
-- ATURAN WAJIB:
--   ✗ Tidak boleh JOIN lintas schema service
--   ✗ Tidak boleh foreign key lintas schema service
--   ✗ Tidak boleh membaca tabel service lain
--   ✓ Komunikasi antar service hanya melalui API / Event / Read Model
--   ✓ Setiap service punya migration sendiri
-- =============================================================================

-- ================================================
-- STEP 1: Extensions (pada database postgres)
-- ================================================
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ================================================
-- STEP 2: Buat semua schema
-- ================================================
CREATE SCHEMA IF NOT EXISTS identity;
CREATE SCHEMA IF NOT EXISTS customer;
CREATE SCHEMA IF NOT EXISTS catalog;
CREATE SCHEMA IF NOT EXISTS orders;
CREATE SCHEMA IF NOT EXISTS inventory;
CREATE SCHEMA IF NOT EXISTS payment;
CREATE SCHEMA IF NOT EXISTS finance;
CREATE SCHEMA IF NOT EXISTS delivery;
CREATE SCHEMA IF NOT EXISTS pos;
CREATE SCHEMA IF NOT EXISTS procurement;
CREATE SCHEMA IF NOT EXISTS payroll;
CREATE SCHEMA IF NOT EXISTS notification;
CREATE SCHEMA IF NOT EXISTS reporting;
CREATE SCHEMA IF NOT EXISTS audit;

-- ================================================
-- STEP 3: Buat role per service dengan LOGIN
-- ================================================
-- Password pattern: S1d4@9G4AsSv1 + suffix unik

DO $$
BEGIN
    -- Identity Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'identity_service_role') THEN
        CREATE ROLE identity_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Id';
    END IF;

    -- Customer Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'customer_service_role') THEN
        CREATE ROLE customer_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Cs';
    END IF;

    -- Catalog Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'catalog_service_role') THEN
        CREATE ROLE catalog_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Ct';
    END IF;

    -- Order Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'order_service_role') THEN
        CREATE ROLE order_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Or';
    END IF;

    -- Inventory Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'inventory_service_role') THEN
        CREATE ROLE inventory_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Iv';
    END IF;

    -- Payment Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'payment_service_role') THEN
        CREATE ROLE payment_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Py';
    END IF;

    -- Finance Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'finance_service_role') THEN
        CREATE ROLE finance_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Fn';
    END IF;

    -- Delivery Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'delivery_service_role') THEN
        CREATE ROLE delivery_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Dl';
    END IF;

    -- POS Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'pos_service_role') THEN
        CREATE ROLE pos_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Ps';
    END IF;

    -- Procurement Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'procurement_service_role') THEN
        CREATE ROLE procurement_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Pc';
    END IF;

    -- Payroll Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'payroll_service_role') THEN
        CREATE ROLE payroll_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Pr';
    END IF;

    -- Notification Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'notification_service_role') THEN
        CREATE ROLE notification_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Nf';
    END IF;

    -- Reporting Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'reporting_service_role') THEN
        CREATE ROLE reporting_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Rp';
    END IF;

    -- Audit Service Role
    IF NOT EXISTS (SELECT FROM pg_roles WHERE rolname = 'audit_service_role') THEN
        CREATE ROLE audit_service_role WITH LOGIN PASSWORD 'S1d4@9G4AsSv1_Au';
    END IF;
END
$$;

-- ================================================
-- STEP 4: Cabut akses default dari public schema
-- ================================================
-- Pastikan service role tidak bisa akses schema lain secara default
REVOKE ALL ON SCHEMA public FROM identity_service_role;
REVOKE ALL ON SCHEMA public FROM customer_service_role;
REVOKE ALL ON SCHEMA public FROM catalog_service_role;
REVOKE ALL ON SCHEMA public FROM order_service_role;
REVOKE ALL ON SCHEMA public FROM inventory_service_role;
REVOKE ALL ON SCHEMA public FROM payment_service_role;
REVOKE ALL ON SCHEMA public FROM finance_service_role;
REVOKE ALL ON SCHEMA public FROM delivery_service_role;
REVOKE ALL ON SCHEMA public FROM pos_service_role;
REVOKE ALL ON SCHEMA public FROM procurement_service_role;
REVOKE ALL ON SCHEMA public FROM payroll_service_role;
REVOKE ALL ON SCHEMA public FROM notification_service_role;
REVOKE ALL ON SCHEMA public FROM reporting_service_role;
REVOKE ALL ON SCHEMA public FROM audit_service_role;

-- ================================================
-- STEP 5: Grant akses HANYA ke schema milik service
-- ================================================

-- Identity Service → schema identity
GRANT USAGE ON SCHEMA identity TO identity_service_role;
GRANT CREATE ON SCHEMA identity TO identity_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA identity TO identity_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA identity TO identity_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA identity
    GRANT ALL ON TABLES TO identity_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA identity
    GRANT ALL ON SEQUENCES TO identity_service_role;

-- Customer Service → schema customer
GRANT USAGE ON SCHEMA customer TO customer_service_role;
GRANT CREATE ON SCHEMA customer TO customer_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA customer TO customer_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA customer TO customer_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA customer
    GRANT ALL ON TABLES TO customer_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA customer
    GRANT ALL ON SEQUENCES TO customer_service_role;

-- Catalog Service → schema catalog
GRANT USAGE ON SCHEMA catalog TO catalog_service_role;
GRANT CREATE ON SCHEMA catalog TO catalog_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA catalog TO catalog_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA catalog TO catalog_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA catalog
    GRANT ALL ON TABLES TO catalog_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA catalog
    GRANT ALL ON SEQUENCES TO catalog_service_role;

-- Order Service → schema orders
GRANT USAGE ON SCHEMA orders TO order_service_role;
GRANT CREATE ON SCHEMA orders TO order_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA orders TO order_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA orders TO order_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA orders
    GRANT ALL ON TABLES TO order_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA orders
    GRANT ALL ON SEQUENCES TO order_service_role;

-- Inventory Service → schema inventory
GRANT USAGE ON SCHEMA inventory TO inventory_service_role;
GRANT CREATE ON SCHEMA inventory TO inventory_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA inventory TO inventory_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA inventory TO inventory_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA inventory
    GRANT ALL ON TABLES TO inventory_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA inventory
    GRANT ALL ON SEQUENCES TO inventory_service_role;

-- Payment Service → schema payment
GRANT USAGE ON SCHEMA payment TO payment_service_role;
GRANT CREATE ON SCHEMA payment TO payment_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA payment TO payment_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA payment TO payment_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA payment
    GRANT ALL ON TABLES TO payment_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA payment
    GRANT ALL ON SEQUENCES TO payment_service_role;

-- Finance Service → schema finance
GRANT USAGE ON SCHEMA finance TO finance_service_role;
GRANT CREATE ON SCHEMA finance TO finance_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA finance TO finance_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA finance TO finance_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA finance
    GRANT ALL ON TABLES TO finance_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA finance
    GRANT ALL ON SEQUENCES TO finance_service_role;

-- Delivery Service → schema delivery
GRANT USAGE ON SCHEMA delivery TO delivery_service_role;
GRANT CREATE ON SCHEMA delivery TO delivery_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA delivery TO delivery_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA delivery TO delivery_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA delivery
    GRANT ALL ON TABLES TO delivery_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA delivery
    GRANT ALL ON SEQUENCES TO delivery_service_role;

-- POS Service → schema pos
GRANT USAGE ON SCHEMA pos TO pos_service_role;
GRANT CREATE ON SCHEMA pos TO pos_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA pos TO pos_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA pos TO pos_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA pos
    GRANT ALL ON TABLES TO pos_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA pos
    GRANT ALL ON SEQUENCES TO pos_service_role;

-- Procurement Service → schema procurement
GRANT USAGE ON SCHEMA procurement TO procurement_service_role;
GRANT CREATE ON SCHEMA procurement TO procurement_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA procurement TO procurement_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA procurement TO procurement_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA procurement
    GRANT ALL ON TABLES TO procurement_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA procurement
    GRANT ALL ON SEQUENCES TO procurement_service_role;

-- Payroll Service → schema payroll
GRANT USAGE ON SCHEMA payroll TO payroll_service_role;
GRANT CREATE ON SCHEMA payroll TO payroll_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA payroll TO payroll_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA payroll TO payroll_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA payroll
    GRANT ALL ON TABLES TO payroll_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA payroll
    GRANT ALL ON SEQUENCES TO payroll_service_role;

-- Notification Service → schema notification
GRANT USAGE ON SCHEMA notification TO notification_service_role;
GRANT CREATE ON SCHEMA notification TO notification_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA notification TO notification_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA notification TO notification_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA notification
    GRANT ALL ON TABLES TO notification_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA notification
    GRANT ALL ON SEQUENCES TO notification_service_role;

-- Reporting Service → schema reporting
GRANT USAGE ON SCHEMA reporting TO reporting_service_role;
GRANT CREATE ON SCHEMA reporting TO reporting_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA reporting TO reporting_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA reporting TO reporting_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA reporting
    GRANT ALL ON TABLES TO reporting_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA reporting
    GRANT ALL ON SEQUENCES TO reporting_service_role;

-- Audit Service → schema audit
GRANT USAGE ON SCHEMA audit TO audit_service_role;
GRANT CREATE ON SCHEMA audit TO audit_service_role;
GRANT ALL ON ALL TABLES IN SCHEMA audit TO audit_service_role;
GRANT ALL ON ALL SEQUENCES IN SCHEMA audit TO audit_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA audit
    GRANT ALL ON TABLES TO audit_service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA audit
    GRANT ALL ON SEQUENCES TO audit_service_role;

-- ================================================
-- STEP 6: Buat tabel migrations di setiap schema
-- ================================================
-- Setiap service mempunyai tabel migration sendiri
-- Laravel menggunakan tabel ini untuk tracking migration

CREATE TABLE IF NOT EXISTS identity.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS customer.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS catalog.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS orders.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS inventory.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS payment.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS finance.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS delivery.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS pos.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS procurement.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS payroll.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS notification.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS reporting.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS audit.migrations (
    id SERIAL PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INTEGER NOT NULL
);

-- ================================================
-- STEP 7: Verifikasi — Test isolasi
-- ================================================
-- Jalankan query berikut untuk memverifikasi isolasi:
--
-- Sebagai order_service_role, coba akses schema catalog:
--   SET ROLE order_service_role;
--   SELECT * FROM catalog.migrations;  -- Harus ERROR: permission denied
--   RESET ROLE;
--
-- Sebagai order_service_role, akses schema orders:
--   SET ROLE order_service_role;
--   SELECT * FROM orders.migrations;   -- Harus BERHASIL
--   RESET ROLE;
--
-- Sebagai catalog_service_role, coba akses schema orders:
--   SET ROLE catalog_service_role;
--   SELECT * FROM orders.migrations;   -- Harus ERROR: permission denied
--   RESET ROLE;

-- ================================================
-- STEP 8: Ringkasan Schema dan Role
-- ================================================
-- | Schema        | Role                        | Akses          |
-- |---------------|-----------------------------|----------------|
-- | identity      | identity_service_role        | FULL (CRUD)    |
-- | customer      | customer_service_role        | FULL (CRUD)    |
-- | catalog       | catalog_service_role         | FULL (CRUD)    |
-- | orders        | order_service_role           | FULL (CRUD)    |
-- | inventory     | inventory_service_role       | FULL (CRUD)    |
-- | payment       | payment_service_role         | FULL (CRUD)    |
-- | finance       | finance_service_role         | FULL (CRUD)    |
-- | delivery      | delivery_service_role        | FULL (CRUD)    |
-- | pos           | pos_service_role             | FULL (CRUD)    |
-- | procurement   | procurement_service_role     | FULL (CRUD)    |
-- | payroll       | payroll_service_role         | FULL (CRUD)    |
-- | notification  | notification_service_role    | FULL (CRUD)    |
-- | reporting     | reporting_service_role       | FULL (CRUD)    |
-- | audit         | audit_service_role           | FULL (CRUD)    |
--
-- Setiap role HANYA bisa akses schema miliknya.
-- Cross-schema access = DENIED.
