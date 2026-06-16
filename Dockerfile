FROM php:8.2-apache

RUN a2enmod rewrite

RUN a2enmod mpm_prefork && a2dismod mpm_event

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data assets/images assets/dokumen \
    && chmod -R 755 assets/images assets/dokumen

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN echo "date.timezone = Asia/Jakarta" > /usr/local/etc/php/conf.d/timezone.ini
