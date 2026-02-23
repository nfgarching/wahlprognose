# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Start full dev environment (server + queue + logs + vite, all concurrently)
composer dev

# Build frontend assets
npm run build

# Fix code style
composer lint

# Run full test suite (config:clear → pint check → artisan test)
composer test

# Run a single test or filter by name
php artisan test --filter=TestName
./vendor/bin/pest --filter=TestName

# Run migrations
php artisan migrate
```

## Architecture

**Stack:** Laravel 12, Livewire 4, Flux UI v2, Tailwind CSS v4, Vite 7, SQLite (default).

**Authentication** is handled by **Laravel Fortify** (`FortifyServiceProvider`). Fortify actions live in `app/Actions/Fortify/`. The provider registers all auth views pointing to Livewire-rendered blade templates in `resources/views/livewire/auth/`.

**Livewire components** follow a mirrored structure:
- PHP classes: `app/Livewire/**`
- Blade views: `resources/views/livewire/**`

Routes are split into `routes/web.php` (public + dashboard) and `routes/settings.php` (authenticated user settings). Settings routes use `Route::livewire()` to bind Livewire components directly to URLs.

**Layout system:**
- `layouts/app/sidebar.blade.php` — full app shell with Flux sidebar, mobile header, and user menu
- `layouts/app.blade.php` — thin wrapper forwarding to the sidebar layout
- `layouts/auth/` — multiple auth layout variants (card, simple, split)

**Flux UI** (`<flux:*>` components) is used throughout views for all UI elements (sidebar, menus, forms, buttons, etc.).

**Testing** uses Pest v4. Feature tests extend `Tests\TestCase` with `RefreshDatabase` and run against an in-memory SQLite database.

**Code style** is enforced by Laravel Pint with the `laravel` preset (`pint.json`).
