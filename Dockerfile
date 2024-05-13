FROM php:8.1-apache

# Update and install dependencies
RUN apt-get update && apt-get install -y \
    git \
    libsodium-dev \
    libicu-dev \
    librabbitmq-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libpng-dev \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    supervisor \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql sodium intl curl fileinfo gd pdo_pgsql pgsql zip

# Cleanup to reduce image size
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Clone the application repository
RUN git clone https://github.com/kyllian-claveau/Stania.git /var/www/html

# Adjust permissions
RUN chown -R www-data:www-data /var/www/html

# Define working directory
WORKDIR /var/www/html

# Add user and adjust directory ownership
RUN groupadd -r stania && useradd -r -g stania stania \
    && mkdir -p /var/www/html \
    && chown -R stania:stania /var/www/html

# Switch to user
USER stania
RUN composer install --no-dev --optimize-autoloader

# Switch back to root
USER root

RUN npm install -g npm@latest
RUN npm run build

# Exposex port 80
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]