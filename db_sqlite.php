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

class IMDBSQLite3 extends SQLite3
{
    function __construct()
    {
    	$this->type = "SQLite";
	    include('config.php');
        $this->open($im_db);
    }

    function gen_where_sentence($where) {
        if ($where) {
            $where_array = array();
            foreach ($where as $field => $value) {
               $where_array[] = $field . " = " . $value;
            }
            return " where " . join(" and ", $where_array);
        } else {
            return "";
        }
    }

    function delete_item_from_table($table, $where) {
        $sql = "delete from " . $table . $this->gen_where_sentence($where);

        $res = "";
        $success = $this->exec($sql);
        if (!$success) {
            $res = $this->lastErrorMsg() . ". SQL: " . $sql;
        }
        return $res;
    }

    function direct_query($sql) {
       $result = $this->query($sql);
       $res = array();
       while($row = $result->fetchArray()) {
           $res[] = $row;
       }
       return $res;
    }

    function get_items_from_table($table, $where = NULL, $order = NULL) {
       $sql = 'select rowid,* from ' . $table . $this->gen_where_sentence($where);
       if ($order) {
    		$sql = $sql . ' order by ' . $order;
       }
       $result = $this->query($sql);
       $res = array();
       while($row = $result->fetchArray()) {
           $res[] = $row;
       }
       return $res;
    }

    function insert_item_into_table($table, $fields) {
       $sql = "insert into " . $table . " values(" . join(",", $fields) . ")";
       $res = "";
       $success = $this->exec($sql);
       if (!$success) {
           $res = $sql . $this->lastErrorMsg() . ". SQL: " . $sql;
       }
       return $res;
    }

    function edit_item_from_table($table, $fields, $where) {
       $sql = "update " . $table . " set ";
       $set_array = array();
       foreach ($fields as $field => $value) {
          $set_array[] = $field . " = " . $value;
       }
       $sql = $sql . join(",", $set_array);
       $sql = $sql . $this->gen_where_sentence($where);

       $res = "";
       $success = $this->exec($sql);
       if (!$success) {
           $res = $this->lastErrorMsg() . ". SQL: " . $sql;
       }
       return $res;
    }

}

?>
