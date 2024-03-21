FROM php:8-fpm AS base

RUN apt-get -y update && \
    apt-get -y install apt-utils

RUN DEBIAN_FRONTEND=noninteractive apt-get -y install git wget

# Install PHP Intl
RUN DEBIAN_FRONTEND=noninteractive apt-get -y install libicu-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl

# Install PHP Accelarator
RUN docker-php-ext-install opcache && \
    docker-php-ext-enable opcache

# Install PHP Zip
RUN DEBIAN_FRONTEND=noninteractive apt-get install -y \
        libzip-dev \
        zip \
    && docker-php-ext-install zip

# Install PHP PDO
RUN docker-php-ext-install pdo_mysql

#Install zip+icu dev libs + pgsql dev support
RUN apt-get install libzip-dev zip libicu-dev libpq-dev -y

# Install PostgreSQL Driver
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && docker-php-ext-install pdo_pgsql pgsql

# Install Composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symdony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    chmod +x /root/.symfony5/bin/symfony && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

WORKDIR /project/src/codebase

FROM base AS dev

ARG UID=1000
ARG GID=1000
ARG XDEBUG_HOST=${XDEBUG_HOST}
ARG XDEBUG_PORT=${XDEBUG_PORT}

# Install xdebug
RUN pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug;
RUN echo 'xdebug.mode=debug,coverage' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.client_host='$XDEBUG_HOST >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.client_port='$XDEBUG_PORT >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.log_level=0' >> /usr/local/etc/php/conf.d/xdebug.ini

# Create Internal User.
RUN groupadd -g $GID appuser && \
    useradd -m -d /home/appuser -r -u $UID -g appuser appuser

USER appuser
