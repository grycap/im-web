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
    include('cred.php');
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

    <script type="text/javascript" charset="utf-8">
        function showForm(form_id) {
            // Function to show/hide and enable/disable the correct fields
            // of the credentials form for the type selected
            var x = document.getElementsByClassName("caja_form_credentials");
            var i;
            for (i = 0; i < x.length; i++) {
            	x[i].style.display = "none";

                var inputs = x[i].getElementsByTagName("input");
                var j;
                for (j = 0; j < inputs.length; j++) {
                	inputs[j].disabled = true;
                }
            }
            var elem = document.getElementById(form_id);
            elem.style.display="block";
            var inputs = elem.getElementsByTagName("input");
            for (i = 0; i < inputs.length; i++) {
            	inputs[i].disabled = false;
            }
        }

        function download(id, filename) {
              var dataToDownload = document.getElementById(id).value;
        	  var link = document.createElement("a");
        	  link.download = filename;
        	  link.href = 'data:Application/octet-stream,' + encodeURIComponent(dataToDownload);
        	  link.click();
        	}
    </script>
</head>


    <?php
            $id = "";
            $type = "";
            $host = "";
            $username = "";
            $password = "";
            $proxy = "";
            
            if (isset($rowid)) {
            	$cred = get_credential($rowid);
            	if ($cred['imuser'] == $_SESSION['user']) {
	                $id = $cred['id'];
	                $type = $cred['type'];
	                $host = $cred['host'];
	                $username = $cred['username'];
	                $project = $cred['project'];
	                $token_type = $cred['token_type'];
	                
	                $proxy = $cred['proxy'];
	                $public_key = $cred['public_key'];
	                $private_key = $cred['private_key'];
	                $certificate = $cred['certificate'];
                }
            }
    ?>



<body onload="showForm('<?php echo $type;?>')">

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
            if (isset($rowid)) {
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
 
 <form action="credinfo.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="op" value="add"/>

    <?php
            }
    ?>

<div id="caja_logosVM">
<div class='h1'>Select type:</div>

<input onchange="showForm('EC2')" type="radio" id="radio2" name="type" value="EC2" <?php if ($type == "EC2") echo 'checked="checked"'  ?>>
   <label for="radio2"><img class="logoVM" src="images/logosVM/ec2.png"></label>
   
<input onchange="showForm('GCE')" type="radio" id="radio8" name="type" value="GCE" <?php if ($type == "GCE") echo 'checked="checked"'  ?>>
   <label for="radio8"><img class="logoVM" src="images/logosVM/GCE.png"></label>
   
<input onchange="showForm('Azure')" type="radio" id="radio11" name="type" value="Azure" <?php if ($type == "Azure") echo 'checked="checked"'  ?>>
   <label for="radio11"><img class="logoVM" src="images/logosVM/Azure.png"></label>
   
<br>

<input onchange="showForm('OpenNebula')" type="radio" id="radio1" name="type" value="OpenNebula" <?php if ($type == "OpenNebula") echo 'checked="checked"'  ?> >
   <label for="radio1" ><img class="logoVM" src="images/logosVM/OpenNebula.png"></label>

<input onchange="showForm('OpenStack')" type="radio" id="radio3" name="type" value="OpenStack" <?php if ($type == "OpenStack") echo 'checked="checked"'  ?>>
   <label for="radio3"><img class="logoVM" src="images/logosVM/openstack.png"></label>

<input onchange="showForm('OCCI')" type="radio" id="radio4" name="type" value="OCCI" <?php if ($type == "OCCI") echo 'checked="checked"'  ?>>
   <label for="radio4"><img class="logoVM" src="images/logosVM/OCCI.png"></label>
   
<input onchange="showForm('FogBow')" type="radio" id="radio9" name="type" value="FogBow" <?php if ($type == "FogBow") echo 'checked="checked"'  ?>>
   <label for="radio9"><img class="logoVM" src="images/logosVM/FogBow.png"></label> 
   


<br>

<input onchange="showForm('Docker')" type="radio" id="radio10" name="type" value="Docker" <?php if ($type == "Docker") echo 'checked="checked"'  ?>>
   <label for="radio10"><img class="logoVM" src="images/logosVM/Docker.png"></label>

<!--
<input onchange="showForm('LibVirt')" type="radio" id="radio5" name="type" value="LibVirt" <?php if ($type == "LibVirt") echo 'checked="checked"'  ?>>
   <label for="radio5"><img class="logoVM" src="images/logosVM/libvirt.png"></label>
-->
<input onchange="showForm('VMRC')" type="radio" id="radio6" name="type" value="VMRC" <?php if ($type == "VMRC") echo 'checked="checked"'  ?>>
   <label for="radio6"><img class="logoVM" src="images/logosVM/VMRC.png"></label>

<input onchange="showForm('InfrastructureManager')" type="radio" id="radio7" name="type" value="InfrastructureManager" <?php if ($type == "InfrastructureManager") echo 'checked="checked"'  ?>>
   <label for="radio7"><img class="logoVM" src="images/logosVM/IM.png"></label> 
   


