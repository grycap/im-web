# Dockerfile to create a container with the IM web GUI
FROM php:apache
LABEL maintainer="Miguel Caballer <micafer1@upv.es>"
LABEL version="1.6.1"
LABEL description="Container image to run the IM web GUI. (http://www.grycap.upv.es/im)"

RUN apt-get update && apt-get install --no-install-recommends -y wget unzip python3 python3-xmltodict && rm -rf /var/lib/apt/lists/* && \
    ln -s /usr/bin/python3 /usr/bin/python && \
    wget https://github.com/grycap/im-web/archive/eoschub.zip && unzip eoschub.zip && rm -f eoschub.zip && mv im-web-eoschub /var/www/html/im-web && \  
    mkdir /var/www/www-data && mv /var/www/html/im-web/im.db /var/www/www-data/im.db  && \
    chown -R www-data /var/www && \
    apt-get purge -y wget unzip && \
    apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && rm -rf ~/.cache/ && \
    docker-php-ext-install mysqli

COPY config.php /var/www/html/im-web/config.php

EXPOSE 80
WORKDIR /var/www/html
CMD ["apache2-foreground"]
