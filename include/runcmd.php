<?php
/*

GamePanelX

Description:  Form handler to SSH into remote servers and perform commands

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('config.php');
include_once('auth.php');
include_once('typeInfo.php');
include_once('ssh2.php');
include_once('functions.php');

// Define all submit options here
$action_restart         = $_POST['restart'];              // From: 'ServerManager.php'
$action_stop            = $_POST['stop'];                 // From: 'ServerManager.php'
$action_button          = $_POST['action_button'];        // From: 'ServerManager.php'
$create_server          = $_POST['create_server'];        // From: 'CreateServer.php'
$create_template        = $_POST['create_template'];      // From: 'CreateTemplate.php'
$delete_template        = $_POST['delete_template'];      // From: 'Templates.php'
$test_ssh_connection    = $_POST['test_ssh_connection'];  // From: 'SSHTest.php'

// Use POST for optional values
$allow_output           = $_POST['allow_output'];
$previous_page          = $_POST['previous_page'];

// Make sure the user chose an action
if (!isset($action_button) && !isset($create_server) && !isset($create_template) && !isset($delete_template) && !isset($test_ssh_connection))
{
    die('<b>Error:</b> SSH action not chosen!  Please report this as a bug with the page you were coming from.');
}

// Decode POST variables
$decoded_ip = base64_decode($_POST['ip']);


// Make sure POST IP isn't empty
if(empty($decoded_ip))
{
    die('<center><b>Error:</b> <i>runcmd.php:</i> No IP Address was given on the previous page!  Check your Remote Server configuration and try again.</center>');
}

// Generate random text
$style = 'normal';
$randomNumber = generateRandomText($style);

// Get SSH Key from config
$ssh_key = $config['encrypt_key'];





//
// Get the parent IP for the given POST IP.
// (if it's already a parent, use it.  If not, use the parent's SSH info)
//


// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die("<b>Error:</b> Failed to connect to the database!");
@mysql_select_db($config['sql_db']) or die("<b>Error:</b> Failed to select the database!");

// If this is a parent, use this IP.  If not, use it's parent IP.
$query_ip = "SELECT ip, parent
              FROM remote
              WHERE (
              ip = '$decoded_ip'
              AND physical = 'Y'
              AND parent != '$decoded_ip'
              )
              OR (
              ip = '$decoded_ip'
              AND physical != 'Y'
              AND parent != ''
              )";
$result_ip = @mysql_query($query_ip) or die("<b>Error:</b> Failed to query the remote table!");

while($row_ip = mysql_fetch_array($result_ip))
{
    $this_ip      = $row_ip['ip'];
    $this_parent  = $row_ip['parent'];
}

// If there is a parent, use this.  If not, use the IP.
if(!empty($this_parent))
{
    $parent_server  = $this_parent;
}
else
{
    $parent_server  = $this_ip;
}



//
// Get SSH Connection details
//
$result_ssh  = @mysql_query("SELECT id,ip,ssh_port,AES_DECRYPT(ssh_user, '$ssh_key') AS ssh_user,AES_DECRYPT(ssh_pass, '$ssh_key') AS ssh_pass FROM remote WHERE ip='$parent_server'") or die('<b>Error:</b> Failed to get SSH details from the remote table!');

while ($row_ssh = mysql_fetch_array($result_ssh))
{
    $ssh_id     = $row_ssh['id'];
    $ssh_ip     = $row_ssh['ip'];
    $ssh_port   = $row_ssh['ssh_port'];
    $ssh_user   = $row_ssh['ssh_user'];
    $ssh_pass   = $row_ssh['ssh_pass'];
}


// For other pages' sake
$ipAddress = $ssh_ip;


mysql_close($db);

// ----------------------------------------------------------------------------------------------------




// Server status - restart/stop
if (isset($action_button))
{
    require("ssh/status.php");
}

// Create server
elseif (isset($create_server))
{
    require("ssh/create_server.php");
}

// Create template
elseif (isset($create_template))
{
    require("ssh/create_template.php");
}

// Delete template
elseif(isset($delete_template))
{
    require("ssh/delete_template.php");
}

// Test SSH Connection
elseif(isset($test_ssh_connection))
{
    require("ssh/test_connection.php");
}

// Otherwise
else
{
    die('<center><b>Error:</b> <i>runcmd.php:</i> No action given!  Please report this as a bug with the page you were coming from.</center>');
}





//
// Output results:
//
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Server Results</span></td>
</tr>
</table>

<br /><br />

<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="../css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Server Output</span></td>
</tr>

<tr class="rowz_title">
  <td align="center" valign="middle"><b><u>Result:</u></b></td>
</tr>

<tr class="rowz_title">
  <td>&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center">
    <span class="rowz_alt">
<?php
// Give extra output for certain things
if (isset($create_template))
{
    echo "<br /><br /><font color=\"red\"><b>Note:</b></font> This process can take 5-10 minutes<br /><br />";
}


// Check if the SSH username/pass/port were empty
if(empty($ssh_user))
{
    die('<center><b>Error:</b> <i>runcmd.php:</i> Unable to obtain the SSH Username from the given IP Address!  Check that your Remote Servers are setup properly, and try again.</center>');
}
elseif(empty($ssh_pass) || empty($ssh_port))
{
    die('<center><b>Error:</b> <i>runcmd.php:</i> Unable to obtain the SSH details (password/port) from the given IP Address!  Check that your Remote Servers are setup properly, and try again.</center>');
}


//
// Connect to slave server and run SSH2 command
//
connect_ssh($ipAddress, $ssh_port, $ssh_user, $ssh_pass, $command, $allow_output, $ssh_timeout);


// Previous page
$previous_page = $config['BASE_DIR'] . '/' . $_POST['previous_page'];
?>
    </span>
  </td>
</tr>

<tr class="rowz_title">
  <td>&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center"><input type="button" name="back" id="back" value="Return to the previous page" onClick="javascript:window.location='<?php echo $previous_page; ?>'"></td>
</tr>
</table>

</body>
</html>
