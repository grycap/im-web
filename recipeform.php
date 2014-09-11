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
            $id = $_GET['id'];
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
        <?php include('recipe.php')?>


<div id="caja_total_blanca">


		<?php include('header.php')?>		
		<?php $menu="Recipes";include('menu.php');?>




<div id="caja_titulo">
	<div id="texto_titulo">
	Infrastructure Manager > Add / Edit Recipe&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_recipes_gran.png">

	</div>
</div>


<div id="caja_contenido_menutab">	

<div id='cssmenutab'>
<ul>
   <li><a href='recipe_list.php'><span>List</span></a></li>
   <li class='active'><a href='recipeform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>


<div id="caja_contenido_tab">	




    <div id="main">
    
    <?php
            $desc = "";
            
            if (isset($id)) {
                $recipe = get_recipe($id);
                $name = $recipe['name'];
                $version = $recipe['version'];
                $module = $recipe['module'];
                $recipe_text = $recipe['recipe'];
                $galaxy_module = $recipe['galaxy_module'];
                $requirements = $recipe['requirements'];
                $desc = $recipe['description'];
    ?>
        <br> 
        <div class='h1'>:: Edit Recipe ::</div>

	 <div id="caja_form_groups">

    <?php
				if (check_admin_user()) {
?>
        <form action="recipeinfo.php" method="post">
    <?php
           		} else {
?>
        <form>
    <?php
            	}
?>
            <input type="hidden" name="op" value="edit"/>
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
    <?php
            } else {
?>
        <br> 
        <div class='h1'>:: Add new Recipe ::</div>
	<br>
	<div id="caja_form_groups">

        <form action="recipeinfo.php" method="post">
            <input type="hidden" name="op" value="add"/>
    <?php
            }
    ?>

                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            Name:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input type="text" name="name" value="<?php echo $name;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Version:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input type="text" name="version" value="<?php echo $version;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Description:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input maxlength="256" size="200" type="descr" name="description" value="<?php echo $desc;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Module:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input type="text" name="module" value="<?php echo $module;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Galaxy Module:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                            <input type="text" name="galaxy_module" value="<?php echo $galaxy_module;?>">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Recipe:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                        	<textarea type="RECIPE" align="bottom" name="recipe"><?php echo $recipe_text;?></textarea>
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Requirements:&nbsp&nbsp
                                        </th>
                                        <td align="left">
                                        	<textarea type="RECIPE" align="bottom" name="requirements"><?php echo $requirements;?></textarea>
                                        </td>
                               </tr>
                                <tr>
                                </tr>
                                <tr>
					<th></th>
					<td align="right">
    <?php
				if (check_admin_user()) {
?>
						<input type="submit" value="Save"/>
						<a href="recipe_list.php"><input type="button" name="Cancelar" value="Cancel"></a>
    <?php
           		} else {
?>
						<a href="recipe_list.php"><input type="button" name="Back" value="Back"></a>
    <?php
           		}
?>
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
