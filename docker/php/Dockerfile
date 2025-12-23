FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache vhost
COPY docker/php/vhost.conf /etc/apache2/sites-available/000-default.conf

# Disable deprecated warnings globally (PHP 8.4 + Nextras)
RUN echo "error_reporting = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED" > /usr/local/etc/php/conf.d/99-no-deprecated.ini \
    && echo "display_errors = Off" >> /usr/local/etc/php/conf.d/99-no-deprecated.ini

# Create nette temp directory with correct permissions
RUN mkdir -p /tmp/nette-temp \
    && chown -R www-data:www-data /tmp/nette-temp \
    && chmod -R 775 /tmp/nette-temp

WORKDIR /var/www/html
EXPOSE 80