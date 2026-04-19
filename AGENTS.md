# inventario-backend

## Project Snapshot

This is a single Laravel 12 backend for warehouse/store inventory management.
Primary code lives in `app/`, schema and seed data live in `database/`, and API routes are defined in `routes/api.php`.
The main runtime pattern is `routes -> Form Requests -> Controllers -> Services -> Repositories -> Eloquent Models/Resources`.
Use this file for repo-wide guidance, then load the nearest sub-file for local conventions:

- Application code: [app/AGENTS.md](app/AGENTS.md)
- Database/schema work: [database/AGENTS.md](database/AGENTS.md)
- System overview: [TECHNICAL OVERVIEW.md](TECHNICAL%20OVERVIEW.md)

## Root Setup Commands

- `composer install`
- `npm install`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan storage:link`
- `php artisan migrate`
- `php artisan db:seed`
- `composer dev`
- `php artisan test`
- `npm run build`

## Universal Conventions

- Follow the existing backend layering: controller -> service -> repository.
- Put business rules in `app/Services/`, not in controllers.
- Keep query shaping and eager loading in `app/Repositories/`.
- Reuse Form Requests in `app/Http/Requests/` for request validation when an endpoint already follows that pattern.
- Keep stock mutations transactional when touching merchant or warehouse inventory.
- Keep uploaded media on the `public` disk and preserve URL accessor behavior in models.
- Preserve route middleware semantics in `routes/api.php`, especially `auth:sanctum` and `role:*` groups.
- Prefer updating `TECHNICAL OVERVIEW.md` or a nearby `AGENTS.md` when architecture or workflow changes.

## Security & Secrets

- Never commit `.env`, API tokens, DB credentials, or copied production secrets.
- Keep Sanctum, session, and CORS changes aligned with the frontend origin and stateful domains.
- Do not bypass `manager` / `keeper` route protections when adding endpoints.
- Treat uploaded files and user-provided fields as untrusted input.

## JIT Index

### Package Structure

- App code: `app/` -> [app/AGENTS.md](app/AGENTS.md)
- Database schema and seeders: `database/` -> [database/AGENTS.md](database/AGENTS.md)
- API routes: `routes/api.php`
- Bootstrap and middleware aliases: `bootstrap/app.php`
- Project overview: `TECHNICAL OVERVIEW.md`

### Quick Find Commands

- API routes: `php artisan route:list --path=api`
- Controllers: `rg -n "class .*Controller" app/Http/Controllers`
- Services: `rg -n "class .*Service|DB::transaction|ValidationException" app/Services`
- Repositories: `rg -n "class .*Repository|->with\(|paginate\(" app/Repositories`
- Request validation: `rg -n "rules\(" app/Http/Requests`
- Auth and roles: `rg -n "auth:sanctum|role:|HasRoles|createToken" app routes config`
- Stock movement: `rg -n "stock|merchant_products|warehouse_products|transactionProducts" app database`
- Tests: `rg -n "class .*Test" tests`

## Definition of Done

- Relevant checks pass: `php artisan test`
- If assets were touched, build succeeds: `npm run build`
- Diff is limited to agreed files/paths
- Any material workflow or architecture change updates the relevant `AGENTS.md` or `TECHNICAL OVERVIEW.md`
