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

if (!isset($_SESSION)) {
    session_start();
}

require_once 'user.php';
require_once 'format.php';

if (isset($_GET['infid'])) {
    $infid = $_GET['infid'];
} else {
    echo "No Inf ID set.";
    die();
}

if (!check_session_user()) {
    invalid_user_error();
} else {
    include 'im.php';
    include 'config.php';

    $res = GetIM()->GetInfrastructureState($infid);
    
    if (is_string($res) && strpos($res, "Error") !== false) {
        echo '{"state": "error", "state_format": "<span style=\'color:red\'>error</span>", "vms": ""}';
    } else {
        $state = $res["state"]["state"];
        $status = formatState($state);

        $vmids = array_keys($res["state"]["vm_states"]);
        sort($vmids);
        
        $vms = "";
        foreach ($vmids as $vm) {
            $vms = $vms . "<a href='getvminfo.php?id=" . $infid . "&vmid=" . $vm . "' alt='VM Info' title='VM Info'>" . $vm . "<br>";
        }

        echo '{"state": "' . $state . '", "state_format": "' . $status . '", "vms": "' . $vms . '"}';
    }
}
?>