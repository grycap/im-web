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

require_once "analyticstracking.php";
?>
<script type="text/javascript" language="javascript" src="js/cookie.js"></script>

<div id="caja_login_superior_contenido">
    <div id="cookie_banner" style="display:none;height: 30px;">
    	This website uses cookies to ensure you get the best experience on our website.
    	<a href="http://cookiesandyou.com/" target="_blank">
    	<input type="button" name="learn"  style="padding:4px 10px 4px 10px;margin:1px 1px;" value="Learn more" style="backgroud:green;">
    	</a>
    	<a href="#" onclick="removeMe();"><input type="button" style="padding:4px 10px 4px 10px;margin:1px 1px;" name="gotit" value="Accept"></a>
    </div>
    <script type="text/javascript" charset="utf-8">
    showCookieBanner()
     </script>
    <?php require 'user_connected.php'?>        
</div>
<div id="imagen-transpa-logo">
                <img src="images/logo_im2.png" alt="Infrastructure Manager Logo" />
            </div>

<div id="imagen-transpa-logo-derecha">
                <img src="images/imagen_cabecera.png" />
            </div>

<div id="linea_cabecera_logo">
</div>
