FROM php:8.4-fpm

RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    zip \
    && apt clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt install -y nodejs \
    && npm install -g npm

COPY . /var/www
WORKDIR /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

COPY composer.json ./
RUN composer install
RUN npm install
RUN npm run build
