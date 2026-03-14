# TECHNICAL OVERVIEW

## Project Summary

This repository is a Laravel 12 backend for warehouse and store inventory management. The backend exposes an API for authentication, users, roles, categories, products, warehouses, merchants, merchant stock assignment, and sales transactions. The codebase follows a layered Laravel structure where HTTP controllers delegate to services, services coordinate business rules, repositories handle Eloquent data access, and resources shape API responses.

## Core Components

### 1. Application bootstrap and routing
- `bootstrap/app.php` configures the Laravel application, loads `routes/web.php`, `routes/api.php`, and `routes/console.php`, enables `statefulApi()`, and registers Spatie role/permission middleware aliases.
- `routes/api.php` is the main API entry point. It defines:
  - public auth routes: `token-login`, `register`, `login`
  - authenticated routes with `auth:sanctum`
  - manager-only routes with `role:manager`
  - shared manager/keeper routes with `role:manager|keeper`

### 2. HTTP layer
The HTTP layer lives under `app/Http/` and is divided into controllers, form requests, and resources.

- Controllers in `app/Http/Controllers/` stay relatively thin and delegate to services.
  - `AuthController.php` handles register, session login, token login, logout, and current-user responses.
  - `CategoryController.php`, `ProductController.php`, `WarehouseController.php`, and `MerchantController.php` expose CRUD APIs.
  - `WarehouseProductController.php` and `MerchantProductController.php` manage stock assignment between warehouses and merchants.
  - `TransactionController.php` records sales transactions and exposes transaction history.
  - `RoleController.php`, `UserController.php`, and `UserRoleController.php` manage users and RBAC-related operations.
- Form requests in `app/Http/Requests/` centralize validation, for example `LoginRequest.php`, `RegisterRequest.php`, `ProductRequest.php`, and `TransactionRequest.php`.
- Resources in `app/Http/Resources/` provide API serialization. `UserResource.php` explicitly shapes output and includes roles and merchant data, while several other resources currently pass through model data.

### 3. Service layer
Business logic is implemented in `app/Services/`.

- `AuthService.php` handles user photo upload before delegating user creation or login to the repository layer.
- `CategoryService.php`, `ProductService.php`, `MerchantService.php`, and `WarehouseService.php` manage CRUD logic plus file upload and cleanup on the public disk.
- `MerchantProductService.php` coordinates stock movement between `warehouse_products` and `merchant_products` using database transactions.
- `TransactionService.php` validates merchant ownership, checks stock, calculates totals, reduces merchant stock, creates transaction headers, and creates transaction line items inside a database transaction.
- `UserRoleService.php` delegates role assignment/removal flows.

### 4. Repository layer
Repositories under `app/Repositories/` isolate Eloquent queries and persistence logic.

- CRUD repositories such as `CategoryRepository.php`, `ProductRepository.php`, `MerchantRepository.php`, `WarehouseRepository.php`, `UserRepository.php`, and `TransactionRepository.php` select fields, eager-load relations, paginate results, and persist updates.
- `AuthRepository.php` handles registration, session login, and Sanctum token creation.
- `MerchantProductRepository.php` and `WarehouseProductRepository.php` encapsulate stock reads and stock updates for pivot tables.
- `TransactionRepository.php` creates transaction records, creates transaction product rows, and returns eager-loaded transaction views.

### 5. Domain models
The domain model layer lives in `app/Models/`.

- `User.php` extends `Authenticatable`, uses `HasApiTokens` and `HasRoles`, and exposes a `merchant()` relation for keeper-owned merchant access.
- `Category.php`, `Product.php`, `Warehouse.php`, `Merchant.php`, and `Transaction.php` model the main business entities.
- `MerchantProduct.php`, `WarehouseProduct.php`, and `TransactionProduct.php` model pivot and line-item records.
- Several models use soft deletes and URL accessors for media paths, for example `User.php`, `Product.php`, `Merchant.php`, and `Warehouse.php`.

### 6. Authentication and authorization
- `laravel/sanctum` is used for authenticated API access and bearer token creation.
- `spatie/laravel-permission` provides roles and permissions.
- The `User` model uses `HasRoles`, and route protection in `routes/api.php` relies on `auth:sanctum` and `role:*` middleware aliases registered in `bootstrap/app.php`.
- `UserRoleSeeder.php` creates `manager`, `keeper`, and `customer` roles, creates base permissions, and seeds one default user per role.

### 7. Database and persistence
- Schema lives in `database/migrations/`.
- Core business tables include categories, products, warehouses, merchants, `warehouse_products`, `merchant_products`, transactions, and transaction products.
- Laravel default infrastructure tables are also present for cache, jobs, sessions, and personal access tokens.
- Seeders live in `database/seeders/`, with `DatabaseSeeder.php` invoking `UserRoleSeeder.php`.

### 8. Frontend/build tooling
This repository is primarily a backend API, but it still includes standard Laravel asset scaffolding:
- `resources/js/app.js`
- `resources/css/app.css`
- `vite.config.js`
- `package.json` with Vite and Tailwind CSS 4

