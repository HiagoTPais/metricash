# Metricash - Financial Management & Cryptocurrency Platform

A financial management platform built with Laravel and Vue.js that allows users to track income/expenses, manage cryptocurrency wallets, and monitor financial indicators.

## Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0 or higher
- Git

## Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/metricash.git
cd metricash
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node.js Dependencies
```bash
npm install
```

### 4. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=metricash
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 6. Run Database Migrations and Seed
```bash
php artisan migrate:fresh --seed

```

### 7. Run Frontend Server
```bash
npm run dev
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

---
