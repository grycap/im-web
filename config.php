<?php
/*
 IM - Infrastructure Manager
 Copyright (C) 2011 - GRyCAP - Universitat Politecnica de Valencia

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


$im_use_rest=false;
$im_use_ssl=false;
$im_host="localhost";
$im_port=8899;
$im_method='http';
$im_db="/home/www-data/im.db";
# In case of using a MySQL backend:
# $im_db="mysql://username:password@mysqlhost/db_name";

# To use that feature the IM recipes file must accesible to the web server
#$recipes_db="/usr/local/im/contextualization/recipes_ansible.db";
# If not set ""
$recipes_db="";
# OpenID Issuer supported use "" to disable OpenID support
#$openid_issuer="https://iam-test.indigo-datacloud.eu/";
$openid_issuer="";
# OpenID Issuer name
$openid_name="";
# OpenID Client data
$CLIENT_ID = 'client_id';
$CLIENT_SECRET = 'client_secret';
$REDIRECT_URI = 'https://server.com/im-web/openid_auth.php';

include_once('db.php');
?>
