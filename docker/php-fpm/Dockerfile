FROM php:8.0-fpm-alpine

RUN apk update && apk add composer bash graphviz

RUN curl -sS https://get.symfony.com/cli/installer | bash

#RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /data/application