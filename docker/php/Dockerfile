FROM php:8.1-fpm-alpine

RUN apk update \
    && apk add git \
            curl \
            vim \
            wget \
            bash \
            zlib \
            zlib-dev \
            patch \
            icu-dev

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install -f xdebug \
    && docker-php-ext-install opcache bcmath intl pdo pdo_mysql  \
    && docker-php-ext-enable xdebug opcache bcmath intl pdo pdo_mysql  \
    && rm -f /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer selfupdate

ENV TIMEZONE="Europe/Paris"
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

CMD ["php-fpm", "-F"]

COPY . /var/www/server
WORKDIR /var/www/server

EXPOSE 9000