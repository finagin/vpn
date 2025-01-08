ARG ALPINE_VERSION=3.21
ARG NODE_VERSION=22.12
ARG PHP_VERSION=8.2


FROM node:${NODE_VERSION}-alpine${ALPINE_VERSION} AS front

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build


FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION} AS base

ENV XDEBUG_MODE=off
ENV APP_ENV=production

RUN apk add --no-cache bash fcgi icu-data-full icu-dev imagemagick-dev libjpeg-turbo-dev libpng-dev postgresql-dev \
    && apk add --no-cache curl-dev imap-dev libxml2-dev libzip-dev bzip2-dev oniguruma-dev autoconf build-base linux-headers \
    && docker-php-ext-install -j$(nproc) bcmath curl gd imap intl mbstring pdo pdo_pgsql soap xml zip \
    && pecl install imagick redis \
    && docker-php-ext-enable bcmath curl gd imagick imap intl mbstring redis soap xml zip

COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

# Needs for integrate with Laravel Sail
RUN adduser -s /bin/bash -DH -G www-data -u 1337 sail

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --no-scripts --prefer-dist --no-progress --no-interaction

COPY --from=front /app/public /var/www/html/public

COPY docker-entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

RUN if [ "$XDEBUG_MODE" != "off" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi

VOLUME /var/www/html/storage/app

ENTRYPOINT ["entrypoint"]
