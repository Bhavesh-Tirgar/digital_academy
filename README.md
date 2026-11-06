# 🎓 Digital Academy — Production EdTech & Learning Management Platform

[![Laravel 11](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![PWA Ready](https://img.shields.io/badge/PWA-Offline%20Capable-5A0FC8?style=for-the-badge&logo=pwa&logoColor=white)](https://web.dev/progressive-web-apps/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

**Digital Academy** is a high-performance, enterprise-grade engineering education platform and creator marketplace built on Laravel 11. Designed for production scale, it delivers lifetime student course ownership, creator revenue splits with anti-fraud escrow, multi-gateway payments, AI-assisted code mentoring, and real-time live broadcasting.

---

## 🏛️ Platform Architecture & Core Pillars

### 1. 🛡️ Anti-Fraud Escrow & Lifetime Student Access
- **Zero Subscription Lock-In for Learners**: Students pay once per course for lifetime access to video modules, source code, and live cohorts.
- **80/20 Instructor Revenue Split**: Instructors earn an 80% net share of every course enrollment.
- **Curriculum Escrow Protection**: Student funds are held securely in platform escrow until the instructor completes and verifies the course syllabus, protecting learners against incomplete courses.

### 2. 💳 Multi-Gateway Payment Engine
- **Direct Card Settlement (Stripe)**: 256-bit SSL encrypted credit and debit card payments via Stripe Payment Intents.
- **UPI & NetBanking (Razorpay)**: Seamless Indian and global payment support including Google Pay, PhonePe, Paytm, and QR code billing.
- **Digital Wallets (PayPal)**: International payment checkout and recurring instructor creator memberships.
- **Free Course Handling ($0.00)**: Instant one-click complimentary course enrollment without credit card hurdles.

### 3. 🎮 Pillar 1: Dopamine Gamification Engine
- **Synthesized Audio**: Native Web Audio API sound generator (8-bit chiptune fanfare, error thuds, level-up arpeggios) without external audio asset latency.
- **Celebration Effects**: Canvas Confetti cannon triggers on 100% quiz scores and milestone course completion.
- **XP & Mastery Ranks**: Dynamic student progression (Apprentice → Journeyman → Master → Principal Engineer) with streak counters and animated toasts.

### 4. 🤖 Pillar 2: Professor AI Code Debugger & Sandbox
- **Socratic Hint Engine**: Guides students to find algorithm bugs themselves rather than spoiling solutions.
- **Big-O Complexity Analyzer**: Live static analyzer estimating algorithmic time and space complexity with optimization recommendations.
- **Interactive Code Sandbox**: Multi-language interactive playground for Python, JavaScript, and PHP with live output console.

### 5. 📜 Pillar 3: Cryptographic Credential Ledger
- **SHA-256 Ledger Integrity**: Certificates feature tamper-proof SHA-256 validation hashes registered on the platform ledger.
- **1-Click LinkedIn Sharing**: Formatted credential sharing with custom badge metadata and course validation links.
- **Public Verification & QR Scanner**: Real-time camera QR scanner and public verification portal (`/verify-certificate`).

### 6. 📱 Pillar 4: Progressive Web App (PWA) Offline Shell
- **Service Worker Caching**: Intelligent cache-first strategy for static assets and offline fallback (`/offline.html`).
- **Installable Desktop & Mobile App**: Compliant `manifest.json` with multi-size SVG app icons and standalone display mode.

---

## ⚙️ Technical Requirements

- **PHP**: 8.2 or 8.3 with extensions: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`
- **Database**: SQLite, MySQL 8.0+, or PostgreSQL 14+
- **Node.js**: 18.x or 20.x with npm / pnpm
- **Composer**: 2.x

---

## 🚀 Quickstart & Local Setup

```bash
# 1. Clone repository
git clone https://github.com/Bhavesh-Tirgar/digital_academy.git
cd digital_academy

# 2. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Install and build frontend assets
npm install
npm run build

# 4. Environment configuration
cp .env.example .env
php artisan key:generate

# 5. Database setup & migrations
php artisan migrate

# 6. Storage symlink
php artisan storage:link

# 7. Start local server
php artisan serve
```

---

## 🔒 Production Deployment Configuration

### 1. Environment Setup (`.env`)
Configure your production `.env` with production keys:

```env
APP_NAME="Digital Academy"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digital_academy_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_strong_db_password

# Payment Gateway Keys
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

RAZORPAY_KEY=rzp_live_...
RAZORPAY_SECRET=your_razorpay_secret

PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret
PAYPAL_MODE=live
```

### 2. Laravel Production Optimization
Run the following optimization commands prior to production traffic:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 3. Nginx Virtual Host Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com;
    root /var/www/digital_academy/public;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 👥 Platform Roles & Permissions

| Role | Access Level | Primary Features |
|---|---|---|
| **Student** | Learner | Course catalog, video console, code sandbox, AI debugger, quizzes, verified certificates |
| **Instructor** | Content Creator | Course authoring, video previews, live cohort studio, interactive quizzes, escrow wallet |
| **Admin** | Platform Operator | Global user management, category administration, escrow payout audit, analytics |

---

## 📄 License

This software is open-sourced under the [MIT License](LICENSE).
