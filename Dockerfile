FROM php:7.4-fpm-alpine

WORKDIR /var/www

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf
RUN apk update

RUN apk add libpng libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev gd && docker-php-ext-install gd && docker-php-ext-install zip
# 3 Install Additional dependencies
RUN apk add --no-cache \
    build-base shadow vim curl \
    php7 \
    php7-fpm \
    php7-common \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-mcrypt \
    php7-mbstring \
    php7-xml \
    php7-openssl \
    php7-json \
    php7-phar \
    php7-zip \
    php7-gd \
    php7-dom \
    php7-session \
    php7-zlib

# 4 Add and Enable PHP-PDO Extenstions
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable pdo_mysql

# 6 Remove Cache
RUN rm -rf /var/cache/apk/*

# 7 Add UID '1000' to www-data
RUN usermod -u 1000 www-data

# 8 Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

#COPY ./deploy/local.ini /usr/local/etc/php/local.ini

RUN chmod +rwx /var/www
RUN chmod -R 777 /var/www
RUN chown -R www-data: /var/www

#RUN mkdir -p /app
#COPY . /app
# setup composer and laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --working-dir="/var/www"

RUN composer dump-autoload --working-dir="/var/www"

RUN php artisan config:clear

RUN php artisan config:cache

#RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
#RUN cd /app && \
#    /usr/local/bin/composer install --no-dev

#RUN chown -R www-data: /app

CMD sh /var/www/docker/startup.sh
