ARG APP_DIR
ARG APP_ENV
ARG APP_DEBUG
ARG APP_RELEASE
ARG APP_SECRET

#++++++++++++++++++++++++++++++++++++++
# PHP application Docker container
#++++++++++++++++++++++++++++++++++++++

FROM php:fpm-alpine AS development

# Context environment
#######################################
ENV PATH="${PATH}:/var/app/bin"
ENV PATH="${PATH}:/var/app/vendor/bin"

# Set application environment
#######################################
ENV APP_DIR="${APP_DIR:-/var/app}"
ENV APP_ENV="${APP_ENV:-dev}"
ENV APP_DEBUG="${APP_DEBUG:-1}"
ENV APP_RELEASE="${APP_RELEASE}"
ENV APP_SECRET="${APP_RELEASE}"

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
COPY ./docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set Composer environment
#######################################
ENV PATH="${PATH}:${HOME}/.composer/vendor/bin"
ENV COMPOSER_ALLOW_SUPERUSER=1
#ENV COMPOSER_HOME="${APP_DIR}/var/composer"
#ENV COMPOSER_CACHE_DIR="${APP_DIR}/var/cache/composer"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Change working directory
#######################################
WORKDIR ${APP_DIR}

# Copy source files to workdir
#######################################
COPY . ${APP_DIR}

# Install application dependencies
#######################################
RUN set -eu; \
    chmod +x /usr/local/bin/entrypoint.sh; \
    # composer config -g github-oauth.github.com <oauthtoken> \
	composer global require "hirak/prestissimo" \
	    --no-progress \
	    --prefer-dist; \
	composer install \
        --no-progress \
        --prefer-dist \
        --classmap-authoritative \
        --apcu-autoloader; \
	composer clear-cache


# Expose port outside
#######################################
EXPOSE 9000

# Set entrypoint
#######################################
ENTRYPOINT ["entrypoint.sh"]

# Run application
#######################################
CMD ["php-fpm"]

# Build: production
#######################################
# FROM scratch as production
