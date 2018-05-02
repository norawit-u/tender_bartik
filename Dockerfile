FROM php:7.2.4-apache

WORKDIR /var/www/html/
COPY . .

RUN sudo apt-get update
RUN sudo apt-get install -y git vim
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN php composer.phar install
ENV APACHE_DOCROOT=/var/www/html/public


ENV DB_CONNECTION=mysql
ENV DB_DATABASE=tenderBartik
ENV DB_HOST=128.199.88.139
ENV DB_PASSWORD=ergweprjgwerighjwethjtr2315
ENV DB_PORT=64566
ENV DB_USERNAME=root

#COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 64564