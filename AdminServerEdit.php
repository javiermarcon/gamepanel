<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


*/
include('include/config.php');
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}


// Show first page
if(!isset($_POST['update']))
{
    // Display information on a user - games, voice server, etc that they have.
    $id_url = $_GET['id'];
    
    // Get all information about this server
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // User Information Variables
    $result_server = @mysql_query("SELECT id,type,userid,date_created,server,ip,port,description,max_slots,map,executable,cmd_line,show_cmd_line FROM userservers WHERE id='$id_url'") or die('<b>Error:</b> Failed to query the userservers table!');
    
    while($row_server = mysql_fetch_array($result_server))
    {
        $id               = $row_server['id'];
        $userid           = $row_server['userid'];
        $date_created     = $row_server['date_created'];
        $server_type      = $row_server['type'];
        $server           = $row_server['server'];
        $ip               = stripslashes($row_server['ip']);
        $port             = stripslashes($row_server['port']);
        $description      = stripslashes($row_server['description']);
        $max_slots        = stripslashes($row_server['max_slots']);
        $map              = stripslashes($row_server['map']);
        $executable       = stripslashes($row_server['executable']);
        $cmd_line         = stripslashes($row_server['cmd_line']);
        $show_cmd_line    = stripslashes($row_server['show_cmd_line']);
    }
    
    // Get server's long name
    $result_long = @mysql_query("SELECT long_name FROM servers WHERE short_name='$server'") or die('<b>Error:</b> Failed to get server\'s long name!');
    
    while($row_long = mysql_fetch_array($result_long))
    {
        $server_long = $row_long['long_name'];
    }
    
    // Get username
    $result_username = @mysql_query("SELECT username FROM users WHERE id='$userid'") or die('<b>Error:</b> Failed to get username!');
    
    while($row_username = mysql_fetch_array($result_username))
    {
        $server_user = $row_username['username'];
    }
    
    
    // Encode in Base64
    $encoded_ip     = base64_encode($ip);
    $encoded_id     = base64_encode($id);
    $encoded_server   = base64_encode($server);
    
    
    // SSH to server, get status
    $server_idz   = $id;
    $server_ip    = $ip;
    $server_port  = $port;
    include('include/server_status.php');
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<title><?php echo $config['title']; ?> | Edit Server</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Edit Server</span></td>
</tr>
</table>

<br /><br />

<form action="include/runcmd.php" method="post">
<input type="hidden" name="ip" value="<?php echo $encoded_ip; ?>">
<input type="hidden" name="main_id" value="<?php echo $encoded_id; ?>">
<input type="hidden" name="main_server" value="<?php echo $encoded_server; ?>">
<input type="hidden" name="previous_page" value="AdminServerEdit.php?id=<?php echo $id_url; ?>">


<table border="0" style="border:1px solid black" cellpadding="2" cellspacing="0" width="400" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left"><span class="top_titles">&nbsp;&nbsp;&nbsp;&nbsp;Server Settings</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center">
  <?php
  // Display server image if available
  $server_image = 'images/servers/medium/' . $server . '.png';
  
  if(file_exists($server_image))
  {
      $server_img_src = $server_image;
  }
  // If not available, display default 'unsupported' image
  else
  {
      $server_img_src = 'images/servers/unsupported.png';
  }
  ?>
  <img src="<?php echo $server_img_src; ?>" border="0" />
  </td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><b><font color="darkblue"><?php echo $server_long; ?></font></b></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left"><span class="top_titles">&nbsp;&nbsp;&nbsp;&nbsp;Server Actions</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
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
  <td colspan="2">&nbsp;</td>
</tr>

<form action="include/screen_command.php" method="post">
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Send Command:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="server_cmd" value="" class="userinput" style="width:170px">&nbsp;<input type="submit" name="send_server_cmd" value="Send"></td>
</tr>
<input type="hidden" name="server_id" value="<?php echo $server_idz; ?>">
<input type="hidden" name="server_ip" value="<?php echo $ip; ?>">
<input type="hidden" name="server_port" value="<?php echo $port; ?>">
<input type="hidden" name="server_name" value="<?php echo $server; ?>">
<input type="hidden" name="server_username" value="<?php echo $server_user; ?>">
</form>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left"><span class="top_titles">&nbsp;&nbsp;&nbsp;&nbsp;Server Settings</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Owner:</span>&nbsp;&nbsp;</td>
  <td align="left"><?php echo $server_user; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Editing ID:</span>&nbsp;&nbsp;</td>
  <td align="left"><?php echo $id; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Date Created:</span>&nbsp;&nbsp;</td>
  <td align="left"><?php echo $date_created; ?></td>
</tr>

<tr class="rowz_title">
  <td colspan="3">&nbsp;</td>
</tr>

<form method="post" action="AdminServerEdit.php">
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Server Description:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $description; ?>" name="description" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">FTP Password:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="ftp_password" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">IP Address:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $ip; ?>" name="ip" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Port:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $port; ?>" name="port" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Max Slots:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $max_slots; ?>" name="max_slots" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only show map option if this is a game server
if($server_type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Startup Map:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $map; ?>" name="map" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Executable:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" value="<?php echo $executable; ?>" name="executable" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left"><span class="top_titles">&nbsp;&nbsp;&nbsp;&nbsp;Command-Line Options</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2">
  <?php
  // Show CMD Line option
  if($show_cmd_line == 'Y')
  {
      echo '<input type="checkbox" name="show_cmd_line" id="show_cmd_line" checked="yes">';
  }
  else
  {
      echo '<input type="checkbox" name="show_cmd_line" id="show_cmd_line">';
  }
  ?>
&nbsp;<label for="show_cmd_line">Allow client to see the Command-Line</label>
</td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><textarea name="cmd_line" style="width:95%;height:100px"><?php echo $cmd_line; ?></textarea></td>
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

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>

<?php

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
$result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the userservers table!');

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

        echo '<tr class="rowz_title">' . "\n";
        echo '  <td align="left" width="20"><input type="text" value="' . $db_name . '" name="' . $opt_name . '" style="width:140px;text-align:right"></td>' . "\n";
        echo '  <td align="left"><input type="text" value="' . $db_value . '" name="' . $opt_value . '" style="width:100%"></td>' . "\n";
        echo '  <td align="right"><span class="rowz_alt" style="font-weight:normal">%opt' . $i . '%</span></td>' . "\n";
        echo '  <td align="right">';
        
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

<tr class="rowz_title">
  <td colspan="4" align="center"><input type="submit" name="update" value="Update" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="id" value="<?php echo $id_url; ?>">
</form>

</body>
</html>
<?php
}


// Update page
elseif(isset($_POST['update']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<title><?php echo $config['title']; ?> | Edit Server</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Edit Server</span></td>
</tr>
</table>

<br /><br />
<?php
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    $post_id = $_POST['id'];
    
    // Get POST values, escape potentially bad characters
    $post_ip        = mysql_real_escape_string($_POST['ip']);
    $post_ftp_password = md5(mysql_real_escape_string($_POST['ftp_password']));
    $post_port      = mysql_real_escape_string($_POST['port']);
    $post_desc      = mysql_real_escape_string($_POST['description']);
    $post_max_pl    = mysql_real_escape_string($_POST['max_slots']);
    $post_map       = mysql_real_escape_string($_POST['map']);
    $post_exe       = mysql_real_escape_string($_POST['executable']);
    $post_cmd       = mysql_real_escape_string($_POST['cmd_line']);
    $post_show_cmd  = mysql_real_escape_string($_POST['show_cmd_line']);
    
    // Format 'show client cmd'
    if($post_show_cmd == 'on')
    {
        $post_show_cmd = 'Y';
    }
    else
    {
        $post_show_cmd = 'N';
    }
    
    // Update normal settings, then all config options
    if (strlen($_POST['ftp_password']) == 0)
    {
    	@mysql_query("UPDATE userservers SET ip='$post_ip',port='$post_port',description='$post_desc',max_slots='$post_max_pl',map='$post_map',executable='$post_exe',cmd_line='$post_cmd',show_cmd_line='$post_show_cmd' WHERE id='$post_id'") or die('<b>Error:</b> Failed to update server!');
    }
    else
    {
    	@mysql_query("UPDATE userservers SET ip='$post_ip',port='$post_port',description='$post_desc',max_slots='$post_max_pl',map='$post_map',executable='$post_exe',cmd_line='$post_cmd',show_cmd_line='$post_show_cmd',password='$post_ftp_password' WHERE id='$post_id'") or die('<b>Error:</b> Failed to update server!');
    }
    
    // Update query
    $update_query = "UPDATE userservers SET ";
    
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $this_opt_name  = 'opt' . $i . '_name';
        $this_opt_value = 'opt' . $i . '_value';
        $this_opt_edit  = 'opt' . $i . '_edit';
        
        // Post values
        $post_name  = mysql_real_escape_string($_POST[$this_opt_name]);
        $post_value = mysql_real_escape_string($_POST[$this_opt_value]);
        $post_edit  = mysql_real_escape_string($_POST[$this_opt_edit]);
        
        // Setup client-editable options
        if($post_edit == 'on')
        {
            $nice_post_edit = 'Y';
        }
        else
        {
            $nice_post_edit = 'N';
        }
        
        // Add to query
        $update_query .= " $this_opt_name='$post_name',";
        $update_query .= "$this_opt_value='$post_value',";
        
        // If at the end, remove comma
        if($i == 10)
        {
            $update_query .= "$this_opt_edit='$nice_post_edit' ";
        }
        else
        {
            $update_query .= "$this_opt_edit='$nice_post_edit', ";
        }
    }
    
    // Finish query
    $update_query .= " WHERE id='$post_id'";
    
    // Run query
    @mysql_query($update_query) or die('<b>Error:</b> Failed to update the userservers table!');
?>

<center>
<b>Success!</b>
<br /><br />
Successfully updated Server settings.
<br /><br />
<a href="AdminServerEdit.php?id=<?php echo $post_id; ?>">Click here to go back</a>
</center>

</body>
</html>
<?php
}
?>
