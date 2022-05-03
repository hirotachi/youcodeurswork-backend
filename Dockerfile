FROM php:8.1-fpm-alpine


WORKDIR /var/www

RUN apk update && apk add \
    build-base \
    vim

RUN docker-php-ext-install pdo pdo_mysql


