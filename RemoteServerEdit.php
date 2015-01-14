<?php
/*

GamePanelX

Description:  Edit remote SSH servers

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
<title><?php echo $config['title']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Remote Server Edit</span></td>
</tr>
</table>

<br /><br />

<center>Your are viewing ID <b>#<?php echo $idURL; ?></b></center>

<br /><br />

<form action="<?php echo $PHP_SELF; ?>" method="post">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Remote Server Settings</span></td>
</tr>

<?php
// Connect to the DB
$db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
mysql_select_db($config['sql_db']) or die(mysql_error());

// Query 'remote' table
$result_remote = mysql_query("SELECT id,ip,available,physical,parent,operating_system,location,datacenter,ssh_port FROM remote WHERE id='$idURL'") or die(mysql_error());

while ($row_remote = mysql_fetch_array($result_remote))
{
    // Include alternating row colors
    include("include/colors.php");

    $id                 = $row_remote['id'];
    $ip_address         = $row_remote['ip'];
    $available          = $row_remote['available'];
    $is_physical        = $row_remote['physical'];
    $parent             = $row_remote['parent'];
    $operating_system   = $row_remote['operating_system'];
    $location           = $row_remote['location'];
    $datacenter         = $row_remote['datacenter'];
    $ssh_port           = $row_remote['ssh_port'];
}

// Get SSH Key from config
$ssh_key = $config['encrypt_key'];

// If physical server, get SSH user and password
if($is_physical == 'Y')
{
    // SSH2 Username
    $result_user  = mysql_query("SELECT AES_DECRYPT(ssh_user, '$ssh_key') AS decrypted_user FROM remote WHERE id='$id'") or die(mysql_error());

    while ($row_user = mysql_fetch_array($result_user))
    {
        $ssh_user = $row_user['decrypted_user'];
    }

    // SSH2 Password
    $result_pass  = mysql_query("SELECT AES_DECRYPT(ssh_pass, '$ssh_key') AS decrypted_pass FROM remote WHERE id='$id'") or die(mysql_error());

    while ($row_pass = mysql_fetch_array($result_pass))
    {
        $ssh_pass = $row_pass['decrypted_pass'];
    }
}
mysql_close($db);
?>
    
    <tr class="rowz_title">
      <td colspan="2">&nbsp;</td>
    </tr>

    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Physical Server:</span>&nbsp;&nbsp;</td>
      <td align="left"><?php if($is_physical == 'Y') echo 'Yes'; else echo 'No'; ?></td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Available:</span>&nbsp;&nbsp;</td>
        <td align="left">
          <select name="available" class="dropdown" style="width:100px">
          <?php
          // Display available or not
          if($available == 'Y')
          {
              echo '  <option value="Y" selected>Yes</option>';
              echo '  <option value="N">No</option>';
          }
          else
          {
              echo '  <option value="Y">Yes</option>';
              echo '  <option value="N" selected>No</option>';
          }
          ?>
          </select>
        </td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">IP Address:</span>&nbsp;&nbsp;</td>
      <td align="left"><input type="text" value="<?php echo $ip_address; ?>" name="ip" class="userinput" style="width:170px"></td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Parent Server:</span>&nbsp;&nbsp;</td>
      <td align="left"><?php echo $parent; ?></td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Operating System:</span>&nbsp;&nbsp;</td>
      <td align="left"><input type="text" value="<?php echo $operating_system; ?>" name="os" class="userinput" style="width:170px"></td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Location:</span>&nbsp;&nbsp;</td>
      <td align="left"><input type="text" value="<?php echo $location; ?>" name="location" class="userinput" style="width:170px"></td>
    </tr>
    <tr class="rowz_title">
      <td align="right"><span class="rowz_alt">Datacenter:</span>&nbsp;&nbsp;</td>
      <td align="left"><input type="text" value="<?php echo $datacenter; ?>" name="datacenter" class="userinput" style="width:170px"></td>
    </tr>
    
    <?php
    //
    // SSH2 Options
    //
    if($is_physical == 'Y')
    {
?>
        <tr class="rowz_title">
          <td colspan="2">&nbsp;</td>
        </tr>

        <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
          <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">SSH Settings</span></td>
        </tr>

        <tr class="rowz_title">
          <td colspan="2">&nbsp;</td>
        </tr>
        
        <tr class="rowz_title">
          <td align="right"><span class="rowz_alt">SSH Username:</span>&nbsp;&nbsp;</td>
          <td align="left"><input type="text" value="<?php echo $ssh_user; ?>" name="ssh_user" class="userinput" style="width:170px"></td>
        </tr>
        <tr class="rowz_title">
          <td align="right"><span class="rowz_alt">SSH Password:</span>&nbsp;&nbsp;</td>
          <td align="left"><input type="text" value="<?php echo $ssh_pass; ?>" name="ssh_pass" class="userinput" style="width:170px"></td>
        </tr>
        <tr class="rowz_title">
          <td align="right"><span class="rowz_alt">SSH Port:</span>&nbsp;&nbsp;</td>
          <td align="left"><input type="text" value="<?php echo $ssh_port; ?>" name="ssh_port" class="userinput" style="width:170px"></td>
        </tr>
        
        <tr class="rowz_title">
          <td colspan="2">&nbsp;</td>
        </tr>
<?php
    }
?>
  <tr class="rowz_title">
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

<br /><br />

<center><input type="submit" name="update" value="Update" style="width:170px"></center>
<input type="hidden" name="getid" value="<?php echo $id; ?>">
<input type="hidden" name="is_physical" value="<?php echo $is_physical; ?>">
</form>

</body>
</html>
<?php
}
elseif (isset($_POST['update']))
{
    include('include/config.php');
    
    // Get the 'id' from POST values since we're on a new page now
    $getID = $_POST['getid'];

    // POST Variables
    $post_ip          = $_POST['ip'];
    $post_available   = $_POST['available'];
    $post_os          = $_POST['os'];
    $post_location    = $_POST['location'];
    $post_datacenter  = $_POST['datacenter'];

    // Connect to the database
    $db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
    mysql_select_db($config['sql_db']) or die(mysql_error());
    
    // If physical server, update SSH user and password
    if($_POST['is_physical'] == 'Y')
    {
        // POST SSH settings
        $post_username    = $_POST['ssh_user'];
        $post_password    = $_POST['ssh_pass'];
        $post_port        = $_POST['ssh_port'];
        
        // Get SSH Key from config
        $ssh_key = $config['encrypt_key'];

        // Update SSH Username
        @mysql_query("UPDATE remote SET ssh_user = AES_ENCRYPT('$post_username', '$ssh_key') WHERE id='$getID'") or die('<b>Error:</b> Failed to update SSH Username!');

        // Update SSH Password
        @mysql_query("UPDATE remote SET ssh_pass = AES_ENCRYPT('$post_password', '$ssh_key') WHERE id='$getID'") or die('<b>Error:</b> Failed to update SSH Password!');
        
        // Update SSH Port
        @mysql_query("UPDATE remote SET ssh_port='$post_port' WHERE id='$getID'") or die('<b>Error:</b> Failed to update SSH Port!');
    }

    // Update all other values
    @mysql_query("UPDATE remote SET ip='$post_ip',available='$post_available',operating_system='$post_os',location='$post_location',datacenter='$post_datacenter' WHERE id='$getID'") or die('<b>Error:</b> Failed to update settings!');
?>
<html>
<head>
<title><?php echo $config['title']; ?></title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Remote Server Edit</span></td>
</tr>
</table>

<br /><br />

<center>
<b>Success!</b>
<br /><br />
<a href="RemoteServerEdit.php?id=<?php echo $getID; ?>"><b>Click to go back</a>
</center>

<?php
}
?>
