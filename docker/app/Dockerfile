### Build: development
FROM php:fpm-alpine AS builder

# installation system packages
RUN apk add --update --no-cache \
    git \
    make \
    bash \
    zip \
    unzip

#    &&  rm -rf \
#          /var/cache/apk/* \
#          /var/lib/apt/lists/*

RUN apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS \
    && pecl install xdebug \
    && pecl install apcu \
    && docker-php-ext-install opcache \
    && docker-php-ext-enable xdebug apcu \
    && pecl clear-cache \
    && apk del .build-dependencies

COPY ./docker/app/php.conf /etc/php7/php-fpm.conf
COPY ./docker/app/php.ini "${PHP_INI_DIR}/php.ini"

# use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# COPY ./docker/app/php.ini "$PHP_INI_DIR/conf.d/app.ini"

# change working directory
WORKDIR /var/app

# install composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"
ENV PATH="${PATH}:/var/app/bin"
ENV PATH="${PATH}:/var/app/vendor/bin"


# https://dockerfile.readthedocs.io/en/latest/content/DockerImages/dockerfiles/php.html#php-ini-variables
# Timezone
# ENV TIMEZONE Europe/Moscow
# ENV PHP_MEMORY_LIMIT 1024M
# ENV MAX_UPLOAD 128M
# ENV PHP_MAX_FILE_UPLOAD 128
# ENV PHP_MAX_POST 128M</blockquote>

#ARG APP_DIR
#ARG APP_RELEASE
#ARG APP_ENV
#ARG APP_DEBUG

#ENV \
#  APP_DIR="${APP_DIR:-/var/app}" \
#  APP_ENV="${APP_ENV:-dev}" \
#  APP_DEBUG="${APP_DEBUG:-1}" \
#  COMPOSER_ALLOW_SUPERUSER="1" \
#  COMPOSER_HOME="${APP_DIR}/var/composer" \
#  COMPOSER_CACHE_DIR="${APP_DIR}/var/cache/composer"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# composer config -g github-oauth.github.com <oauthtoken>

# install dependencies
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock

RUN set -eux; \
    # Symfony CLI
    # curl -LsS https://get.symfony.com/cli/installer | bash; \
    # mv /root/.symfony/bin/symfony /usr/local/bin/symfony; \
	composer global require "hirak/prestissimo" --no-progress; \
	# https://github.com/paratestphp/paratest
	# composer global require "brianium/paratest" --no-progress; \
	composer install --no-progress --prefer-dist --no-scripts --no-autoloader; \
	composer clear-cache

# copy source files to workdir
COPY . /var/app

# finish composer
RUN set -eux; \
    composer dump-autoload --no-scripts --optimize; \
    composer dump-env dev

# expose port outside
EXPOSE 9000

# run application
CMD ["php-fpm"]

#RUN \
#  curl --location --output /usr/local/bin/composer https://getcomposer.org/composer-stable.phar &> /dev/null && \
#  curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar &> /dev/null
#   chmod +x /usr/local/bin/composer \
#   chmod +x /usr/local/bin/phpunit
#   mkdir -p "$COMPOSER_HOME" \
#   mkdir -p "$COMPOSER_CACHE_DIR"
