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
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
<style type="text/css">
.layers123 {
	font-weight: bold;
}
</style>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<div align="center">
<font face="verdana">
<br />
<?php
// Get user's first name for greeting
$query="SELECT first_name FROM users WHERE username='$GPXuserName'";
sqlCon($query);
$first_name = $row['first_name'];

if(!empty($first_name))
{
    echo 'Hola, <b>' . $first_name . '</b>!';
}
else
{
    echo 'Hola, <b>' . $GPXuserName . '</b>!';
}

?>
<br /></font>
<div align="center"><font face="verdana" class="layers123">Bienvenido a Game Panel</font> power by www.imperialhost.com.ar</div>
<br>
<br>
<table width="500" height="105" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr align="center">
    <td width="139" align="center"><a href="ServerManager.php?type=game" target="_self" style="color:black"><img src="images/main/supported_games.png" border="0"></a></td>
    <td width="161" align="center"><a href="http://190.210.25.117/webftp/index.php" target="_self" style="color:black"><img src="images/main/ftp-scp.png" width="113" height="94" border="0"></a></td>
    <td width="180" align="center"><a href="ServerManager.php?type=voip" target="_self" style="color:black"><img src="images/main/supported_voip.png" border="0"></a><a href="About.php" target="_self" style="color:black"></a></td>
  </tr>
  <tr align="center">
    <td width="139" align="center"><a href="ServerManager.php?type=game" target="_self" style="color:black"><b>Game Servers</b></a><a href="MyInfo.php" target="_self" style="color:black"></a></td>
    <td width="161" align="center"><a href="http://190.210.25.117/webftp/index.php" target="_self" style="color:black"><b>Web FTP</b></a></td>
    <td width="180" align="center"><a href="ServerManager.php?type=voip" target="_self" style="color:black"><b>Voice Servers</b></a><a href="About.php" target="_self" style="color:black"></a></td>
  </tr>
</table>
<br />
<table width="500" height="105" border="0" align="center" cellpadding="2" cellspacing="2">
  <tr align="center">
    <td width="139" align="center"><a href="MyInfo.php" target="_self" style="color:black"><img src="images/main/my-info.png" border="0"></a></td>
    <td width="161" align="center"><a href="Settings.php" target="_self" style="color:black"><img src="images/main/settings.png" border="0"></a></td>
    <td width="180" align="center"><a href="About.php" target="_self" style="color:black"><img src="images/main/sobre_nosotros.png" width="50" height="51" border="0"></a></td>
  </tr>
  <tr align="center">
    <td width="139" align="center"><a href="MyInfo.php" target="_self" style="color:black"><b>Mi Informacion </b></a></td>
    <td width="161" align="center"><a href="Settings.php" target="_self" style="color:black"><b>Configuraciones</b></a></td>
    <td width="180" align="center"><a href="About.php" target="_self" style="color:black"><b>Sobre Nosotros?</b></a></td>
  </tr>
</table>
</div></body>
</html>
