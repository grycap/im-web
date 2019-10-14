<?php
    $return_string = "";

	if(isset($_POST['vo']))
	{
        $vo = $_POST['vo']; 
        exec('python EGI_AppDB.py sites ' . escapeshellarg($vo), $sites);
        foreach ($sites as $site) {
            $site_p = explode(";", $site);
            $return_string .= "<option value=\"" . $site_p[1] . "\">" . $site_p[0] . "</option>";
        }
    } else {
        exec('python EGI_AppDB.py vos', $vos);
        foreach ($vos as $vo) {
            $return_string .= "<option value=\"" . $vo . "\">" . $vo . "</option>";
        }
    }

    echo $return_string;
?>