</div>

<div id="InfrastructureManager" class="caja_form_credentials">
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
                                            User:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            
                                        </th>
                                        <td>
                                           
                                         </td>
										<th align="left">
                                            Password:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
                                        </td>
				<tr>
					<td colspan="4" align="right">
						 <input type="submit" value="Save"/>
						<a href="credentials.php"><input type="button" name="Cancelar" value="Cancel"></a>
					</td>
				</tr>                  
                                
                        </tbody>
                </table>
    </div>
    
<div id="VMRC" class="caja_form_credentials">
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
                                            User:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            
                                        </th>
                                        <td>
                                           
                                         </td>
										<th align="left">
                                            Password:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
                                        </td>
				<tr>
					<td colspan="4" align="right">
						 <input type="submit" value="Save"/>
						<a href="credentials.php"><input type="button" name="Cancelar" value="Cancel"></a>
					</td>
				</tr>                  
                                
                        </tbody>
                </table>
    </div>

<div id="Docker" class="caja_form_credentials">
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
                                            Host:
                                        </th>
                                        <td>
                                            <input type="text" name="host" value="<?php echo $host;?>">
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
    </div>

<div id="OpenNebula" class="caja_form_credentials">
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
                                            User:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            Host:
                                        </th>
                                        <td>
                                           <input type="text" name="host" value="<?php echo $host;?>">
                                         </td>
										<th align="left">
                                            Password:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
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
    </div>

<div id="OpenStack" class="caja_form_credentials">
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
                                            Access Key:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>

                               </tr>
                               <tr>
                                        <th align="left">
                                            Host:
                                        </th>
                                        <td>
                                           <input type="text" name="host" value="<?php echo $host;?>">
                                         </td>
										<th align="left">
                                            Secret key:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
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
    </div>
    
<div id="EC2" class="caja_form_credentials">
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
                                            Access Key:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>
                               </tr>
                               <tr>
                                        <th align="left">
                                        </th>
                                        <td>
                                         </td>
										<th align="left">
                                            Secret Key:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
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
    </div>
    
    
<div id="Azure" class="caja_form_credentials">
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
                                            Subscription ID:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            Private Key:
                                        </th>
                                        <td colspan="3">
                                           <input type="file" name="private_key">
                                           <?php
                                           if (strlen(trim($private_key)) > 0) {
                                           	echo "<textarea id='private_key_value' name='private_key_value' style='display:none;'>" . $private_key . "</textarea>";
                                           	echo "<a class='download' href='javascript:download(\"private_key_value\", \"key.pem\");'>Download</a>";
                                           }
                                           ?>
                                         </td>
										<th align="left">
                                        </th>
                                        <td>
                                        </td>
							</tr>
                               <tr>
                                        <th align="left">
                                            Public Key:
                                        </th>
                                        <td colspan="3">
                                           <input type="file" name="public_key">
                                           <?php
                                           if (strlen(trim($public_key)) > 0) {
                                           	echo "<textarea id='public_key_value' name='public_key_value' style='display:none;'>" . $public_key . "</textarea>";
                                           	echo "<a class='download' href='javascript:download(\"public_key_value\", \"cert.pem\");'>Download</a>";
                                           }
                                           ?>
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

<div id="OCCI" class="caja_form_credentials">
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
                                            User:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>


                               </tr>
                               <tr>
                                        <th align="left">
                                            
                                        </th>
                                        <td>
                                           
                                         </td>
										<th align="left">
                                            Host:
                                        </th>
                                        <td>
                                            <input type="text" name="host" value="<?php echo $host;?>">
                                        </td>
								<tr>
                                        <th align="left">
                                            Proxy
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
    </div>
    
<div id="GCE" class="caja_form_credentials">
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
                                            Service Email:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            
                                        </th>
                                        <td>
                                            
                                        </td>

						 <th align="left">
                                            Project ID:
                                        </th>
                                        <td>
                                            <input type="text" name="project" value="<?php echo $project;?>">
                                        </td>
                               </tr>
                               <tr>
                                        <th align="left">
                                            Server Cert:
                                        </th>
                                        <td colspan="3">
                                           <input type="file" name=""certificate"">
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

    </div>
    
<div id="FogBow" class="caja_form_credentials">
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
                                            User:
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo $username;?>">
                                        </td>
                               </tr>
                               <tr>
                                        <th align="left">
                                            Host:
                                        </th>
                                        <td>
                                           <input type="text" name="host" value="<?php echo $host;?>">
                                         </td>
										<th align="left">
                                            Password:
                                        </th>
                                        <td>
                                            <input type="password" name="password">
                                        </td>
                                </tr>
                               <tr>
                                        <th align="left">
                                        </th>
                                        <td>                                           
                                         </td>
										<th align="left">
                                            Token Type:
                                        </th>
                                        <td>
                                            <input type="text" name="token_type">
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

    </div>

    </form>

 </div>
</div>
</div>
    <?php include('footer.php')?>
</body>
</html>
<?php
    }
?>
