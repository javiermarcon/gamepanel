<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.

*/
include_once("include/config.php");

session_start();
session_destroy();
$_SESSION['online'] == 0;
unset($_SESSION['online']); 
?>
<html>
<head>
<title><?php echo $config['title']; ?> | Please Login</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
</head>

<body bgcolor="#000000" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="<?php echo $config['BASE_DIR'] . '/css/' . $config['theme'] . '/img/gpx_grad.png'; ?>" height="80"><img src="<?php echo $config['BASE_DIR'] . '/' . $config['top_logo']; ?>" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<br />

<table border="0" class="login_table" align="center" width="400" cellpadding="2" cellspacing="2">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles"><b><?php echo $config['CompanyName']; ?></b> - Desconectar</span></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><font color="black">You have been successfully logged out.<br /><a href="login.php"> <span title="Haz clic para obtener traducciones alternativas">Haga clic</span> <span title="Haz clic para obtener traducciones alternativas">aquí</span> <span title="Haz clic para obtener traducciones alternativas">para</span> <span title="Haz clic para obtener traducciones alternativas">volver a iniciar sesión</span></a></font></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</body>
</html>