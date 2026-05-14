FROM php:8.3-fpm

RUN apt-get update && apt-get install -y git curl unzip libzip-dev libsqlite3-dev gnupg2

RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor > /usr/share/keyrings/microsoft.gpg \\
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" > /etc/apt/sources.list.d/mssql-release.list \\
    && apt-get update \\
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev

RUN pecl install sqlsrv pdo_sqlsrv \\
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \\
    && docker-php-ext-install pdo pdo_sqlite zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
