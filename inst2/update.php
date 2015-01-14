<?php
/*

GamePanelX

Description:  Installation file

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
error_reporting(E_ERROR);
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/default/main.css">
<title>GamePanelX | Update</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="../include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="../css/default/img/gpx_grad.png" height="80"><img src="../css/default/img/gpx.png" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/default/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">GamePanelX Update</span></td>
</tr>
</table>

<br /><br />
<?php
require('../include/config.php');

// Forced update
$forced_url = $_GET['force'];

// Get GPX version we're updating to
$install__version     = '0.62';
$install__page_title  = 'GamePanelX | The Game Server Control Panel';
$install__top_logo    = 'css/default/img/gpx.png';


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get current version
$result_version = @mysql_query("SELECT value FROM configuration WHERE setting='GPXVersion'") or die('<center><b>Error:</b> Failed to query the configuration table!</center>');

while($row_version = mysql_fetch_array($result_version))
{
    $user_version = $row_version['value'];
}

// Make sure they aren't already up to date
if($user_version >= $install__version && $forced_url != 1)
{
    die('<center><b>Error:</b> This version is already up to date!  To force an update, <a href="update.php?force=1">Click Here</a>.</center>');
}


########################################################################


if(!isset($_POST['submit']))
{
?>

<center>
This will update your GamePanelX Version <b><?php echo $user_version; ?></b> to version <b><?php echo $install__version; ?></b>

<br /><br /><br />
<form method="post" action="update.php?force=1">
<input type="submit" name="submit" value="Update" style="width:170px">
<input type="hidden" name="user_version" value="<?php echo $user_version; ?>">
</form>
</center>


</body>
</html>
<?php
}

elseif(isset($_POST['submit']))
{
    // User's version
    $user_version = $_POST['user_version'];
    
    // Run the update from the 'updates' folder
    $update_name = str_replace('.', '', $user_version) . '_' . str_replace('.', '', $install__version);
    
    /*
    // If update is the same, use older version
    if($user_version == $install__version)
    {
        $update_name = "052_054";
    }
    */

    $update_filename = "updates/$update_name.php";
    
    if(file_exists($update_filename))
    {
        include($update_filename);
    }
    else
    {
        die('<center><b>Error:</b> The update file doesn\'t exist!  Either a database update wasn\'t needed for this version or there was an unknown error.</center>');
    }
?>
<center>
<b>Success!</b>

<br /><br />

Successfully updated your GamePanelX Installation.  You may now delete the 'install' folder, and log in.
</center>
<?php
}
?>
