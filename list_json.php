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

$page = 1;
$perPage = 999999;
$offset = 0;
$filter = "";

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
if (isset($_GET['perPage'])) {
    $perPage = $_GET['perPage'];
}
if (isset($_GET['offset'])) {
    $offset = $_GET['offset'];
}
if (isset($_GET['queries'])) {
    $filter = $_GET['queries']['search'];
}

if (!isset($_SESSION)) {
    session_start();
}

require_once 'user.php';
require_once 'format.php';
if (!check_session_user()) {
    ?>
        {
            "records": [
            {
                "id": "Error",
                "vms": "",
                "outputs": "",
                "cont.Message": "Invalid user",
                "status": "",
                "reconfigure": "",
                "delete": "",
                "addResources": ""
            }
            ],
            "queryRecordCount": 1,
            "totalRecordCount": 1
        }
    <?php
} else {
    include 'im.php';
    include 'config.php';
    $res = GetIM()->GetInfrastructureList();
        
    if (is_string($res) and strpos($res, "Error") !== false) {
        ?>
        {
            "records": [
            {
                "id": "Error",
                "vms": "",
                "outputs": "",
                "cont.Message": "<?php echo $res;?>",
                "status": "",
                "reconfigure": "",
                "delete": "",
                "addResources": ""
            }
            ],
            "queryRecordCount": 1,
            "totalRecordCount": 1
        }
        <?php
    } else {
        if (count($res) > 0) {
            $text = '{ "records": [';
            
            $queryCont = 0;
            $numElem = 0;
            $cont = 0;
            foreach ($res as $inf) {
                if ($filter == "" || strpos($inf, $filter) !== false) {
                    $queryCont++;

                    if ($numElem < $offset) {
                        $numElem++;
                        continue;
                    }
    
                    if ($perPage > $cont) {
                        if ($cont > 0) {
                            $text = $text . ',';
                        }
                        $cont++;
                        $text = $text . '{';
                        $text = $text . '"id":"' . $inf . '",'; 
                        
                        $full_state = GetIM()->GetInfrastructureState($inf);
                        $status = "N/A";
                        if (!(is_string($full_state) && strpos($full_state, "Error") !== false)) {
                            $state = $full_state["state"];
                            $status = formatState($state);
                        }
        
                        $text = $text . '"vms":"';
                        if ($status == "N/A") {
                            $text = $text . 'N/A';
                        } else {
                            $vmids = array_keys($full_state["vm_states"]);
                            sort($vmids);
                            
                            foreach ($vmids as $vm) {
                                $text = $text . "<a href='getvminfo.php?id=" . $inf . "&vmid=" . $vm . "' alt='VM Info' title='VM Info'>" . $vm . "<br>";
                            }
                        }
                        $text = $text . '",';
                        
                        $text = $text . '"outputs":"<a href=\"getoutputs.php?id=' . $inf . '\">Show</a>",';
                        $text = $text . '"cont.Message":"<a href=\"getcontmsg.php?id=' . $inf . '\">Show</a>",';
                        $text = $text . '"status":"' . $status . '",';
                        
                        if ($state == "configured" || $state == "unconfigured") {
                            $text = $text . '"reconfigure":"<a href=\"operate.php?op=reconfigure&infid=' . $inf . '\"><img src=\"images/reload.png\" border=\"0\" alt=\"Reconfigure\" title=\"Reconfigure\"></a>",';
                        } else {
                            $text = $text . '"reconfigure":"N/A",';
                        }
                        
                        $text = $text . '"delete": "<a onclick=\"javascript:confirm_delete(\'' . $inf . '\')\" href=\"#\"><img src=\"images/borrar.gif\" border=\"0\" alt=\"Delete\" title=\"Delete\"></a>",';
                        $text = $text . '"addResources":"<a href=\"form.php?id=' . $inf . '?>\"><img src=\"images/add_resources_icon.png\" border=\"0\" alt=\"Add Resources\" title=\"Add Resources\"></a>"';
                        
                            
                        $text = $text . '}';
                    }
                }
            }
            
            $text = $text . '], "queryRecordCount": ' . $queryCont . ', "totalRecordCount": ' .  count($res) . '}'; 

            echo $text;
        
        } else {
            ?>
        {
            "records": [],
            "queryRecordCount": 0,
            "totalRecordCount": 0
        }
            <?php
        }
    }
}
?>