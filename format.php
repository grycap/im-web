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

    function formatState($state) {
        // posibles estados: unknown, pending, running, off, failed
        // en el caso de la otra vers del IM tabien configured
 
	$res = $state;

	if ($state == "failed") $res = "<span style='color:red'>failed</span>";
	if ($state == "unknown") $res = "<span style='color:orange'>unknown</span>";
	if ($state == "running") $res = "<span style='color:green'>configuring</span>";
	if ($state == "configured") $res = "<span style='color:green'>" . $state . "</span>";

        return $res;
    }
    
    function formatCloud($tokens) {
        $res = "";
		if (strcmp($tokens["provider.type"],"EC2") == 0) {
			return "EC2";
		} else {
		        $res = $res . $tokens["provider.type"] . "<br>";
	        	$res = $res . $tokens["provider.host"] . ":" . $tokens["provider.port"] . "<br>";
		        return $res;
		}
    }
    
    function formatIPs($tokens) {
        $res = "";
        for ($i=0;in_array('net_interface.' . $i . '.ip', array_keys($tokens));$i++) {
            $res = $res . str_replace("'","",$tokens['net_interface.' . $i . '.ip']) . '<br>';
        }       
        
        return $res;
    }
    
    function formatRADL($tokens) {
        $res = "<table>";
        
        foreach ($tokens as $key => $value) {
            if ($key != "state" && strpos($key,"net_interface") === false && strpos($key,"provider.") === false) {
                    $res = $res . "<tr>\n";
                    $res = $res . "<td>" . $key . "</td>\n";
                    $res = $res . "<td><pre>" . $value . "</pre></td>\n";
                    $res = $res . "</tr>\n";
                }
        }
        
        $res = $res . "</table>";
        
        return $res;
    }
    
    function parseRADL($radl) {
        // TODO: esto habria que hacerlo mejor
        $ini = strpos($radl, "system");
        $ini = strpos($radl, "(", $ini)+1;
        $fin = strpos($radl, ")\n\n");

        $parts = explode(" and", substr($radl, $ini, $fin-$ini));
        
        $tot = "";
        $i = 0;
        $res = array();
        while ($i < count($parts))
        {
            $comp = $part = $parts[$i];
            if (strpos($part, "contains")) {
                while ($i < count($parts) and !strpos($part, ")")) {
                    $i++;
                    $part = $parts[$i];
                    $comp = $comp . "and" . $part;
                }
            }

            if (strpos($comp, "contains")) {
                $tok = explode("contains", $comp);
                $res[trim($tok[0])] = trim($tok[1]);
            } else {
                $tok = explode(" = ", $comp);
		if (count($tok) > 1) {
	                $res[trim($tok[0])] = str_replace("'","",trim($tok[1]));
		} else {
	                $res[trim($tok[0])] = "";
		}
            }

            $i++;
        }
        
        return $res;
    }
?>
