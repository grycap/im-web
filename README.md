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
$im_host="servproject.i3m.upv.es";
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

