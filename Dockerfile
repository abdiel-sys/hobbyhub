FROM php:8.2-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install dependencies for composer
RUN apt-get update && apt-get install -y \
  unzip \
  git \
  libzip-dev

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY src/ .

# Install PHPMailer
RUN composer require phpmailer/phpmailer

EXPOSE 80

