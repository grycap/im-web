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

require_once 'crypt.php';

function check_user_token()
{
    include 'config.php';

    include_once 'OAuth2/Client.php';
    include_once 'OAuth2/GrantType/IGrantType.php';
    include_once 'OAuth2/GrantType/AuthorizationCode.php';

    $client = new OAuth2\Client($CLIENT_ID, $CLIENT_SECRET, OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
    $client->setAccessToken($_SESSION['user_token']);
    $client->setAccessTokenType(OAuth2\Client::ACCESS_TOKEN_BEARER);
    $params = array('schema' => 'openid', 'access_token' => $_SESSION['user_token']);
    $USER_INFO_ENDPOINT = $openid_issuer . 'userinfo';
    $response = $client->fetch($USER_INFO_ENDPOINT, $params);
    if ($response['code'] == 200) {
        return true;
    } else {
        return false;
    }
}

function check_session_user()
{
    include 'config.php';

    if (isset($_SESSION['user']) && isset($_SESSION['password'])) {
        $password = $_SESSION['password'];
        $username = $_SESSION['user'];
    
        $res = false;
        $db = new IMDB();
        $res = $db->get_items_from_table("user", array("username" => "'" . $db->escapeString($username) . "'"));
        $db->close();
        
        if (count($res) > 0) {
            $res = check_password($password, $res[0]["password"]);
        }
    
        return $res;
    } elseif (isset($_SESSION['user']) && isset($_SESSION['user_token'])) {
        return check_user_token();
    } else {
        return false;
    }
}

function check_admin_user()
{
    include 'config.php';
    
    if (!isset($_SESSION['user']) || !isset($_SESSION['password'])) {
        return false;
    } else {
        $password = $_SESSION['password'];
        $user = $_SESSION['user'];
    
        $res = false;
        $db = new IMDB();
        $fields = array();
        $fields["username"] = "'" . $db->escapeString($user) . "'";
        $fields["permissions"] = "1";
        $res = $db->get_items_from_table("user", $fields);
        $db->close();
        
        if (count($res) > 0) {
            $res = check_password($password, $res[0]["password"]);
        } else {
            $res = false;
        }
    
        return $res;
    }
}

function get_users()
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("user");
    $db->close();
    return $res;
}

function get_user($username)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("user", array("username" => "'" . $db->escapeString($username) . "'"));
    $db->close();
    if (count($res) > 0) {
        return $res[0];
    } else {
        return null;
    }
}

function get_user_groups($username)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("users_grp", array("username" => "'" . $db->escapeString($username) . "'"));
    $db->close();
    return $res;
}

function insert_user($username, $password, $groups, $permissions)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields[] = "'" . $db->escapeString($username) . "'";
    $fields[] = "'" . $db->escapeString(crypt_password($password)) . "'";
    $fields[] = "'" . strval($permissions) . "'";
    $res = $db->insert_item_into_table("user", $fields);

    if ($res != "") {
        return $res;
    }

    $all_ok = true;
    $error_msg = "";
    if (!is_null($groups)) {
        foreach ($groups as $group) {
            $fields = array();
            $fields[] = "'" . $db->escapeString($group) . "'";
            $fields[] = "'" . $db->escapeString($username) . "'";
            $res = $db->insert_item_into_table("users_grp", $fields);
            if ($res != "") {
                $all_ok = false;
                $error_msg = $res;
            }

        }
    }
    if (!$all_ok) {
        $res = "Error adding user groups: " . $error_msg;
    }

    $db->close();

    return $res;
}

function change_password($username, $password)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields["password"] = "'" . $db->escapeString(crypt_password($password)) . "'";
    $where = array("username" => "'" . $username . "'");
    $res = $db->edit_item_from_table("user", $fields, $where);
    $db->close();

    return $res;
}

function edit_user($username, $new_username, $password, $groups, $permissions)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields["username"] = "'" . $db->escapeString($new_username) . "'";
    $fields["permissions"] = strval($permissions);
    if (strlen(trim($password)) > 0) {
        $fields["password"] = "'" . $db->escapeString(crypt_password($password)) . "'";
    }
    $where = array("username" => "'" . $username . "'");
    $res = $db->edit_item_from_table("user", $fields, $where);

    if ($res != "") {
            $res = $db->lastErrorMsg() . $sql;
    }

    // borramos para volver a anyadirlos
    $grp_res = $db->delete_item_from_table("users_grp", array("username" => "'" . $username . "'"));
    if ($grp_res != "") {
        $res = "Error adding user groups: " . $grp_res;
    }

    $all_ok = true;
    $error_msg = "";
    if (!is_null($groups)) {
        foreach ($groups as $group) {
            $fields = array();
            $fields[] = "'" . $db->escapeString($group) . "'";
            $fields[] = "'" . $db->escapeString($username) . "'";
            $grp_res = $db->insert_item_into_table("users_grp", $fields);
            if ($grp_res != "") {
                $all_ok = false;
                $error_msg = $grp_res;
            }
        }
    }
    if (!$all_ok) {
        $res = "Error adding user groups: " . $error_msg;
    }

    $db->close();
            
    return $res;
}

function delete_user($username)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $res = $db->delete_item_from_table("user", array("username" => "'" . $username . "'"));
            
    // remove the groups
    $res = $db->delete_item_from_table("users_grp", array("username" => "'" . $username . "'"));
    
    // remove the credencials
    $res = $db->delete_item_from_table("credentials", array("imuser" => "'" . $username . "'"));
    
    // remove the radls
    $res = $db->delete_item_from_table("radls", array("imuser" => "'" . $username . "'"));

    $db->close();

    return $res;
}
?>
