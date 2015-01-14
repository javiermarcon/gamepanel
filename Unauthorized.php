<?php
/*

GamePanelX

Description:  Page to notify a user if unauthorized

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/auth.php');
?>
<html>
<head>
<title>Unauthorized</title>
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px">

<br /><br />

<center>

<h2>Unauthorized User</h2>

<br />

<b>Error</b>: You are not an authorized user.<br />
Please check your privileges and/or contact technical support.</center>

</body>
</html>