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
require_once 'group.php';
require_once 'config.php';
 
if (!isset($_SESSION)) {
    session_start();
}   

if (!check_session_user() || !check_admin_user()) {
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
                $name = $_GET['id'];
                $err = delete_group($name);
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . urlencode($err));
                } else {
                    header('Location: group_list.php');
                }
            } else {
                header('Location: error.php?msg=No id');
            }
        } elseif ($op == "add") {
            $name = $_POST['name'];
            $desc = $_POST['description'];
                
            $err = insert_group($name, $desc);
            if (strlen($err) > 0) {
                header('Location: error.php?msg=' . urlencode($err));
            } else {
                header('Location: group_list.php');
            }
        } elseif ($op == "edit") {
            if (isset($_POST['id'])) {
                $name = $_POST['id'];
                $new_name = $_POST['name'];
                $desc = $_POST['description'];
                    
                $err = edit_group($name, $new_name, $desc);
                if (strlen($err) > 0) {
                    header('Location: error.php?msg=' . urlencode($err));
                } else {
                    header('Location: group_list.php');
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
