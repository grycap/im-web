<?php
include('config.php');

function GetIM() {
	if ($im_use_rest) {
		include('im-rest.php');
		return IMRest::connect($im_host,$im_port);
	} else {
		include('im-xml-rpc.php');	
		if ($im_use_ssl) {
			$im_method = 'https';
		} else {
			$im_method = 'http';
		}
		return IMXML::connect($im_host,$im_port, $im_method);
	}
}

?>
