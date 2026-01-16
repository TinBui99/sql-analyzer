FROM php:8.2-apache

# ======================
# System packages
# ======================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        intl \
        mbstring \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ======================
# Install Composer
# ======================
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# ======================
# Apache config
# ======================
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# Copy Apache config
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

#RUN a2enmod rewrite headers
# Enable mod_rewrite
RUN a2enmod rewrite

# ======================
# PHP config
# ======================
COPY php.ini /usr/local/etc/php/conf.d/app.ini

# ======================
# Workdir
# ======================
WORKDIR /var/www/html

# ======================
# Copy application files
# ======================
COPY . .

# Create public directory if it doesn't exist
RUN mkdir -p public

## ======================
## Install dependencies
## ======================
#RUN if [ -f "composer.json" ]; then \
#    if [ "$APP_ENV" = "production" ]; then \
#        composer install --no-dev --optimize-autoloader --no-interaction; \
#    else \
#        composer install --optimize-autoloader --no-interaction; \
#    fi \
#fi

# ======================
# Set permissions
# ======================
#RUN chown -R www-data:www-data /var/www/html \
#    && mkdir -p /var/www/html/storage \
#    && chmod -R 755 /var/www/html/storage

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]