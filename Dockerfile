FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    supervisor \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    libpq-dev \
    nginx \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install pgsql pdo_pgsql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


COPY docker/nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && rm -f /var/www/html/index.nginx-debian.html
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
COPY docker/nginx/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini


COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

COPY .env.example .env

# Install dependencies
RUN composer install
RUN npm install
RUN npm run build

# Generate application key
RUN php artisan key:generate

# Cache configuration and routes
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
