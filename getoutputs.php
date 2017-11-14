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
    
    include('user.php');
    if (!check_session_user()) {
	header('Location: index.php?error=Invalid User');
    } else {
        if (isset($_GET['id'])) {
        	include('im.php');
        	include('config.php');
        	$id = $_GET['id'];
        	$outputs = GetIM()->GetOutputs($id);
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
    
    <?php include('radl.php')?>


   
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
   <li><a href='list.php'><span>List</span></a></li>
   <li class='active'><a><span>Inf id: <?php echo $id;?></span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	


    <div id="main">
    

        <div class='h1'>:: TOSCA outputs::</div>
		<br>
        <div id='log'>

 <table class="list" style="width:100%;margin-left: 0px;">
	<tbody>
		<tr>
		  <td style="width:10px;background:#a27c3b;"><img src="images/icon_info.png"></td>
                <th style="width:90px;">Outputs</th>
                <td style="text-align:left;background:#e9d6b5;">
                	<table>
                	
<?php
					foreach ($outputs as $key => $value) {
?>
                	<tr>
                	<td>
                	<?php echo $key;?>
                	</td>
                	<td>
                	<?php
                	if (is_array($value)) {
                		$new_value = "";
                		foreach ($value as $k => $v) {
                			$new_value = $new_value . $k . " = " . $v . "<br>\n";
                		}
                		$value = $new_value;
                	}
                	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value)) {
                	
                		echo "<a href='", $value, "' target='_blank'>", $value, "</a>";
                	} else {
                		echo $value;
                	}
                	?>
                	</td>
                	</tr>
<?php
					}
?>
                	</table>
                </td>
		</tr>
	</tbody>
 </table>

		</div>


</div>

        </form>


 </div>

</body>
</html>
<?php
    }
?>




