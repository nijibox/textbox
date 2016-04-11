FROM php:5.6-cli
# Install extensions and composer

RUN apt-get update \
    && apt-get install -y libmcrypt-dev zlib1g-dev git
RUN docker-php-ext-install -j$(nproc) mbstring mcrypt pdo_mysql zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /app
WORKDIR /app

# deploy app
COPY . /app
RUN composer install --no-scripts --no-dev

ENV PORT=5000

CMD [ "php", "artisan", "serve", "--host", "0.0.0.0", "--port", $PORT ]
