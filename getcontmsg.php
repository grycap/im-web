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
if (!check_session_user()) {
	invalid_user_error();
} else {
    if (isset($_GET['id'])) {
        include_once 'im.php';
        include 'config.php';
        $id = $_GET['id'];
        if (isset($_GET['vmid'])) {
             $vmid = $_GET['vmid'];
             $cont_msg = GetIM()->GetVMContMsg($id, $vmid);
        } else {
            $cont_msg = GetIM()->GetInfrastructureContMsg($id);
        }
    }

    ?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link rel="shortcut icon" href="images/favicon.ico">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel="stylesheet" href="css/style_login2.css"> 
    <link rel="stylesheet" href="css/style_intro2.css"> 
    <link rel="stylesheet" href="css/style_menu2.css">
    <link rel="stylesheet" href="css/style_menutab.css">

</head>
<body>

    <?php include_once 'radl.php'?>
   
<div id="caja_total_blanca">

    <?php include 'header.php'?>        
    <?php $menu="Infrastructures";include 'menu.php';?>
    <?php include 'footer.php'?>    

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Infrastructures&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_infra_gran.png">
    </div>
</div>

<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li><a href='list.php'><span>List</span></a></li>
   <li class='active'><a><span>Inf id: <?php echo htmlspecialchars($id);?></span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    

    <div id="main">
    
        <div class='h1'>:: Contextualization Log ::</div>
        <br>
        <div id='log'>
    <?php
    echo str_replace("\n", "<br>", htmlspecialchars($cont_msg));
    ?>
        </div>

</div>
        </form>

 </div>

</body>
</html>
    <?php
}
?>
