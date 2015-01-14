<?php
/*

GamePanelX

Description:  Manage Game/Voip Templates

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
  <td align="center" valign="middle"><span class="top_page_titles"><?php echo ucwords($url_server_type); ?> Templates</span></td>
</tr>
</table>

<br /><br />

<form action="Templates.php?type=<?php echo $url_server_type; ?>" name="templates" method="post">
<table border="0" class="tablez" width="600" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td align="center" width="30"><span class="top_titles">#</span></td>
  <td align="left" width="40"><span class="top_titles">&nbsp;</span></td>
  <td align="left" width="200"><span class="top_titles">Server Name</span></td>
  <td align="left" width="160"><span class="top_titles">Description</span></td>
  <td align="center" width="60"><span class="top_titles">Default</span></td>
  <td align="center" width="60"><span class="top_titles">Available</span></td>
  <td align="center" width="60"><span class="top_titles">Action</span></td>
</tr>

<?php
// Grab all game restores from db
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

$result_templates = @mysql_query("SELECT templates.id,templates.available,templates.is_default,templates.description,templates.ip,servers.short_name,servers.long_name FROM templates LEFT JOIN servers ON templates.server = servers.short_name WHERE templates.type='$url_server_type' ORDER BY templates.server ASC") or die('<b>Error:</b> Failed to query the templates table!');
$num_templates    = mysql_num_rows($result_templates);

// Include alternating row colors
include('include/colors.php');

// Output for no rows
if($num_templates == 0)
{
?>
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center" colspan="12"><span class="rowz_alt">No results to display</span></td>
</tr>
<?php
}

while($row_templates = mysql_fetch_array($result_templates))
{
    // Include alternating row colors
    include('include/colors.php');

    // `templates` table
    $id           = $row_templates['id'];
    $type         = $row_templates['type'];
    $available    = $row_templates['available'];
    $is_default   = $row_templates['is_default'];
    $desc         = $row_templates['description'];
    $ip           = $row_templates['ip'];
    
    // `servers` table
    $short_name   = $row_templates['short_name'];
    $long_name    = $row_templates['long_name'];
?>
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center"><span class="rowz_alt"><input name="delete[]" id="delete[]" type="checkbox" value="<?php echo $id; ?>"></td>
  <td align="left"><span class="rowz_alt"><img src="images/servers/small/<?php echo $short_name; ?>.png" border="0" /></span></td>
  <td align="left"><span class="rowz_alt"><b><?php echo $long_name; ?></b></span></td>
  <td align="left"><span class="rowz_alt"><?php echo $desc; ?></span></td>
  <td align="center"><span class="rowz_alt">
<?php 
// Default templates
if ($is_default == 'Y')
{
    echo 'Yes';
}
elseif ($is_default == 'N')
{
    echo 'No';
}
// Otherwise
else
{
    echo '<i>Unset</i>';
}
?>
</span></td>
  <td align="center"><span class="rowz_alt">
<?php 
// Available templates
if ($available == 'Y')
{
    echo 'Yes';
}
elseif ($available == 'N')
{
    echo 'No';
}
// Otherwise
else
{
    echo '<i>Unset</i>';
}
?>
  </span></td>
  <td align="center" valign="middle"><input type="button" name="edit" id="edit" value="Edit" style="width:100%" onClick="window.location.href='TemplateEdit.php?id=<?php echo $id; ?>'"></td>
<?php } ?>
</table>

<br /><br />

<center>
<input name="submit" type="submit" id="submit" value="Delete Game Template">
</center>
</form>

</body>
</html>
<?php
}



//
// Second Page
//
elseif (isset($_POST['submit']))
{
$checkDelete = $_REQUEST['delete'];
$delList = implode(",", $checkDelete);

//If nothing was selected...
if (empty($delList))
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
  <td align="center" valign="middle"><span class="top_page_titles"><?php echo ucwords($url_server_type); ?> Templates</span></td>
</tr>
</table>

<br /><br />

<center>
<b>Error:</b> No <?php echo ucwords($url_server_type); ?> Templates were selected!<br /><br />
Click <a href="Templates.php?type=<?php echo $url_server_type; ?>">here</a> to go back.
</center>

</body>
</html>
  <?php
}

// Otherwise
else
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
  <td align="center" valign="middle"><span class="top_page_titles"><?php echo ucwords($url_server_type); ?> Templates - Edit Rows</span></td>
</tr>
</table>

<br /><br />

<center>
<?php
// Output
echo 'Row(s) <b>' . $delList . '</b> will be deleted.<br /><br />';

// Connect to sql and get the file path for this ID
$query = "SELECT ip,file_path FROM templates WHERE id IN($delList)";
sqlCon($query);

$file_path  = $row['file_path'];
$ip         = $row['ip'];

// Encode the file path in base64
$encoded_file_path  = base64_encode($file_path);
$encoded_ip         = base64_encode($ip);
$encoded_del_list   = base64_encode($delList);
?>
Click below to confirm deletion of the template file(s) from the database and remote server.<br />

<br /><br />

<form action="include/runcmd.php" method="post">
<input type="hidden" name="file_path" id="file_path" value="<?php echo $encoded_file_path; ?>">
<input type="hidden" name="ip" id="ip" value="<?php echo $encoded_ip; ?>">
<input type="hidden" name="del_list" id="del_list" value="<?php echo $encoded_del_list; ?>">
<input type="hidden" name="previous_page" value="Templates.php?type=<?php echo $url_server_type; ?>">

<input type="submit" name="delete_template" id="delete_template" value="Delete Template">
</form>

<?php
    }
  }
?>
