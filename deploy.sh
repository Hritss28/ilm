#!/bin/bash

# ============================================================
# Deployment Script - Info Lantas Mojokerto
# ============================================================
# Usage: ./deploy.sh
# ============================================================

set -e

echo "=========================================="
echo " Deploying Info Lantas Mojokerto"
echo "=========================================="

# Step 1: Install PHP dependencies (production)
echo ""
echo "[1/6] Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Step 2: Run database migrations
echo ""
echo "[2/6] Running database migrations..."
php artisan migrate --force

# Step 3: Seed categories (idempotent)
echo ""
echo "[3/6] Seeding categories..."
php artisan db:seed --class=CategorySeeder --force

# Step 4: Create storage symlink
echo ""
echo "[4/6] Creating storage link..."
php artisan storage:link 2>/dev/null || true

# Step 5: Optimize application
echo ""
echo "[5/6] Optimizing application..."
php artisan optimize

# Step 6: Build frontend assets
echo ""
echo "[6/6] Building frontend assets..."
npm ci --production=false
npm run build

echo ""
echo "=========================================="
echo " Deployment complete!"
echo "=========================================="
echo ""
echo "Post-deployment checklist:"
echo "  - Verify .env is configured correctly"
echo "  - Ensure cron job is set up for scheduler"
echo "  - Check storage permissions (775 for storage/ and bootstrap/cache/)"
echo ""
