FROM php:8.1.3-fpm-alpine3.15

WORKDIR /var/www/

RUN apk add --no-cache \
    openssl \
    git \
    vim \
    curl \
    nodejs \
    npm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo_mysql mysqli
RUN npm i -g yarn

CMD ["php-fpm"]
