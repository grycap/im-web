# Dockerfile to create a container with the IM web GUI
FROM php:apache
MAINTAINER Miguel Caballer <micafer1@upv.es>
LABEL version="1.4.3"
LABEL description="Container image to run the IM web GUI. (http://www.grycap.upv.es/im)"

RUN apt-get update && apt-get install -y php5-mcrypt php5-sqlite php5-curl sqlite git
RUN cd /var/www/html && git clone https://github.com/grycap/im-web.git
RUN mkdir /var/www/www-data && mv /var/www/html/im-web/im.db /var/www/www-data/im.db
RUN chown -R www-data /var/www
COPY config.php /var/www/html/im-web/config.php

EXPOSE 80
WORKDIR /var/www/html
CMD ["apache2-foreground"]
