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


function get_recipes() {
    include('config.php');

    $db = new RecipesDB();
    $res = $db->get_items_from_table("recipes");
    $db->close();
    return $res;
}

function get_recipe($id) {
    include('config.php');

    $db = new RecipesDB();
    $res = $db->get_items_from_table("recipes", array("rowid" => $id));
    $db->close();
    if (count($res) > 0)
        return $res[0];
    else
        return NULL;
}

function insert_recipe($name, $version, $desc, $module, $recipe, $galaxy_module, $requirements) {
    include('config.php');

    $res = "";
    $db = new RecipesDB();
    $fields = array();
    $fields[] = "'" . $db->escapeString($name) . "'";
    $fields[] = "'" . $db->escapeString($version) . "'";
    $fields[] = "'" . $db->escapeString($module) . "'";
    $fields[] = "'" . $db->escapeString($recipe) . "'";
    $fields[] = "1"; # IsApp set to true
    $fields[] = "'" . $db->escapeString($galaxy_module) . "'";
    $fields[] = "'" . $db->escapeString($desc) . "'";
    $fields[] = "'" . $db->escapeString($requirements) . "'";
    $res = $db->insert_item_into_table("recipes",$fields);
    $db->close();

    return $res;
}

function edit_recipe($id, $name, $version, $desc, $module, $recipe, $galaxy_module, $requirements) {
    include('config.php');

    $res = "";
    $db = new RecipesDB();
    $fields = array();
    $fields["name"] = "'" . $db->escapeString($name) . "'";
    $fields["version"] = "'" . $db->escapeString($version) . "'";
    $fields["module"] = "'" . $db->escapeString($module) . "'";
    $fields["recipe"] = "'" . $db->escapeString($recipe) . "'";
    $fields["galaxy_module"] = "'" . $db->escapeString($galaxy_module) . "'";
    $fields["description"] = "'" . $db->escapeString($desc) . "'";
    $fields["requirements"] = "'" . $db->escapeString($requirements) . "'";
    $where = array("rowid" => $id);
    $res = $db->edit_item_from_table("recipes",$fields,$where);
    $db->close();

    return $res;
}

function delete_recipe($id) {
    include('config.php');

    $db = new RecipesDB();
    $res = $db->delete_item_from_table("recipes", array("rowid" => $id));
    $db->close();
    return $res;
}
?>
