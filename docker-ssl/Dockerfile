# Dockerfile to create a container with the IM web GUI
FROM php:apache
MAINTAINER Miguel Caballer <micafer1@upv.es>
LABEL version="1.5.14"
LABEL description="Container image to run the IM web GUI. (http://www.grycap.upv.es/im)"

ADD default-ssl.conf /etc/apache2/sites-enabled/

# Add a default self-signed certificate
ADD server.crt /etc/ssl/certs/server.crt
ADD server.key /etc/ssl/certs/server.key

RUN apt-get update && apt-get install -y wget unzip net-tools && rm -rf /var/lib/apt/lists/*
RUN wget https://github.com/grycap/im-web/archive/master.zip && unzip master.zip && rm -f master.zip && mv im-web-master /var/www/html/im-web
RUN mkdir /var/www/www-data && mv /var/www/html/im-web/im.db /var/www/www-data/im.db
RUN chown -R www-data /var/www
COPY config.php /var/www/html/im-web/config.php

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install sockets

RUN a2enmod rewrite
RUN a2enmod ssl

EXPOSE 80
WORKDIR /var/www/html
CMD ["apache2-foreground"]
