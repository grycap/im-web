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
    if (isset($_POST['password']))
        $_SESSION['password'] = $_POST['password'];
    if (isset($_POST['username']))
        $_SESSION['user'] = $_POST['username'];
    
    include_once('format.php');
    include_once('user.php');
    if (!check_session_user()) {
        header('Location: index.php?error=Invalid User');
    } else {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link rel="shortcut icon" href="images/favicon.ico">
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
    <link href="css/jquery.dynatable.css" rel="stylesheet" type="text/css" media="all"/>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/style_login2.css"> 
    <link rel="stylesheet" href="css/style_intro2.css"> 
    <link rel="stylesheet" href="css/style_menu2.css">
    <link rel="stylesheet" href="css/style_menutab.css">
    <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.dynatable.js"></script>
</head>
<body>

<div id="caja_total_blanca">


		<?php include('header.php')?>		
		<?php $menu="Infrastructures";include('menu.php');?>		
              <?php include('footer.php')?>






<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Infrastructures&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_infra_gran.png">
	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='list.php'><span>List</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	


  <div id="main">

    <script type="text/javascript" charset="utf-8">
        function confirm_delete(url, id) {
            var r=confirm("Sure that you want to delete the Infrastructure with id: " + id + "?");
            if (r==true) {
                window.location.href = url;
            }
        }

        $(document).ready(function() {
        	$('#example').dynatable({
        		  dataset: {
        			    ajax: true,
        			    ajaxUrl: 'list_json.php',
        			    ajaxOnLoad: true,
        			    records: [],
        				queryRecordCount: 0,
        				totalRecordCount: 0
        			  }
        	});
        } );
    </script>
    
<p align="left">
Refresh <a href="#" onclick="javascript:location.reload();"><img src="images/reload.png" style="vertical-align:middle" border="0"></a>
</p>
    <table>
    <tr>
    <td>
    <table class="list" id="example">
        <thead>
            <tr>
                <th>ID</th>
                <th data-dynatable-column="vms">VM IDs</th>
				<?php
				if ($im_use_rest)
				{
				?>
                <th>Outputs</th>
				<?php
				}
				?>
                <th>Cont. Message</th>
                <th>Status</th>
                <th style="font-style:italic;">Reconfigure</th>
                <th style="font-style:italic;">Delete</th>
                <th style="font-style:italic;">Add Resources</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    </td>
    </tr>
    </table>

    <br>
    </div>
</div>
 

</div>
</body>
</html>
<?php
    }
?>