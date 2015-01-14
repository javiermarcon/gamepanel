<?php
/*

GamePanelX

Description:  Create new server support

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

if(!isset($_POST['submit']))
{
  
// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
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
  <td align="center" valign="middle"><span class="top_page_titles">Create Supported <?php echo ucwords($url_server_type); ?> Server</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="CreateSupportedServer.php?type=<?php echo $url_server_type; ?>">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles"><?php echo ucwords($url_server_type); ?> Configuration</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center">
  <?php
  // Display correct image
  if($url_server_type == 'game')
  {
      echo '<img src="images/main/supported_games.png" border="0" />';
  }
  elseif($url_server_type == 'voip')
  {
      echo '<img src="images/main/supported_voip.png" border="0" />';
  }
  else
  {
      echo '<img src="images/main/supported_games.png" border="0" />';
  }
  ?>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Short Name: </span></td>
  <td align="left"><input type="text" value="" name="short_name" maxlength="12" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Long Name: </span></td>
  <td align="left"><input type="text" value="" name="long_name" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only show 'style' for games
if($url_server_type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Style: </span></td>
  <td align="left"><input type="text" value="" name="style" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Log File: </span></td>
  <td align="left"><input type="text" value="" name="log_file" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default Port: </span></td>
  <td align="left"><input type="text" value="" name="port" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Reserved Ports: </span></td>
  <td align="left"><input type="text" value="" name="reserved_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">TCP Ports: </span></td>
  <td align="left"><input type="text" value="" name="tcp_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">UDP Ports: </span></td>
  <td align="left"><input type="text" value="" name="udp_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Executable: </span></td>
  <td align="left"><input type="text" value="" name="executable" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Max Slots: </span></td>
  <td align="left"><input type="text" value="" name="max_slots" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only show 'map' for games
if($url_server_type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default Map: </span></td>
  <td align="left"><input type="text" value="" name="map" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Setup CMD Line: </span></td>
  <td align="left"><input type="text" value="" name="setup_cmd" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">CMD Line: </span></td>
  <td align="left"><input type="text" value="" name="cmd_line" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Working Directory: </span></td>
  <td align="left"><input type="text" value="" name="working_dir" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Setup Directory: </span></td>
  <td align="left"><input type="text" value="" name="setup_dir" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><input type="checkbox" name="available" id="available"> <label for="available">This is available for use</label></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>
</table>

<br />

<table border="0" style="border:1px solid black" cellpadding="2" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td align="left" width="20"><span class="top_titles">Description</span></td>
  <td align="left"><span class="top_titles">Value</span></td>
  <td align="center" width="50"><span class="top_titles">#</span></td>
  <td align="left" width="120"><span class="top_titles">&nbsp;</span></td>
</tr>

<?php
// Loop through all 10 config options
for($i=1; $i <= 10; $i++)
{
    // Option names
    $opt_name   = 'opt' . $i . '_name';
    $opt_edit   = 'opt' . $i . '_edit';
    $opt_value  = 'opt' . $i . '_value';

    echo '<tr class="rowz_title">' . "\n";
    echo '  <td align="left" width="20"><input type="text" value="" name="' . $opt_name . '" style="width:140px;text-align:right"></td>' . "\n";
    echo '  <td align="left"><input type="text" value="" name="' . $opt_value . '" style="width:100%"></td>' . "\n";
    echo '  <td align="right"><span class="rowz_alt" style="font-weight:normal">%opt' . $i . '%</span></td>' . "\n";
    echo '  <td align="right">';
    echo '<input type="checkbox" name="' . $opt_edit . '" id="' . $opt_edit . '">';
    echo '&nbsp;<label for="' . $opt_edit . '">Client-Editable</label></td></tr>';
}
?>

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="4" align="center"><input type="submit" name="submit" value="Submit" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>
</table>
<?php
}

// Insert Page
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
  <td align="center" valign="middle"><span class="top_page_titles">Create Supported <?php echo ucwords($url_server_type); ?> Server</span></td>
</tr>
</table>

<br /><br />

<?php
    // Server Type
    $url_server_type = $_GET['type'];
    
    // Post Values
    $post_short_name  = $_POST['short_name'];
    $post_long_name   = $_POST['long_name'];
    $post_available   = $_POST['available'];
    $post_style       = $_POST['style'];
    $post_log_file    = $_POST['log_file'];
    $post_port        = $_POST['port'];
    $post_res_ports   = $_POST['reserved_ports'];
    $post_tcp_ports   = $_POST['tcp_ports'];
    $post_udp_ports   = $_POST['udp_ports'];
    $post_executable  = $_POST['executable'];
    $post_max_slots   = $_POST['max_slots'];
    $post_map         = $_POST['map'];
    $post_setup_cmd   = $_POST['setup_cmd'];
    $post_cmd_line    = $_POST['cmd_line'];
    $post_working_dir = $_POST['working_dir'];
    $post_setup_dir   = $_POST['setup_dir'];
    
    // Format 'game available'
    if($post_available == 'on')
    {
        $post_available = 'Y';
    }
    else
    {
        $post_available = 'N';
    }
    
    // Begin insert query
    $insert_query = "INSERT INTO servers VALUES('','$post_short_name','$post_long_name','$url_server_type','$post_available','$post_style','$post_log_file','$post_port','$post_res_ports','$post_tcp_ports','$post_udp_ports','$post_executable','$post_max_slots','$post_map','$post_setup_cmd','$post_cmd_line','$post_working_dir','$post_setup_dir',";
    
    // Loop through 10 config options
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $this_opt_name  = 'opt' . $i . '_name';
        $this_opt_value = 'opt' . $i . '_value';
        $this_opt_edit  = 'opt' . $i . '_edit';
        
        // Post values
        $post_name  = $_POST[$this_opt_name];
        $post_value = $_POST[$this_opt_value];
        $post_edit  = $_POST[$this_opt_edit];
        
        // Format client-edit
        if($post_edit == 'on')
        {
            $post_edit = 'Y';
        }
        else
        {
            $post_edit = 'N';
        }
        
        // Add these options to the insert query
        if($i == 10)
        {
            $insert_query .= "'$post_name','$post_edit','$post_value')";
        }
        else
        {
            $insert_query .= "'$post_name','$post_edit','$post_value',";
        }
    }
    
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Insert game into database
    @mysql_query($insert_query) or die('<b>Error:</b> Failed to insert into the servers table!');
?>
<center>
<b>Success!</b>
<br /><br />
Successfully created your new server support.
<br /><br />
<a href="SupportedServers.php">Click here to go back</a>
</center>
<?php
}

