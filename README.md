# Tasuku

A simple task management app built with Laravel.

## Requirements

- PHP
- Composer
- Node.js & npm
- PostgreSQL

## Setup

```bash
git clone https://github.com/hzokbe/tasuku.git

cd tasuku

composer install

npm install

cp .env.example .env

php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tasuku
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run the migrations:

```bash
php artisan migrate
```

## Running

```bash
composer dev
```

This starts the Laravel server, queue worker, and Vite dev server simultaneously.

The app will be available at [http://localhost:8000](http://localhost:8000).
