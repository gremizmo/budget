FROM php:8.3.7-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libicu-dev \
    zip \
    unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl pdo_mysql

RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer

RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

COPY . .

EXPOSE 9000

ENV XDEBUG_MODE=coverage

CMD ["php-fpm"]