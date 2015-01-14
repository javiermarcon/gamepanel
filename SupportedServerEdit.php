<?php
/*

GamePanelX

Description:  Create new game support

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

// Get ID from URL
$url_id = $_GET['id'];

if(empty($url_id) || !is_numeric($url_id))
{
    die('<b>Error:</b> Invalid ID given!');
}

if(!isset($_POST['submit']))
{
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

    // Get server info
    $result_serverz = @mysql_query("SELECT short_name,long_name,type,available,style,log_file,port,reserved_ports,tcp_ports,udp_ports,executable,max_slots,map,setup_cmd,cmd_line,working_dir,setup_dir FROM servers WHERE id='$url_id'") or die('<b>Error:</b> Failed to query the servers table!');
    
    while($row_serverz = mysql_fetch_array($result_serverz))
    {
        $short_name     = stripslashes($row_serverz['short_name']);
        $long_name      = stripslashes($row_serverz['long_name']);
        $type           = stripslashes($row_serverz['type']);
        $available      = $row_serverz['available'];
        $style          = stripslashes($row_serverz['style']);
        $log_file       = stripslashes($row_serverz['log_file']);
        $port           = stripslashes($row_serverz['port']);
        $res_ports      = stripslashes($row_serverz['reserved_ports']);
        $tcp_ports      = stripslashes($row_serverz['tcp_ports']);
        $udp_ports      = stripslashes($row_serverz['udp_ports']);
        $executable     = stripslashes($row_serverz['executable']);
        $max_slots      = stripslashes($row_serverz['max_slots']);
        $map            = stripslashes($row_serverz['map']);
        $setup_cmd      = stripslashes($row_serverz['setup_cmd']);
        $cmd_line       = stripslashes($row_serverz['cmd_line']);
        $working_dir    = stripslashes($row_serverz['working_dir']);
        $setup_dir      = stripslashes($row_serverz['setup_dir']);
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
  <td align="center" valign="middle"><span class="top_page_titles">Edit Supported Server</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="SupportedServerEdit.php?id=<?php echo $url_id; ?>">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Edit Server Setup</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center">
  <?php
  // Show icon for the server.  If no icon exists, use the 'unsupported' icon
  $icon_loc = 'images/servers/medium/' . $short_name . '.png';
  
  if(file_exists($icon_loc))
  {
      echo '<img src="' . $icon_loc . '" border="0" width="64" height="64" />';
  }
  else
  {
      echo '<img src="images/servers/medium/unsupported.png" border="0" width="64" height="64" />';
  }
  
  echo '<br /><b>' . $long_name . '</b><br />';
  ?>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Short Name: </span></td>
  <td align="left"><input type="text" value="<?php echo $short_name; ?>" name="short_name" maxlength="12" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Long Name: </span></td>
  <td align="left"><input type="text" value="<?php echo $long_name; ?>" name="long_name" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only show if this is a game
if($type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Style: </span></td>
  <td align="left"><input type="text" value="<?php echo $style; ?>" name="style" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Log File: </span></td>
  <td align="left"><input type="text" value="<?php echo $log_file; ?>" name="log_file" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default Port: </span></td>
  <td align="left"><input type="text" value="<?php echo $port; ?>" name="port" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Reserved Ports: </span></td>
  <td align="left"><input type="text" value="<?php echo $res_ports; ?>" name="reserved_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">TCP Ports: </span></td>
  <td align="left"><input type="text" value="<?php echo $tcp_ports; ?>" name="tcp_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">UDP Ports: </span></td>
  <td align="left"><input type="text" value="<?php echo $udp_ports; ?>" name="udp_ports" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Executable: </span></td>
  <td align="left"><input type="text" value="<?php echo $executable; ?>" name="executable" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Max Slots: </span></td>
  <td align="left"><input type="text" value="<?php echo $max_slots; ?>" name="max_slots" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Working Directory: </span></td>
  <td align="left"><input type="text" value="<?php echo $working_dir; ?>" name="working_dir" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Setup Directory: </span></td>
  <td align="left"><input type="text" value="<?php echo $setup_dir; ?>" name="setup_dir" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only show if this is a game
if($type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default Map: </span></td>
  <td align="left"><input type="text" value="<?php echo $map; ?>" name="map" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2">Initial Setup Command:<br /><textarea name="setup_cmd_line" style="width:95%;height:100px"><?php echo $setup_cmd; ?></textarea></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2">Startup Command-Line:<br /><textarea name="cmd_line" style="width:95%;height:100px"><?php echo $cmd_line; ?></textarea></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2">
  <?php
  // Setup 'available'
  if($available == 'Y')
  {
      echo '<input type="checkbox" name="available" id="available" checked="yes">';
  }
  else
  {
      echo '<input type="checkbox" name="available" id="available">';
  }
  ?>
&nbsp;<label for="available">This is available for use</label>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2" style="font-weight:normal"><b>XML Export:</b> <a href="XMLExport.php?id=<?php echo $url_id; ?>" style="color:black">Click here to generate an XML file for this server</a></td>
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
$query_server = "SELECT";

// Get all 10 config settings for this server
for($i=1; $i <= 10; $i++)
{
    $query_server = trim($query_server);
    
    // Get options
    $query_server .= ' opt' . $i . '_name,';
    $query_server .= 'opt' . $i . '_edit,';
    
    if($i == 10)
    {
        $query_server .= 'opt' . $i . '_value';
    }
    else
    {
        $query_server .= 'opt' . $i . '_value,';
    }
}

$query_server .= " FROM servers WHERE id='$url_id'";

// Run query
$result_server = @mysql_query($query_server) or die('<b>Error:</b> Failed to query the servers table!');

while($row_server = mysql_fetch_array($result_server))
{
    // Loop through all 10 config options
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_edit   = 'opt' . $i . '_edit';
        $opt_value  = 'opt' . $i . '_value';
        
        // DB values
        $db_name    = $row_server[$opt_name];
        $db_edit    = $row_server[$opt_edit];
        $db_value   = $row_server[$opt_value];

        echo '<tr class="rowz_title">' . "\n";
        echo '  <td align="left" width="20"><input type="text" value="' . $db_name . '" name="' . $opt_name . '" style="width:140px;text-align:right"></td>' . "\n";
        echo '  <td align="left"><input type="text" value="' . $db_value . '" name="' . $opt_value . '" style="width:100%"></td>' . "\n";
        echo '  <td align="right"><span class="rowz_alt" style="font-weight:normal">%opt' . $i . '%</span></td>' . "\n";
        echo '  <td align="right">';
        
        // Setup 'client-editable field'
        if($db_edit == 'Y')
        {
            echo '<input type="checkbox" name="' . $opt_edit . '" id="' . $opt_edit . '" checked="yes">';
        }
        else
        {
            echo '<input type="checkbox" name="' . $opt_edit . '" id="' . $opt_edit . '">';
        }
        echo '&nbsp;<label for="' . $opt_edit . '">Client-Editable</label></td></tr>';
    }
}
?>

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>
</table>

<br /><br />

<center>
<input type="submit" name="submit" value="Update" style="width:170px">
</center>

</body>
</html>
<?php
}

// Insert Page
elseif(isset($_POST['submit']))
{
  $url_id = $_GET['id'];
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
  <td align="center" valign="middle"><span class="top_page_titles">Edit Supported Server</span></td>
</tr>
</table>

<br /><br />

<?php
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Post Values
    $post_short_name  = mysql_real_escape_string($_POST['short_name']);
    $post_long_name   = mysql_real_escape_string($_POST['long_name']);
    $post_type        = mysql_real_escape_string($_POST['type']);
    $post_style       = mysql_real_escape_string($_POST['style']);
    $post_log_file    = mysql_real_escape_string($_POST['log_file']);
    $post_port        = mysql_real_escape_string($_POST['port']);
    $post_res_ports   = mysql_real_escape_string($_POST['reserved_ports']);
    $post_tcp_ports   = mysql_real_escape_string($_POST['tcp_ports']);
    $post_udp_ports   = mysql_real_escape_string($_POST['udp_ports']);
    $post_executable  = mysql_real_escape_string($_POST['executable']);
    $post_max_slots   = mysql_real_escape_string($_POST['max_slots']);
    $post_map         = mysql_real_escape_string($_POST['map']);
    $post_setup_cmd   = mysql_real_escape_string($_POST['setup_cmd_line']);
    $post_cmd_line    = mysql_real_escape_string($_POST['cmd_line']);
    $post_working_dir = mysql_real_escape_string($_POST['working_dir']);
    $post_setup_dir   = mysql_real_escape_string($_POST['setup_dir']);
    $post_available   = $_POST['available'];
    
    // Format 'server available'
    if($post_available == 'on')
    {
        $post_available = 'Y';
    }
    else
    {
        $post_available = 'N';
    }
    
    // Begin insert query
    $update_query = "UPDATE servers SET short_name='$post_short_name',long_name='$post_long_name',available='$post_available',style='$post_style',log_file='$post_log_file',port='$post_port',reserved_ports='$post_res_ports',tcp_ports='$post_tcp_ports',udp_ports='$post_udp_ports',executable='$post_executable',max_slots='$post_max_slots',map='$post_map',setup_cmd='$post_setup_cmd',cmd_line='$post_cmd_line',working_dir='$post_working_dir',setup_dir='$post_setup_dir',";
    
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
            $update_query .=  "$this_opt_name='$post_name',$this_opt_edit='$post_edit',$this_opt_value='$post_value'";
        }
        else
        {
            $update_query .=  "$this_opt_name='$post_name',$this_opt_edit='$post_edit',$this_opt_value='$post_value',";
        }
    }
    
    // Finish query
    $update_query .= " WHERE id='$url_id'";

    // Update server
    @mysql_query($update_query) or die('<b>Error:</b> Failed to update the servers table!');
?>
<center>
<b>Success!</b>
<br /><br />
Successfully updated.
<br /><br />
<a href="SupportedServerEdit.php?id=<?php echo $url_id; ?>">Click here to go back</a>
</center>
<?php
}
