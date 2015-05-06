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

    include('im.php');
    include('config.php');
 
    if(!isset($_SESSION)) session_start();   
    
    include('user.php');
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
                
                $res = CreateInfrastructure($im_host,$im_port,$radl);
                
                if (strpos($res, "Error") !== false) {
                    header('Location: error.php?msg=' . urlencode($res));
                } else {
                    header('Location: list.php');
                }
            } elseif ($op == "destroy") {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $res = DestroyInfrastructure($im_host,$im_port,$id);
                    
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
                    
                    $res = RemoveResource($im_host,$im_port,$infid, $vmid);
                    
                    if (strpos($res, "Error") !== false) {
                        header('Location: error.php?msg=' . urlencode($res));
                    } else {
                        header('Location: list.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "addresource") {
                $radl = $_POST['radl'];
                
                if (isset($_POST['infid'])) {
                    $infid = $_POST['infid'];
    
                    $res = AddResource($im_host,$im_port,$infid, $radl);
                    
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
            
            		$res = Reconfigure($im_host,$im_port,$infid, "");
            
            		if (strpos($res, "Error") !== false) {
            			header('Location: error.php?msg=' . urlencode($res));
            		} else {
            			header('Location: list.php');
            		}
            	} else {
            		header('Location: error.php?msg=No id');
            	}
            }
            
            
            
        } else {
            header('Location: error.php?msg=No op');
        }
    }
?>
