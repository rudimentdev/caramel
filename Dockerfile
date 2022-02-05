FROM php:8.1-cli

# Install extension php xdebug & pdo-pgsql
RUN apt-get update \
    && apt-get install -y openssl libpq-dev libonig-dev \
    && pecl install xdebug \
    && docker-php-ext-install pdo_pgsql \
    && docker-php-ext-enable xdebug

# Configure Xdebug
RUN { \
    echo '[xdebug]'; \
    echo 'xdebug.mode=debug'; \
    echo 'xdebug.start_with_request=yes'; \
    echo 'xdebug.client_host=host.docker.internal'; \
    echo 'xdebug.client_port=9003'; \
} > /usr/local/etc/php/conf.d/xdebug.ini

VOLUME [ "/app" ]
WORKDIR /app
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80
EXPOSE 80
