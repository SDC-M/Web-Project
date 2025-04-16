FROM dunglas/frankenphp AS php-base
RUN install-php-extensions pdo pdo_mysql

FROM php-base
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
RUN apt-get update -y && apt-get install -y git zip
COPY src /app/src
COPY frontend /app/frontend
COPY composer.json composer.lock /app/
COPY public /app/public
RUN composer install --ignore-platform-reqs --no-dev -a
ENTRYPOINT ["frankenphp", "php-server" ,"-r", "public/"]
