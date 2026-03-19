<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## HashThink Test Project Setup

This project uses Laravel for the API/backend and a Node.js Socket.IO server for broadcasting transaction status updates.

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- npm
- PostgreSQL database (Supabase)

## 1. Clone Laravel Project

```bash
git clone <your-repository-url> hashthink-test
cd hashthink-test
```

## 2. Install PHP Dependencies

```bash
composer install
```

## 3. Install Node Dependencies

```bash
npm install
```

## 4. Environment Setup

Create `.env` from example:

```bash
cp .env.example .env
php artisan key:generate
```

### Database Config in `.env` (Supabase)

Use your Supabase PostgreSQL connection details:

```env
APP_NAME="HashThink Test"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=aws-0-<region>.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.<project_ref>
DB_PASSWORD=<your_supabase_password>
DB_SSLMODE=require
```

Notes:
- `DB_HOST`, `DB_PORT`, `DB_USERNAME`, and `DB_PASSWORD` come from Supabase database settings.
- Keep credentials private and never commit `.env`.

### Socket.IO Details in `.env`

```env
BROADCAST_CONNECTION=socket_io
SOCKETIO_URL=http://localhost:3000
SOCKETIO_HOST=localhost
SOCKETIO_PORT=3000
```

## 5. Run Migration

```bash
php artisan migrate
```

## 6. Run Seeder

```bash
php artisan db:seed
```

## 7. Start Laravel Server

```bash
php artisan serve
```

Laravel will run at:

```text
http://127.0.0.1:8000
```

## 8. Start Queue Worker

In a new terminal (keep Laravel server running):

```bash
php artisan queue:work
```

This is required to process queued jobs like creating new transactions.

## 9. Start Socket Broadcast Server

In a new terminal (keep Laravel server running in the first terminal):

```bash
node socket-server.js
```

Socket.IO server runs on:

```text
http://localhost:3000
```

## Quick Run Order

1. Clone project
2. `composer install`
3. `npm install`
4. Configure `.env` (Supabase + Socket.IO)
5. `php artisan migrate`
6. `php artisan db:seed`
7. `php artisan serve`
8. `php artisan queue:work`
9. `node socket-server.js`

## Optional: Frontend Dev Server

If you are working on frontend assets:

```bash
npm run dev
```

## Troubleshooting

- If broadcasting does not work, confirm both servers are running (`php artisan serve` and `node socket-server.js`).
- If queued transaction creation is not working, confirm the queue worker is running (`php artisan queue:work`).
- If DB connection fails, verify Supabase host/port/user/password and `DB_CONNECTION=pgsql`.
- If migrations fail on a non-empty database, check existing schema/tables in Supabase before re-running.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
