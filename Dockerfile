FROM php:8.3-fpm-alpine
WORKDIR /app

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ git bash icu-dev libzip-dev rabbitmq-c rabbitmq-c-dev linux-headers

RUN pecl install apcu-5.1.23 && pecl install amqp-2.1.1 && pecl install xdebug

RUN docker-php-ext-install -j$(nproc) \
        bcmath \
        opcache \
        intl \
        zip \
        pdo_mysql

RUN docker-php-ext-enable amqp apcu opcache

RUN curl -sS https://get.symfony.com/cli/installer | bash -s - --install-dir /usr/local/bin

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN php /tmp/composer-setup.php --install-dir=/bin/ --filename=composer --version=2.6.5
RUN rm /tmp/composer-setup.php


COPY .docker/php/ /usr/local/etc/php/

RUN mkdir -p /opt/home
RUN chmod 777 /opt/home

ENV HOME /opt/home
