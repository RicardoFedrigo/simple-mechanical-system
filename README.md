# Mechanical Workshop Management System

A pure OOP PHP 8+ workshop ERP using MVC, repository/service pattern, PDO, Bootstrap 5, SCSS, and Docker.

## Quick Start

1. Copy `.env.example` to `.env`.
2. Run `docker compose up --build -d`.
3. Run `docker compose exec php composer install`.
4. Import the database schema:
   - `docker compose exec mysql sh -c 'mysql -u root -proot workshop < /var/www/html/database/migrations/001_schema.sql'`
   - or use phpMyAdmin at `http://localhost:8081`.
5. Open `http://localhost:8080`.

## Composer Scripts

- `composer install` — install PHP dependencies.
- `composer migrate` — run the database migration script (`php database/migrate.php`).
- `composer run-script post-autoload-dump` — prints `autoload ready` after autoload generation.

> Important: The migration script requires the PHP `pdo_mysql` driver.
> The recommended workflow is to run it inside Docker:
> `docker compose exec php composer migrate`
>
> If you run PHP on the host, ensure your PHP CLI has the MySQL PDO extension installed.
> For Debian/Ubuntu hosts, install e.g. `sudo apt install php8.2-mysql`.

## Features

- Session authentication with role checks
- Admin middleware protection
- Bootstrap 5 dashboard skeleton
- Repository + service layer architecture
- SCSS assets and Dockerized environment

## UML

NOT FINISH YET! Changes will be made
![previus uml](docs/workshop_uml.png)
