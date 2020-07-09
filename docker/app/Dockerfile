ARG APP_DIR
ARG APP_ENV
ARG APP_DEBUG
ARG APP_RELEASE
ARG APP_SECRET

#++++++++++++++++++++++++++++++++++++++
# PHP application Docker container
#++++++++++++++++++++++++++++++++++++++

FROM php:fpm-alpine AS development

# Application environment
#######################################
ENV \
  APP_DIR="${APP_DIR:-/var/app}" \
  APP_ENV="${APP_ENV:-dev}" \
  APP_DEBUG="${APP_DEBUG:-1}" \
  APP_RELEASE="${APP_RELEASE}"
  # APP_SECRET="${APP_SECRET}" \

# Context environment
#######################################
# ENV PATH="${PATH}:${HOME}/.composer/vendor/bin"
# ENV PATH="${PATH}:${APP_DIR}/bin"
# ENV PATH="${PATH}:${APP_DIR}/vendor/bin"

# Installation system packages
#######################################
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

# Install application dependencies
#######################################
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN set -eux; \
  # composer config -g github-oauth.github.com <oauthtoken> \
  composer global require "hirak/prestissimo" --no-progress; \
  # composer global require "brianium/paratest" --no-progress; \
  composer install --prefer-dist --classmap-authoritative; \
  composer clear-cache;

# Expose port & run application
#######################################
EXPOSE 9000

CMD ["php-fpm"]

# Build: production
#######################################
# FROM scratch as production
