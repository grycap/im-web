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
    
require_once 'format.php';

if (!isset($_GET['id']) or !isset($_GET['vmid'])) {
	error('No Id or vmid');
} else {
    $id = $_GET['id'];
    $vmid = $_GET['vmid'];

    $rand = sha1(rand());
    $_SESSION['rand'] = $rand;
    
    include_once 'im.php';
    include_once 'config.php';
    $res = GetIM()->GetVMInfo($id, $vmid);
        
    if (is_string($res) && strpos($res, "Error") !== false) {
        error($res);
    } else {
        $radl_tokens = parseRADL($res);
        $outports = getOutPorts($res);
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
        function operatevm(op) {
        	document.getElementById('opfield').value = op;
        	var r = true;
        	if (op == "destroyvm") {
            	r=confirm("Sure that you want to delete the VM?");
        	}
            if (r==true) {
            	document.getElementById('operateform').submit();
            }
        }
    </script>
</head>

<body>

<div id="caja_total_blanca">

        <?php include 'header.php'?>
        <?php $menu="Infrastructures";include 'menu.php';?>
        <?php include 'footer.php'?>

<div id="caja_titulo">
    <div id="texto_titulo">
    Infrastructure Manager > Infrastructures&nbsp&nbsp&nbsp<img class="imagentitulo" src="images/icon_infra_gran.png">
    </div>
</div>

<div id="caja_contenido_menutab">    

<div id='cssmenutab'>
<ul>
   <li><a href='list.php'><span>List</span></a></li>
   <li class='active'><a><span>VM id: <?php echo htmlspecialchars($vmid);?></span></a></li>
</ul>
</div>
</div>

<div id="caja_contenido_tab">    

    <div id="main">

    <br> 
        <div class='h1'>:: Informacion de la VM id: <?php echo htmlspecialchars($vmid);?>&nbsp::</div>
      <br><br>

        <table class="list" style="width:100%;margin-left: 0px;">
            <tbody>
            <tr>
          <td style="width:10px;background:#777;"><img src="images/icon_state.png"></td>
                <td style="width:90px; background:#777;padding-left:0px;">State</td>
                <td colspan="3" style="text-align:left; padding-left:20px; font-weight:bold;background:#CCC;"><?php echo formatState($radl_tokens['state']);?></td>
            </tr>
            <tr>
          <td style="width:10px;background:#777;"><img src="images/icon_deploy.png"></td>
                <td style="width:90px; background:#777;padding-left:0px;">Deployment</td>
                <td colspan="3" style="text-align:left; padding-left:20px;font-weight:bold;background:#CCC;"><?php echo formatCloud($radl_tokens);?>
                </td>
            </tr>

        <tr>
          <td style="width:10px;background:#777;"><img src="images/icon_ip.png"></td>
                <th style="width:90px; background:#777;padding-left:0px;">IPs</th>
        <?php
        if (count($outports) > 0) {
            ?>
                <td style="text-align:left; padding-left:20px;font-weight:bold;background:#CCC;"><?php echo formatIPs($radl_tokens);?></td>
                <th style="width:90px; background:#777;padding-left:0px;">Ports</th>
                <td style="text-align:left; padding-left:20px;font-weight:bold;background:#CCC;"><?php echo formatOutPorts($outports);?></td>
            <?php
        } else {
            ?>
                <td colspan="3" style="text-align:left; padding-left:20px;font-weight:bold;background:#CCC;"><?php echo formatIPs($radl_tokens);?></td>          
            <?php
        }
        ?>      
            </tr>

   </tbody>
        </table>

<br>
 <table class="list" style="width:100%;margin-left: 0px;">
            <tbody>

            <tr>
          <td style="width:10px;background:#a27c3b;"><img src="images/icon_info.png"></td>
                <th style="width:90px;background:#a27c3b;">Information</th>
                <td style="text-align:left;background:#e9d6b5;">
                    <table>
             <?php echo formatRADL($radl_tokens);?>
                    <tr>
                    <td>
                    Cont. Message
                    </td>
                    <td>
                    <a href="getcontmsg.php?id=<?php echo htmlspecialchars($id);?>&vmid=<?php echo htmlspecialchars($vmid);?>">Show >></a>
                    </td>
                    </tr>
                    </table>
                </td>
            </tr>

            </tbody>
        </table>

<table style="width:165px;">
    <tbody>
        <tr>
               	<form action="operate.php" method="post" id="operateform">
               	<input type="hidden" name="op" value="" id="opfield"/>
               	<input type="hidden" name="infid" value="<?php echo htmlspecialchars($id);?>"/>
               	<input type="hidden" name="vmid" value="<?php echo htmlspecialchars($vmid);?>"/>
               	<input type="hidden" name="rand" value="<?php echo $rand;?>"/>
               	</form>
            <td style="text-align:center;">
            <a href="#" onclick="javascript:operatevm('stopvm')"><img style="border:0px;" src="images/icon_stopVM.jpg" border="0" alt="Stop VM" title="Stop VM"></a>
            </td>
            <td style="text-align:center;">
            <a href="#" onclick="javascript:operatevm('startvm')"><img style="border:0px;" src="images/icon_startVM.jpg" border="0" alt="Start VM" title="Start VM"></a>
            </td>
            <td style="text-align:center;">
            <a href="#" onclick="javascript:operatevm('rebootvm')"><img style="border:0px;" src="images/icon_rebootVM.jpg" border="0" alt="Reboot VM" title="Reboot VM"></a>
            </td>
            <td style="text-align:center;">
            <a href="#" onclick="javascript:operatevm('destroyvm')"><img style="border:0px;" src="images/icon_terminateVM.jpg" border="0" alt="Terminate VM" title="Terminate VM"></a>
            </td>
        </tr>
    </tbody>
</table>

    </div>
    
    </div>
</div>
</body>
</html>
        <?php
    }
}
?>
