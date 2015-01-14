<html>
<head>
<title>GamePanelX | Installation</title>
<link rel="stylesheet" type="text/css" href="../css/default/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:110px; left:20px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="../include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="../css/default/img/gpx_grad.png" height="80"><img src="../css/default/img/gpx.png" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>


<?php
//
// Check if the PECL SSH2 Module exists
//
if(!function_exists('ssh2_connect'))
{
    die('<br /><br /><center><b>Error:</b> The PECL SSH2 module is not installed!  You must install this to connect to Remote Servers.<br /><br /><a href="http://www.gamepanelx.com/wiki/index.php?title=Install_SSH2_Module" target="_blank" style="color:#333;text-decoration:underline">Click here for more info</a>.</center>');
}
?>


<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/default/img/largeGrad.png">
<tr>
<th align="center" valign="middle">GamePanelX Installation</th>
</tr>
</table>

<br /><br />

<center>
<b>Thank you for choosing GamePanelX!</b>

<br /><br />

Click below to begin the installation process.

<br /><br /><br />

<input type="button" onClick="window.location.href='install.php'" value="Install" style="width:170px">

<br /><br />

<b>OR</b>

<br /><br />

To update an existing installation:

<br /><br />

<input type="button" onClick="window.location.href='update.php'" value="Update" style="width:170px">
</center>

<br /><br />

</body>
</html>
