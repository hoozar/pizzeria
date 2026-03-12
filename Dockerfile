FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y git unzip

# Add PostreSQL support for PHP
RUN apt-get install -y libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pgsql pdo_pgsql
RUN docker-php-ext-enable pgsql pdo_pgsql

# Add xdebug support
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Setup cron and cron tasks
RUN apt-get -y install cron
COPY crontab /etc/cron.d/pizza-orders-cron
RUN chmod 0744 /etc/cron.d/pizza-orders-cron
RUN touch /var/log/cron.log

# setup misc
RUN apt-get -y install procps

# Clear instalations
# RUN rm -rf /var/lib/apt/lists/*

# Quick dirty fix for git ownership error
RUN git config --global --add safe.directory /app

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install PHP dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --prefer-dist --optimize-autoloader

# Create storage directory
RUN mkdir -p storage && chmod 777 storage
