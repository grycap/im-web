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
 
    include('config.php');   
    include('user.php');
    if (!check_session_user()) {
	header('Location: index.php?error=Invalid User');
    } else {
        if (isset($_GET['id'])) {
            $rowid = $_GET['id'];
        }

?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" >
<link rel="shortcut icon" href="images/favicon.ico">
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="stylesheet" href="css/style_login2.css"> 
    <link rel="stylesheet" href="css/style_intro2.css"> 
    <link rel="stylesheet" href="css/style_menu2.css">
    <link rel="stylesheet" href="css/style_menutab.css">

</head>





<body>
        <?php include('cred.php')?>


<div id="caja_total_blanca">


		<?php include('header.php')?>		
		<?php $menu="Credentials";include('menu.php');?>







<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Add / Edit Credential&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_creden_gran.png">
	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li><a href='credentials.php'><span>List</span></a></li>
   <li class='active'><a href='credform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	


    <div id="main">
    
    <?php
            $id = "";
            $type = "";
            $host = "";
            $username = "";
            $password = "";
            $proxy = "";
            
            if (isset($rowid)) {
                $cred = get_credential($rowid);
                $id = $cred['id'];
                $type = $cred['type'];
                $host = $cred['host'];
                $username = $cred['username'];
    ?>

         <br>
        <div class='h1'>:: Edit Credential ::</div>


         <form action="credinfo.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="op" value="edit"/>
            <input type="hidden" name="rowid" value="<?php echo $rowid;?>"/>

    <?php
            } else {
    ?>
        <br> 
        <div class='h1'>:: Add new Credential ::</div>


 <form action="credinfo.php" method="post">
            <input type="hidden" name="op" value="add"/>





    <?php
            }
    ?>
<br>


<div id="caja_logosVM">
<div class='h1'>Select type:</div>

<input type="radio" id="radio1" name="type" value="OpenNebula" <?php if ($type == "OpenNebula") echo 'checked="checked"'  ?> >
   <label for="radio1" ><img src="images/logosVM/OpenNebula.png"></label>&nbsp&nbsp&nbsp&nbsp

<input type="radio" id="radio2" name="type" value="EC2" <?php if ($type == "EC2") echo 'checked="checked"'  ?>>
   <label for="radio2"><img src="images/logosVM/ec2.png"></label>&nbsp&nbsp&nbsp&nbsp

<input type="radio" id="radio3" name="type" value="OpenStack" <?php if ($type == "OpenStack") echo 'checked="checked"'  ?>>
   <label for="radio3"><img src="images/logosVM/openstack.png"></label> &nbsp&nbsp&nbsp&nbsp

<input type="radio" id="radio4" name="type" value="OCCI" <?php if ($type == "OCCI") echo 'checked="checked"'  ?>>
   <label for="radio4"><img src="images/logosVM/OCCI.png"></label> 
   
<input type="radio" id="radio9" name="type" value="FogBow" <?php if ($type == "FogBow") echo 'checked="checked"'  ?>>
   <label for="radio9"><img src="images/logosVM/FogBow.png"></label> 

<br>

<input type="radio" id="radio10" name="type" value="Docker" <?php if ($type == "Docker") echo 'checked="checked"'  ?>>
   <label for="radio10"><img src="images/logosVM/Docker.png"></label> &nbsp&nbsp&nbsp&nbsp
   
<input type="radio" id="radio5" name="type" value="LibVirt" <?php if ($type == "LibVirt") echo 'checked="checked"'  ?>>
   <label for="radio5"><img src="images/logosVM/libvirt.png"></label> &nbsp&nbsp&nbsp&nbsp

<input type="radio" id="radio6" name="type" value="VMRC" <?php if ($type == "VMRC") echo 'checked="checked"'  ?>>
   <label for="radio6"><img src="images/logosVM/VMRC.png"></label> &nbsp&nbsp&nbsp&nbsp

<input type="radio" id="radio7" name="type" value="InfrastructureManager" <?php if ($type == "InfrastructureManager") echo 'checked="checked"'  ?>>
   <label for="radio7"><img src="images/logosVM/IM.png"></label> 
   
<input type="radio" id="radio8" name="type" value="GCE" <?php if ($type == "GCE") echo 'checked="checked"'  ?>>
   <label for="radio7"><img src="images/logosVM/GCE.png"></label> 

</div>


<div id="caja_form_credentials">
                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            ID:
                                        </th>
                                        <td>
                                            <input type="text" name="id" value="<?php echo $id;?>">
                                        </td>

						 <th align="left">
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUser:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            Host:&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td>
                                           <input type="text" name="host" value="<?php echo $host;?>">
                                         </td>
										<th align="left">
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPassword:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
                                        </td>
							</tr>
                               <tr>
                                        <th align="left">
                                            Proxy:&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td colspan="3">
                                           <input type="file" name="proxy">
                                         </td>
										<th align="left">
                                        </th>
                                        <td>
                                        </td>
							</tr>
				<tr>
					<td colspan="4" align="right">
						 <input type="submit" value="Save"/>
						<a href="credentials.php"><input type="button" name="Cancelar" value="Cancel"></a>
					</td>
				</tr>
						
                                                                                                                                    
                                           
                                        
                                
                        </tbody>
                </table>

        </form>
    </div>

 </div>
</div>
</div>
    <?php include('footer.php')?>
</body>
</html>
<?php
    }
?>
