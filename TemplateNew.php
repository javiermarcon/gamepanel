<?php
/*

GamePanelX

Description:  Create a game template

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/statusInfo.php');
include_once('include/SqlCon.php');
include_once('include/functions.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include("Unauthorized.php");
    exit(0);
}


// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
}


if (!isset($_POST['submit']))
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
  <td align="center" valign="middle"><span class="top_page_titles">Create <?php echo ucwords($url_server_type); ?> Template</span></td>
</tr>
</table>

<br /><br />

<form action="TemplateNew.php?type=<?php echo $url_server_type; ?>" method="post">
<table border="0" class="tablez" width="500" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">New <?php echo ucwords($url_server_type); ?> Template</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/template_<?php echo $url_server_type; ?>.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Create for server:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="server" style="width:200px">
      <option value="" selected="selected">Select a server</option>
<?php
// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Create a list of all available games
$result_gm = @mysql_query("SELECT short_name,long_name FROM servers WHERE type='$url_server_type' AND available='Y' ORDER BY short_name ASC") or die('<b>Error:</b> Failed to query the servers table!');

while($row_gm = mysql_fetch_array($result_gm))
{
    $short_name = $row_gm['short_name'];
    $long_name  = $row_gm['long_name'];

    echo '<option value="' . $short_name . '">' . $long_name . '</option>';
}
?>

</select>
  </td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Physical Server:&nbsp;&nbsp;</span></td>
  <td align="left">
  <select name="ip" style="width:170px">
    <option value="" selected="selected">Select an IP Address</option>
<?php
$type = "physical";
list_available_ips($type);
?>
  </select>
</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Full path to template directory:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="file_path" value="" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Description:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="description" value="" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default for this game:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="is_default">
      <option value="Y" selected>Yes</option>
      <option value="N">No</option>
    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Available for use:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="available">
      <option value="Y" selected>Yes</option>
      <option value="N">No</option>
    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><input type="submit" name="submit" value="Continue" style="width:170px"></td>
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
  <td align="center" valign="middle"><span class="top_page_titles">Create <?php echo ucwords($url_server_type); ?> Templates</span></td>
</tr>
</table>

<br /><br />

<form action="include/runcmd.php" method="post">
<table border="1" class="tablez" width="400" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">New Game Template</span></td>
</tr>

<tr class="rowz_title">
  <td width="150" align="center"><b><u>Begin Setup Process:</u></b><br /><br />
<?php
// Check for empty fields
if(empty($_POST['file_path']) || empty($_POST['description']) || empty($_POST['ip']) || empty($_POST['server']))
{
    die('<b>Error:</b> You left a required field blank!');
}


include_once('include/functions.php');

// Make a random nickname for tar file's name
$style = "normal";
$addonText = generateRandomText($style);

// Encode all POST variables in base64
$encoded_server     = base64_encode($_POST['server']);
$encoded_file_path  = base64_encode($_POST['file_path']);
$encoded_desc       = base64_encode($_POST['description']);
$encoded_type       = base64_encode($url_server_type);
$encoded_available  = base64_encode($_POST['available']);
$encoded_default    = base64_encode($_POST['is_default']);
$encoded_ip         = base64_encode($_POST['ip']);
$encoded_rand       = base64_encode($addonText);
?>
<input type="hidden" name="server" value="<?php echo $encoded_server; ?>">
<input type="hidden" name="file_path" value="<?php echo $encoded_file_path; ?>">
<input type="hidden" name="description" value="<?php echo $encoded_desc; ?>">
<input type="hidden" name="type" value="<?php echo $encoded_type; ?>">
<input type="hidden" name="available" value="<?php echo $encoded_available; ?>">
<input type="hidden" name="is_default" value="<?php echo $encoded_default; ?>">
<input type="hidden" name="ip" value="<?php echo $encoded_ip; ?>">
<input type="hidden" name="random_text" value="<?php echo $encoded_rand; ?>">
<input type="hidden" name="previous_page" value="TemplateNew.php?type=<?php echo $url_server_type; ?>">
<input type="hidden" name="allow_output" value="1">
<input type="hidden" name="skip_parent_check" value="1">
<br />
<input type="submit" name="create_template" value="Begin Setup">
<br /><br />
<b>Please Note:</b> This process can take up to 2 minutes to complete.<br /><br />
  </td>
</tr>
</table>
</form>

</body>
</html>
<?php } ?>
