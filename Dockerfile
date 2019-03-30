FROM richarvey/nginx-php-fpm
ENV ERRORS 1
ENV ENABLE_XDEBUG 1
ENV APPLICATION_ENV development
ADD vhost.conf /etc/nginx/sites-available/default.conf
