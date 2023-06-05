# Specify the base image
FROM php:8.0-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions for php
RUN docker-php-ext-install pdo_mysql mbstring zip sockets exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy both Laravel projects to container
COPY src /var/www/html/src
COPY booking /var/www/html/booking
COPY frontend /var/www/html/frontend


# Install dependencies for Laravel projects
WORKDIR /var/www/html/src
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs
WORKDIR /var/www/html/booking
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs
WORKDIR /var/www/html/frontend
RUN composer install --optimize-autoloader --no-dev --ignore-platform-reqs
# Expose ports
EXPOSE 9000
EXPOSE 9001
EXPOSE 8000

# Start Laravel projects
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=9000 & php /var/www/html/booking/artisan serve --host=0.0.0.0 --port=9001 & php /var/www/html/frontend/artisan serve --host=0.0.0.0 --port=8000"]
