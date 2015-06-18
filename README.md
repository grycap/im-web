IM - Infrastructure Manager web GUI
===================================

IM is a tool that ease the access and the usability of IaaS clouds by automating
the VMI selection, deployment, configuration, software installation, monitoring
and update of Virtual Appliances. It supports APIs from a large number of
virtual platforms, making user applications cloud-agnostic. In addition it
integrates a contextualization system to enable the installation and
configuration of all the user required applications providing the user with a
fully functional infrastructure.


1. INSTALLATION
===============

1.1 REQUISITES
--------------

IM web interface is based on PHP, so a web server with PHP support must be installed.

Also the mcrypt PHP module must be installed and enabled.

It is also required to install the PHP module to access SQLite databases.

1.2 INSTALLING
--------------

Select a proper path in the document root of the web server to install the IM web interface
(i.e. /var/www/im).

```
$ tar xvzf IM-web-X.XX.tar.gz
$ mv IM-X.XX /var/www/im
$ chown -R www-data /var/www/im
```

1.2 CONFIGURATION
--------------

Adjust the configuration settings in the file config.php:

* Address of the IM host
$im_host="im-server.domain.com";
* Port of the IM service
$im_port=8899;
* Path of the IM web interface DB. The original path will be /var/www/im/im.db
  but is more secure to move it to a path not in the path of the web server.
  The file and the directory must have write permissions to the web server user.
$im_db="/home/www-data/im.db";
* In case that the IM service and web interface are in the same host, the Recipes
  feature can be activated. Specify the path of the recipes_ansible.db file of the
  IM and take care that the file and the directory must have write permissions to
  the web server user. In other case set "".
$recipes_db="/usr/local/im/contextualization/recipes_ansible.db";

1.3 DEFAULT USER
----------------

The default administrator user is admin with password admin.

2. DOCKER IMAGE
===============

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