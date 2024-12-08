FROM php:8.3.7-fpm

WORKDIR /var/www/html

# Install required dependencies
RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        bcmath \
        sockets \
    && pecl install amqp xdebug \
    && docker-php-ext-enable amqp xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Install MailHog
RUN curl -sL https://github.com/mailhog/MailHog/releases/download/v1.0.1/MailHog_linux_amd64 -o /usr/local/bin/mailhog && \
    chmod +x /usr/local/bin/mailhog

COPY . .

EXPOSE 9000

ENV XDEBUG_MODE=coverage

CMD ["php-fpm"]