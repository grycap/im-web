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

<div id="caja_total_blanca">

    <?php require 'header.php'?>
    <?php require 'menu.php';?>

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Change Password&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_users_gran.png">
    </div>
</div>

<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li class='active'><a href='#'><span>Change Password</span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    

    <div id="main">
    
        <br> 
        <div class='h1'>:: Change Password ::</div>

    <div id="caja_form_add_users">

        <form action="userinfo.php" method="post">
            <input type="hidden" name="op" value="password"/>

                <table>
                        <tbody>
                                <tr>
                                        <th align="left">
                                            New Password:

                                        </th>
                                        <td>
                                            <input type="password" name="password" id="password">
                                        </td>
                               </tr>
                                <tr>
                                        <th align="left">
                                            Confirm Password:

                                        </th>
                                        <td>
                                            <input type="password" name="password2" id="password2">
                                        </td>
                               </tr>
                                <tr>
                                </tr>
                                <tr>
                    <td align="right" colspan="5">
                     <input type="submit" value="Save"/>
                    <a href="list.php"><input type="button" name="Cancelar" value="Cancel"></a>
                    </td>
                                 </tr>
                        </tbody>
                </table>
        </form>
</div>
 </div>
</div>
</div>
    <?php require 'footer.php'?>
</body>
</html>
