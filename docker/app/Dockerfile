ARG APP_DIR
ARG APP_ENV
ARG APP_DEBUG
ARG APP_RELEASE

#++++++++++++++++++++++++++++++++++++++
# PHP application Docker container
#++++++++++++++++++++++++++++++++++++++

FROM php:fpm-alpine AS development

#######################################
# Context environment
ENV PATH="${PATH}:${HOME}/.composer/vendor/bin"
ENV PATH="${PATH}:/var/app/bin"
ENV PATH="${PATH}:/var/app/vendor/bin"

#######################################
# Set Composer environment
ENV COMPOSER_ALLOW_SUPERUSER=1
# ENV COMPOSER_HOME="${APP_DIR}/var/composer"
# ENV COMPOSER_CACHE_DIR="${APP_DIR}/var/cache/composer"

#######################################
# Set application environment
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

#######################################
# Set default configuration
COPY ./docker/app/php.ini "${PHP_INI_DIR}/app.ini"

#######################################
# Change working directory
WORKDIR ${APP_DIR}

#######################################
# Install application dependencies
COPY composer.json "${APP_DIR}/composer.json"
COPY composer.lock "${APP_DIR}/composer.lock"
COPY symfony.lock "${APP_DIR}/symfony.lock"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN set -eu; \
    # composer config -g github-oauth.github.com <oauthtoken> \
	composer global require "hirak/prestissimo" --no-progress; \
	composer install --no-progress --prefer-dist --no-scripts --no-autoloader; \
	composer clear-cache

#######################################
# Copy source files to workdir
COPY . ${APP_DIR}

#######################################
# Finish composer
RUN set -eu; \
    composer dump-autoload --no-scripts --optimize; \
    composer dump-env ${APP_ENV}

#######################################
# Expose port outside
EXPOSE 9000

#######################################
# Run application
CMD ["php-fpm"]
