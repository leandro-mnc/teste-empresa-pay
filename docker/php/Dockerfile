FROM php:8-alpine

WORKDIR /var/www

ENV TZ=America/Sao_Paulo

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so

RUN apk add icu-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

COPY --from=composer:2.5.8 /usr/bin/composer /usr/local/bin/composer