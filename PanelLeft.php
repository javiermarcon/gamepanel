<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.

*/
include_once('include/config.php');
include_once('include/auth.php');
?>
<html>
<head>
<title><?php echo $config['title']; ?></title>
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/panelLeft.css">
</head>

<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<table align="center" cellpadding="0" cellspacing="0" style="border-right:1px solid black" width="100%" height="100%">
  <tr>
    <td valign="top">
    
<div align="center">
<font color="#FFFFFF" face="verdana" size="3">
<b><?php echo $config['Imperial Host']; ?></b><br />
</font>

<br />

<font color="white">Logged in: <b><?php echo $GPXuserName; ?></b></font>
<br />
<div align="center"><b><a href="logout.php" target="_top">Logout</a></b> </div>
<br />
<br />
<!-- Begin Navigation -->
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
  <!-- General -->
  <tr style="vertical-align:middle;height:20px;line-height:20px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;<a href="MyInfo.php" target="mainFrame"><img src="images/edit.png" border="0" /></a>&nbsp;<span class="left_panel_title">Configuracion General </span></td>
  </tr>
  <tr>
    <td align="left"><a href="Main.php" target="mainFrame"><img src="images/main/main-14.png" border="0" />&nbsp;Inicio</a></td>
  </tr>
  <tr>
    <td align="left"><a href="Settings.php" target="mainFrame"><img src="images/main/settings-14.png" border="0" />&nbsp;Editar mi Configuracion </a></td>
  </tr>
  <tr>
    <td align="left"><a href="MyInfo.php" target="mainFrame"><img src="images/edit.png" border="0" />&nbsp;Editar mi Informacion </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- My Servers -->
  <tr style="vertical-align:middle;height:20px;line-height:20px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;<img src="images/main/server-14.png" border="0" />&nbsp;<span class="left_panel_title">Mi Servers</span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="ServerManager.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/games.png" border="0" />&nbsp;Game Servers</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="ServerManager.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/vent.png" border="0" />&nbsp;Voip Servers</a></td>
  </tr>
  <tr>
    <td align="left"><a href="ServerManager.php?type=game" target="mainFrame">&nbsp;<img src="images/servers/leftpanel/gestor_ftp.jpg" alt="" width="18" border="0" />&nbsp;</a><a href="http://190.210.25.117/webftp/index.php">Ftp Servers</a></td>
  </tr>
  <!-- About -->
  <tr style="vertical-align:middle;height:20px;line-height:20px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;<img src="images/main/sobre_nosotros2.png" border="0" />&nbsp;<span class="left_panel_title">GamePanel</span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="About.php" target="mainFrame"><img src="images/main/sobre_nosotros2.png" border="0" />&nbsp;Sobre Nosotros </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
</table>
</div></td>
  </tr>
</table>

</body>
</html>
