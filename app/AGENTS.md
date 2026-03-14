# app/

## Package Identity
This folder contains the core Laravel application code.
Most backend feature work happens here across controllers, requests, resources, services, repositories, and models.
The dominant implementation style is thin controllers, service-layer business rules, repository-layer data access, and Eloquent models/resources for persistence and API output.

## Setup & Run
- `composer install`
- `php artisan serve`
- `php artisan queue:listen --tries=1`
- `php artisan route:list --path=api`
- `php artisan test`
- `composer dev`

## Patterns & Conventions
- Keep controllers thin. Follow `app/Http/Controllers/ProductController.php` and `app/Http/Controllers/TransactionController.php`.
- Put validation in `app/Http/Requests/*Request.php`. Examples: `app/Http/Requests/ProductRequest.php`, `app/Http/Requests/TransactionRequest.php`, `app/Http/Requests/UserRoleRequest.php`.
- Put business rules in services. Copy the style in `app/Services/ProductService.php`, `app/Services/TransactionService.php`, and `app/Services/MerchantProductService.php`.
- Put query/eager-loading logic in repositories. See `app/Repositories/ProductRepository.php`, `app/Repositories/MerchantRepository.php`, and `app/Repositories/TransactionRepository.php`.
- Keep stock mutations transactional. The main examples are `app/Services/TransactionService.php` and `app/Services/MerchantProductService.php`.
- Keep file upload and cleanup logic in services, not controllers. See `app/Services/CategoryService.php`, `app/Services/ProductService.php`, `app/Services/MerchantService.php`, and `app/Services/WarehouseService.php`.
- Preserve model accessor behavior for media URLs. Examples: `app/Models/User.php`, `app/Models/Product.php`, `app/Models/Merchant.php`, and `app/Models/Warehouse.php`.
- Route authorization is mostly enforced in `routes/api.php`; new endpoints should be placed into the correct `auth:sanctum` and `role:*` groups.
- Repository reads commonly use explicit `select([...])`, eager loading, and pagination. Keep that style unless the task requires broader model hydration.
- Resources are mixed: `app/Http/Resources/UserResource.php` customizes output, while `app/Http/Resources/CategoryResource.php`, `ProductResource.php`, and `TransactionResource.php` currently pass through model attributes.
- Inventory attachment endpoints use dedicated services/repositories rather than controller-level relation logic. See `app/Http/Controllers/MerchantProductController.php` and `app/Http/Controllers/WarehouseProductController.php`.

## Key Files
- `app/Http/Controllers/AuthController.php` - register, session login, token login, logout, current user
- `app/Http/Controllers/TransactionController.php` - sales transaction APIs and merchant transaction listing
- `app/Services/TransactionService.php` - stock validation, subtotal/tax/grand total calculation, transaction creation
- `app/Services/MerchantProductService.php` - stock movement between warehouse and merchant inventory
- `app/Repositories/TransactionRepository.php` - eager-loaded transaction reads and line-item persistence
- `app/Models/Product.php` - category relation, merchant/warehouse relations, stock helpers, thumbnail accessor
- `app/Models/User.php` - Sanctum tokens, roles, merchant relation
- `app/Http/Resources/UserResource.php` - explicit user API response shape
- `app/Http/Requests/TransactionRequest.php` - nested product payload validation

## JIT Index Hints
- Auth flow: `rg -n "createToken|Auth::attempt|auth:sanctum" app routes`
- Role flow: `rg -n "assignRole|HasRoles|role:" app routes`
- Upload handling: `rg -n "store\('|deletePhoto|Storage::disk\('public'" app/Services app/Models`
- Stock movement: `rg -n "updateStock|stock|warehouse_id|merchant_id" app/Services app/Repositories`
- Eager loading: `rg -n "->with\(" app/Repositories`
- Validation rules: `rg -n "class .*Request|rules\(" app/Http/Requests`
- Resource serialization: `rg -n "class .*Resource|toArray\(" app/Http/Resources`

## Common Gotchas
- `app/Http/Requests/CategoryRequest.php` and `app/Http/Requests/ProductRequest.php` currently require image uploads on both create and update.
- Merchant stock and warehouse stock are separate tables: `merchant_products` and `warehouse_products`. Stock movement is handled explicitly in services.
- `app/Http/Resources/UserResource.php` expects roles and merchant context to be available when shaping auth/user responses.

## Pre-PR Checks
`php artisan test && php artisan route:list --path=api`
