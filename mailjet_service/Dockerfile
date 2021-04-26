# Use alpine that works with Nginx
FROM php:7.3.13-fpm-alpine3.9

# Apk Alpine installer
RUN apk add --no-cache shadow openssl bash mysql-client nodejs npm alpine-sdk autoconf librdkafka-dev vim nginx openrc
RUN mkdir -p /run/nginx && \
    echo "pid /run/nginx.pid;" >> /etc/nginx/nginx.conf

# docker-php-ext-install allows install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath

#Install Kafka lib
RUN pecl install rdkafka

#Activate Kafka
RUN ln -s /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
    echo "extension=rdkafka.so" >> /usr/local/etc/php/php.ini

RUN usermod -u 1000 www-data
WORKDIR /var/www

RUN rm -rf /var/www/html
RUN ln -s public html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm /etc/nginx/conf.d/default.conf
COPY .docker/nginx/nginx.conf /etc/nginx/conf.d
COPY . .

USER www-data

EXPOSE 80
ENTRYPOINT [ "/var/www/entrypoint.sh" ]
