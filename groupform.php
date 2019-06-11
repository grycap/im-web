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
if (!check_session_user() || !check_admin_user()) {
	invalid_user_error();
} else {
    if (isset($_GET['id'])) {
        $name = $_GET['id'];
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
    <?php include_once 'group.php'?>

<div id="caja_total_blanca">

    <?php include 'header.php'?>        
    <?php $menu="Groups";include 'menu.php';?>

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Add / Edit Group&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_groups_gran.png">

    </div>
</div>

<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li><a href='group_list.php'><span>List</span></a></li>
   <li class='active'><a href='groupform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">

    <div id="main">
    
    <?php
        $desc = "";
            
    if (isset($name)) {
        $group = get_group($name);
        $desc = $group['description'];
        ?>
        <br> 
        <div class='h1'>:: Edit Group ::</div>

     <div id="caja_form_groups">

        <form action="groupinfo.php" method="post">
            <input type="hidden" name="op" value="edit"/>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($name);?>"/>
            <?php
    } else {
        ?>
        <br> 
        <div class='h1'>:: Add new Group ::</div>
    <br>
    <div id="caja_form_groups">

        <form action="groupinfo.php" method="post">
            <input type="hidden" name="op" value="add"/>
        <?php
    }
    ?>
                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            Name:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input type="text" name="name" value="<?php echo htmlspecialchars($name);?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Description:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input maxlength="256" size="200" type="descr" name="description" value="<?php echo htmlspecialchars($desc);?>">
                                        </td>
                               </tr>
                                <tr>
                                </tr>
                                <tr>
                    <th></th>
                    <td align="right"><input type="submit" value="Save"/> <a href="group_list.php"><input type="button" name="Cancelar" value="Cancel"></a></td>
                                </tr>

                        </tbody>
                </table>
                </form>
</div>
 </div>
</div>
</div>
    <?php include 'footer.php'?>
</body>
</html>
    <?php
}
?>
