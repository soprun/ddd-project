FROM php:fpm-alpine

# installation system packages
RUN apk add --update --no-cache \
    git \
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

# use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# RUN ln -s "${PHP_INI_DIR}/php.ini-production" "${PHP_INI_DIR}/php.ini"
#RUN ln -s "${PHP_INI_DIR}/php.ini-development" "${PHP_INI_DIR}/php.ini"
COPY ./docker/app/app.ini "$PHP_INI_DIR/conf.d/app.ini"

# install composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer global require "hirak/prestissimo" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer global require "php-coveralls/php-coveralls" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache

# change working directory
WORKDIR /var/app

# install dependencies
# COPY composer.json composer.json
# COPY composer.lock composer.lock
# COPY symfony.lock symfony.lock
# RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader && rm -rf /root/.composer

# copy source files to workdir
COPY . /var/app

ENV PATH="${PATH}:/var/app/bin"
ENV PATH="${PATH}:/var/app/vendor/bin"

# finish composer
RUN composer dump-autoload --no-scripts --no-dev --optimize

# expose port outside
EXPOSE 9000

# run application
CMD ["php-fpm"]
