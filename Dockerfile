# Utilisation de l'image officielle PHP avec Apache
FROM php:8.2.4-apache

# Installation des dépendances nécessaires
RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libwebp-dev \
        libicu-dev \
        g++ \
        libpng-dev \
        libxml2-dev \
        libcurl4-gnutls-dev \
        libonig-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libpq-dev \
        supervisor \
        npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) curl fileinfo gd intl mbstring pdo pdo_mysql pdo_pgsql zip \
    && a2enmod rewrite \
    && a2enmod brotli \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get autoremove -y && apt-get clean

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.0.14

# Clonage du dépôt
RUN git clone "https://github.com/kyllian-claveau/stania.git" /var/www/html \
    && chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

# Met à jour Composer
RUN composer self-update --2

# Copiez une configuration personnalisée pour Apache
COPY 000-default.conf /etc/apache2/sites-available/
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Exécutez Composer pour installer les dépendances
RUN composer install --no-scripts --no-autoloader --verbose

RUN composer dump-autoload --optimize