FROM php:8.0-fpm-alpine

# Instalar dependências
RUN apk add --no-cache \
    bash \
    autoconf \
    dpkg-dev \
    dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    mysql-client

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar o código da aplicação
COPY . /var/www/html

# Expor a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
