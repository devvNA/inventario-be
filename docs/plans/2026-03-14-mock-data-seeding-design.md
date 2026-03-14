# Mock Data Seeding Design

## Context

The project currently has only minimal seed data in `database/seeders/DatabaseSeeder.php` and `database/seeders/UserRoleSeeder.php`. That is enough for basic RBAC bootstrapping, but not enough to make the application feel realistic during local development, frontend integration, or demo usage.

The goal of this design is to add structured mock data under `database/seeders/` so each relevant business/auth table has 5 records and all foreign-key relationships remain valid. The result should make list/detail endpoints and inventory flows look real without changing runtime application logic.

## Recommended Approach

Use multiple dedicated seeders with deterministic data instead of one large seeder or heavy factory usage.

Why this approach:
- keeps foreign-key ordering explicit
- makes the dataset stable across repeated runs
- makes each table's mock data easy to maintain
- fits the current repo, which only has `database/factories/UserFactory.php`

## Scope

Populate 5 records for the relevant auth/business tables used by the application:
- users
- roles
- permissions
- categories
- products
- warehouses
- merchants
- warehouse_products
- merchant_products
- transactions
- transaction_products

Out of scope:
- `sessions`
- `password_reset_tokens`
- `cache`
- `jobs`
- `failed_jobs`
- `personal_access_tokens`

These are framework/runtime tables and should not be manually populated as business mock data.

## Current Files To Reuse

- `database/seeders/DatabaseSeeder.php` - current seeder entrypoint
- `database/seeders/UserRoleSeeder.php` - current role/permission/user seeding pattern
- `database/factories/UserFactory.php` - optional reference only; not the primary strategy
- `app/Services/TransactionService.php` - reference for subtotal/tax/grand-total business rules
- `app/Services/MerchantProductService.php` - reference for warehouse-to-merchant stock semantics

## Proposed Seeder Structure

Recommended files under `database/seeders/`:
- `RolePermissionSeeder.php`
- `UserSeeder.php`
- `CategorySeeder.php`
- `ProductSeeder.php`
- `WarehouseSeeder.php`
- `MerchantSeeder.php`
- `WarehouseProductSeeder.php`
- `MerchantProductSeeder.php`
- `TransactionSeeder.php`

`DatabaseSeeder.php` should be updated to call them in safe FK order.

## Seeding Order

1. roles + permissions
2. users
3. categories
4. products
5. warehouses
6. merchants
7. warehouse_products
8. merchant_products
9. transactions + transaction_products

## Data Rules

### Roles and permissions
- Expand the current seed strategy so there are 5 unique roles and 5 unique permissions.
- Keep role names relevant to the system.
- Ensure role assignment to users remains valid.

### Users
- Seed exactly 5 users.
- Each user must have unique `email` and `phone`.
- Use stable mock photo URLs/paths.
- At least the users referenced by merchants must have keeper-compatible roles.

### Categories
- Seed exactly 5 realistic retail/inventory categories.
- Keep `name` unique.
- Fill `photo` and `tagline`.

### Products
- Seed exactly 5 products.
- Every product must reference a valid `category_id`.
- Keep `name` unique.
- Fill `thumbnail`, `about`, `price`, and `is_popular`.

### Warehouses
- Seed exactly 5 warehouses.
- Keep `name` and `phone` unique.
- Fill `address` and `photo`.

### Merchants
- Seed exactly 5 merchants.
- Every merchant must reference a valid `keeper_id`.
- Keep `name` and `phone` unique.
- Fill `address` and `photo`.

### Warehouse stock
- Seed exactly 5 `warehouse_products` rows.
- Every row must reference valid `warehouse_id` and `product_id`.
- Stock should be positive and large enough to support downstream merchant stock.

### Merchant stock
- Seed exactly 5 `merchant_products` rows.
- Every row must reference valid `merchant_id`, `product_id`, and `warehouse_id`.
- Merchant stock should be logically derived from warehouse stock and must not exceed available stock.

### Transactions
- Seed exactly 5 `transactions` rows and 5 `transaction_products` rows.
- Keep it simple: one line item per transaction.
- Every transaction must reference a valid `merchant_id`.
- Every transaction product must reference a valid `transaction_id` and `product_id`.
- `sub_total`, `tax_total`, and `grand_total` must be internally consistent.
- Follow the same tax expectation implied by `app/Services/TransactionService.php` (`tax_total = sub_total * 0.1`, `grand_total = sub_total + tax_total`).

## Implementation Notes

- Prefer deterministic arrays over uncontrolled Faker output for core relational data.
- Use `firstOrCreate`, `updateOrCreate`, or careful truncation/reset strategy only if required by the chosen implementation.
- Keep the implementation focused inside `database/seeders/` and `DatabaseSeeder.php`.
- Do not modify application services/controllers just to support seeding.
- For image columns, use stable string values only; no real file upload workflow is needed.

## Verification

Run these commands after implementation:

1. Reset and seed the database:
   - `php artisan migrate:fresh --seed`

2. Sanity-check row counts and relations:
   - inspect the main tables in the database client, or
   - use `php artisan tinker` to verify counts and sample relations

3. Verify the app still boots and tests still run:
   - `php artisan test`

4. Optional API sanity checks:
   - `php artisan route:list --path=api`
   - inspect list endpoints from the frontend or API client to confirm realistic mock output

## Expected Outcome

After implementation, the backend will have a realistic starter dataset with 5 records in each relevant table, valid foreign keys, believable inventory/merchant/transaction relationships, and a much better local/demo experience without changing core application behavior.
