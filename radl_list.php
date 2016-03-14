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

    if (isset($_GET['username'])) {
	$_SESSION['user'] = $_GET['username'];
	$_SESSION['password'] = $_GET['password'];
    }

    include('user.php');
    if (!check_session_user()) {
	header('Location: index.php?error=Invalid User');
    } else {
        $user = $_SESSION['user'];
            
        include_once('radl.php');
        $radls = get_radls($user);
        $radl_params = NULL;
        
        if (isset($_GET['parameters'])) {
        	$parameters = $_GET['parameters'];
        } else {
        	$parameters = NULL;
        }

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
		<?php $menu="RADL";include('menu.php');?>







<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > RADLs&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_radl_gran.png">

	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='radl_list.php'><span>List</span></a></li>
   <li><a href='radlform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	







    <div id="main">
 



    <?php
        if (count($radls) > 0)
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
                            { "bSortable": false },
                            { "bSortable": false },
                            { "bSortable": false }
                        ]
                } );
        } );

        function confirm_delete(url, name) {
            var r=confirm("Sure that you want to delete the RADL named: " + name + "?");
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
                Name
                </th>
                <th>
                Description
                </th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspLaunch</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspEdit</th>
                <th style="font-style:italic;">&nbsp&nbsp&nbsp&nbspDelete</th>
            </tr>
        </thead>
        <tbody>
    <?php
    
            foreach ($radls as $radl) {
				if ($parameters != NULL && $radl['rowid'] == $parameters) {
					// find the params in the RADL
					$radl_params = array();
					$pos = -1;
					while ($pos = strpos($radl['radl'], "@input.", $pos+1)) {
						$pos = $pos + 7;
						$pos_fin = strpos($radl['radl'], "@", $pos);
						$param_name = substr($radl['radl'], $pos, $pos_fin-$pos);
						if (array_search($param_name, $radl_params) === false) {
							$radl_params[] = $param_name;
						}
					}
				}
            ?>
            <tr>
                <td>
                    <?php echo $radl['name']?>
                </td>
                <td>
                    <?php echo $radl['description']?>
                </td>
                <td>
                <?php
                if (radl_user_can($radl['rowid'], $user, "x")) {
                ?>
                    <a href="radlinfo.php?op=launch&id=<?php echo $radl['rowid'];?>"><img src="images/lanzar.gif" border="0" alt="Launch" title="Launch"></a>
                <?php
                }
                ?>
                </td>
                <td>
                    <a href="radlform.php?id=<?php echo $radl['rowid'];?>"><img src="images/modificar.gif" border="0" alt="Edit" title="Edit"></a>
                </td>
                <td>
               <?php
                if (radl_user_can($radl['rowid'], $user, "w")) {
                ?>
                    <a onclick="javascript:confirm_delete('radlinfo.php?op=delete&id=<?php echo $radl['rowid'];?>', '<?php echo $radl['name']?>')" href="#"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
                <?php
                }
                ?>
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
        
        if ($radl_params != NULL) {
?>
<script type="text/javascript" charset="utf-8">
	$(function() {
		// this initializes the dialog
		 $( "#dialog" ).dialog({
			 autoOpen: true,
			 width: 350,
			 modal: true,
			 buttons: {
				 Launch: function() {
					 var url = 'radlinfo.php?op=launch&id=<?php echo $parameters;?>&parameters=1';
<?php
			foreach ($radl_params as $param_name) {
				echo "					 var " . $param_name . " = $( '#" . $param_name . "' );\n";
				echo "					 url = url + '&" . $param_name . "=' + String(" . $param_name . ".val());\n";
			} 
?>
					 $( this ).dialog( "close" );
					 
					 window.location.href = url;
				 },
				 Cancel: function() {
				 	$( this ).dialog( "close" );
				 }
			 },
			 });
	});
</script>
<div id="dialog" title="Parameters">
	<fieldset>
<?php
			foreach ($radl_params as $param_name) {
?>
	<label for="<?php echo $param_name;?>"><?php echo $param_name;?></label>
	<input type="text" name="<?php echo $param_name;?>" id="<?php echo $param_name;?>" style="border: 1px solid #000;"><br>
<?php
			}
?>
	</fieldset>
	</form>
</div>
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
