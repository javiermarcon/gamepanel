<?php
/*

GamePanelX

Description:  Top panel with main logo

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/auth.php');
?>
<html>
<head>
<title><? echo $config['title']; ?></title>
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
</head>

<body topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#000000">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="<?php echo $config['BASE_DIR'] . '/css/' . $config['theme'] . '/img/gpx_grad.png'; ?>" height="80"><img src="<?php echo $config['BASE_DIR'] . '/' . $config['top_logo']; ?>" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

</body>
</html>