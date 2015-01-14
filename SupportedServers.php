<?php
/*

GamePanelX

Description:  View/edit currently supported servers

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

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

if (!isset($_POST['delete_rows']))
{

// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
}

// Give default value
if(empty($url_server_type))
{
    $url_server_type = 'game';
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Supported <?php echo ucwords($url_server_type); ?> Servers</span></td>
</tr>
</table>

<br /><br />

<form action="SupportedServers.php?type=<?php echo $url_server_type; ?>" method="post">
<table border="0" class="tablez" width="600" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td align="center" width="20"><span class="top_titles">#</span></td>
  <td align="center" width="40"><span class="top_titles">&nbsp;</span></td>
  <td align="left" width="250"><span class="top_titles">Full Name</span></td>
  <td align="left" width="100"><span class="top_titles">Short Name</span></td>
  <?php
  // Only show 'style' if this is a game
  if($url_server_type == 'game')
  {
      echo '<td align="center" width="50"><span class="top_titles">Style</span></td>';
  }
  ?>
  <td align="center" width="50"><span class="top_titles">Available</span></td>
  <td align="center" width="50"><span class="top_titles">&nbsp;</span></td>
</tr>


<?php
// Grab SSH info from table 'remote_ssh'
//
// Id, IP Address, and "secret key" for AES Encryption
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> Failed to select the database!</center>');

$result = @mysql_query("SELECT id,short_name,long_name,available,style FROM servers WHERE type='$url_server_type' ORDER by short_name") or die('<center><b>Error:</b> Failed to query the servers table!</center>');

while ($row = mysql_fetch_array($result))
{
    // Include alternating row colors
    include("include/colors.php");

    $id               = $row['id'];
    $short_name       = $row['short_name'];
    $long_name        = $row['long_name'];
    $available        = $row['available'];
    $style            = $row['style'];

    // Nicer available text
    if ($available == 'Y')
    {
        $available_nice = '<font color="green"><b>Yes</b></font>';
    }
    else
    {
        $available_nice = '<font color="red"><b>No</b></font>';
    }
?>
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center"><input type="checkbox" name="delete[]" id="delete[]" value="<?php echo $id; ?>"></td>
  <td align="center" valign="middle"><font color="#000000">
  <?php
  // Show icon for the game.  If no icon exists, use the 'unsupported' icon
  $icon_loc = 'images/servers/small/' . $short_name . '.png';
  
  if(file_exists($icon_loc))
  {
      echo '<img src="' . $icon_loc . '" border="0" width="28" height="28" />';
  }
  ?>
  </td>
  <td align="left"><font color="#000000">&nbsp;<b><?php echo $long_name; ?></b></font></td>
  <td align="left"><font color="#000000">&nbsp;<?php echo $short_name; ?></font></td>
  <?php
  // Only show 'style' if this is a game
  if($url_server_type == 'game')
  {
      echo '<td align="center"><font color="#000000">' . $style . '</font></td>';
  }
  ?>
  <td align="center"><font color="#000000"><?php echo $available_nice; ?></font></td>
  <td align="center"><input type="button" value="Edit" onclick="window.location='SupportedServerEdit.php?id=<?php echo $id; ?>'"></td>
</tr>
<?php } ?>
</table>

<br /><br />

<center>
<input type="submit" name="delete_rows" value="Delete Selected">
<input type="hidden" name="short_name" value="<?php echo $short_name; ?>">
</center>
</form>

</body>
</html>

<?php
// Form handler for emailing passwords/keys
}
elseif (isset($_POST['delete_rows']))
{

// Setup deletion of the selected rows
$delete   = $_REQUEST['delete'];
$delList = implode(",", $delete);

// Make sure no fields were left blank
if (empty($delete))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<?php include("include/PopupInfo.html"); ?>

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Supported <?php echo ucwords($url_server_type); ?> Servers</font></td>
</tr>
</table>

<br /><br />

<center>
<b>Error:</b> You didn't select any rows!<br /><br />
Please go <a href="SupportedServers.php"><b>back</b></a> and try again.
</center>

</body>
</html>
<?php

// Kill the page
exit(0);
}

// Connect to the DB
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> Failed to connect to the database!</center>');
@mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> Failed to select the database!</center>');

// Delete the selected row(s)
@mysql_query("DELETE FROM servers WHERE id IN ($delList)") or die("<b>Error:</b> Failed to delete rows '$delList' from the servers table!");

?>
<html>
<head>
<title>GamePanelX</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<?php include("include/PopupInfo.html"); ?>

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
<th align="center" valign="middle">Supported <?php echo ucwords($url_server_type); ?> Servers</th>
</tr>
</table>

<br /><br />

<center>
The selected rows have been deleted successfully.

<br /><br />

<a href="SupportedServers.php"><b>Click Here</b></a> to return to Supported <?php echo ucwords($url_server_type); ?> Servers
</center>

<?php } ?>
