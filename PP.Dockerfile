# Build osu-tools
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build-osu-tools
WORKDIR /src
RUN git clone --depth 1 https://github.com/ppy/osu-tools.git .
WORKDIR /src/PerformanceCalculator
RUN dotnet publish -c Release -r linux-x64 --self-contained false -o /app/publish

# Build the main app
FROM php:8.4-fpm

RUN apt update && apt install -y \
    $PHPIZE_DEPS \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    unzip \
    zip \
    && apt clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN curl -sSL https://dot.net/v1/dotnet-install.sh -o dotnet-install.sh \
    && chmod +x dotnet-install.sh \
    && ./dotnet-install.sh --channel 8.0 --runtime dotnet --install-dir /usr/local/bin \
    && rm dotnet-install.sh

WORKDIR /opt/osu-tools
COPY --from=build-osu-tools /app/publish .

COPY . /var/www
WORKDIR /var/www

COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

COPY composer.json ./
RUN composer install

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER www-data

CMD ["php", "artisan", "queue:listen", "-vvv", "--queue=pp"]
