

<?php
if(!isset($_SESSION)) session_start();

include('config.php');
?>
<html>
<head>
<link rel="shortcut icon" href="images/favicon.ico">
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="stylesheet" href="css/style_login2.css"> 
<link rel="stylesheet" href="css/style_intro2.css"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
</head>
<body>
<div id="caja_total_blanca">
	
	

	<div id="caja_login_superior">
		<div id="caja_login_superior_componentes">
			<form action="list.php" method="post">
<?php
    if (isset($_GET['info'])) {
?>
        <div id="texto_info">
        <div id="texto_open_sans">
                        <?php echo $_GET['info'];?>
                </div>
        </div>
<?php
    }

    if (isset($_GET['error'])) {
?>
        <div id="texto_error">
        <div id="texto_open_sans">
                        <?php echo $_GET['error'];?>
                </div>
        </div>
<?php
    }
?>
			<input type="text" name="username" value="" placeholder="Username">
			<input type="password" name="password" value="" placeholder="Password">
			<input type="submit" value="Login">
			<a href="adduser.php"><input type="button" name="Register" value="Register"></a>
<?php
			if (!empty($openid_issuer)) {
?>
		&nbsp<a href="openid_auth.php"><input type="button" name="OpenID" value="OpenID" title="<?php echo $openid_name;?>"></a>
<?php
    }
?>
			</form>
		</div>			
	</div>	



	<div id="caja_portada_logo_centro">
		<img src="images/logo_portada.png">
	</div>	

       

	


	<div id="caja_pie_portada">
		<div id="caja_texto_pie_portada">
			&copy GRyCAP&nbsp&nbsp|&nbsp&nbsp<a href="terms.html">Terms of Service</a>&nbsp&nbsp|&nbsp&nbspUniversidad Polit&eacutecnica de Valencia&nbsp&nbsp|&nbsp&nbspEdificio 8B, Acceso N, Planta 1
&nbsp&nbsp|&nbsp&nbspCamino de Vera s/n, 46022, Valencia&nbsp&nbsp|&nbsp&nbspwww.grycap.upv.es
		</div>
	</div>	

	
	<div id="linea_pie_portada">
	</div>
</div>	
	
</body>
</html>
