<?php
/*

GamePanelX

Description:  Home page with frames

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/SqlCon.php');
include_once('include/auth.php');

// Make sure nobody can log in until the install folder is deleted
if (file_exists('install'))
{
    die('<b>ERROR:</b> You must delete the folder \'install\' before you can log in!');
}

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

// If they're an admin, forward to AdminMain.php
if($row['is_admin'] == 'Y')
{
    $mainFrameLoc = 'AdminMain.php';
    $leftPanelLoc = 'PanelLeftAdmin.php';
}

// If they're a normal user, send to Main.php
else
{
    $mainFrameLoc = 'Main.php';
    $leftPanelLoc = 'PanelLeft.php';
}
?>
<html>
<head>
<title><?php echo $config['title']; ?></title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
</head>

<frameset border="0" frameborder="0" framespacing="0" rows="81,100%">
<frame src="PanelTop.php" name="topFrame">
<frameset border="0" frameborder="0" framespacing="0" cols="210,*">
<frame src="<?php echo $leftPanelLoc; ?>" name="leftFrame">
<frame src="<?php echo $mainFrameLoc; ?>" name="mainFrame">
</frameset>

</html>
