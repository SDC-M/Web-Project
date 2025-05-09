FROM dunglas/frankenphp:1.5-php8.4.6-alpine AS php-base
RUN install-php-extensions pdo pdo_mysql

FROM php-base
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN apk update && apk add git zip ffmpeg
WORKDIR /app
COPY src /app/src
COPY frontend /app/frontend
COPY composer.json composer.lock /app/
COPY public /app/public
RUN mkdir profilepicture && mkdir images && composer install --ignore-platform-reqs --no-dev -a -o
# RUN echo > /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'extension=spx.so' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'spx.http_enabled=1' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'spx.http_key="dev"' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini \
#    && echo 'spx.http_ip_whitelist="*"' >> /usr/local/etc/php/conf.d/docker-php-ext-spx.ini
ENTRYPOINT ["frankenphp", "php-server" ,"-r", "public/"]
