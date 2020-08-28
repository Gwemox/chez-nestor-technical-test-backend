ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION}-fpm-alpine AS symfony_dev_php
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>

ARG APP_ENV=dev

# persistent / runtime deps
RUN apk add --no-cache \
        bash \
        git \
    ;

#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
#RUN symfony server:ca:install

WORKDIR /srv/app

EXPOSE 80
CMD symfony server:start --port=80 --no-tls

