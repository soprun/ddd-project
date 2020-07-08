#++++++++++++++++++++++++++++++++++++++
# PHP application Docker container
#++++++++++++++++++++++++++++++++++++++

ARG APP_DIR
ARG APP_ENV
ARG APP_DEBUG
ARG APP_RELEASE

FROM php:fpm-alpine AS build

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
# ENV COMPOSER_HOME="${APP_DIR}/var/composer"
# ENV COMPOSER_CACHE_DIR="${APP_DIR}/var/cache/composer"

ENV PATH="${PATH}:${HOME}/.composer/vendor/bin"
ENV PATH="${PATH}:/var/app/bin"
ENV PATH="${PATH}:/var/app/vendor/bin"

ENV APP_DIR="${APP_DIR:-/var/app}"
ENV APP_ENV="${APP_ENV:-dev}"
ENV APP_DEBUG="${APP_DEBUG:-1}"
ENV APP_RELEASE="${APP_RELEASE}"

# installation system packages
RUN apk add --update --no-cache \
    git \
    make \
    bash \
    zip \
    unzip

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
    && pecl install xdebug \
    && pecl install apcu \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable xdebug apcu \
    && pecl clear-cache \
    && apk del .build-dependencies

# use the default configuration
COPY ./docker/app/php.ini "${PHP_INI_DIR}/app.ini"

# change working directory
WORKDIR ${APP_DIR}

# install dependencies
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN set -eux; \
    # composer config -g github-oauth.github.com <oauthtoken> \
	composer global require "hirak/prestissimo" --no-progress; \
	composer install --no-progress --prefer-dist --no-scripts --no-autoloader; \
	composer clear-cache

# copy source files to workdir
COPY . ${APP_DIR}

# finish composer
RUN set -eux; \
    composer dump-autoload --no-scripts --optimize; \
    composer dump-env dev

# expose port outside
EXPOSE 9000

# run application
CMD ["php-fpm"]

FROM build AS development

FROM build AS production
