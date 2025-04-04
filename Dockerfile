FROM php:8.4-cli AS php-base
RUN docker-php-ext-install mysqli pdo pdo_mysql



FROM php-base
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY src /app/src
COPY frontend /app/frontend
COPY composer.json composer.lock /app/
COPY public /app/public
RUN apt-get update -y && apt-get install -y git zip
RUN composer install
ENTRYPOINT ["php", "-S", "0.0.0.0:8080","-t","/app/public"]
