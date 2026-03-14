# database/

## Package Identity
This folder contains schema, factories, and seed data for the inventory backend.
It defines the core domain tables for users, roles/permissions, categories, products, warehouses, merchants, warehouse stock, merchant stock, transactions, jobs, sessions, and personal access tokens.

## Setup & Run
- `php artisan migrate`
- `php artisan db:seed`
- `php artisan migrate:fresh --seed`
- `php artisan test`
- `php artisan tinker`

## Patterns & Conventions
- Keep migration table names aligned with model and relation usage. Examples: `merchant_products` maps to `app/Models/MerchantProduct.php`, and `warehouse_products` maps to `app/Models/WarehouseProduct.php`.
- Inventory flow is split across two stock tables: warehouse inventory in `warehouse_products`, merchant-facing inventory in `merchant_products`.
- Sales are modeled with a transaction header plus line items: `transactions` and `transaction_products`.
- Role/permission tables come from `database/migrations/2025_04_07_022255_create_permission_tables.php` and are used by Spatie Permission.
- API token storage comes from `database/migrations/2025_04_07_135030_create_personal_access_tokens_table.php` and is used by Sanctum.
- Several domain models use soft deletes (`Product`, `Merchant`, `Warehouse`, `Transaction`, `MerchantProduct`, `WarehouseProduct`), so schema and query changes should preserve `deleted_at` behavior when relevant.
- `database/seeders/DatabaseSeeder.php` currently calls `database/seeders/UserRoleSeeder.php`.
- `database/seeders/UserRoleSeeder.php` seeds `manager`, `keeper`, and `customer` roles, creates base permissions, and creates default users for those roles.
- Keep foreign keys and cascade behavior consistent with existing pivot-table migrations.

## Key Files
- `database/migrations/2025_03_17_023219_create_categories_table.php`
- `database/migrations/2025_03_17_023219_create_products_table.php`
- `database/migrations/2025_03_17_023219_create_warehouses_table.php`
- `database/migrations/2025_03_17_023220_create_merchants_table.php`
- `database/migrations/2025_03_17_023220_create_warehouse_products_table.php`
- `database/migrations/2026_03_17_023220_create_merchant_products_table.php`
- `database/migrations/2025_03_17_023222_create_transactions_table.php`
- `database/migrations/2025_03_18_031849_create_transaction_products_table.php`
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/UserRoleSeeder.php`

## JIT Index Hints
- Stock schema: `rg -n "warehouse_products|merchant_products|stock" database/migrations`
- Auth/RBAC schema: `rg -n "permission|personal_access_tokens|users" database/migrations`
- Seeded roles/users: `rg -n "Role::|Permission::|assignRole|@example.com" database/seeders`
- Factories: `rg -n "Factory" database/factories`
- Migration order: `ls database/migrations`

## Common Gotchas
- `config/database.php` defaults to `sqlite`, but the active local environment may switch to MySQL through `.env`.
- `phpunit.xml` leaves DB-specific testing overrides commented out, so DB-heavy tests may need explicit environment setup.
- Queue, cache, session, and token infrastructure tables may be required locally even when the feature work is not directly about inventory.

## Pre-PR Checks
`php artisan migrate --pretend && php artisan test`