## Component Interactions

### Request and control flow
A typical API request follows this path:
1. Laravel boots through `bootstrap/app.php`.
2. `routes/api.php` resolves the request to a controller action.
3. Route middleware enforces authentication and role rules.
4. A Form Request validates input before controller execution when a dedicated request class is used.
5. The controller delegates to a service.
6. The service applies business rules and calls one or more repositories.
7. Repositories query or mutate Eloquent models.
8. The controller returns JSON directly or through an API resource.

### Example: authentication flow
- `AuthController.php` receives validated credentials from `LoginRequest.php` or `RegisterRequest.php`.
- `AuthService.php` optionally stores uploaded profile photos on the public disk.
- `AuthRepository.php` performs `Auth::attempt()`, session regeneration for web login, or Sanctum token creation for token login.
- `UserResource.php` formats the current user response and includes role and merchant context.

### Example: warehouse to merchant stock assignment
- `MerchantProductController.php` receives a validated request.
- `MerchantProductService.php` starts a database transaction.
- It checks the warehouse stock through `WarehouseProductRepository.php`.
- It ensures the product is not already assigned to the merchant through `MerchantProductRepository.php`.
- It reduces stock in `warehouse_products` and creates a new row in `merchant_products`.

### Example: transaction flow
- `TransactionController.php` accepts nested line items validated by `TransactionRequest.php`.
- `TransactionService.php` loads the merchant, verifies the authenticated keeper owns that merchant, checks each merchant stock record, loads product prices, calculates line subtotals plus tax and grand total, reduces merchant stock, creates the transaction, and creates line items.
- `TransactionRepository.php` later reads transactions with eager-loaded merchant, keeper, product, and category relations for list/detail responses.

### Data access patterns
- Query shaping and eager loading live in repositories rather than controllers.
- File upload and file deletion logic live in services rather than controllers.
- Relationship traversal is heavily Eloquent-based, especially for categories, merchants, warehouses, products, and transaction products.

## Deployment Architecture

### Runtime dependencies
- PHP `^8.2`
- Laravel `^12.0`
- Composer for PHP dependencies
- Node/npm for Vite asset tooling
- A relational database, with `config/database.php` defaulting to SQLite but supporting MySQL, MariaDB, PostgreSQL, and SQL Server through environment configuration

### Development commands
- `composer install`
- `npm install`
- `php artisan key:generate`
- `php artisan storage:link`
- `php artisan migrate`
- `php artisan db:seed`
- `composer dev`
- `php artisan test`
- `npm run build`

### Build and local development setup
- `composer dev` runs three processes concurrently:
  - `php artisan serve`
  - `php artisan queue:listen --tries=1`
  - `npm run dev`
- `npm run build` builds production assets with Vite.

### External integrations and infrastructure concerns
- Sanctum stateful domains are configured in `config/sanctum.php`.
- CORS is configured in `config/cors.php` and currently allows `http://localhost:5174` with credentials.
- Sessions default to the database driver in `config/session.php`.
- Queues default to the database driver in `config/queue.php`.
- Redis is configured as an available backend in `config/database.php`, but no custom Redis-specific application code is present in the project files reviewed.

### Containerization
- `laravel/sail` is installed as a development dependency, but there is no custom root-level Docker or Compose setup in this repository snapshot.

## Runtime Behavior

### Application initialization
- Laravel initializes via `bootstrap/app.php`.
- Middleware aliases for `role`, `permission`, and `role_or_permission` are registered during bootstrap.
- `statefulApi()` is enabled so Sanctum SPA authentication can use cookies for stateful requests.

### Request handling
- Public endpoints allow registration and both session/token login.
- Protected endpoints require Sanctum authentication.
- Manager-only APIs handle administrative CRUD and stock assignment flows.
- Manager/keeper APIs allow shared read access to categories, products, warehouses, and merchant transaction flows.

### Business workflows
- CRUD workflows follow a repeated pattern: controller -> service -> repository -> model.
- Media workflows store uploaded files on the public disk and remove prior files when records are updated or deleted.
- Inventory workflows are split across warehouse stock, merchant stock, and transaction sale flows.
- Transaction workflows use database transactions to keep stock updates and transaction records consistent.

### Error handling
- Validation is primarily handled by Laravel Form Requests and `ValidationException` in services/repositories.
- Some controllers catch `ModelNotFoundException` and return JSON 404 responses.
- Authentication failures return JSON 401 responses from the auth repository.

### Background tasks and async behavior
- Queue infrastructure is configured and local development starts a queue listener through `composer dev`.
- No custom jobs, scheduled commands, or non-default console commands were found in the reviewed application files.

## Architectural Style

The project uses a layered Laravel architecture with explicit service and repository abstractions on top of Eloquent. The dominant interaction pattern is:

`routes -> middleware -> form requests -> controllers -> services -> repositories -> models/resources`

This keeps HTTP concerns, business rules, and database access separated while still using Laravel conventions for models, validation, authentication, storage, and response formatting.
