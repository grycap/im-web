<?php
include('config.php');

if ($im_use_rest) {
	include('im-rest.php');
} else {
	include('im-xml-rpc.php');	
}
?>
