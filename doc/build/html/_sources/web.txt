IM Web Interface
================

The IM Web client is a graphical interface to access the XML-RPC API of IM Server.

Prerequisites
-------------
As a web application it needs a web server (e.g. Apache) with support to PHP language
and with the SQLite D.B. support. It must have access to the XML-RPC API of the IM Server.

Configuration
-------------

The web interface reads the configuration from :file:`$IM_WEB_PATH/config.php`. It has 
the following variables::

	$im_host="localhost";
	$im_port=8899;
	$im_db="/home/www-data/im.db";
	# To use that feature the IM recipes file must accesible to the web server
	#$recipes_db="/usr/local/im/contextualization/recipes_ansible.db";
	# If not set ""
	$recipes_db="";
	# To activate the EC3 functionality, currently unavailable
	$ec3=False;
	$ec3_path="/var/www/im/ec3";


Usage
-----


Infrastructures
^^^^^^^^^^^^^^^

Credentials
^^^^^^^^^^^


RADLs
^^^^^

Recipes
^^^^^^^


Admin
^^^^^
