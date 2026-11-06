#!/bin/bash
set -e

echo "=== Deploying Digital Academy Production ==="

# 1. Pull latest production code
git pull origin main

# 2. Install PHP production dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 3. Compile frontend production assets (if node is installed on server)
if command -v npm &> /dev/null
then
    npm ci || npm install
    npm run build
fi

# 4. Run database migrations safely
php artisan migrate --force

# 5. Clear and cache configurations, routes, and views for high performance
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Link public storage
php artisan storage:link --quiet || true

echo "=== Production Deployment Successful! ==="
