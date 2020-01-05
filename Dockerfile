FROM php:7.4-fpm
RUN docker-php-ext-install pdo_mysql
RUN apt install php-mysqlnd
