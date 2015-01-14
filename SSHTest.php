<?php
/*

GamePanelX

Description:  Update configuration

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/infobox.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}

if(!isset($_POST['submit']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<script type="text/javascript">
function checkReq()
{
    if(document.sshTest.ip.value == "" || document.sshTest.ip.value == "none")
    {
        alert('You must select a Remote Server!');
        return false;
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
  <td align="center" valign="middle"><span class="top_page_titles">Test SSH Connection</span></td>
</tr>
</table>

<br /><br />

<form method="post" name="sshTest" action="<?php echo $PHP_SELF; ?>" onSubmit="return checkReq()">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Select a server</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/cmd-line.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Physical Server:</span>&nbsp;&nbsp;</td>
  <td align="left">
    <select name="ip">
<?php
// List available physical servers
require('include/functions.php');

$type = 'physical';
list_available_ips($type);
?>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><input type="submit" name="submit" value="Confirm" style="width:170px"></td>
</tr>

</table>
</form>

</body>
</html>
<?php
}

elseif(isset($_POST['submit']))
{
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
  <td align="center" valign="middle"><span class="top_page_titles">Test SSH Connection</span></td>
</tr>
</table>

<br /><br />
<?php
    // Make sure IP wasn't empty
    if(empty($_POST['ip']))
    {
        die('<center><b>Error:</b> IP Address was left empty!</center>');
    }
    
    // Encode IP Address
    $encoded_ip = base64_encode($_POST['ip']);
?>
<form method="post" action="include/runcmd.php">
<input type="hidden" name="previous_page" value="SSHTest.php">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Confirm Server</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Testing on IP: </span></td>
  <td align="left"><b><?php echo $_POST['ip']; ?></b></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><input type="submit" name="test_ssh_connection" value="Test Connection" style="width:170px"></td>
</tr>

</table>
<input type="hidden" name="ip" value="<?php echo $encoded_ip; ?>">
<input type="hidden" name="previous_page" value="SSHTest.php">
</form>

<?php
}

?>
