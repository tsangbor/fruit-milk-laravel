FROM php:7.4-fpm-alpine

RUN apk add --no-cache nginx wget

RUN mkdir -p /run/nginx

COPY docker/nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www

# Install dependencies
RUN docker-php-ext-configure gd \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql mbstring \
    && docker-php-ext-install pdo \
    && docker-php-ext-enable opcache \
    && docker-php-ext-install zip

# Copy files
COPY . /var/www
COPY ./deploy/local.ini /usr/local/etc/php/local.ini

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
