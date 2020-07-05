FROM php:fpm-alpine

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

# use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./docker/app/php.conf /etc/php7/php-fpm.conf
COPY ./docker/app/app.ini "$PHP_INI_DIR/conf.d/app.ini"

# install composer
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
    # Symfony CLI
    # curl -sS https://get.symfony.com/cli/installer | bash; \
    # mv /root/.symfony/bin/symfony /usr/local/bin/symfony; \
	composer global require "hirak/prestissimo" --no-progress; \
	# https://github.com/paratestphp/paratest
	# composer global require "brianium/paratest" --no-progress; \
	composer clear-cache

# change working directory
WORKDIR /var/app

# install dependencies
COPY composer.json composer.json
COPY composer.lock composer.lock
COPY symfony.lock symfony.lock
RUN composer install --prefer-dist --no-scripts --no-autoloader && rm -rf /root/.composer

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
