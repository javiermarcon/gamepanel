<?php
/*

GamePanelX

Description:  About GamePanelX

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/statusInfo.php');
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<? echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Sobre GamePanel</span></td>
</tr>
</table>

<br /><br />

<center>
  <p><img src="images/main/about-64.png" border="0" /><br />
    Version: <b><?php echo $config['GPX-VERSION']; ?></b><br /><br />
  <b>Game Panel</b> un panel de control web para servidores de juegos diseñado,  copilado y traducido por gamepanel basado en anteriores modelos de codigos abiertos GPL / GNU<br />
    Esta version es un codigo privado de <a href="http://www.imperialhost.com.ar" target="_BLANK" style="color:black">www.imperialhost.com.ar</a> quien tiene todos los derechos reservados </p>
  <p>Mas info en  <a href="http://www.gamepanel.com.ar" target="_BLANK" style="color:black">Game Panel</a> </p>
  <p><a href="http://www.gamepanel.com.ar" target="_BLANK" style="color:black">www.gamepanel.com.ar</a></p>
  <p><br />
</p>
</center>

</body>
</html>