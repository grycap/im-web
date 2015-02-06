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
        $user = $_SESSION['user'];
            
        include_once('cred.php');
        $creds = get_credentials($user);

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
		<?php $menu="Credentials";include('menu.php');?>		







<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Credentials&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_creden_gran.png">
	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='credentials.php'><span>List</span></a></li>
   <li><a href='credform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	
    <div id="main">

 
    <?php
        if (count($creds) > 0)
        {
    ?>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
                $('#example').dataTable( {
                        //"oLanguage": {
                        //        "sUrl": "dataTables.spanish.txt"
                        //},
			"aaSorting" : [],
                        "aoColumns": [
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false }
                        ]
                } );
        } );
        
        function confirm_delete(url, id) {
            var r=confirm("Sure that you want to delete the Credential with id: " + id + "?");
            if (r==true) {
                window.location.href = url;
            }
        }
    </script>
    
    <table class="list" id="example">
        <thead>
            <tr>
                <th>
                ID
                </th>
                <th>
                Type
                </th>
                <th>
                Host
                </th>
                </th>
                <th style="font-style:italic;">Edit</th>
                <th style="font-style:italic;">Delete</th>
                <th style="padding:0px; font-style:italic;">Enabled</th>
                <th style="font-style:italic;">Order</th>
            </tr>
        </thead>
        <tbody>
    <?php
    
            $cont = 0;
            foreach ($creds as $cred) {
    ?>
            <tr>
                <td>
                    <?php echo $cred['id']?>
                </td>
                <td>
                    <?php if (strcmp($cred['type'],"OpenNebula") == 0){ ?>
			<img src="images/logosVM/OpenNebulaRow.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"EC2") == 0){ ?>
			<img src="images/logosVM/ec2Row.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"OpenStack") == 0){ ?>
			<img src="images/logosVM/openstackRow.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"OCCI") == 0){ ?>
			<img src="images/logosVM/OCCIRow.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"LibVirt") == 0){ ?>
			<img src="images/logosVM/libvirtRow.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"VMRC") == 0){ ?>
			<img src="images/logosVM/VMRCRow.png">
			<?php } ?>

			<?php if (strcmp($cred['type'],"InfrastructureManager") == 0){ ?>
			<img src="images/logosVM/IMRow.png">
			<?php } ?>
			
			<?php if (strcmp($cred['type'],"GCE") == 0){ ?>
			<img src="images/logosVM/GCERow.png">
			<?php } ?>
			
			<?php if (strcmp($cred['type'],"FogBow") == 0){ ?>
			<img src="images/logosVM/FogBowRow.png">
			<?php } ?>
			
			<?php if (strcmp($cred['type'],"Docker") == 0){ ?>
			<img src="images/logosVM/DockerRow.png">
			<?php } ?>
			
			<?php if (strcmp($cred['type'],"Azure") == 0){ ?>
			<img src="images/logosVM/AzureRow.png">
			<?php } ?>



                </td>
                <td>
                    <?php echo $cred['host']?>
                </td>
                <td>
                    <a href="credform.php?id=<?php echo $cred['rowid'];?>"><img src="images/modificar.gif" border="0" alt="Edit" title="Edit"></a>
                </td>
                <td>
                    <a onclick="javascript:confirm_delete('credinfo.php?op=delete&id=<?php echo $cred['rowid'];?>', '<?php echo $cred['id']?>')" href="#"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
                </td>
		<td>
                    <?php
			if ($cred['enabled']) {
				echo "<a href='credinfo.php?op=disable&id=" . $cred['rowid'] . "'><img src='images/enable.gif' border='0' alt='Enabled click to Disable' title='Enabled click to Disable'></a>";
			} else {
				echo "<a href='credinfo.php?op=enable&id=" . $cred['rowid'] . "'><img src='images/disable.gif' border='0' alt='Disabled click to Enable' title='Disabled click to Enable'></a>";
			}
			?>
		</td>
                <td>
                    <?php
			if ($cont > 0) {
                    ?>
                    <a href="credinfo.php?op=order&id=<?php echo $cred['rowid'];?>&order=<?php echo $cred['ord'];?>&new_order=<?php echo $cred['ord']-1;?>"><img src="images/up.gif" border="0" alt="Up" title="Up"></a>
                    <?php
			}
			if ($cont<count($creds)-1) {
                    ?>
                    <a href="credinfo.php?op=order&id=<?php echo $cred['rowid'];?>&order=<?php echo $cred['ord'];?>&new_order=<?php echo $cred['ord']+1;?>"><img src="images/down.gif" border="0" alt="Down" title="Down"></a>
                    <?php
			}
                    ?>
                </td>
            </tr>
            <?php
                    $cont++;
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
</div>

<?php include('footer.php')?>

</body>
</html>
<?php
    }
?>
