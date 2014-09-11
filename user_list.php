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

    if(!isset($_SESSION)) session_start();

    include_once('user.php');
    if (!check_session_user() || !check_admin_user()) {
	header('Location: index.php?error=Invalid User');
    } else {
        $users = get_users();

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


		<?php include('header.php')?>		
		<?php $menu="Users";include('menu.php');?>







<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Users&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_users_gran.png">
	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='user_list.php'><span>List</span></a></li>
   <li><a href='userform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	




    <div id="main">
    

    <?php
        if (count($users) > 0)
        {
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
			    { "bSortable": true },
                            { "bSortable": false },
                            { "bSortable": false }
                        ]
                } );
        } );
        
        function confirm_delete(url, username) {
            var r=confirm("Sure that you want to delete the User with username: " + username + "?");
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
                Username
                </th>
                <th>
                Groups
                </th>
                <th>
                Permissions
                </th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspEdit</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspDelete</th>
            </tr>
        </thead>
        <tbody>
    <?php
    
            foreach ($users as $user) {
    ?>
            <tr>
                <td>
                    <?php echo $user['username']?>
                </td>
                <td>
                    <?php
		    $groups = get_user_groups($user['username']);
		    foreach ($groups as $group) {
			echo $group['grpname'] . "<br>";
		    }
		    ?>
                </td>
                <td>
                    <?php
			if ($user['permissions']) {
				echo "Administrator";
			} else {
				echo "Standard";
			}
			?>
                </td>
                <td>
                    <a href="userform.php?id=<?php echo $user['username'];?>"><img src="images/modificar.gif" border="0" alt="Edit" title="Edit"></a>
                </td>
                <td>
                    <a onclick="javascript:confirm_delete('userinfo.php?op=delete&id=<?php echo $user['username'];?>', '<?php echo $user['username']?>')" href="#"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
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
    <?php include('footer.php')?>
</div>
</body>
</html>
<?php
    }
?>
