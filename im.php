<?php
require_once 'im-rest.php';
require_once 'im-xml-rpc.php';    

function GetIM()
{
    include 'config.php';

    // for the unit tests
    if (isset($GLOBALS['mock_im'])) {
        return $GLOBALS['mock_im'];
    }

    if ($im_use_rest) {
    	return IMRest::connect($im_host, $im_port, $im_path);
    } else {
        if ($im_use_ssl) {
            $im_method = 'https';
        } else {
            $im_method = 'http';
        }
        return IMXML::connect($im_host, $im_port, $im_method, $im_path);
    }
}
?>