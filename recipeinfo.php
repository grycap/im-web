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
require_once 'recipe.php';
require_once 'config.php';
 
if (!isset($_SESSION)) {
    session_start();
}   

if (!check_session_user() || !check_admin_user()) {
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
                $err = delete_recipe($id);
                if (strlen($err) > 0) {
                	error($err);
                } else {
                    header('Location: recipe_list.php');
                }
            } else {
            	error('No ID');
            }
        } elseif ($op == "add") {
            $name = $_POST['name'];
            $version = $_POST['version'];
            $module = $_POST['module'];
            $recipe = $_POST['recipe'];
            $galaxy_module = $_POST['galaxy_module'];
            $desc = $_POST['description'];
            $requirements = $_POST['requirements'];
                
            $err = insert_recipe($name, $version, $desc, $module, $recipe, $galaxy_module, $requirements);
            if (strlen($err) > 0) {
            	error($err);
            } else {
                header('Location: recipe_list.php');
            }
        } elseif ($op == "edit") {
            if (isset($_POST['id'])) {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $version = $_POST['version'];
                $module = $_POST['module'];
                $recipe = $_POST['recipe'];
                $galaxy_module = $_POST['galaxy_module'];
                $desc = $_POST['description'];
                $requirements = $_POST['requirements'];
                    
                $err = edit_recipe($id, $name, $version, $desc, $module, $recipe, $galaxy_module, $requirements);
                if (strlen($err) > 0) {
                	error($err);
                } else {
                    header('Location: recipe_list.php');
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
