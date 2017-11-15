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
    if (!check_session_user()) {
	header('Location: index.php?error=Invalid User');
    } else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        
        $user = $_SESSION['user'];
        $user_groups = get_user_groups($user);

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
    
    <?php include_once('radl.php')?>


   
<div id="caja_total_blanca">


		


		<?php include('header.php')?>		
		<?php $menu="RADL";include('menu.php');?>
		<?php include('footer.php')?>	







<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Add / Edit RADL&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_radl_gran.png">
	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li><a href='radl_list.php'><span>List</span></a></li>
   <li class='active'><a href='radlform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	


    <div id="main">
    
    <?php
            $name = "";
            $desc = "";
            $radl_data = "Type here...";
            $group = "";
            $group_r = "";
            $group_w = "";
            $group_x = "";
            $other_r = "";
            $other_w = "";
            $other_x = "";
            
            if (isset($id)) {
                $radl = get_radl($id);
                $name = $radl['name'];
                $desc = $radl['description'];
                $radl_data = $radl['radl'];
                $group = $radl['grpname'];
                $group_r = $radl['group_r'];
                $group_w = $radl['group_w'];
                $group_x = $radl['group_x'];
                $other_r = $radl['other_r'];
                $other_w = $radl['other_w'];
                $other_x = $radl['other_x'];
    ?>


        <div class='h1'>:: Edit RADL ::</div>

        <div id="caja_form_radls">


                <?php
                if (radl_user_can($id, $user, "w")) {
                ?>
        <form action="radlinfo.php" method="post">
                <?php
                } else {
                ?>
        <form action="error.php?msg=No modifications allowed" method="post">
                <?php
                }
                ?>



            <input type="hidden" name="op" value="edit"/>
            <input type="hidden" name="id" value="<?php echo $id;?>"/>


    <?php
            } else {
    ?>



       <br>
        <div class='h1'>:: Add new RADL ::</div>


					 

       <div id="caja_form_radls">


        <form action="radlinfo.php" method="post">

				     


            <input type="hidden" name="op" value="add"/>
    <?php
            }
    ?>

                <table >
                        <tbody>
				<tr>
					<td colspan=2>
					RADL
					</td>
				</tr>
				<tr>
					<td colspan=2>
<textarea type="RADL" align="bottom" name="radl"><?php echo $radl_data;?></textarea>
					</td>
				</tr>
                               <tr>
<th align="left" class="th_form_radl">Name:</th>
<td width="30"><input type="text" name="name" value="<?php echo $name;?>"></td>
</tr>



<tr>
<th align="left" class="th_form_radl">Description:</th>
<td><input maxlength="256" size="200" type="descr_radl" name="description" value="<?php echo $desc;?>"></td>
</tr>




<tr>
<th align="left" class="th_form_radl">Group:</th>
<td><select name="group">
                                                <?php
                                                foreach ($user_groups as $user_group) {
                                                ?>
                                                    <option value="<?php echo $user_group['grpname'];?>"
                                                    <?php if ($group == $user_group['grpname']) echo 'selected="selected"';?>
                                                                         
                                                    ><?php echo $user_group['grpname'];?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
</td>

</tr>



<tr>
<th align="left"><br>Permission_Group:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
<td> <br>r: <input type="checkbox" name="group_r" value="1"
                                            <?php if ($group_r == "1") echo 'checked';?>>
                                            &nbsp&nbsp&nbspw: <input type="checkbox" name="group_w" value="1"
                                            <?php if ($group_w == "1") echo 'checked';?>>
                                            &nbsp&nbsp&nbspx: <input type="checkbox" name="group_x" value="1"
                                            <?php if ($group_x == "1") echo 'checked';?>>
</td>
</tr>

<tr>
<th align="left">Permission_Other:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</th>
<td>r: <input type="checkbox" name="other_r" value="1"
                                            <?php if ($other_r == "1") echo 'checked';?>>
                                            &nbsp&nbsp&nbspw: <input type="checkbox" name="other_w" value="1"
                                            <?php if ($other_w == "1") echo 'checked';?>>
                                            &nbsp&nbsp&nbspx: <input type="checkbox" name="other_x" value="1"
                                            <?php if ($other_x == "1") echo 'checked';?>></td>

</tr>
<tr>
<td colspan=2 align=right>
<?php
                if (!isset($id) || radl_user_can($id, $user, "x")) {
?>
<a href="radlinfo.php?op=launch&id=<?php echo $id;?>"><input type="button" name="Launch" value="Launch"></a>
<?php
                }
                if (!isset($id) || radl_user_can($id, $user, "w")) {
?>
 					<input type="submit" value="Save"/>
                     <?php
                }
?>
                    <a href="radl_list.php"><input type="button" name="Cancelar" value="Cancel"></a>
</td>
</tr>

</tbody>  
                
                       
                </table>


</div>

        </form>


 </div>

</body>
</html>
<?php
    }
?>




