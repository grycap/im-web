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

// mainly for internal use
function unique_salt() {
    return substr(sha1(mt_rand()),0,22);
}
 
// this will be used to generate a hash
function crypt_password($password) {
	// blowfish
	$algo = '$2a';
	// cost parameter
	$cost = '$10';
	
    return crypt($password,
                $algo .
                $cost .
                '$' . unique_salt());
}
 
// this will be used to compare a password against a hash
function check_password($password, $hash) {
    $full_salt = substr($hash, 0, 29);
    $new_hash = crypt($password, $full_salt);
    return ($hash == $new_hash);
}
?>
