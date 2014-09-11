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

    include_once('radl.php');
    include_once('config.php');
    
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
            if ($op == "delete") {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $err = delete_radl($id);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . $err);
                    } else {
                        header('Location: radl_list.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "add") {
                $name = $_POST['name'];
                $desc = $_POST['description'];
                $radl = $_POST['radl'];
                $imuser = $_SESSION['user'];
                $group = $_POST['group'];
                
                $group_r = (isset($_POST['group_r']) ? "1" : "0");
                $group_w = (isset($_POST['group_w']) ? "1" : "0");
                $group_x = (isset($_POST['group_x']) ? "1" : "0");
                
                $other_r = (isset($_POST['other_r']) ? "1" : "0");
                $other_w = (isset($_POST['other_w']) ? "1" : "0");
                $other_x = (isset($_POST['other_x']) ? "1" : "0");
    
                $err = insert_radl($imuser, $name, $desc, $radl, $group, $group_r, $group_w, $group_x, $other_r, $other_w, $other_x);
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . $err);
                } else {
                    header('Location: radl_list.php');
                }
            } elseif ($op == "edit") {
                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    
                    $name = $_POST['name'];
                    $desc = $_POST['description'];
                    $radl = $_POST['radl'];
                    $group = $_POST['group'];
                    
                    $group_r = (isset($_POST['group_r']) ? "1" : "0");
                    $group_w = (isset($_POST['group_w']) ? "1" : "0");
                    $group_x = (isset($_POST['group_x']) ? "1" : "0");
                    
                    $other_r = (isset($_POST['other_r']) ? "1" : "0");
                    $other_w = (isset($_POST['other_w']) ? "1" : "0");
                    $other_x = (isset($_POST['other_x']) ? "1" : "0");
                    
                    $err = edit_radl($id, $name, $desc, $radl, $group, $group_r, $group_w, $group_x, $other_r, $other_w, $other_x);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . $err);
                    } else {
                        header('Location: radl_list.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "launch") {
                if (isset($_GET['id'])) {
                	include_once('im.php');
                	
                    $id = $_GET['id'];
                    
                    $radl = get_radl($id);
                    
                    // Miramos si el RADL tiene parametros por definir
                    if (strpos($radl['radl'], "@input.")) {
                    	// Miramos si ya nos pasan los valores 
                    	if (isset($_GET['parameters'])) {
                    		$pos = -1;
                    		$params_ok = true;
                    		while ($pos = strpos($radl['radl'], "@input.", $pos+1)) {
                    			$pos_fin = strpos($radl['radl'], "@", $pos+1);
                    			$param_replace = substr($radl['radl'], $pos, $pos_fin-$pos+1);
                    			$param_name = substr($radl['radl'], $pos+7, $pos_fin-$pos-7);
                    			if (isset($_GET[$param_name])) {
                    				$radl['radl'] = str_replace($param_replace, $_GET[$param_name], $radl['radl']);
                    			} else {
                    				$params_ok = false;
                    				header('Location: error.php?msg=RADL parameter ' . $param_name . ' undefined.');
                    			}			
                    		}
                    		
                    		// tenemos todos los parametros, asi que lanzamos el RADL substituido
                    		if ($params_ok) {
	                    		$res = CreateInfrastructure($im_host,$im_port,$radl['radl']);
	                    		 
	                    		if (strpos($res, "Error") === False) {
	                    			header('Location: list.php');
	                    		} else {
	                    			header('Location: error.php?msg=' . $res);
	                    		}
                    		}
                    	} else { 
                    		// no tenemos los parametros, los pedimos
                    		header('Location: radl_list.php?parameters=' . $id);
                    	}
                    } else {
                    	// no tenemos parametros
	                    $res = CreateInfrastructure($im_host,$im_port,$radl['radl']);
	                    
	                    if (strpos($res, "Error") === False) {
	                        header('Location: list.php');
	                    } else {
	                        header('Location: error.php?msg=' . $res);
	                    }
                	}
                } else {
                    header('Location: error.php?msg=No id');
                }
            } else {
                header('Location: error.php?msg=Incorrect op: ' . $op);
            }
        } else {
            header('Location: error.php?msg=No op');
        }
    }
?>
