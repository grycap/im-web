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

/**
 * For intenal use
 * 
 * @return string salt
 */
function unique_salt()
{
    return substr(sha1(mt_rand()), 0, 22);
}
 
/**
 * Generate a hash
 *
 * @param string $password password to be hashed
 *
 * @return string    hash of the password
 */
function crypt_password($password)
{
    // blowfish
    $algo = '$2a';
    // cost parameter
    $cost = '$10';
    
    return crypt(
        $password,
        $algo .
                $cost .
        '$' . unique_salt()
    );
}
 
/**
 * Compare a password against a hash
 *
 * @param string $password password to be compared
 * @param string $hash     hash to compare to
 *
 * @return bool   true if the password and hash are equivalent
 */
function check_password($password, $hash)
{
    $full_salt = substr($hash, 0, 29);
    $new_hash = crypt($password, $full_salt);
    return ($hash == $new_hash);
}
?>
