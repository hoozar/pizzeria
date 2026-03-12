FROM php:8.4-cli

# Install system dependencies
RUN apt-get update && apt-get install -y git unzip

# Add PostreSQL support for PHP
RUN apt-get install -y libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pgsql pdo_pgsql
RUN docker-php-ext-enable pgsql pdo_pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug

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

# Expose port
EXPOSE 8000

# Start PHP built-in server
# CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

# setup cron and cron tasks
ADD crontab /etc/cron.d/pizza-orders-cron
RUN chmod 0644 /etc/cron.d/pizza-orders-cron
RUN touch /var/log/cron.log
RUN apt-get -y install cron
CMD cron && tail -f /var/log/cron.log
