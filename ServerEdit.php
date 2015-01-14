<?php
/*

GamePanelX

Description:  Edit Server Settings

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/infobox.php');
include_once('include/statusInfo.php');
include_once('include/functions.php');

// Get ID from URL
$id_url = $_GET['id'];

if (!isset($_POST['submit']))
{
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

    // Get all personal info
    $result_server = @mysql_query("SELECT id,DATE_FORMAT(date_created, '%m/%d/%Y %h:%i') AS date_created,server,ip,port,description,max_slots,map,cmd_line,show_cmd_line FROM userservers WHERE id='$id_url'") or die('<b>Error:</b> Failed to query the database!');

    while($row_server = mysql_fetch_array($result_server))
    {
        // Strip all slashes off rows
        $id             = $row_server['id'];
        $date_created   = $row_server['date_created'];
        $server_name    = $row_server['server'];
        $ip             = $row_server['ip'];
        $port           = $row_server['port'];
        $max_slots      = $row_server['max_slots'];
        $orig_cmd_line  = $row_server['cmd_line'];
        $show_cmd_line  = $row_server['show_cmd_line'];
        
        // Since we use mysql_real_escape_string on update, strip those slashes off these
        $description    = stripslashes($row_server['description']);
        $map            = stripslashes($row_server['map']);
    }
    
    // Get long server name from servers table
    $result_long_name = @mysql_query("SELECT long_name FROM servers WHERE short_name='$server_name'") or die('<b>Error:</b> Failed to query the servers table!');

    while($row_long_name = mysql_fetch_array($result_long_name))
    {
        $server_long_name = $row_long_name['long_name'];
    }
    
    // Create full command line
    if($show_cmd_line == 'Y')
    {
        $cmd_line = build_cmd_line($id);
    }
    
    
    // Encode in Base64
    $encoded_ip     = base64_encode($ip);
    $encoded_id     = base64_encode($id);
    $encoded_server = base64_encode($server_name);
    
    
    // SSH to server, get status
    $server_ip    = $ip;
    $server_port  = $port;
    include('include/server_status.php');
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
  <td align="center" valign="middle"><span class="top_page_titles">Edit Server Settings</span></td>
</tr>
</table>

<br /><br />

<form action="include/runcmd.php" method="post">

<?php
// Check if we need to skip the parent server check
$result_parentz = @mysql_query("SELECT parent FROM remote WHERE ip='$encoded_ip' AND physical='N' AND available='Y'") or die('<b>Error:</b> Failed to query the remote table!');
$num_parentz    = mysql_num_rows($result_parentz);

if($num_parentz == 0)
{
    // Skip check for parent server since this is the only IP
    echo '<input type="hidden" name="skip_parent_check" value="1">';
}
?>
<input type="hidden" name="ip" value="<?php echo $encoded_ip; ?>">
<input type="hidden" name="main_id" value="<?php echo $encoded_id; ?>">
<input type="hidden" name="main_server" value="<?php echo $encoded_server; ?>">
<input type="hidden" name="previous_page" value="ServerEdit.php?id=<?php echo $id_url; ?>">

<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Manage Server</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="3" align="center">
  <?php
  // Display server image if available
  $server_image = 'images/servers/medium/' . $server_name . '.png';
  
  if(file_exists($server_image))
  {
      $server_img_src = $server_image;
  }
  // If not available, display default 'unsupported' image
  else
  {
      $server_img_src = 'images/main/unsupported.png';
  }
  ?>
  <img src="<?php echo $server_img_src; ?>" border="0" />
  </td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><b><font color="darkblue"><?php echo $server_long_name; ?></font></b></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Status:&nbsp;&nbsp;</span></td>
  <td align="left">
      <?php
      // After SSH'ing into the server, this is the status returned
      $server_status = trim($gs_status);

      // Online Status
      if($server_status == 'online')
      {
          echo '<font color="green"><b>Online</b></font>';
      }
      
      // Offline Status
      elseif($server_status == 'offline')
      {
          echo '<font color="red"><b>Offline</b></font>';
      }

      // Otherwise
      else
      {
          echo '<font color="orange"><b>Unknown</b></font>';
      }
      ?>
  </td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Action:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="action">
      <option value="restart" selected>Restart</option>
      <option value="stop">Stop</option>
    </select>&nbsp;
    <input type="submit" name="action_button" value="Go">
  </td>
</tr>
</form>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Date Created:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $date_created; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Connection Info:&nbsp;&nbsp;</span></td>
  <td align="left"><font color=blue><?php echo $ip . ':' . $port; ?></font></td>
</tr>


<form method="post" action="<?php echo $PHP_SELF; ?>">
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Server Description:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" value="<?php echo $description; ?>" name="description" maxlength="64" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<?php
// If client is allowed to see the Command-Line, show it
if($show_cmd_line == 'Y')
{
?>
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Current Startup Parameters</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="3" align="center"><textarea name="cmd_line" style="width:95%;height:75px" READONLY><?php echo $cmd_line; ?></textarea></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>
<?php
}
?>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Edit Startup Settings</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<?php
//
// Startup Settings
//

// First, show map if not empty
if(!empty($map))
{
    echo '<tr class="rowz_title">';
    echo '  <td align="right"><span class="rowz_alt">Startup Map:&nbsp;&nbsp;</span></td>';
    echo '  <td align="left"><input type="text" value="' . $map . '" name="map" maxlength="48" class="userinput" style="width:170px"></td>';
}

//
// Begin 10 configuration settings
//
$param_query = 'SELECT';

// Get all 10 config settings for this server
for($i=1; $i <= 10; $i++)
{
    trim($param_query);
    
    // Get options
    $param_query .= ' opt' . $i . '_name,';
    $param_query .= 'opt' . $i . '_edit,';
    
    if($i == 10)
    {
        $param_query .= 'opt' . $i . '_value';
    }
    else
    {
        $param_query .= 'opt' . $i . '_value,';
    }
}

// Finish query
$param_query .= " FROM userservers WHERE id='$id_url'";

// Query for all config options
$result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the database!');

while($row_opt = mysql_fetch_array($result_client_fields))
{
    // Loop through all 10 config options
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_edit   = 'opt' . $i . '_edit';
        $opt_value  = 'opt' . $i . '_value';
        
        // Option values
        $db_name  = stripslashes($row_opt[$opt_name]);
        $db_edit  = stripslashes($row_opt[$opt_edit]);
        $db_value = stripslashes($row_opt[$opt_value]);
        
        // Make sure this setting is used, and that the client can edit it
        if(!empty($db_name) && $db_edit == 'Y')
        {
            echo '<tr class="rowz_title">';
            echo '  <td align="right"><span class="rowz_alt">' . $db_name . ':&nbsp;&nbsp;</span></td>';
            
            // Switched settings - settings that are just On or Off (Y %switch%)
            if(preg_match("/^Y\ \%switch\%$/", $db_value))
            {
                echo '<td align="left">';
                echo '<select name="' . $opt_name . '" class="dropdown" style="width:170px">';
                echo '<option value="Y %switch%" selected>On</option>';
                echo '<option value="N %switch%">Off</option>';
                echo '</select>';
                echo '</td>';
            }
            elseif(preg_match("/^N\ \%switch\%$/", $db_value))
            {
                echo '<td align="left">';
                echo '<select name="' . $opt_name . '" class="dropdown" style="width:170px">';
                echo '<option value="Y %switch%">On</option>';
                echo '<option value="N %switch%" selected>Off</option>';
                echo '</select>';
                echo '</td>';
            }
            // Otherwise, normal input box option
            else
            {
                echo '  <td align="left"><input type="text" value="' . $db_value . '" name="' . $opt_name . '" maxlength="48" class="userinput" style="width:170px"></td>';
            }
            
            echo '</tr>';
        }
    }
}


?>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><input type="submit" name="submit" value="Update" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

</table>
<input type="hidden" name="id" value="<?php echo $id_url; ?>">
</form>

</body>
</html>
<?php
}

// If submit is hit, update / show success
elseif (isset($_POST['submit']))
{

$post_id = $_POST['id'];
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
      <td align="center" valign="middle"><span class="top_page_titles">Edit Server Settings</span></td>
    </tr>
    </table>

    <br /><br />
<?php
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');


    //
    // Begin 10 configuration settings
    //
    $param_query = 'SELECT userid,server,ip,port,description,max_slots,log_file,map,executable,cmd_line,';

    // Get all 10 config settings for this server
    for($i=1; $i <= 10; $i++)
    {
        trim($param_query);
        
        // Get options
        $param_query .= ' opt' . $i . '_name,';
        $param_query .= 'opt' . $i . '_edit,';
        
        if($i == 10)
        {
            $param_query .= 'opt' . $i . '_value';
        }
        else
        {
            $param_query .= 'opt' . $i . '_value,';
        }
    }

    // Finish query
    $param_query .= " FROM userservers WHERE id='$post_id'";

    // Query for all config options
    $result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the userservers table!');

    while($row_opt = mysql_fetch_array($result_client_fields))
    {
        // Normal options
        $userid         = $row_opt['userid'];
        $ip             = $row_opt['ip'];
        $server         = $row_opt['server'];
        $port           = $row_opt['port'];
        $description    = $row_opt['description'];
        $max_slots      = $row_opt['max_slots'];
        $log_file       = $row_opt['log_file'];
        $map            = $row_opt['map'];
        $executable     = $row_opt['executable'];
        $orig_cmd_line  = $row_opt['cmd_line'];
        
        // Escape bad chars etc on POST Values
        $post_map       = mysql_real_escape_string($_POST['map']);
        $post_desc      = mysql_real_escape_string($_POST['description']);
        
        // Update Description
        if($post_desc != $description)
        {
            @mysql_query("UPDATE userservers SET description='$post_desc' WHERE id='$post_id'");
        }
        
        // Update Map
        if($post_map != $map && !empty($post_map))
        {
            // If admin set "Strip Client Commands" in configuration, strip out common server options
            // E.g. - or + so nobody tries to cheat the system
            if($config['strip_client_commands'] == 'Y')
            {
                $bad_chars = array("-","+");
                $first_char = substr($post_map, 0, 1);
                $last_char  = substr($post_map, -1, 1);
                
                // Remove first character if bad
                if(in_array($first_char, $bad_chars))
                {
                    $post_map = str_replace($first_char, "", $post_map);
                }
                
                // Remove last character if bad
                if(in_array($last_char, $bad_chars))
                {
                    $post_map = str_replace($last_char, "", $post_map);
                }
            }
          
          
            // Update the database value for this
            @mysql_query("UPDATE userservers SET map='$post_map' WHERE id='$post_id'");
        }
        
        // Get server username
        $result_username = @mysql_query("SELECT username FROM users WHERE id='$userid'") or die('<b>Error:</b> Failed to get username!');
        
        while($row_username = mysql_fetch_array($result_username))
        {
            $server_username = $row_username['username'];
        }

        // Parse the command-line
        $cmd_line  = str_replace("%executable%", $executable, $orig_cmd_line);  // Remove executable
        $cmd_line  = str_replace("%ip%", $ip, $cmd_line);                     // IP Address
        $cmd_line  = str_replace("%port%", $port, $cmd_line);                 // Port
        $cmd_line  = str_replace("%log_file%", $log_file, $cmd_line);         // Log File
        $cmd_line  = str_replace("%username%", $server_username, $cmd_line);  // Username
        $cmd_line  = str_replace("%server%", $server, $cmd_line);             // Server Name
        $cmd_line  = str_replace("%working_dir%", $working_dir, $cmd_line);   // Working Directory
        $cmd_line  = str_replace("%setup_dir%", $setup_dir, $cmd_line);       // Setup Directory
        
        // Variation on the "Map" value
        $cmd_line  = str_replace("%map%", $map, $cmd_line);             // Startup Map
        $cmd_line  = str_replace("%default_map%", $map, $cmd_line);           // Startup Map
        $cmd_line  = str_replace("%startup_map%", $map, $cmd_line);     // Startup Map
        
        // Variation on the "Max Slots" value
        $cmd_line  = str_replace("%max_slots%", $max_slots, $cmd_line);       // Max Slots
        $cmd_line  = str_replace("%max_players%", $max_slots, $cmd_line);     // Max Slots
        
        
        

        // Loop through all 10 config options
        for($i=1; $i <= 10; $i++)
        {
            // Option names
            $opt_name   = 'opt' . $i . '_name';
            $opt_edit   = 'opt' . $i . '_edit';
            $opt_value  = 'opt' . $i . '_value';

            // Database values
            $db_name  = $row_opt[$opt_name];
            $db_edit  = $row_opt[$opt_edit];
            $db_value = $row_opt[$opt_value];

            // POST Values
            $post_name  = mysql_real_escape_string($_POST[$opt_name]);
            $post_edit  = mysql_real_escape_string($_POST[$opt_edit]);
            $post_value = mysql_real_escape_string($_POST[$opt_value]);

            // Current Option
            $this_opt = '%opt' . $i . '%';
            
            // If this option was used
            if(!empty($post_name))
            {
                // If admin set "Strip Client Commands" in configuration, strip out common server options
                // E.g. - or + so nobody tries to cheat the system
                if($config['strip_client_commands'] == 'Y')
                {
                    $bad_chars = array("-","+");
                    $first_char = substr($post_name, 0, 1);
                    $last_char  = substr($post_name, -1, 1);
                    
                    // Remove first character if bad
                    if(in_array($first_char, $bad_chars))
                    {
                        $post_name = str_replace($first_char, "", $post_name);
                    }
                    
                    // Remove last character if bad
                    if(in_array($last_char, $bad_chars))
                    {
                        $post_name = str_replace($last_char, "", $post_name);
                    }
                }
            
            
                // Single values with switch
                if(preg_match("/\%switch\%/", $post_name))
                {
                    // Escape certain characters in DB name
                    $escaped_name = preg_quote($db_name);
                    
                    // Switch was on
                    if($post_name == 'Y %switch%')
                    {
                        // Make sure this setting is in the CMD Line
                        if(!preg_match("/$escaped_name/", $orig_cmd_line))
                        {
                            // Add to CMD Line
                            $orig_cmd_line .= " $db_name";
                            
                            // Update the database with the new CMD Line
                            @mysql_query("UPDATE userservers SET cmd_line='$orig_cmd_line' WHERE id='$post_id'");
                        }
                    }
                    // Otherwise
                    else
                    {
                        // Make sure this setting is NOT in the CMD Line
                        if(preg_match("/$escaped_name/", $orig_cmd_line))
                        {
                            // Remove from the CMD Line
                            $orig_cmd_line = str_replace(" $db_name", "", $orig_cmd_line);
                            
                            // Update the database with the new CMD Line
                            @mysql_query("UPDATE userservers SET cmd_line='$orig_cmd_line' WHERE id='$post_id'");
                        }
                    }
                    
                    // Update with new value
                    @mysql_query("UPDATE userservers SET $opt_value='$post_name' WHERE id='$post_id'");
                }
                
                // All other normal values
                else
                {
                    // Update the database value for this
                    @mysql_query("UPDATE userservers SET $opt_value='$post_name' WHERE id='$post_id'");
                }
            }
            // Otherwise use the default value
            else
            {
               // Replace this option with the database value
                $cmd_line  = str_replace($this_opt, $db_value, $cmd_line);
            }
        }
    }
    
    // Give some output
?>
<center>
<b>Success!</b><br /><br />
Successfully updated server settings.<br /><br />
<a href="ServerEdit.php?id=<?php echo $post_id; ?>">Click here to go back.</a>
</center>
<?php
}

?>
