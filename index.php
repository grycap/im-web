<?php
if (!isset($_SESSION)) {
    session_start();
}

require 'config.php';
?>
<html>
<head>
<link rel="shortcut icon" href="images/favicon.ico">
<title>Infrastructure Manager | GRyCAP | UPV</title>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<link rel="stylesheet" href="css/style_login2.css"> 
<link rel="stylesheet" href="css/style_intro2.css"> 
<script type="text/javascript" language="javascript" src="js/cookie.js"></script>
<?php
require_once "analyticstracking.php";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
</head>
<body onload="showCookieBanner()">
<div id="caja_total_blanca">

    <div id="caja_login_superior">
    <div id="cookie_banner" style="display:none;">
    	This website uses cookies to ensure you get the best experience on our website.
    	<a href="http://cookiesandyou.com/">Learn more</a>
    	<a href="#" onclick="removeMe();"><input type="button" name="gotit" value="Got it!"></a>
    </div>
        <div id="caja_login_superior_componentes">
            <form action="list.php" method="post">
<?php
if (isset($_SESSION['info'])) {
    ?>
        <div id="texto_info">
        <div id="texto_open_sans">
                    <?php echo htmlspecialchars($_SESSION['info']);unset($_SESSION['info']); ?>
                </div>
        </div>
    <?php
}

if (isset($_SESSION['error'])) {
    ?>
        <div id="texto_error">
        <div id="texto_open_sans">
                    <?php echo htmlspecialchars($_SESSION['error']);unset($_SESSION['error']); ?>
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
        &nbsp<a href="openid_auth.php"><input type="button" name="OpenID" value="<?php echo $openid_name;?>" title="OpenID"></a>
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
            &copy GRyCAP&nbsp&nbsp|&nbsp&nbsp<a href="terms.html">Terms of Service</a>&nbsp&nbsp|&nbsp&nbsp<a href="https://www.upv.es" target="_blank">Universidad Polit&eacutecnica de Valencia</a>&nbsp&nbsp|&nbsp&nbspEdificio 8B, Acceso N, Planta 1
&nbsp&nbsp|&nbsp&nbspCamino de Vera s/n, 46022, Valencia&nbsp&nbsp|&nbsp&nbsp<a href="https://www.grycap.upv.es" target="_blank">www.grycap.upv.es</a>
        </div>
    </div>    

    <div id="linea_pie_portada">
    </div>
</div>    
    
</body>
</html>
