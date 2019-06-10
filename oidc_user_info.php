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
if (!isset($_SESSION)) {
    session_start();
}

require_once 'user.php';
require_once 'jwt.php';

if (!isset($_SESSION['user_token'])) {
	invalid_user_error("No OIDC User");
} else {

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link rel="shortcut icon" href="images/favicon.ico">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/datatable.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel="stylesheet" href="css/style_login2.css"> 
    <link rel="stylesheet" href="css/style_intro2.css"> 
    <link rel="stylesheet" href="css/style_menu2.css">
    <link rel="stylesheet" href="css/style_menutab.css">
    <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
</head>
<body>

<div id="caja_total_blanca">

    <?php require 'header.php'?>        
    <?php require 'menu.php'?>

<div id="caja_titulo">
    <div id="texto_titulo">
    OIDC User Info&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_users_gran.png">
    </div>
</div>


<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li class='active'><span></span></li>
   <li><span></span></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    
    <div id="main">
 
    <br><br>
	
    <?php    
    	$decoded = JWT::decode($_SESSION['user_token']);
    ?>
		<div id="caja_form_users">
		  <form>
                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            Sub:
                                        </th>
                                        <td>
                                            <input type="text" style="width:512px;" disabled value="<?php echo $decoded->sub;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Iss:
                                        </th>
                                        <td>
                                            <input type="text" style="width:512px;" disabled value="<?php echo $decoded->iss;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Access Token:
                                        </th>
                                        <td>
                                        <textarea disabled type="RECIPE"><?php echo $_SESSION['user_token'];?></textarea>
                                        </td>
                               </tr>
                        </tbody>
                </table>
              </form>
           </div>
    </div>

</div>
</div>

<?php require 'footer.php'?>

</body>
</html>
    <?php
}
?>