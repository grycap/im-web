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

/**
 * Decrypt sensitive data if crypted
 *
 * @param  array	row with the credentials data
 * @return array    row with the credentials data decrypted
 */
function decrypt_credentials($row)
{
	include 'config.php';

	$fields = array("username", "password", "private_key", "certificate");
	foreach ($fields as $field) {
		if ((substr( $row[$field], 0, strlen($cred_cryp_start)) ) === $cred_cryp_start) {
            try {
			    $row[$field] = decrypt(substr($row[$field], strlen($cred_cryp_start)), $cred_crypt_key);
            } catch (Exception $e) {
                $row[$field] = NULL;
            }
		}
	}
	
	return $row;
}

/**
 * Get the credentials stored for the user specified 
 * 
 * @param  string $user IM user ID
 * @return array    db rows with the user credentials
 */
function get_credentials($user)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("credentials", array("imuser" => "'" . $db->escapeString($user) . "'"), "ord");
    $db->close();
    if (!is_null($res)) {
    	$newres = array();
    	foreach ($res as $row) {
    		$newres[] = decrypt_credentials($row);
    	}
    	return $newres;
    } else {
    	return $res;
    }
}

/**
 * Get the credential with the specified id
 *
 * @param  string $id Credential ID
 * @return array    db row with the credential specified
 */
function get_credential($id)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->get_items_from_table("credentials", array("rowid" => $id));
    $db->close();
    if (count($res) > 0) {
    	return decrypt_credentials($res[0]);
    } else {
        return null;
    }
}

function insert_credential($imuser, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant, $subscription_id, $auth_version, $domain, $service_region, $base_url)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields[] = "'" . $db->escapeString($id) . "'";
    $fields[] = "'" . $imuser . "'";
    $fields[] = "'" . $type . "'";
    $fields[] = "'" . $db->escapeString($host) . "'";
    if (strlen(trim($username)) > 0) {
    	$fields[] = "'" . $cred_cryp_start . encrypt($username, $cred_crypt_key) . "'";
    } else {
    	$fields[] = "''";
    }
    if (strlen(trim($password)) > 0) {
    	$fields[] = "'" . $cred_cryp_start . encrypt($password, $cred_crypt_key) . "'";
    } else {
    	$fields[] = "''";
    }
    $fields[] = 1;

    $res = $db->direct_query("select max(ord) as max_ord from credentials where imuser = '" . $imuser . "'");
    $fields[] = $res[0]['max_ord']+1;
    
    $fields[] = "'" . $db->escapeString($proxy) . "'";
    $fields[] = "'" . $db->escapeString($token_type) . "'";
    $fields[] = "'" . $db->escapeString($project) . "'";
    $fields[] = "'" . $db->escapeString($public_key) . "'";
    if (strlen(trim($private_key)) > 0) {
    	$fields[] = "'" . $cred_cryp_start . encrypt($private_key, $cred_crypt_key) . "'";
    } else {
    	$fields[] = "''";
    }
    if (strlen(trim($certificate)) > 0) {
    	$fields[] = "'" . $cred_cryp_start . encrypt($certificate, $cred_crypt_key) . "'";
    } else {
    	$fields[] = "''";
    }
    $fields[] = "'" . $db->escapeString($tenant) . "'";
    $fields[] = "'" . $db->escapeString($subscription_id) . "'";
    $fields[] = "'" . $db->escapeString($auth_version) . "'";
    $fields[] = "'" . $db->escapeString($domain) . "'";
    $fields[] = "'" . $db->escapeString($service_region) . "'";
    $fields[] = "'" . $db->escapeString($base_url) . "'";

    $res = $db->insert_item_into_table("credentials", $fields);
    $db->close();

    return $res;
}

function edit_credential($rowid, $id, $type, $host, $username, $password, $token_type, $project, $proxy, $public_key, $private_key, $certificate, $tenant, $subscription_id, $auth_version, $domain, $service_region, $base_url)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array();
    $fields["id"] = "'" . $db->escapeString($id) . "'";
    $fields["type"] = "'" . $type . "'";
    $fields["host"] = "'" . $db->escapeString($host) . "'";
    $fields["username"] = "'" . $cred_cryp_start . encrypt($username, $cred_crypt_key) . "'";
    $fields["token_type"] = "'" . $db->escapeString($token_type) . "'";
    $fields["project"] = "'" . $db->escapeString($project) . "'";
    if (strlen(trim($password)) > 0) {
    	$fields["password"] = "'" . $cred_cryp_start . encrypt($password, $cred_crypt_key) . "'";
    }
    if (strlen(trim($proxy)) > 0) {
        $fields["proxy"] = "'" . $db->escapeString($proxy) . "'";
    }
    if (strlen(trim($public_key)) > 0) {
        $fields["public_key"] = "'" . $db->escapeString($public_key) . "'";
    }
    if (strlen(trim($private_key)) > 0) {
    	$fields["private_key"] = "'" . $cred_cryp_start . encrypt($private_key, $cred_crypt_key) . "'";
    }
    if (strlen(trim($certificate)) > 0) {
    	$fields["certificate"] = "'" . $cred_cryp_start . encrypt($certificate, $cred_crypt_key) . "'";
    }
    if (strlen(trim($tenant)) > 0) {
        $fields["tenant"] = "'" . $db->escapeString($tenant) . "'";
    }
    if (strlen(trim($subscription_id)) > 0) {
        $fields["subscription_id"] = "'" . $db->escapeString($subscription_id) . "'";
    }
    if (strlen(trim($auth_version)) > 0) {
        $fields["auth_version"] = "'" . $db->escapeString($auth_version) . "'";
    }
    if (strlen(trim($domain)) > 0) {
        $fields["domain"] = "'" . $db->escapeString($domain) . "'";
    }
    if (strlen(trim($service_region)) > 0) {
        $fields["service_region"] = "'" . $db->escapeString($service_region) . "'";
    }
    if (strlen(trim($base_url)) > 0) {
        $fields["base_url"] = "'" . $db->escapeString($base_url) . "'";
    }

    $where = array("rowid" => $rowid);
    $res = $db->edit_item_from_table("credentials", $fields, $where);
    $db->close();

    return $res;
}

function delete_credential($id)
{
    include 'config.php';

    $db = new IMDB();
    $res = $db->delete_item_from_table("credentials", array("rowid" => $id));
    $db->close();
    return $res;
}

function enable_credential($id, $enable)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array("enabled" => $enable);
    $where = array("rowid" => $id);
    $res = $db->edit_item_from_table("credentials", $fields, $where);
    $db->close();

    return $res;
}

function change_order($id, $user, $order, $new_order)
{
    include 'config.php';

    $res = "";
    $db = new IMDB();
    $fields = array("ord" => $order);
    $where = array("ord" => $new_order, "imuser" => "'" . $user . "'");
    $res = $db->edit_item_from_table("credentials", $fields, $where);

    if (strlen($res) == 0) {
        $fields = array("ord" => $new_order);
        $where = array("rowid" => $id);
        $res = $db->edit_item_from_table("credentials", $fields, $where);
    }

    $db->close();

    return $res;
}
?>
