FROM php:8.2-apache
RUN apt-get update && apt-get install -y \
    libpng-dev \
    zlib1g-dev \
&& docker-php-ext-install gd mysqli
COPY src/ /var/www/html/