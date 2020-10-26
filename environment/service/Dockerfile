FROM php:7.4.9-apache

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt-get update && \
  apt-get install -y dos2unix zip git && \
  apt-get clean

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install sockets

RUN echo "xdebug.remote_autostart=1" >> $PHP_INI_DIR/php.ini
RUN echo "xdebug.remote_enable=1" >> $PHP_INI_DIR/php.ini
RUN echo "xdebug.remote_host=host.docker.internal" >> $PHP_INI_DIR/php.ini
RUN echo "upload_max_filesize=20M" >> $PHP_INI_DIR/php.ini
RUN a2enmod rewrite

COPY ./startscript /bin/startscript
RUN chmod +x /bin/startscript
RUN dos2unix /bin/startscript

COPY ./downloadcomposer /root/downloadcomposer
RUN chmod +x /root/downloadcomposer && \
  cd /root && \
  dos2unix /root/downloadcomposer && \
  ./downloadcomposer

RUN apt-get remove -y dos2unix

WORKDIR /var/www/html

CMD ["startscript"]