FROM php:8.4-apache

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && a2enmod rewrite

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Настройка Apache для Laravel
COPY <<EOF /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Order Allow,Deny
        Allow from all
    </Directory>
</VirtualHost>
EOF

WORKDIR /var/www/html

# Сначала копируем только composer файлы для кэширования
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Копируем остальной проект
COPY . .

# Финальная установка
RUN composer dump-autoload --optimize

# Права доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Стартовый скрипт
CMD php artisan config:clear && \
    php artisan route:clear && \
    php artisan migrate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    apache2-foreground
