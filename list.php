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
    include('user.php');
    if (!check_session_user()) {
        header('Location: index.php?error=Invalid User');
    } else {
        include('im.php');
        include('config.php');
        $res = GetInfrastructureList($im_host,$im_port,$im_method);
        
        if (is_string($res) and strpos($res, "Error") !== false) {
            header('Location: error.php?msg=' . $res);
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
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/style_login2.css"> 
    <link rel="stylesheet" href="css/style_intro2.css"> 
    <link rel="stylesheet" href="css/style_menu2.css">
    <link rel="stylesheet" href="css/style_menutab.css">
    <script type="text/javascript" language="javascript" src="js/jquery.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery-ui.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
    
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
    
    <?php
        if (count($res) > 0)
        {
    ?>
    <script type="text/javascript" charset="utf-8">
        function confirm_delete(url, id) {
            var r=confirm("Sure that you want to delete the Infrastructure with id: " + id + "?");
            if (r==true) {
                window.location.href = url;
            }
        }

        $(document).ready(function() {
                $('#example').dataTable( {
                        //"oLanguage": {
                        //        "sUrl": "dataTables.spanish.txt"
                        //},
                        "aoColumns": [
                            { "bSortable": true },
                            { "bSortable": false },
                            { "bSortable": false },
                            	<?php
                                    if ($im_use_rest)
                                    {
                                ?>
                                { "bSortable": false },
                                <?php
                                    }
                                ?>
                            { "bSortable": true },
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false }
                        ]
                } );
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
                <th>
                ID
                </th>
                <th>
                VM IDs
                </th>
				<?php
				if ($im_use_rest)
				{
				?>
                <th width="100px">
                Outputs
                </th>
				<?php
				}
				?>
                <th width="100px">
                Cont. Message
                </th>
                <th>
                Status
                </th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspReconfigure</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspDelete</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspAdd Resources</th>

            </tr>
        </thead>
        <tbody>
    <?php
        
            foreach ($res as $inf) {
                    $vm_list = GetInfrastructureInfo($im_host,$im_port,$im_method,$inf);

                    if (is_string($vm_list) || count($vm_list) == 0) {
						$vm_list = array("N/A");
					}

					$state = GetInfrastructureState($im_host,$im_port,$im_method,$inf);
					$status = "N/A";
                   	if (!(is_string($state) && strpos($state, "Error") !== false)) {
						$status = formatState($state);
					}
                ?>
            <tr>
                <td>
                    <?php echo $inf;?>
                </td>
                <td>
<?php
                    foreach ($vm_list as $vm) {
                    	if ($vm !== "N/A") {
                            echo "<a href='getvminfo.php?id=" . $inf . "&vmid=" . $vm . "' alt='VM Info' title='VM Info'>" . $vm . "<br>";
                    	} else {
                    		echo "N/A";
                    	}
                    }
?>
                </td>
		<?php
		if ($im_use_rest)
		{
		?>
		<td>
				<a href="getoutputs.php?id=<?php echo $inf;?>">Show</a>
		</td>
		<?php
		}
		?>
		<td>
				<a href="getcontmsg.php?id=<?php echo $inf;?>">Show</a>
		</td>
                <td>
                    <?php echo $status;?>
                </td>
                <td>
		<?php
		if ($state == "configured" || $state == "unconfigured")
		{
		?>
                    <a href="operate.php?op=reconfigure&infid=<?php echo $inf;?>"><img src="images/reload.png" border="0" alt="Reconfigure" title="Reconfigure"></a>
		<?php
		} else {
			echo "N/A";
		}
		?>
                </td>
                <td>
                    <a onclick="javascript:confirm_delete('operate.php?op=destroy&id=<?php echo $inf;?>', '<?php echo $inf;?>')" href="#"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
                </td>
                <td>
                    <a href="form.php?id=<?php echo $inf;?>"><img src="images/add_resources_icon.png" border="0" alt="Add Resources" title="Add Resources"></a>
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
        } else {
              ?>
            

        <div class='h1'>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:: No infrastructures available ::</div>

<?php
        }
?>
    <br>
    </div>
</div>




   

</div>
</body>
</html>
<?php
        }
    }
?>
