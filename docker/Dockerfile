# Dockerfile to create a container with the IM web GUI
FROM php:apache
MAINTAINER Miguel Caballer <micafer1@upv.es>
LABEL version="1.5.5"
LABEL description="Container image to run the IM web GUI. (http://www.grycap.upv.es/im)"

RUN apt-get update && apt-get install -y wget unzip && rm -rf /var/lib/apt/lists/*
RUN wget https://github.com/grycap/im-web/archive/master.zip && unzip master.zip && rm -f master.zip && mv im-web-master /var/www/html/im-web 
RUN mkdir /var/www/www-data && mv /var/www/html/im-web/im.db /var/www/www-data/im.db
RUN chown -R www-data /var/www
COPY config.php /var/www/html/im-web/config.php

EXPOSE 80
WORKDIR /var/www/html
CMD ["apache2-foreground"]
