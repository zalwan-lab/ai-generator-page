#!/bin/sh
set -e

echo "=== ENTRYPOINT START ==="

cd /var/www/html

PORT=${PORT:-8080}
echo "PORT=$PORT"

if grep -q "__PORT__" /etc/nginx/conf.d/default.conf; then
    echo "Injecting nginx PORT..."
    sed "s/__PORT__/${PORT}/g" \
        /etc/nginx/conf.d/default.conf \
        > /etc/nginx/conf.d/default.tmp

    mv /etc/nginx/conf.d/default.tmp \
       /etc/nginx/conf.d/default.conf
fi

echo "=== NGINX CONFIG ==="
cat /etc/nginx/conf.d/default.conf

echo "=== TEST NGINX ==="
nginx -t

mkdir -p storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views \
         bootstrap/cache

chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

php artisan config:clear || true

echo "=== START SUPERVISORD ==="
exec "$@"
