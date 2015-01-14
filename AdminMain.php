<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/statusInfo.php');
include_once('include/SqlCon.php');

//Make check to see if the logged in user is an admin.
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
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<br> 
<div align="center">
<font face="verdana">
<font color="red"><b>Administrador</b></font><br>
 Bienvenido a Panel de Control de <a href="www.imperialhost.com.ar">Imperial Host</a><br>
</font>
</div>

<br><br>


<table border="0" width="500" cellpadding="2" cellspacing="2" align="center">

  <!-- First Row -->
  <tr align="center">
    <td width="20" align="center">
        <a href="UserEditor.php" target="_self" style="color:black"><img src="images/main/user-group-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="RemoteServerManager.php" target="_self" style="color:black"><img src="images/main/server-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="Configuration.php" target="_self" style="color:black"><img src="images/main/settings-30.png" border="0"></a></td>
  </tr>
  <tr align="center">
    <td width="20" align="center"><a href="UserEditor.php" target="_self" style="color:black"><b>Editor de Usuario</b></a></td>
    <td width="20" align="center"><a href="RemoteServerManager.php" target="_self" style="color:black"><b>Servidor Remoto</b></a></td>
    <td width="20" align="center"><a href="Configuration.php" target="_self" style="color:black"><b>Configuracion</b></a></td>
  </tr>
  
  <!-- Second Row -->
  <tr align="center">
    <td width="20" align="center">
        <a href="ListServers.php?type=game" target="_self" style="color:black"><img src="images/main/supported_games-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="ListServers.php?type=voip" target="_self" style="color:black"><img src="images/main/supported_voip-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="MyInfo.php" target="_self" style="color:black"><img src="images/main/my-info-30.png" border="0"></a></td>
  </tr>
  <tr align="center">
    <td width="20" align="center"><a href="ListServers.php?type=game" target="_self" style="color:black"><b>Game Servers</b></a></td>
    <td width="20" align="center"><a href="ListServers.php?type=voip" target="_self" style="color:black"><b>Voice Servers</b></a></td>
    <td width="20" align="center"><a href="MyInfo.php" target="_self" style="color:black"><b>Editar Mis Detalles</b></a></td>
  </tr>

  <!-- Third Row -->
  <tr align="center">
    <td width="20" align="center">
        <a href="Templates.php?type=game" target="_self" style="color:black"><img src="images/main/template_game-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="Templates.php?type=voip" target="_self" style="color:black"><img src="images/main/template_voip-30.png" border="0"></a></td>
    <td width="20" align="center">
        <a href="About.php" target="_self" style="color:black"><img src="images/main/about-30.png" border="0"></a></td>
  </tr>
  <tr align="center">
    <td width="20" align="center"><a href="Templates.php?type=game" target="_self" style="color:black"><b>Games Templates</b></a></td>
    <td width="20" align="center"><a href="Templates.php?type=voip" target="_self" style="color:black"><b>Voice Templates</b></a></td>
    <td width="20" align="center"><a href="About.php" target="_self" style="color:black"><b>Nosotros</b></a></td>
  </tr>
</table>

</body>
</html>
