FROM php:8.2-fpm

# Install dependensi sistem dan ekstensi PHP untuk SQLite & Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev

RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Copy Composer dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur working directory
WORKDIR /var/www

# Copy seluruh kodingan proyek
COPY . /var/www

# Install dependensi PHP
RUN composer install --no-dev --optimize-autoloader

# Izinkan penulisan folder storage & cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000

# Jalankan server bawaan Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000