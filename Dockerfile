FROM composer:2.8 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-progress \
    --optimize-autoloader \
    --no-scripts


FROM php:8.4-fpm-alpine AS base

WORKDIR /var/www/html

RUN apk add --no-cache \
    bash \
    curl \
    fcgi \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-install pdo_mysql bcmath opcache

COPY . .
COPY --from=vendor /app/vendor ./vendor


FROM base AS frontend

RUN apk add --no-cache nodejs npm
RUN npm ci && npm run build


FROM base AS runtime

COPY --from=frontend /var/www/html/public/build ./public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint
COPY docker/mariadb/healthcheck.sh /healthcheck.sh

RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php-fpm -t || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["php-fpm", "-F"]


FROM nginx:1.27-alpine AS web

WORKDIR /var/www/html

COPY docker/nginx/prod.conf /etc/nginx/conf.d/prod.conf
COPY --from=runtime /var/www/html/public ./public
