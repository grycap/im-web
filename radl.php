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

// op tiene que ser r,w o x
function radl_user_can($id, $user, $op) {
    include('config.php');
    include_once('user.php');
    
    $user_groups = get_user_groups($user);

    $res = false;
            
    $sql = "select name from radls where rowid = " . $id;
    $sql = $sql . " and (imuser = '" . $user . "' or other_" . $op . " = '1'";
            
    if (count($user_groups) > 0) {
        $sql = $sql . " or (group_" . $op . " = '1' and (";
                
        for($i=0;$i<count($user_groups);$i++) {
            $group = $user_groups[$i];
            if ($i > 0) $sql = $sql . " or ";
            $sql = $sql . "grpname = '" . $group['grpname'] . "'";
        }
                
        $sql = $sql . "))";
    }
            
    $sql = $sql . ")";

    $db = new IMDB();
    $result = $db->direct_query($sql);
    if (count($result) > 0) {
        $res = true;
    }
    $db->close();

    return $res;
}


function get_radls($user) {
    include('config.php');
    include_once('user.php');
    
    $user_groups = get_user_groups($user);

    $sql = "select rowid,* from radls where imuser = '" . $user . "'";
    $sql = $sql . " or other_r = '1'";
            
    if (count($user_groups) > 0) {
        $sql = $sql . " or (group_r = '1' and (";
                
        for($i=0;$i<count($user_groups);$i++) {
            $group = $user_groups[$i];
            if ($i > 0) $sql = $sql . " or ";
            $sql = $sql . "grpname = '" . $group['grpname'] . "'";
         }
                
        $sql = $sql . "))";
    }

    $db = new IMDB();
    $res = $db->direct_query($sql);
    $db->close();

    return $res;
}

function get_radl($id) {
    include('config.php');

    $db = new IMDB();
    $res = $db->get_items_from_table("radls", array("rowid" => "'" . $id . "'"));
    $db->close();
    if (count($res) > 0)
        return $res[0];
    else
        return NULL;
}



function insert_radl($imuser, $name, $desc, $radl, $group, $group_r, $group_w, $group_x, $other_r, $other_w, $other_x) {
    include('config.php');

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields[] = "'" . $imuser . "'";
    $fields[] = "'" . $db->escapeString($name) . "'";
    $fields[] = "'" . $db->escapeString($desc) . "'";
    $fields[] = "'" . $db->escapeString($radl) . "'";
    $fields[] = "'" . $db->escapeString($group) . "'";
    $fields[] = $group_r;
    $fields[] = $group_w;
    $fields[] = $group_x;
    $fields[] = $other_r;
    $fields[] = $other_w;
    $fields[] = $other_x;
    $res = $db->insert_item_into_table("radls",$fields);
    $db->close();

    return $res;
}

function edit_radl($id, $name, $desc, $radl, $group, $group_r, $group_w, $group_x, $other_r, $other_w, $other_x) {
    include('config.php');

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields["name"] = "'" . $db->escapeString($name) . "'";
    $fields["description"] = "'" . $db->escapeString($desc) . "'";
    $fields["radl"] = "'" . $db->escapeString($radl) . "'";
    $fields["grpname"] = "'" . $db->escapeString($group) . "'";
    $fields["group_r"] =  $group_r;
    $fields["group_w"] =  $group_w;
    $fields["group_x"] =  $group_x;
    $fields["other_r"] =  $other_r;
    $fields["other_w"] =  $other_w;
    $fields["other_x"] =  $other_x;
    $where = array("rowid" => "'" . $id . "'");
    $res = $db->edit_item_from_table("radls",$fields,$where);
    $db->close();

    return $res;
}

function delete_radl($id) {
    include('config.php');

    $db = new IMDB();
    $res = $db->delete_item_from_table("radls", array("rowid" => "'" . $id . "'"));
    $db->close();
    return $res;
}
?>
