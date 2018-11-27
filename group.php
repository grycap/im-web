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


function get_groups()
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("grp");
    $db->close();
    return $res;
}

function get_group($name)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("grp", array("name" => "'" . $db->escapeString($name) . "'"));
    $db->close();
    if (count($res) > 0) {
        return $res[0];
    } else {
        return null;
    }
}

function insert_group($name, $desc)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields[] = "'" . $db->escapeString($name) . "'";
    $fields[] = "'" . $db->escapeString($desc) . "'";
    $res = $db->insert_item_into_table("grp", $fields);
    $db->close();

    return $res;
}

function edit_group($name, $new_name, $desc)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields["name"] = "'" . $db->escapeString($new_name) . "'";
    $fields["description"] = "'" . $db->escapeString($desc) . "'";
    $where = array("name" => "'" . $name . "'");
    $res = $db->edit_item_from_table("grp", $fields, $where);
    $db->close();

    return $res;
}

function delete_group($name)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->delete_item_from_table("grp", array("name" => "'" . $name . "'"));
    $db->close();
    return $res;
}
?>
