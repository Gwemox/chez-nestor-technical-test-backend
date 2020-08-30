ARG PHP_VERSION=7.4

FROM php:${PHP_VERSION}-fpm-alpine AS php_api
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
RUN apk add --no-cache \
        bash \
        git \
        postgresql-dev \
    ;
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN docker-php-ext-install \
        pdo \
        pdo_pgsql \
    ;
## Add the wait script to the image
ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.7.3/wait /wait
RUN chmod +x /wait
#RUN symfony server:ca:install

FROM php_api AS symfony_dev_php
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
ARG APP_ENV=dev
WORKDIR /srv/app
EXPOSE 80
CMD symfony server:start --port=80 --no-tls

FROM composer:latest AS symfony_build
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
WORKDIR /srv/app
COPY ./composer.* ./
RUN composer install -n --no-dev

FROM composer:latest AS symfony_build_dev
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
WORKDIR /srv/app
COPY ./composer.* ./
RUN composer install -n --dev

FROM php_api AS api_test
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
ARG APP_ENV=test
WORKDIR /srv/app
COPY --from=symfony_build_dev /usr/bin/composer /usr/bin/composer
COPY --from=symfony_build_dev /srv/app/vendor vendor
COPY --from=symfony_build_dev /srv/app/var var
COPY --from=symfony_build_dev /srv/app/bin bin
COPY . .
RUN php bin/phpunit --check-version
ENTRYPOINT /wait && php bin/console doctrine:database:drop --force && php bin/console doctrine:database:create && php bin/console doctrine:migration:migrate --no-interaction && php bin/phpunit
#CMD php /srv/app/bin/console doctrine:migration:migrate --no-interaction && php bin/phpunit

FROM php_api AS api
MAINTAINER Thibault Buathier <thibault.buathier@gmail.com>
ARG APP_ENV=prod
WORKDIR /srv/app
COPY . .
COPY --from=symfony_build /srv/app/vendor vendor
COPY --from=symfony_build /srv/app/var var
COPY --from=symfony_build /srv/app/bin bin
EXPOSE 80
CMD /wait && php bin/console doctrine:migration:migrate --no-interaction --allow-no-migration && chown www-data:www-data -R var && symfony server:start --port=80 --no-tls

