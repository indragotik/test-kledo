# Kledo (Scaffolded)

This repository is a scaffolded Laravel 12 application with a Clean Code architecture example (User and Product modules), Sanctum authentication, Swagger (l5-swagger) annotations, and PHPUnit tests.

## What was generated

- Clean Code structure under `app/` with `DTO`, `Repositories`, `Services`, `Http/Controllers`, and `Http/Requests`.
- `AuthController`, `AuthService`, `RegisterRequest` and `LoginRequest` for Sanctum-based auth.
- `User` module: `UserDTO`, `UserRepository`, `UserService`, controller and requests.
- `Product` module: `Product` model, `ProductDTO`, `ProductRepository`, `ProductService`, controller, requests, and migration.
- Basic PHPUnit tests for Auth and Product features and service unit tests.

## Setup

Requirements:

- PHP (as required by your project)
- Composer
- A database (MySQL, SQLite, etc.)

Steps:

1. Install composer dependencies (if not already present):

```bash
composer install
```

2. Copy your `.env` from `.env.example` and set DB credentials. For quick tests you can use SQLite:

```bash
touch database/database.sqlite
cp .env.example .env
# set DB_CONNECTION=sqlite and DB_DATABASE=/full/path/to/database/database.sqlite in .env
php artisan key:generate
```

3. Run migrations:

```bash
php artisan migrate
```

4. (Optional) Install Sanctum and l5-swagger per the packages' docs if not present. This scaffold expects Sanctum available for token creation.

Sanctum quick steps:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

l5-swagger quick steps:

```bash
composer require "darkaonline/l5-swagger"
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

Then visit `/api/documentation` after generating docs.

## API Endpoints (examples)

Auth:

- POST /api/register {name,email,password,password_confirmation}
- POST /api/login {email,password}
- POST /api/logout (auth)
- GET /api/me (auth)

Products (auth required):

- GET /api/products
- GET /api/products/{id}
- POST /api/products {name,description,price,stock,picture_url}
- PUT /api/products/{id}
- DELETE /api/products/{id}

## Running tests

Run the PHPUnit suite:

```bash
./vendor/bin/phpunit
```

## Notes and next steps

- The scaffolded Services and Repositories follow the rule: controllers call services; services call repositories.
- Add binding in a service provider (`AppServiceProvider`) to bind interfaces to implementations if you switch to interfaces.
- Swagger annotations are included in controllers; configure `l5-swagger` to generate docs.

If you want, I can:

- Wire up service provider bindings.
- Add route definitions to `routes/api.php`.
- Generate more comprehensive tests and seeders.
