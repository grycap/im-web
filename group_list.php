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
    include_once 'group.php';
    $groups = get_groups();

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

    <?php include 'header.php'?>        
    <?php $menu="Groups";include 'menu.php';?>

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Groups&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_groups_gran.png">

    </div>
</div>

<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='group_list.php'><span>List</span></a></li>
   <li><a href='groupform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    

    
    <div id="main">

    <?php
    if (count($groups) > 0) {
        ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                $('#example').dataTable( {
                        //"oLanguage": {
                        //        "sUrl": "dataTables.spanish.txt"
                        //},
                        "aoColumns": [
                            { "bSortable": true },
                            { "bSortable": true },
                            { "bSortable": false },
                            { "bSortable": false }
                        ]
                } );
        } );
        
        function confirm_delete(url, name) {
            var r=confirm("Sure that you want to delete the Group with name: " + name + "?");
            if (r==true) {
                window.location.href = url;
            }
        }
    </script>

    <table>
    <tr>
    <td>
    <table class="list" id="example">
        <thead>
            <tr>
                <th>
                Group
                </th>
                <th>
                Description
                </th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspEdit</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspDelete</th>

            </tr>
        </thead>
        <tbody>
        <?php
    
        foreach ($groups as $group) {
            ?>
            <tr>
                <td>
                <?php echo $group['name']?>
                </td>
                <td>
                <?php echo $group['description']?>
                </td>
                <td>
                    <a href="groupform.php?id=<?php echo $group['name'];?>"><img src="images/modificar.gif" border="0" alt="Edit" title="Edit"></a>
                </td>
                <td>
                    <a onclick="javascript:confirm_delete('groupinfo.php?op=delete&id=<?php echo $group['name'];?>', '<?php echo $group['name']?>')" href="#"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
                </td>
            </tr>
                <?php
        }
    
        ?>
        </tbody>
    </table>
    </td>
    </tr>
    </table>
        <?php
    }
    ?>
    <br>
    </div>
</div>
    <?php include 'footer.php'?>
</div>
</body>
</html>
    <?php
}
?>
