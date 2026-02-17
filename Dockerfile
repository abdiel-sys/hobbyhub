FROM php:apache

RUN docker-php-ext-install pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the application code
COPY src/ .

# Expose port 80
EXPOSE 80
