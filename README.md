# Pulse

> A beautiful, real-time website uptime monitoring dashboard built with Laravel and Livewire.

[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-v4-38B2AC?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

Pulse gives you a single dashboard to monitor the health of your websites and APIs. Add URLs, track response times, and see at a glance which of your services are operational, degraded, or down.

---

## ✨ Features

- **Real-time monitoring** — Automatic checks every 5 minutes with on-demand refresh
- **Status intelligence** — Operational (2xx–3xx), Degraded (4xx), or Down (5xx/errors)
- **Response time tracking** — Measure and compare latency across all your sites
- **Stats at a glance** — Total sites, operational count, average response time
- **Modern auth** — Registration, login, 2FA, password reset, email verification
- **Flux UI** — Polished interface built with the official Livewire component library
- **Dark mode** — System-aware theme with smooth transitions
- **Fully tested** — Pest feature and unit tests with CI on push

---

## 🛠 Tech Stack

| Layer      | Technology                  |
| ---------- | --------------------------- |
| Framework  | Laravel 12                  |
| Frontend   | Livewire 4, Flux UI, Alpine.js |
| Styling    | Tailwind CSS v4             |
| Auth       | Laravel Fortify             |
| Build      | Vite 7                      |
| Tests      | Pest 3                      |

---

## 🚀 Quick Start

### Requirements

- PHP 8.2+
- Composer
- Node.js 22+
- SQLite (or MySQL/PostgreSQL)

### Installation

```bash
# Clone the repository
git clone https://github.com/aplactuan/pulse.git
cd pulse

# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup (SQLite by default)
touch database/database.sqlite
php artisan migrate

# Build assets
npm run build
```

### Run Locally

```bash
# Option 1: One command (server + queue + Vite)
composer run dev

# Option 2: Manual
php artisan serve        # Terminal 1
php artisan queue:listen # Terminal 2 (if using queues)
npm run dev              # Terminal 3 (for hot reload)
```

Visit [http://localhost:8000](http://localhost:8000), register, and add your first site.

---

## ⚙️ Configuration

| Variable   | Description                    | Default  |
| ---------- | ------------------------------ | -------- |
| `APP_NAME` | Application name               | Laravel  |
| `DB_CONNECTION` | Database driver           | sqlite   |
| `APP_URL`  | Base URL for links             | localhost |

See `.env.example` for full options.

---

## 🧪 Testing

```bash
# Run all tests (includes lint check)
composer test

# Run tests only
php artisan test --compact

# Run specific test file
php artisan test --compact tests/Feature/SiteMonitorDashboardTest.php
```

---

## 📁 Project Structure

```
app/
├── Livewire/
│   └── SiteMonitor.php    # Core monitoring logic
├── Models/
│   ├── Site.php
│   └── User.php
resources/
├── views/
│   ├── livewire/site-monitor.blade.php
│   └── partials/site-monitor/
tests/
├── Feature/
│   ├── SiteMonitorDashboardTest.php
│   └── SiteTest.php
```

---

## 📄 License

MIT License.
