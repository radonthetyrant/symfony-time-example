FROM php:8-fpm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apt update && \
    apt install --no-install-recommends -y git unzip software-properties-common && \
    pecl channel-update pecl.php.net && \
    docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable mysqli pdo pdo_mysql

#RUN apt install php8-mysql

