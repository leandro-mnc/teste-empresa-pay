FROM php:8.2-apache

ARG XDEBUG_VERSION="xdebug-3.2.2"

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

WORKDIR /var/www/html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ENV TZ=America/Sao_Paulo

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql

RUN apt update && apt install -y redis-server \
        && pecl install redis \
        && docker-php-ext-enable redis.so

RUN apt update && apt install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

RUN yes | pecl install ${XDEBUG_VERSION} \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=develop,coverage,debug,profile" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=IDE" >> /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:2.5.8 /usr/bin/composer /usr/local/bin/composer

EXPOSE 80 9003