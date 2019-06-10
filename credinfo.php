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

require_once 'cred.php';
require_once 'config.php';
require_once 'user.php';

if (!isset($_SESSION)) {
    session_start();
}   

if (!check_session_user()) {
	invalid_user_error();
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
                	error(urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "add") {
            $imuser = $_SESSION['user'];
            $id = $_POST['id'];
            $type = $_POST['type'];

            $host = "";
            $username = "";
            $password = "";
            $token_type = "";
            $project = "";
            $proxy = "";
            $private_key = "";
            $public_key = "";
            $certificate = "";
            $tenant = "";
            $subscription_id = "";
            $auth_version = "";
            $domain = "";
            $service_region = "";
            $base_url = "";

            if (isset($_POST['host'])) {
                $host = $_POST['host'];
            }
            if (isset($_POST['username'])) {
                $username = $_POST['username'];
            }
            if (isset($_POST['password'])) {
                $password = $_POST['password'];
            }
            if (isset($_POST['token_type'])) {
                $token_type = $_POST['token_type'];
            }
            if (isset($_POST['project'])) {
                $project = $_POST['project'];
            }
            if (isset($_POST['tenant'])) {
                $tenant = $_POST['tenant'];
            }
            if (isset($_POST['auth_version'])) {
                $auth_version = $_POST['auth_version'];
            }
            if (isset($_POST['domain'])) {
                $domain = $_POST['domain'];
            }
            if (isset($_POST['service_region'])) {
                $service_region = $_POST['service_region'];
            }
            if (isset($_POST['base_url'])) {
                $base_url = $_POST['base_url'];
            }
            if (isset($_POST['subscription_id'])) {
                $subscription_id = $_POST['subscription_id'];
            }
                
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
                
            $err = insert_credential($imuser, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant, $subscription_id, $auth_version, $domain, $service_region, $base_url);
            if (strlen($err) > 0) {
            	error(urlencode($err));
            } else {
                header('Location: credentials.php');
            }
        } elseif ($op == "edit") {
            if (isset($_POST['id'])) {
                $rowid = $_POST['rowid'];
                $id = $_POST['id'];
                $type = $_POST['type'];
                    
                $host = "";
                $username = "";
                $password = "";
                $token_type = "";
                $project = "";
                $proxy = "";
                $private_key = "";
                $public_key = "";
                $certificate = "";
                $tenant = "";
                $subscription_id = "";
                $auth_version = "";
                $domain = "";
                $service_region = "";
                $base_url = "";
                    
                if (isset($_POST['host'])) {
                     $host = $_POST['host'];
                }
                if (isset($_POST['username'])) {
                    $username = $_POST['username'];
                }
                if (isset($_POST['password'])) {
                    $password = $_POST['password'];
                }
                if (isset($_POST['token_type'])) {
                    $token_type = $_POST['token_type'];
                }
                if (isset($_POST['project'])) {
                    $project = $_POST['project'];
                }
                if (isset($_POST['tenant'])) {
                    $tenant = $_POST['tenant'];
                }
                if (isset($_POST['auth_version'])) {
                    $auth_version = $_POST['auth_version'];
                }
                if (isset($_POST['domain'])) {
                    $domain = $_POST['domain'];
                }
                if (isset($_POST['service_region'])) {
                    $service_region = $_POST['service_region'];
                }
                if (isset($_POST['base_url'])) {
                    $base_url = $_POST['base_url'];
                }
                if (isset($_POST['subscription_id'])) {
                    $subscription_id = $_POST['subscription_id'];
                }
                    
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

                    $err = edit_credential($rowid, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant, $subscription_id, $auth_version, $domain, $service_region, $base_url);
                if (strlen($err) > 0) {
                	error(urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "enable") {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $err = enable_credential($id, 1);
                if (strlen($err) > 0) {
                	error(urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "disable") {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $err = enable_credential($id, 0);
                if (strlen($err) > 0) {
                	error(urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "order") {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $order = $_GET['order'];
                $new_order = $_GET['new_order'];
                $imuser = $_SESSION['user'];
                $err = change_order($id, $imuser, $order, $new_order);
                if (strlen($err) > 0) {
                	error(urlencode($err));
                } else {
                    header('Location: credentials.php');
                }
            } else {
            	error('No ID');
            }
        } else {
        	error('Incorrect op: ' . $op);
        }
    } else {
    	error('No op');
    }
}
?>
