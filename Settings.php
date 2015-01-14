<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


*/
include_once("include/config.php"); 
include_once("include/auth.php");
include_once("include/statusInfo.php");
include_once("include/SqlCon.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_titles">Configuraciones</span></td>
</tr>
</table>

<br /><br />

<table border="0" class="tablez" width="600" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Mis Configuraciones</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Usuario:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $GPXuserName; ?></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Contraseña:&nbsp;&nbsp;</span></td>
  <td align="left"><a href="ChangePassword.php">Click para cambiar de Contraseña</a></td>
</tr>

<?php
// Ip Address stuff
$ip           = $_SERVER['REMOTE_ADDR'];
$hostAddress  = gethostbyaddr($ip);
?>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Tu IP es:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $ip; ?></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Tu hostname es:&nbsp;&nbsp;</span></td>
  <td align="left"><?php if(!empty($hostAddress)) echo $hostAddress; ?></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">Para mas seguridad de los clentes se guardan la ip y hostname de cada vez que te contes al panel de <a href="www.gamepanel.com.ar">www.gamepanel.com.ar</a> by <a href="www.imperialhost.com.ar">Imperial Host</a></td>
</tr>

</table>

</body>
</html>
