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
$msg = $_GET['msg']

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
    Error&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/error.png">
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

    <div class="texto_error">
    Error: <?php echo str_replace("\n", "<br>", $msg);?> <br>
    </div>
    <br>
    </div>

</div>
</div>

<?php require 'footer.php'?>

</body>
</html>
