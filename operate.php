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

require_once 'im.php';
require_once 'config.php';
 
if (!isset($_SESSION)) {
    session_start();
}   

require_once 'user.php';
if (!check_session_user()) {
	invalid_user_error();
} else {
    $op = "";
    if (isset($_POST['op'])) {
        $op = $_POST['op'];
    }

    $rand = "";
    if (isset($_POST['rand'])) {
    	$rand = $_POST['rand'];
    }
    
    if ($rand != $_SESSION["rand"]) {
    	error("Invalid rand parameter.");
    } else {
    
    if (strlen($op) > 0) {
        if ($op == "create") {
            $radl = $_POST['radl'];
            $async = false;
            if (isset($_POST['async'])) {
                    $async = $_POST['async'];
            }

            $res = GetIM()->CreateInfrastructure($radl, $async);

            if (strpos($res, "Error") !== false) {
            	error($res);
            } else {
                header('Location: list.php');
            }
        } elseif ($op == "destroy") {
        	if (isset($_POST['id'])) {
        		$id = $_POST['id'];
                $res = GetIM()->DestroyInfrastructure($id);
                    
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: list.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "destroyvm") {
        	if (isset($_POST['infid']) and isset($_POST['vmid'])) {
        		$infid = $_POST['infid'];
        		$vmid = $_POST['vmid'];
                    
                $res = GetIM()->RemoveResource($infid, $vmid);
                    
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: list.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "stopvm") {
        	if (isset($_POST['infid']) and isset($_POST['vmid'])) {
        		$infid = $_POST['infid'];
        		$vmid = $_POST['vmid'];
                
                 $res = GetIM()->StopVM($infid, $vmid);
                
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "startvm") {
        	if (isset($_POST['infid']) and isset($_POST['vmid'])) {
        		$infid = $_POST['infid'];
        		$vmid = $_POST['vmid'];
                     
                 $res = GetIM()->StartVM($infid, $vmid);
                     
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
                }
            } else {
            	error('No ID');
            }
        }  elseif ($op == "rebootvm") {
        	if (isset($_POST['infid']) and isset($_POST['vmid'])) {
        		$infid = $_POST['infid'];
        		$vmid = $_POST['vmid'];
        		
        		$res = GetIM()->RebootVM($infid, $vmid);
        		
        		if (strpos($res, "Error") !== false) {
        			error($res);
        		} else {
        			header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
        		}
        	} else {
        		error('No ID');
        	}
        } elseif ($op == "addresource") {
            $radl = $_POST['radl'];
                
            if (isset($_POST['infid'])) {
                $infid = $_POST['infid'];
    
                $res = GetIM()->AddResource($infid, $radl);
                    
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: list.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "reconfigure") {
        	if (isset($_POST['infid'])) {
        		$infid = $_POST['infid'];
            
                 $res = GetIM()->Reconfigure($infid, "");
            
                if (strpos($res, "Error") !== false) {
                	error($res);
                } else {
                    header('Location: list.php');
                }
            } else {
            	error('No ID');
            }
        } else {
        	error('Incorrect Operation: ' . urlencode($op));
        }
            
            
    } else {
    	error('No op');
    }
    }
}
?>
