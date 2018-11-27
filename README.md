# IM - Infrastructure Manager web GUI

* Build Status [![Build Status](http://jenkins.i3m.upv.es/buildStatus/icon?job=grycap/im-web-unit)](http://jenkins.i3m.upv.es:8080/job/grycap/job/im-web-unit/)

IM is a tool that ease the access and the usability of IaaS clouds by automating
the VMI selection, deployment, configuration, software installation, monitoring
and update of Virtual Appliances. It supports APIs from a large number of
virtual platforms, making user applications cloud-agnostic. In addition it
integrates a contextualization system to enable the installation and
configuration of all the user required applications providing the user with a
fully functional infrastructure.

## 1 INSTALLATION

### 1.1 REQUISITES

IM web interface is based on PHP, so a web server with PHP support must be installed.

Also the mcrypt PHP modules must be installed and enabled.

It is also required to install the PHP module to access SQLite databases.

In case of using the REST API it is also required to install the CURL PHP module.

### 1.2 INSTALLING

Select a proper path in the document root of the web server to install the IM web interface
(i.e. /var/www/im).

```sh
tar xvzf IM-web-X.XX.tar.gz
mv IM-X.XX /var/www/im
chown -R www-data /var/www/im
```

### 1.2 CONFIGURATION

Adjust the configuration settings in the file config.php:

  * Flag to set the usage of the REST API instead of the XML-RPC one.
```php
$im_use_rest=false;
```
* Flag to set the usage of the APIs using HTTPS protocol instead of the standard HTTP.
```
$im_use_ssl=false;
```
* Address of the IM host
```
$im_host="im-server.domain.com";
```
* Port of the IM service
```
$im_port=8899;
```
* Path of the IM web interface DB. The original path will be /var/www/im/im.db
  but is more secure to move it to a path not in the path of the web server.
  The file and the directory must have write permissions to the web server user.
```
$im_db="/home/www-data/im.db";
```
* In case that the IM service and web interface are in the same host, the Recipes
  feature can be activated. Specify the path of the recipes_ansible.db file of the
  IM and take care that the file and the directory must have write permissions to
  the web server user. In other case set "".
```
$recipes_db="/usr/local/im/contextualization/recipes_ansible.db";
```
* OpenID Issuer supported use "" to disable OpenID support.
```
$openid_issuer="https://iam-test.indigo-datacloud.eu/";
```
* OpenID Issuer name.
```
$openid_name="INDIGO IAM";
```
* OpenID Client data.
```
$CLIENT_ID = 'client_id';
$CLIENT_SECRET = 'client_secret';
$REDIRECT_URI = 'https://server.com/im-web/openid_auth.php';
```

### 1.3 DEFAULT USER

The default administrator user is admin with password admin.

## 2 DOCKER IMAGE

A Docker image named `grycap/im-web` has been created to make easier the deployment of an IM web GUI using the 
default configuration. Information about this image can be found here: https://registry.hub.docker.com/u/grycap/im-web/.

This container is prepaired to work linked with the IM service container `grycap/im`, in this way:

* First launch the IM service specifying the name "im":

```sh
sudo docker run -d -p 8899:8899 --name im grycap/im 
```

* Then launch the im-web container linking to the im:

```sh
sudo docker run -d -p 80:80 --name im-web --link im:im grycap/im-web 
```

It also supports environment variables to set the IM service location:

  * im_use_rest: Uses the REST API instead of the XML-RPC that is the default one. Default value "false".
  * im_use_ssl: Uses HTTPS to connect with the APIs. Default value "false".
  * im_host: Hostname of the IM service. Default value "im".
  * im_port: Port of the IM service. Default value "8899".
  * im_db: Location of the D.B. file used in the web application to store data. Default value "/home/www-data/im.db".
  * openid_issuer: URL of the OpenID Issuer. Default value "".
  * openid_name: OpenID Issuer name. Default value "".
  * client_id: OpenID Client ID. Default value "client_id".
  * client_secret: OpenID Client Secret. Default value "client_secret".
  * redirect_uri: OpenID redirect URI . Default value "https://server.com/im-web/opend_auth.php". 

```sh
docker run -p 80:80 -e "im_use_rest=true" -e "im_host=server.domain" -e "im_port=8800" -d grycap/im-web
```

There is also a version SSL enabled. In this case the docker image have a selfsigned certificate for testing purposes. Add your own in the docker command:

```sh
docker run -p 80:80 -p 443:443 -v server.crt:/etc/ssl/certs/server.crt -v server.key:/etc/ssl/certs/server.key -d grycap/im-web:1.5.5-ssl
```