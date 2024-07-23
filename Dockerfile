# mello-core
FROM mellointeractive/backend-ubuntu:22.04

ENV PHP_V=8.2

RUN apt-get update -y --fix-missing && \
    apt-get install -y \
    libmagickwand-dev \
    imagemagick

RUN pecl install imagick

RUN echo extension=imagick.so > /etc/php/${PHP_V}/mods-available/imagick.ini
RUN ln -s /etc/php/${PHP_V}/mods-available/imagick.ini /etc/php/${PHP_V}/fpm/conf.d/20-imagick.ini
RUN ln -s /etc/php/${PHP_V}/mods-available/imagick.ini /etc/php/${PHP_V}/cli/conf.d/20-imagick.ini

RUN service php${PHP_V}-fpm restart

WORKDIR /var/www
COPY . .

RUN mkdir -p bootstrap/cache
RUN mkdir -p storage/app storage/framework storage/logs
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache/data
RUN touch storage/logs/laravel.log
RUN touch storage/logs/worker.log
RUN chmod -R 775 storage bootstrap/cache
RUN usermod -a -G root www-data

RUN composer install
RUN php artisan optimize

COPY dockerfile.d/supervisord.conf /etc/supervisor/conf.d/
CMD ["/usr/bin/supervisord"]
