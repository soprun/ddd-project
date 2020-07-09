ARG APP_DIR
ARG APP_ENV
ARG APP_DEBUG
ARG APP_RELEASE
ARG APP_SECRET

#++++++++++++++++++++++++++++++++++++++
# PHP application Docker container
#++++++++++++++++++++++++++++++++++++++
FROM composer:2.0 AS composer

WORKDIR /var/app

# COPY composer.* /var/app/

COPY ./composer.json /var/app/composer.json
COPY ./composer.lock /var/app/composer.lock
COPY ./symfony.lock /var/app/symfony.lock

RUN set -eux; \
  composer install \
    --no-dev \
    --no-scripts \
    --no-suggest \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader;

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative

FROM php:7.4-fpm-alpine AS development

# Application environment
#######################################
ENV \
  APP_DIR="${APP_DIR:-/var/app}" \
  APP_ENV="${APP_ENV:-dev}" \
  APP_DEBUG="${APP_DEBUG:-1}"

ENV APP_SECRET="${APP_SECRET}"
ENV APP_RELEASE="${APP_RELEASE}"

ENV GIT_BRANCH=""
ENV GIT_COMMIT_SHA=""

ENV COMPOSER_ALLOW_SUPERUSER=1

# Context environment
#######################################
# ENV PATH="${PATH}:${HOME}/.composer/vendor/bin"
# ENV PATH="${PATH}:${APP_DIR}/bin"
# ENV PATH="${PATH}:${APP_DIR}/vendor/bin"

# Installation system packages
#######################################
#RUN apk add --update --no-cache \
#    git \
#    make \
#    bash \
#    zip \
#    unzip

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
  bash \
  && pecl install xdebug \
  && pecl install apcu \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable xdebug apcu \
  && pecl clear-cache \
  && apk del .build-dependencies

# Set default configuration
#######################################
COPY ./docker/app/php.ini "${PHP_INI_DIR}/app.ini"

# Change working directory
#######################################
WORKDIR ${APP_DIR}

# Copy source files to workdir
#######################################
# todo: copy only specifically what we need
COPY . ${APP_DIR}

COPY --from=composer /var/app/vendor /var/app/vendor

# Expose port & run application
#######################################
EXPOSE 9000

CMD ["php-fpm"]

# Build: production
#######################################
# FROM scratch as production
