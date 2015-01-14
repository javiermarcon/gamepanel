<?php
/*

GamePanelX

Description:  Create a new remote server

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);


//
// Check if administrator
//
if($row['is_admin'] != 'Y')
{
    require('Unauthorized.php');
    exit;
}
  

if(!isset($_POST['submit']))
{
?>
<html>
<head>
<title><?php echo $config['title']; ?> | New Remote Server Setup</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
<script type="text/javascript">
// See if the checkbox is enabled
function verify_checked()
{
    if (document.remoteForm.physical.checked)
    {
        // Enable all Physical Server options
        document.remoteForm.ssh_username.disabled = false;
        document.remoteForm.ssh_password.disabled = false;
        document.remoteForm.ssh_port.disabled     = false;

        document.remoteForm.ssh_username.value    = "";
        document.remoteForm.ssh_password.value    = "";
        document.remoteForm.ssh_port.value        = "";
    }

    else
    {
        // Disable all Physical Server options
        document.remoteForm.ssh_username.disabled = true;
        document.remoteForm.ssh_password.disabled = true;
        document.remoteForm.ssh_port.disabled     = true;

        document.remoteForm.ssh_username.value    = "(disabled)";
        document.remoteForm.ssh_password.value    = "";
        document.remoteForm.ssh_port.value        = "(disabled)";
    }
}

function checkReq()
{
    // IP Address
    if(document.remoteForm.ip_address.value == "")
    {
        alert('Please enter an IP Address');
        return false;
    }
    
    // Physical
    if(document.remoteForm.physical.checked == true)
    {
        // Make sure SSH details were filled out
        if(document.remoteForm.ssh_username.value == "")
        {
            alert('You left the SSH Username empty!');
            return false;
        }
        if(document.remoteForm.ssh_password.value == "")
        {
            alert('You left the SSH Password empty!');
            return false;
        }
        if(document.remoteForm.ssh_port.value == "")
        {
            alert('You left the SSH Port empty!');
            return false;
        }
    }
    else
    {
        // Check if a parent was selected
        if(document.remoteForm.parent_servers.value == "")
        {
            alert('You must select a parent server!');
            return false;
        }
    }

    return true;
}
</script>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">New Remote Server</span></td>
</tr>
</table>

<br /><br />

<table width="400" cellpadding="0" cellspacing="0" class="tablez" style="border:1px solid black" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Server Details</span></td>
</tr>

<form method="post" action="<?php echo $PHP_SELF; ?>" name="remoteForm" onSubmit="return checkReq()">

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/server-64.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right">&nbsp;<b>IP Address:&nbsp;&nbsp;</b></td>
  <td align="left"><input type="text" name="ip_address" id="ip_address" maxlength="20" style="width:150px" class="userinput" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right">&nbsp;<b>Operating System:&nbsp;&nbsp;</b></td>
  <td align="left"><input type="text" name="os" style="width:150px;" maxlength="30" value="Linux" class="userinput"></td>
</tr>

<tr class="rowz_title">
  <td align="right">&nbsp;<b>Location:&nbsp;&nbsp;</b></td>
  <td align="left"><input type="text" name="location" style="width:150px;" maxlength="30" value="<?php echo $_POST['location']; ?>" class="userinput"></td>
</tr>

<tr class="rowz_title">
  <td align="right">&nbsp;<b>Data Center:&nbsp;&nbsp;</b></td>
  <td align="left"><input type="text" name="datacenter" style="width:150px;" maxlength="30" value="<?php echo $_POST['datacenter']; ?>" class="userinput"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Physical Server Settings</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="checkbox" name="physical" id="physical" value="1" onclick="verify_checked()">&nbsp;<label for="physical">This is a Physical Server</label></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><b>SSH Username:</b>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="ssh_username" id="ssh_username" value="(disabled)" disabled="true" class="userinput"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><b>SSH Password:</b>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="ssh_password" id="ssh_password" value="" disabled="true" class="userinput"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><b>SSH Port:</b>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="ssh_port" id="ssh_port" value="(disabled)" disabled="true" class="userinput"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Standard Server Settings</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><b>Parent Server:&nbsp;&nbsp;</b></td>
  <td align="left">
    <select name="parent_servers" id="parent_servers">
      <option value="">Select a Parent Server</option>

<?php
// Get all parent servers
require('include/functions.php');

$type = 'physical';
list_available_ips($type);
?>

    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Other Options</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><input type="checkbox" name="available" id="available" value="1" checked>&nbsp;&nbsp;</td>
  <td align="left"><label for="available">This server is available for use</label></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="submit" value="Submit" name="submit" id="submit" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

</table>
</form>

</body>
</html>
<?
}

// Update values
elseif(isset($_POST['submit']))
{
?>
    <html>
    <head>
    <title><?php echo $config['title']; ?> | New Remote Server Setup</title>
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
      <td align="center" valign="middle"><span class="top_page_titles">New Remote Server</span></td>
    </tr>
    </table>

    <br /><br />
    <?php
    //Declare nicer variables
    $available          = $_POST['available'];
    $operating_system   = $_POST['os'];
    $location           = $_POST['location'];
    $datacenter         = $_POST['datacenter'];
    $physical           = $_POST['physical'];
    $parent_server      = $_POST['parent_servers'];

    // If 'physical' is set, these options follow
    $ssh_user           = $_POST['ssh_username'];
    $ssh_pass           = $_POST['ssh_password'];
    $ssh_port           = $_POST['ssh_port'];
    
    // SSH Encryption Key
    $ssh_key            = $config['encrypt_key'];

    //Prepare "physical" for insert
    if ($physical)
    {
        $is_physical = "Y";
    }
    else
    {
        $is_physical = "N";
    }

    //Prepare "available" for insert
    if ($available)
    {
        $is_available = "Y";
    }
    else
    {
        $is_available = "N";
    }

    //If required fields are blank...
    if(empty($_POST['ip_address']))
    {
    ?>
    <html>
    <head>
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
      <td align="center" valign="middle"><span class="top_page_titles">New Remote Server</span></td>
    </tr>
    </table>

    <br /><br />

    <b>Error:</b> You didn't enter all the required fields!<br /><br />
    Please go <a href="RemoteServerNew.php"><b>back</b></a> and try again.

    </body>
    </html>
    <?
    exit(0);
    }

    ####################################################################
    
    //
    // Create Remote Server/IP
    //
    $ip_address = $_POST['ip_address'];
    
    // Create Remote Server
    require_once('include/functions.php');
    if(!create_remote_server($ip_address,$is_available,$is_physical,$parent_server,$operating_system,$location,$datacenter,$ssh_user,$ssh_pass,$ssh_port))
    {
        die('<b>Error:</b> Remote Server creation failed!');
    }
?>

<center>
<b>Success!</b>
<br /><br />
<a href="RemoteServerManager.php">Remote Server Manager</a>
</center>

</body>
</html>
<?php
}
?>
