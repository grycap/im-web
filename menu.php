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
    include('config.php');
?>
<div id="caja_menu_lateral">
<div id="caja_menu">
	<div id='cssmenu'>
		<ul>
			<li><a <?php if ($menu == "Infrastructures") echo 'class="seleccionado"'; ?> href='list.php'><span><img style="vertical-align: middle;" src="images/icon_infra.png" />&nbsp&nbspInfrastructures</span></a></li>
			<li><a <?php if ($menu == "Credentials") echo 'class="seleccionado"'; ?> href='credentials.php'><span><img style="vertical-align: middle;" src="images/icon_creden.png" />&nbsp&nbspCredentials</span></a></li>
			<li><a <?php if ($menu == "RADL") echo 'class="seleccionado"'; ?> href='radl_list.php'><span><img style="vertical-align: middle;" src="images/icon_radl.png" />&nbsp&nbspRADLs</span></a></li>
	<?php
	if ($recipes_db != "") {
	?>			
			<li><a <?php if ($menu == "Recipes") echo 'class="seleccionado"'; ?> href='recipe_list.php'><span><img style="vertical-align: middle;" src="images/icon_recipes.png" />&nbsp&nbspRecipes</span></a></li>
	<?php
	}
	
    	if (check_admin_user()) {
		$admin = False;
		$user_style = "";
		$group_style = "";
		if ($menu == "Users" || $menu == "Groups") {
			$admin = True;
			$user_style = "height:36px;";
			$group_style = "height:36px;";
		}
		if ($menu == "Users") $user_style = "height:36px;background-color:#DDD;font-weight:bold;";
		if ($menu == "Groups") $group_style = "height:36px;background-color:#DDD;font-weight:bold;";
       ?>
			<li class='<?php if ($admin) echo 'seleccionado'; else echo 'has-sub';?>'><a href='#'><span><img style="vertical-align: middle;" src="images/icon_admin.png" />&nbsp&nbspAdmin&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<img style="vertical-align: middle;" src="images/icon_admin_desp.png" /></span></a>

	<?php
	if (!$admin) {
	?>
		<ul>
	<?php
	}
	?>
               <li style=<?php echo $user_style;?>><a href='user_list.php'><span style="font-size: 13px;"><img style="vertical-align: middle; padding-left:26px;" src="images/icon_users.png" />&nbsp&nbspUsers</span></a></li>
               <li style=<?php echo $group_style;?> class='last'><a href='group_list.php'><span style="font-size: 13px;"><img style="vertical-align: middle; padding-left:26px;" src="images/icon_groups.png" />&nbsp&nbspGroups</span></a></li>
	
	<?php
	if (!$admin) {
	?>
              </ul>
	<?php
	}
	?>
                     </li>

         		      
	<?php
    				    }
	?>

        <li></li>    		</ul>

	</div>	
</div>
</div>
