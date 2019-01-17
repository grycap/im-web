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
    header('Location: index.php?error=Invalid User');
} else {
    $op = "";
    if (isset($_POST['op'])) {
        $op = $_POST['op'];
    } elseif (isset($_GET['op'])) {
        $op = $_GET['op'];
    }

    if (strlen($op) > 0) {
        if ($op == "create") {
            $radl = $_POST['radl'];
            $async = false;
            if (isset($_POST['async'])) {
                    $async = $_POST['async'];
            }

            $res = GetIM()->CreateInfrastructure($radl, $async);

            if (strpos($res, "Error") !== false) {
                header('Location: error.php?msg=' . urlencode($res));
            } else {
                header('Location: list.php');
            }
        } elseif ($op == "destroy") {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $res = GetIM()->DestroyInfrastructure($id);
                    
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "destroyvm") {
            if (isset($_GET['infid']) and isset($_GET['vmid'])) {
                $infid = $_GET['infid'];
                $vmid = $_GET['vmid'];
                    
                $res = GetIM()->RemoveResource($infid, $vmid);
                    
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "stopvm") {
            if (isset($_GET['infid']) and isset($_GET['vmid'])) {
                 $infid = $_GET['infid'];
                 $vmid = $_GET['vmid'];
                
                 $res = GetIM()->StopVM($infid, $vmid);
                
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "startvm") {
            if (isset($_GET['infid']) and isset($_GET['vmid'])) {
                 $infid = $_GET['infid'];
                 $vmid = $_GET['vmid'];
                     
                 $res = GetIM()->StartVM($infid, $vmid);
                     
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        }  elseif ($op == "rebootvm") {
        	if (isset($_GET['infid']) and isset($_GET['vmid'])) {
        		$infid = $_GET['infid'];
        		$vmid = $_GET['vmid'];
        		
        		$res = GetIM()->RebootVM($infid, $vmid);
        		
        		if (strpos($res, "Error") !== false) {
        			header('Location: error.php?msg=' . urlencode($res));
        		} else {
        			header('Location: getvminfo.php?id=' . $infid . '&vmid=' . $vmid);
        		}
        	} else {
        		header('Location: error.php?msg=No id');
        	}
        } elseif ($op == "addresource") {
            $radl = $_POST['radl'];
                
            if (isset($_POST['infid'])) {
                $infid = $_POST['infid'];
    
                $res = GetIM()->AddResource($infid, $radl);
                    
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "reconfigure") {
            if (isset($_GET['infid'])) {
                 $infid = $_GET['infid'];
            
                 $res = GetIM()->Reconfigure($infid, "");
            
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } else {
            header('Location: error.php?msg=Incorrect Operation: ' . urlencode($op));
        }
            
            
    } else {
        header('Location: error.php?msg=No op');
    }
}
?>
