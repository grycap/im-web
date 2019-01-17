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

require_once 'user.php';
require_once 'cred.php';
require_once 'config.php';
 
if (!isset($_SESSION)) {
    session_start();
}   

$op = "";
if (isset($_POST['op'])) {
    $op = $_POST['op'];
} elseif (isset($_GET['op'])) {
    $op = $_GET['op'];
}

if (($op == "password" && !check_session_user()) || ($op != "register" && $op != "password" && (!check_session_user() || !check_admin_user()))) {
    header('Location: index.php?error=Invalid User' . $op);
} else {    
        
    if (strlen($op) > 0) {
        if ($op == "delete") {
            if (isset($_GET['id'])) {
                $username = $_GET['id'];
                $err = delete_user($username);
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . urlencode($err));
                } else {
                    header('Location: user_list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "password") {
            $username = $_SESSION["user"];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            $err = "";
            if (strlen(trim($password)) > 0) {
                if (trim($password) != trim($password2)) {
                    $err = "The passwords are not equal.";
                }
            }
                        
            if ($err == "") {
                $err = change_password($username, $password);
            }
            if (strlen($err) > 0) {
                header('Location: index.php?error=' . $err);
            } else {
                $_SESSION['password'] = $password;
                header('Location: list.php');
            }
        } elseif ($op == "register") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $err = "";

            if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
            	$err = "Username is not a valid email.";
            }

            if (strlen(trim($password)) > 0) {
                if (trim($password) != trim($password2)) {
                    $err = "The passwords are not equal.";
                }
            }
                        
            if ($err == "") {
                $err = insert_user($username, $password, array('users'), 0);
                $err = insert_credential($username, "", "InfrastructureManager", "", $username, $password, '', '', '', '', '', '', '', '', '', '', '', '');
                $err = insert_credential($username, "", "VMRC", "http://servproject.i3m.upv.es:8080/vmrc/vmrc", "micafer", "ttt25", '', '', '', '', '', '', '', '', '', '', '', '');
            }
            if (strlen($err) > 0) {
                header('Location: index.php?error=' . $err);
            } else {
                header('Location: index.php?info=User added successfully');
            }
        } elseif ($op == "add") {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $groups = $_POST['user_groups'];
            $permissions = $_POST['permissions'];

            $err = "";
            if (strlen(trim($password)) > 0) {
                if (trim($password) != trim($password2)) {
                    $err = "The passwords are not equal.";
                }
            }
                        
            if ($err == "") {
                        $err = insert_user($username, $password, $groups, $permissions);
            }
            if (strlen($err) > 0) {
                header('Location: error.php?msg=' . urlencode($err));
            } else {
                header('Location: user_list.php');
            }
        } elseif ($op == "edit") {
            if (isset($_POST['id'])) {
                $username = $_POST['id'];
                $new_username = $_POST['username'];
                $password = $_POST['password'];
                $password2 = $_POST['password2'];
                $groups = $_POST['user_groups'];
                $permissions = $_POST['permissions'];

                $err = "";
                if (strlen(trim($password)) > 0) {
                    if (trim($password) != trim($password2)) {
                        $err = "The passwords are not equal.";
                    }
                }
                    
                if ($err == "") {
                            $err = edit_user($username, $new_username, $password, $groups, $permissions);
                }
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . urlencode($err));
                } else {
                    header('Location: user_list.php');
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
