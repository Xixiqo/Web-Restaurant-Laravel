# Web Restaurant (Laravel + Vue)

This repository contains:

- `backend/` → Laravel API (PHP)
- `frontend/` → Vue 3 + Vite client

## Prerequisites

- Git
- PHP 8.2+
- Composer
- Node.js 20+ and npm
- MySQL (or compatible database)

## 1) Clone the repository

```bash
git clone https://github.com/Xixiqo/Web-Restaurant-Laravel.git
cd Web-Restaurant-Laravel
```

## 2) Backend setup (`backend/`)

```bash
cd backend
composer install
cp .env.example .env
```

Edit `.env` and set at least:

- `APP_URL` (example: `http://localhost:8000`)
- `FRONTEND_URL` (example: `http://localhost:5173`)
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Optional payment config: `XENDIT_SECRET_KEY`, `XENDIT_CALLBACK_TOKEN`

Then run:

```bash
php artisan key:generate
php artisan migrate --seed
```

Install backend frontend-assets dependencies:

```bash
npm install
```

## 3) Frontend setup (`frontend/`)

From repository root:

```bash
cd frontend
npm install
```

The frontend API base URL is currently `http://localhost:8000/api` (see `frontend/src/services/api.js`), so run backend on port `8000`.

## 4) Run the project

Open two terminals.

### Terminal A (backend API)

```bash
cd backend
php artisan serve --port=8000
```

### Terminal B (frontend app)

```bash
cd frontend
npm run dev
```

Now open: `http://localhost:5173`

## Default seeded accounts

- Admin: `admin@sunuyhouse.com` / `password`
- User: `test@example.com` / `password`

For security, change these credentials (or remove seeded users) before deploying to production.

### Frontend

```bash
cd frontend
npm run dev
npm run build
npm run lint
```

### Backend

```bash
cd backend
php artisan serve
php artisan test
npm run build
```
