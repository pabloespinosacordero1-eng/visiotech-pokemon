FROM php:8.3-cli

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Instalar utilidades necesarias para Composer
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Exponer puerto
EXPOSE 8000

# Servidor embebido de PHP
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]