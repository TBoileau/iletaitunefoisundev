FROM php:8.1-apache

RUN apt-get update \
    &&  apt-get install -y --no-install-recommends \
        locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev unzip libpq-dev nodejs npm wget \
        apt-transport-https lsb-release ca-certificates

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen  \
    &&  echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen \
    &&  locale-gen

RUN curl -sS https://getcomposer.org/installer | php -- \
    &&  mv composer.phar /usr/local/bin/composer

RUN set -xe; \
    mkdir -p /usr/local/src; \
    cd /usr/local/src; \
    composer require \
            phpstan/phpstan \
            phpstan/phpstan-dibi \
            phpstan/phpstan-doctrine \
            phpstan/phpstan-phpunit \
            phpstan/phpstan-symfony \
            phpstan/phpstan-strict-rules \
            nunomaduro/phpinsights \
            sebastian/phpcpd \
            bmitch/churn-php \
            phpmd/phpmd \
            icanhazstring/composer-unused;

ENV PATH /usr/local/src/vendor/bin:$PATH


RUN curl -sS https://get.symfony.com/cli/installer | bash \
    &&  mv /root/.symfony/bin/symfony /usr/local/bin

RUN docker-php-ext-configure \
            intl \
    &&  docker-php-ext-install \
            pdo pdo_mysql pdo_pgsql opcache intl zip calendar dom mbstring gd xsl

RUN pecl install apcu && docker-php-ext-enable apcu

RUN npm install --global yarn

RUN git config --global user.email "t-boileau@email.com" \
    &&  git config --global user.name "TBoileau"

WORKDIR /var/www/html/