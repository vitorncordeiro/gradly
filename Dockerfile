FROM php:8.1-apache

RUN a2enmod rewrite \
  && docker-php-ext-install pdo_mysql mysqli

RUN printf '%s\n' \
  '<VirtualHost *:80>' \
  '  Alias /gradly /var/www/html' \
  '  <Directory /var/www/html>' \
  '    Options Indexes FollowSymLinks' \
  '    AllowOverride All' \
  '    Require all granted' \
  '  </Directory>' \
  '</VirtualHost>' \
  > /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

RUN usermod -u 1000 www-data