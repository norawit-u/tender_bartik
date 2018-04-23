FROM php:7.2.4-apache

COPY . /var/www/html/
ENV APACHE_DOCROOT=/var/www/html/public
#COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 64555