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

if (!isset($_SESSION)) {
    session_start();
}

require_once 'user.php';
if (!check_session_user() || !check_admin_user()) {
	invalid_user_error();
} else {
    if (isset($_GET['id'])) {
        $username = $_GET['id'];
    }
    include_once 'group.php';
    $groups = get_groups();

    $rand = sha1(rand());
    $_SESSION['rand'] = $rand;
    
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

    <script type="text/javascript" charset="utf-8">
        function delete_group() {
            user_groups = document.getElementById("user_groups");
            items_to_delete = new Array()
            for (var i=0;i<user_groups.length;i++)
            {
                if (user_groups.options[i].selected) {
                    items_to_delete.push(user_groups.options[i].index);
                }
            }
            
            var offset = 0;
            for (var i=0;i<items_to_delete.length;i++) {
                user_groups.options.remove(items_to_delete[i] - offset);
                offset++;
            }
        }

        function set_users() {
            user_groups = document.getElementById("user_groups");
            for (var i=0;i<user_groups.length;i++)
            {
                user_groups.options[i].selected = true;
            }
        }

        function add_group() {
            groups = document.getElementById("groups");
            user_groups = document.getElementById("user_groups");
            sel_option = groups.options[groups.selectedIndex];

            var found = false;
            for (var i=0;i<user_groups.length;i++)
            {
                if (user_groups.options[i].value == sel_option.value) found = true;
            }
            
            if (!found) {
                var option=document.createElement("option");
                option.text=sel_option.text;
                option.value=sel_option.value;
                user_groups.add(option,null);
            }
        }
    </script>
</head>
<body>

<div id="caja_total_blanca">


    <?php include 'header.php'?>        
    <?php $menu="Users";include 'menu.php';?>

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Add / Edit User&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_users_gran.png">
    </div>
</div>


<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li><a href='user_list.php'><span>List</span></a></li>
   <li class='active'><a href='userform.php'><span>Add +</span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    


    <div id="main">
    
    <?php
        $password = "";
        $permissions = 0;
        $user_groups = array(array('grpname' => 'users'));
            
    if (isset($username)) {
        $user = get_user($username);
        $password = $user['password'];
        $permissions = $user['permissions'];
        $user_groups = get_user_groups($username);
        ?>
        <br> 
        <div class='h1'>:: Edit User ::</div>

    <div id="caja_form_users">

        <form action="userinfo.php" method="post" onsubmit="javascript:set_users()">
            <input type="hidden" name="op" value="edit"/>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($username);?>"/>
            <input type="hidden" name="rand" value="<?php echo $rand;?>"/>
            <?php
    } else {
        ?>
        <br> 
        <div class='h1'>:: Add new User ::</div>

    <div id="caja_form_users">

        <form action="userinfo.php" method="post" onsubmit="javascript:set_users()">
            <input type="hidden" name="op" value="add"/>
            <input type="hidden" name="rand" value="<?php echo $rand;?>"/>
        <?php
    }
    ?>

                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            Username:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td>
                                            <input type="text" name="username" value="<?php echo htmlspecialchars($username);?>">
                                        </td>

                    <th align="left">
                                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspGroups:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                        </th>
                                        <td rowspan="3">
                        <select id="user_groups" name="user_groups[]" size=5 multiple type="grupos">
                                            <?php
                                            foreach ($user_groups as $group) {
                                                ?>
                                                    <option value="<?php echo htmlspecialchars($group['grpname']);?>"><?php echo htmlspecialchars($group['grpname']);?></option>
                                                <?php
                                            }
                                            ?>
                                            </select>
                      <td rowspan="3">
                            <a href="#" onclick="javascript:delete_group()"><img src="images/borrar.gif" border="0" alt="Delete" title="Delete"></a>
                                                
                                            </td>
                                            
                                        </td>

                               </tr>
                                <tr>
                                        <th align="left">
                                            Password:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                                        </th>
                                        <td>
                                            <input type="password" name="password" id="password">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Confirm Password:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                                        </th>
                                        <td>
                                            <input type="password" name="password2" id="password2">
                                        </td>
                               </tr>

                   <tr>
                                        <th align="left">
                                            Permissions:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                                        </th>
                                        <td>
                                            <select name="permissions">
                                                    <option value="0"
                                                        <?php if (!$permissions) { echo 'selected="selected"';
}?>
                                                            >Standard</option>
                                                    <option value="1"
                                                        <?php if ($permissions) { echo 'selected="selected"';
}?>
                                                            >Administrator</option>
                                            </select>
                                        </td>
                                        <th align="left">
                    </th>
                                        <td>
                                            
                                            <select id="groups" name="groups">
                                                <?php
                                                foreach ($groups as $group) {
                                                    ?>
                                                    <option value="<?php echo htmlspecialchars($group['name']);?>"><?php echo htmlspecialchars($group['name']);?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                     </td>
                        
                    <td>
                                            <a href="#" onclick="javascript:add_group()"><img src="images/add.gif" border="0" alt="Add" title="Add"></a>
                                        </td>
                                </tr>
                                <tr>
                                </tr>
                                <tr>
                    <td align="right" colspan="5">
                     <input type="submit" value="Save"/>
                    <a href="user_list.php"><input type="button" name="Cancelar" value="Cancel"></a>
                    </td>
                                 </tr>
                        </tbody>
                </table>
        </form>
</div>
 </div>
</div>
</div>
    <?php include 'footer.php'?>
</body>
</html>
    <?php
}
?>
