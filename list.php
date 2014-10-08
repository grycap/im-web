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
        $res = GetInfrastructureList($im_host,$im_port);
        
        if (is_string($res) and !strpos($res, "Error")) {
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
                    $inf_info = GetInfrastructureInfo($im_host,$im_port,$inf);
                    
                    if (is_string($inf_info)) {
						$vm_list = array("N/A");
						$cont_out = "ERROR!";
					} else {
                    	$vm_list = $inf_info['vm_list'];
                    	$cont_out = $inf_info['cont_out'];

                    	$vm_info = GetVMInfo($im_host,$im_port,$inf, $vm_list[count($vm_list)-1]);
                    	$radl_tokens = parseRADL($vm_info);
                    	if (is_string($vm_info) && strpos($vm_info, "Error")) {
							$status = "N/A";
						} else {
							$status = formatState($radl_tokens['state']);
						}
                    }
                ?>
            <tr>
                <td>
                    <?php echo $inf;?>
                </td>
                <td>
<?php
                    foreach ($vm_list as $vm) { 
                            echo "<a href='getvminfo.php?id=" . $inf . "&vmid=" . $vm . "' alt='VM Info' title='VM Info'>" . $vm . "<br>";
                    }
?>
                </td>
		<td>
<?php
                    if (strlen(trim($cont_out)) > 0) {
?>

<script type="text/javascript" charset="utf-8">
	$(function() {
		// this initializes the dialog (and uses some common options that I do)
		$("#dialog_<?php echo $inf;?>").dialog({autoOpen : false, modal : true, show : "blind", hide : "blind", height: 500, width: 'auto'});
		// next add the onclick handler
		$("#showdiv_<?php echo $inf;?>").click(function() {
			$("#dialog_<?php echo $inf;?>").dialog("open");
			return false;
		});
	});
</script>
                <a id="showdiv_<?php echo $inf;?>" href="#">Show</a>
                <div id="dialog_<?php echo $inf;?>" title="Cont">
			<?php echo "'" . str_replace("\n","<br>",$cont_out) . "'";?>
		</div>
<?php
                    } else {
                            echo "N/A";
                    }
?>
		</td>
                <td>
                    <?php echo $status;?>
                </td>
                <td>
		<?php
		if ($radl_tokens['state'] == "configured" || $radl_tokens['state'] == "failed")
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
