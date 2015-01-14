<?php
/*

GamePanelX

Description:  Edit game templates

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once("include/config.php"); 
include_once("include/auth.php");
include_once("include/statusInfo.php");
include_once("include/SqlCon.php");

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row[is_admin]!="Y")
{
    include("Unauthorized.php");
    exit(0);
}
    
// Use URL to grab the ID
$idURL = $_GET['id'];

// Make sure of no funny business
if (empty($idURL) && !isset($_POST['update']))
{
  die('<b>Error:</b> Please don\'t play with the URL.  Exiting.');
}

// Update button
if (!isset($_POST['update']))
{
?>
<html>
<head>
<title>GamePanelX</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<?php include('include/PopupInfo.html'); ?>

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Edit Template</span></td>
</tr>
</table>

<br /><br />
<center>Your are viewing ID <b>#<?php echo $idURL; ?></b></center>
<br /><br />


<?php
// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get info for this template
$result = @mysql_query("SELECT templates.id,templates.available,templates.is_default,templates.description,templates.file_path,templates.ip,servers.short_name,servers.long_name FROM templates LEFT JOIN servers ON templates.server = servers.short_name WHERE templates.id='$idURL' ORDER BY templates.server") or die('<b>Error:</b> Failed to query the templates table!');

while ($row = mysql_fetch_array($result))
{
    // Include alternating row colors
    include('include/colors.php');

    $id           = $row['id'];
    $available    = $row['available'];
    $is_default   = $row['is_default'];
    $description  = $row['description'];
    $file_path    = $row['file_path'];
    $ip           = $row['ip'];
    $short_name   = $row['short_name'];
    $long_name    = $row['long_name'];

    
}
?>


<form action="<?php echo $PHP_SELF; ?>" method="post">
<table border="0" class="tablez" width="400" cellpadding="2" cellspacing="0" align="center">

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="left">ID:</td>
  <td align="left"><?php echo $id; ?></td>
</tr>
<tr class="rowz_title">
  <td align="left">Server:</td>
  <td align="left"><?php echo $long_name; ?></td>
</tr>
<tr class="rowz_title">
  <td align="left">Available:</td>
  <td align="left">
<?php
// Available Templates
if($available == 'Y')
{
    echo '<select name="available">';
    echo '  <option value="Y" selected>Yes</option>';
    echo '  <option value="N">No</option>';
    echo '</select>';
}
else
{
    echo '<select name="available">';
    echo '  <option value="Y">Yes</option>';
    echo '  <option value="N" selected>No</option>';
    echo '</select>';
}
?>
  </td>
</tr>
<tr class="rowz_title">
  <td align="left">Default:</td>
  <td align="left">
<?php
// Default Templates
if($is_default == 'Y')
{
    echo '<select name="is_default">';
    echo '  <option value="Y" selected>Yes</option>';
    echo '  <option value="N">No</option>';
    echo '</select>';
}
else
{
    echo '<select name="is_default">';
    echo '  <option value="Y">Yes</option>';
    echo '  <option value="N" selected>No</option>';
    echo '</select>';
}
?>
  </td>
</tr>
<tr class="rowz_title">
  <td align="left">Description:</td>
  <td align="left"><input type="text" name="description" id="description" value="<?php echo $description; ?>" class="userinput" style="width:100%"></td>
</tr>
<tr class="rowz_title">
  <td align="left">File Path:</td>
  <td align="left"><input type="text" name="file_path" id="file_path" value="<?php echo $file_path; ?>" class="userinput" style="width:100%"></td>
</tr>
<tr class="rowz_title">
  <td align="left">IP Address:</td>
  <td align="left"><input type="text" name="ip" id="ip" value="<?php echo $ip; ?>" class="userinput" style="width:100%"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>
  
<tr class="rowz_title">
  <td align="center" colspan="2"><b>Warning:</b> If you modify the file path, this server may not be able to create game servers on the remote server.<br /><br />
<input type="submit" name="update" value="Update" style="width:170px">&nbsp;&nbsp;
<input type="hidden" name="getid" value="<?php echo $id; ?>">
<input type="hidden" name="server" value="<?php echo $short_name; ?>">
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>
</table>
</form>

</body>
</html>
<?php
}
elseif (isset($_POST['update']))
{
    include_once('include/config.php');
    
    // Get the 'id' from POST values
    $getID = $_POST['getid'];

    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

    // POST Values
    $post_server        = $_POST['server'];
    $post_available     = mysql_real_escape_string($_POST['available']);
    $post_default       = mysql_real_escape_string($_POST['is_default']);
    $post_description   = mysql_real_escape_string($_POST['description']);
    $post_file_path     = mysql_real_escape_string($_POST['file_path']);
    $post_ip_addr       = mysql_real_escape_string($_POST['ip']);
    
    // If they chose 'default', set all other templates for this server to not default
    if($post_default == 'Y')
    {
        @mysql_query("UPDATE templates SET is_default='N' WHERE server='$post_server'") or die('<b>Error:</b> Failed to query the templates table!');
    }
    
    
    // Update everything
    @mysql_query("UPDATE templates SET available='$post_available',is_default='$post_default',description='$post_description',file_path='$post_file_path',ip='$post_ip_addr' WHERE id='$getID'") or die('<b>Error:</b> Failed to query the templates table!');
    ?>
    <html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
    </head>

    <body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

    <?php include('include/PopupInfo.html'); ?>

    <div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
    <script src="include/loading.js"></script>

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
    <tr>
      <td align="center" valign="middle"><span class="top_page_titles">Edit Template</span></td>
    </tr>
    </table>

    <br /><br />
    
    <center>
    Your Template has been successfully updated!

    <br /><br />

    <a href="TemplateEdit.php?id=<?php echo $getID; ?>"><b>Click Here</b></a> to return
    </center>

<?php
}
?>