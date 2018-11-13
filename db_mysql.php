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

class IMDBMySQL
{
    function __construct($link = NULL)
    {
    	$this->type = "MySQL";
    	$this->db_schema = array(
    			"credentials" => "CREATE TABLE credentials (
        rowid int NOT NULL AUTO_INCREMENT,
        id VARCHAR(256),
        imuser VARCHAR(256),
        type VARCHAR(256),
        host VARCHAR(256),
        username VARCHAR(256),
        password VARCHAR(256),
        enabled int,
        ord int,
        proxy TEXT,
        token_type VARCHAR(256),
        project VARCHAR(256),
        public_key TEXT,
        private_key TEXT,
        certificate TEXT,
        tenant VARCHAR(256),
        subscription_id VARCHAR(256),
        auth_version VARCHAR(256),
        domain VARCHAR(256),
        service_region VARCHAR(256),
        base_url VARCHAR(256),
        PRIMARY KEY (rowid)
        );",
    			"user" => "CREATE TABLE user (
        rowid int NOT NULL AUTO_INCREMENT,
        username VARCHAR(256) UNIQUE NOT NULL,
        password VARCHAR(256) NOT NULL,
        permissions int,
        PRIMARY KEY (rowid)
        );",
    			"users_grp" => "CREATE TABLE users_grp (
        rowid int NOT NULL AUTO_INCREMENT,
        grpname VARCHAR(256) NOT NULL,
        username VARCHAR(256) NOT NULL,
        FOREIGN KEY(grpname) REFERENCES grp(name),
        FOREIGN KEY(username) REFERENCES user(username),
        PRIMARY KEY (rowid)
        );",
    			"grp" => "CREATE TABLE grp (
        rowid int NOT NULL AUTO_INCREMENT,
        name VARCHAR(256) UNIQUE NOT NULL,
        description VARCHAR(256),
        PRIMARY KEY (rowid)
        );",
    			"radls" => "CREATE TABLE radls (
        rowid int NOT NULL AUTO_INCREMENT,
        imuser VARCHAR(128) NOT NULL,
        name VARCHAR(128) NOT NULL,
        description VARCHAR(256),
        radl TEXT,
        grpname VARCHAR(256)  NOT NULL,
        group_r int,
        group_w int,
        group_x int,
        other_r int,
        other_w int,
        other_x int,
        PRIMARY KEY (rowid)
        );"
    			);

	    include('config.php');
	    # format: mysql://username:password@server/db_name
	    $url = parse_url($im_db);
	    if ($link) {
	    	$this->link = $link;
	    } else {
	    	$this->link = new mysqli($url['host'], $url['user'], $url['pass']);
	    }
	    $this->db_name = ltrim($url['path'], "/");
	    $success = $this->link->select_db($this->db_name);
	    if (!$success) {
	    	$res = $this->create_db();
	    }
    }

    function escapeString($str) {
    	return $this->link->real_escape_string($str);
    }
    
    function create_db() { 
    	$success = $this->link->query("CREATE DATABASE `" . $this->escapeString($this->db_name) . "`;");
    	if (!$success) {
    		return "Error creating DB: " . $this->link->error;
    	}
    	$success = $this->link->select_db($this->db_name);
    	if (!$success) {
    		return "Error selecting DB: " . $this->link->error;
    	}
    	$success = $this->link->query("set foreign_key_checks=0;");
    	if (!$success) {
    		return "Error foreign_key_checks DB: " . $this->link->error;
    	}
    	foreach ($this->db_schema as $table) {
    		$success = $this->link->query($table);
    		if (!$success) {
    			return "Error creating tables in the DB: " . $this->link->error;
    		}
    	}

    	return "";
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
        return $this->direct_exec($sql);
    }

    function close() {
    	$this->link->close();
    }
    
    function direct_query($sql) {
       $result = $this->link->query($sql);
       if (!$result) {
       	var_dump($this->link->error . ". SQL: " . $sql);
       }

       $res = array();
       while($row = $result->fetch_assoc()) {
           $res[] = $row;
       }
       $result->free();
       return $res;
    }

    function direct_exec($sql) {
    	$res = "";
    	$success = $this->link->query($sql);
    	if (!$success) {
    		$res = $this->link->error . ". SQL: " . $sql;
    	}
    	$success = $this->link->commit();
    	if (!$success) {
    		$res = $this->link->error . ". SQL: " . $sql;
    	}
    	return $res;
    }
    
    function get_items_from_table($table, $where = NULL, $order = NULL) {
    	$sql = 'select * from ' . $table . $this->gen_where_sentence($where);
    	if ($order) {
    		$sql = $sql . ' order by ' . $order;
    	}
       return $this->direct_query($sql);
    }

    function insert_item_into_table($table, $fields) {
       $sql = "insert into " . $table . " values(NULL, " . join(",", $fields) . ")";
       return $this->direct_exec($sql);
    }

    function edit_item_from_table($table, $fields, $where) {
       $sql = "update " . $table . " set ";
       $set_array = array();
       foreach ($fields as $field => $value) {
          $set_array[] = $field . " = " . $value;
       }
       $sql = $sql . join(",", $set_array);
       $sql = $sql . $this->gen_where_sentence($where);
       return $this->direct_exec($sql);
    }

}

?>
