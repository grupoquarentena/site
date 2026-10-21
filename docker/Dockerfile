FROM composer:2 AS composer_binary

FROM php:8.5-apache-trixie AS base
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public TZ=America/Bahia
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev libicu-dev libzip-dev libsqlite3-dev libonig-dev unzip ca-certificates \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql pdo_sqlite intl mbstring zip bcmath opcache pcntl \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer_binary /usr/bin/composer /usr/local/bin/composer
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/apache/php.ini /usr/local/etc/php/conf.d/99-quarentena.ini
COPY docker/entrypoint.sh /usr/local/bin/quarentena-entrypoint
RUN chmod +x /usr/local/bin/quarentena-entrypoint
WORKDIR /var/www/html
ENTRYPOINT ["quarentena-entrypoint"]
CMD ["apache2-foreground"]

FROM base AS desenvolvimento

FROM base AS producao
COPY --chown=www-data:www-data . /var/www/html
RUN test -s composer.lock \
    && composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache
