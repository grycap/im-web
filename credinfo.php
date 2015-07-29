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

    include_once('cred.php');
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
                    $err = delete_credential($id);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . urlencode($err));
                    } else {
                        header('Location: credentials.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "add") {
            	$imuser = $_SESSION['user'];
                $id = $_POST['id'];
                $type = $_POST['type'];
                $host = $_POST['host'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $token_type = $_POST['token_type'];
                $project = $_POST['project'];
                $proxy = "";
                $private_key = "";
                $public_key = "";
                $certificate = "";
                $tenant = $_POST['tenant'];
                
                if (isset($_FILES['proxy']['tmp_name'])) {
                	$proxy = file_get_contents($_FILES['proxy']['tmp_name']);
                }
                if (isset($_FILES['public_key']['tmp_name'])) {
                	$public_key = file_get_contents($_FILES['public_key']['tmp_name']);
                }
                if (isset($_FILES['private_key']['tmp_name'])) {
                	$private_key = file_get_contents($_FILES['private_key']['tmp_name']);
                }
                if (isset($_FILES['certificate']['tmp_name'])) {
                	$certificate = file_get_contents($_FILES['certificate']['tmp_name']);
                }
                
                $err = insert_credential($imuser, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant);
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } elseif ($op == "edit") {
                if (isset($_POST['id'])) {
                    $rowid = $_POST['rowid'];
                    $id = $_POST['id'];
                    
                    $type = $_POST['type'];
                    $host = $_POST['host'];
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $token_type = $_POST['token_type'];
	                $project = $_POST['project'];
	                $proxy = "";
	                $private_key = "";
	                $public_key = "";
	                $certificate = "";
	                $tenant = $_POST['tenant'];
	                
	                if (isset($_FILES['proxy']['tmp_name'])) {
	                	$proxy = file_get_contents($_FILES['proxy']['tmp_name']);
	                }
	                if (isset($_FILES['public_key']['tmp_name'])) {
	                	$public_key = file_get_contents($_FILES['public_key']['tmp_name']);
	                }
	                if (isset($_FILES['private_key']['tmp_name'])) {
	                	$private_key = file_get_contents($_FILES['private_key']['tmp_name']);
	                }
	                if (isset($_FILES['certificate']['tmp_name'])) {
	                	$certificate = file_get_contents($_FILES['certificate']['tmp_name']);
	                }									

                    $err = edit_credential($rowid, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . urlencode($err));
                    } else {
                        header('Location: credentials.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "enable") {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $err = enable_credential($id, 1);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . urlencode($err));
                    } else {
                        header('Location: credentials.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "disable") {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $err = enable_credential($id, 0);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . urlencode($err));
                    } else {
                        header('Location: credentials.php');
                    }
                } else {
                    header('Location: error.php?msg=No id');
                }
            } elseif ($op == "order") {
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $order = $_GET['order'];
                    $new_order = $_GET['new_order'];
                    $imuser = $_SESSION['user'];
                    $err = change_order($id, $imuser, $order, $new_order);
                    if (strlen($err) > 0) {
                        header('Location: error.php?msg=' . urlencode($err));
                    } else {
                        header('Location: credentials.php');
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
