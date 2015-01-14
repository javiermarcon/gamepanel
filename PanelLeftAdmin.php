<?php
/*

GamePanelX

Description:  Left admin panel with links to main pages

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/auth.php');
include_once('include/SqlCon.php');

// Kill page if user is not an admin
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}
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
<b>GamePanelX</b><br />
Version <?php echo $config['GPX-VERSION']; ?>
</font>

<br /><br />

<font color="white">Logged in: <b><?php echo $GPXuserName; ?></b></font>
<br />
<div align="center"><b><a href="logout.php" target="_top">Logout</a></b> </div>
<br />
<br />
<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
  <!-- Server Setup -->
  <tr style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/main/settings-14.png" border="0" />&nbsp;  Configuraci&oacute;n del servidor </td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="AdminMain.php" target="mainFrame"><img src="images/servers/leftpanel/main.png" border="0" />&nbsp;  P&aacute;gina principal </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="Configuration.php" target="mainFrame"><img src="images/servers/leftpanel/settings.png" border="0" />&nbsp;  Editar configuraci&oacute;n </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="SSHTest.php" target="mainFrame"><img src="images/servers/leftpanel/cmd_line.png" border="0" />&nbsp;  Prueba de conexi&oacute;n SSH </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- User Editing -->
  <tr style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/main/user-group-14.png" border="0" />&nbsp;  Cuentas de usuarios</td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="UserEditor.php" target="mainFrame"><img src="images/servers/leftpanel/user_group.png" border="0" />&nbsp;  Editor del usuarios</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="CreateUser.php" target="mainFrame"><img src="images/servers/leftpanel/add_user.png" border="0" />&nbsp;  Crear usuario </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="MyInfo.php" target="mainFrame"><img src="images/edit.png" border="0" />&nbsp;  Editar Mi Cuenta </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- Remote Servers -->
  <tr style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/main/server-14.png" border="0" />&nbsp;  Servidores remotos </td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="RemoteServerManager.php" target="mainFrame"><img src="images/servers/leftpanel/server.png" border="0" />&nbsp;  Administrador de servidores remotos </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="RemoteServerNew.php" target="mainFrame"><img src="images/servers/leftpanel/server_add.png" border="0" />&nbsp;  Crear servidor remoto </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- User Servers -->
  <tr height="20" style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/servers/leftpanel/games.png" border="0" height="14" width="14" />&nbsp;  Servidores del usuario </td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="ListServers.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/games.png" border="0" />&nbsp;Lista de Game Servers</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="ListServers.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/vent.png" border="0" />&nbsp;Lista de Voip Servers</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="CreateServer.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/games.png" border="0" />&nbsp;Crear Game Server</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="CreateServer.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/vent.png" border="0" />&nbsp;Crear Voip Server</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- Supported Servers -->
  <tr height="20">
    <td align="left" style="vertical-align:middle;height:20px;line-height:20px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')" valign="middle">&nbsp;&nbsp;&nbsp;<img src="images/servers/small/games.png" border="0" height="14" width="14" />&nbsp;  Servidore de Arranque <a href="SupportedServers.php?type=voip" target="mainFrame"></a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="SupportedServers.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/games.png" border="0" />&nbsp;</a><a href="CreateSupportedServer.php?type=voip" target="mainFrame">Arranque de</a><a href="SupportedServers.php?type=voip" target="mainFrame"></a><a href="SupportedServers.php?type=game" target="mainFrame"> Game Servers</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="SupportedServers.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/vent.png" border="0" />&nbsp;</a><a href="CreateSupportedServer.php?type=voip" target="mainFrame">Arranque de</a><a href="SupportedServers.php?type=voip" target="mainFrame"> Voip Servers</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="CreateSupportedServer.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/games.png" border="0" />&nbsp;</a><a href="CreateSupportedServer.php?type=voip" target="mainFrame">Crear arranque de</a><a href="CreateSupportedServer.php?type=game" target="mainFrame"> Game Server</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="CreateSupportedServer.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/vent.png" border="0" />&nbsp;Crear arranque de VoIP </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="XMLImport.php" target="mainFrame"><img src="images/xml-14.png" border="0" /> Importar datos de servidor XML </a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- Games Templates -->
  <tr style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/main/template-14.png" border="0" />&nbsp;<span class="left_panel_title">Pre-Servers</span></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="Templates.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/template_game.png" border="0" />&nbsp;Game <span class="left_panel_title">Pre-Servers</span></a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="Templates.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/template_voip.png" border="0" />&nbsp;Voip <span class="left_panel_title">Pre-Servers</span></a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="TemplateNew.php?type=game" target="mainFrame"><img src="images/servers/leftpanel/template_game.png" border="0" />&nbsp;Create <span class="left_panel_title">Pre-Servers</span> Game</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;<a href="TemplateNew.php?type=voip" target="mainFrame"><img src="images/servers/leftpanel/template_voip.png" border="0" />&nbsp;Crear <span class="left_panel_title">Pre-Servers</span> Voip</a></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <!-- About -->
  <tr style="vertical-align:middle;height:20px;line-height:19px;background-image:url('css/<?php echo $config['theme']; ?>/img/smallGrad.png')">
    <td align="left">&nbsp;&nbsp;&nbsp;<img src="images/main/sobre_nosotros2.png" border="0" />&nbsp;<span class="left_panel_title">GamePanel</span></td>
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
