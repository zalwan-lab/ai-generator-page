#!/usr/bin/env bash
set -euo pipefail

echo "Starting entrypoint..."

cd /var/www/html

PORT=${PORT:-8080}
echo "PORT=$PORT"

# Replace nginx port placeholder
if grep -q "__PORT__" /etc/nginx/conf.d/default.conf; then
    echo "Injecting PORT into nginx config..."
    sed "s/__PORT__/${PORT}/g" \
        /etc/nginx/conf.d/default.conf \
        > /etc/nginx/conf.d/default.tmp

    mv /etc/nginx/conf.d/default.tmp \
       /etc/nginx/conf.d/default.conf
fi

echo "Final nginx config:"
cat /etc/nginx/conf.d/default.conf

# Install vendor if missing
if [ ! -f vendor/autoload.php ]; then
    echo "vendor missing — running composer install..."
    composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
fi

# Setup .env
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY if missing
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Wait for MySQL
if [ "${DB_CONNECTION:-mysql}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306}..."
    for i in $(seq 1 60); do
        if php -r "exit(@fsockopen(getenv('DB_HOST') ?: 'mysql', (int)(getenv('DB_PORT') ?: 3306)) ? 0 : 1);"; then
            echo "MySQL is up."
            break
        fi
        sleep 1
    done
fi

php artisan config:clear || true
php artisan migrate --force || true

if [ "${APP_ENV:-local}" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

chown -R www-data:www-data storage bootstrap/cache vendor || true

echo "Starting supervisord..."
exec "$@"
