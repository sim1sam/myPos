#!/usr/bin/env bash

set -euo pipefail

# Usage:
#   bash scripts/deploy-production.sh /var/www/mypos
# If APP_DIR is omitted, current directory is used.

APP_DIR="${1:-$(pwd)}"

echo "Starting production deploy in: ${APP_DIR}"
cd "${APP_DIR}"

if [[ ! -f artisan ]]; then
  echo "Error: artisan file not found in ${APP_DIR}"
  exit 1
fi

if [[ ! -f composer.json ]]; then
  echo "Error: composer.json file not found in ${APP_DIR}"
  exit 1
fi

if [[ ! -f package.json ]]; then
  echo "Error: package.json file not found in ${APP_DIR}"
  exit 1
fi

echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "Installing Node dependencies..."
npm ci

echo "Building frontend assets..."
npm run build

echo "Applying database migrations..."
php artisan migrate --force

echo "Removing stale Vite hot file (if present)..."
rm -f public/hot

echo "Clearing and rebuilding Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Fixing storage symlink (if needed)..."
php artisan storage:link || true

echo "Production deploy completed successfully."
