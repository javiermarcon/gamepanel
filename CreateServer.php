<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.

*/
include_once('include/config.php');
include_once('include/SqlCon.php');
include_once('include/auth.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query="SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);
$isAdmin = $row['is_admin'];

if($isAdmin != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}

// Get userid from URL
$url_id           = $_GET['id'];

// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
}

if(!empty($url_id) && !is_numeric($url_id))
{
    die('<b>Error:</b> Invalid URL ID!');
}

if (!isset($_POST['next']) && !isset($_POST['submit']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
<script type="text/javascript">
function checkValues()
{
    if(document.createServer.user.value == "")
    {
        alert('You didn\'t select a user!');
        return false;
    }
    if(document.createServer.server.value == "")
    {
        alert('You didn\'t select a server!');
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
  <td align="center" valign="middle"><span class="top_page_titles">Crear <?php echo ucwords($url_server_type); ?> Server</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="CreateServer.php?type=<?php echo $url_server_type; ?>" name="createServer" onSubmit="return checkValues()">
<table width="400" cellpadding="0" cellspacing="0" class="tablez" style="border:1px solid black" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Step 1 of 3 - Server Setup</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center">
<?php
if($url_server_type == 'game')
{
    echo '<img src="images/main/supported_games.png" border="0" />';
}
elseif($url_server_type == 'voip')
{
    echo '<img src="images/main/supported_voip.png" border="0" />';
}
elseif($url_server_type == 'other')
{
    echo '<img src="images/main/supported_games.png" border="0" />';
}
else
{
    echo 'User Servers';
}
?>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Username:&nbsp;&nbsp;</span></td>
  <td align="left">
    <?php
    // Use ID from the URL
    if(!empty($url_id))
    {
        // Connect to database
        $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
        @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
        // Get username from ID
        $result_username = @mysql_query("SELECT username FROM users WHERE id='$url_id'") or die('<b>Error:</b> Failed to get username!');
        
        while($row_username = mysql_fetch_array($result_username))
        {
            $username = $row_username['username'];
        }
        
        //echo '<input type="text" name="user" value="' . $username . '" style="width:200px" readonly>';
        echo '<b>' . $username . '</b>';
        echo '<input type="hidden" name="user" value="' . $username . '">';
    }
    else
    {
    ?>
      <select name="user" style="width:200px">
      <option value="" selected="selected">Select a user</option>
    <?php
        // Connect to database and make a dropdown list of users to choose from
        $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
        @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

        $result = mysql_query("SELECT username FROM users WHERE is_admin != 'Y'") or die(mysql_error());

        while ($row = mysql_fetch_array($result))
        {
            echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
        }
        echo '</select>';
    }
    ?>
  </td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt"><?php echo ucwords($url_server_type); ?> Server Name:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="server" style="width:200px">
      <option value="" selected="selected">Select a <?php echo $url_server_type; ?> server</option>
<?php
// Create a list of all available servers
//
// Only show servers that have a default available template set
$query_avail = "SELECT 
                  servers.short_name,
                  servers.long_name 
                FROM servers 
                LEFT JOIN templates ON 
                  servers.short_name = templates.server 
                WHERE servers.type='$url_server_type' 
                  AND servers.available='Y' 
                  AND templates.available = 'Y'
                  AND templates.is_default = 'Y'
                ORDER BY servers.long_name ASC";

// Get results
$result_gm  = @mysql_query($query_avail) or die('<b>Error:</b> Failed to get available servers!');
$num_gm     = mysql_num_rows($result_gm);

// Available supported servers
if($num_gm >= 1)
{
    while($row_gm = mysql_fetch_array($result_gm))
    {
        $short_name = $row_gm['short_name'];
        $long_name  = $row_gm['long_name'];

        echo '<option value="' . $short_name . '">' . $long_name . '</option>';
    }
}
// No supported servers found
else
{
    echo '<option value="">No templates found</option>';
}
?>
</select>
</td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

</table>

<br>

<div align="center">
<input type="submit" value="Next" name="next">&nbsp;&nbsp;
<input type="button" value="Cancel" onClick="window.location.href='Main.php'" name="cancel">
</form>
</div>
</body>
</html>
<?php
}



//
// Show server options
//
elseif (isset($_POST['next']) && !isset($_POST['submit']))
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
  <td align="center" valign="middle"><span class="top_page_titles">Create <?php echo ucwords($url_server_type); ?> Server</span></td>
</tr>
</table>

<br /><br />
<?php
    // Declare nicer variables
    $post_user        = $_POST['user'];
    $post_server      = $_POST['server'];
    $post_physical_ip = $_POST['physical_ip'];
    
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Get default server info
    $result_server = @mysql_query("SELECT long_name,log_file,port,max_slots,map,executable,cmd_line,working_dir FROM servers WHERE short_name='$post_server'") or die('<b>Error:</b> Failed to query the servers table!');
    
    while($row_server = mysql_fetch_array($result_server))
    {
        $long_name      = stripslashes($row_server['long_name']);
        $log_file       = stripslashes($row_server['log_file']);
        $port           = stripslashes($row_server['port']);
        $max_slots      = stripslashes($row_server['max_slots']);
        $map            = stripslashes($row_server['map']);
        $executable     = stripslashes($row_server['executable']);
        $cmd_line       = stripslashes($row_server['cmd_line']);
        $working_dir    = stripslashes($row_server['working_dir']);
    }
?>

<form method="post" action="CreateServer.php?type=<?php echo $url_server_type; ?>">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles"><?php echo ucwords($url_server_type); ?> Server Options</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="3" align="center">
  <?php
  // Display server image if available
  $server_image = 'images/servers/medium/' . $post_server . '.png';
  
  if(file_exists($server_image))
  {
      $server_img_src = $server_image;
  }
  // If not available, display default 'unsupported' image
  else
  {
      $server_img_src = 'images/servers/medium/unsupported.png';
  }
  ?>
  <img src="<?php echo $server_img_src; ?>" border="0" />
  </td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><b><font color="darkblue"><?php echo $long_name; ?></font></b></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Server Description: </span></td>
  <td align="left"><input type="text" value="" name="description" maxlength="20" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Ftp Password: </span></td>
  <td align="left"><input type="text" value="" name="ftp_password" maxlength="20" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">IP Address: </span></td>
  <td align="left">
    <select name="server_setup_ip" style="width:170px">
  <?php
  // List all available IP Addresses.
  // (Either a physical IP, or a non-physical IP that has a Parent associated with it)
  $result_ip  = @mysql_query("SELECT ip FROM remote WHERE available='Y' AND physical='Y' OR parent!=''") or die('<b>Error:</b> Failed to get available IP Addresses!');
  $num_ip     = mysql_num_rows($result_ip);
  
  // Results found.  Show the corresponding IP's
  if($num_ip >= 1)
  {
      while($row_phys = mysql_fetch_array($result_ip))
      {
          $avail_ip = $row_phys['ip'];
          
          echo '<option value="' . $avail_ip . '">' . $avail_ip . '</option>';
      }
  }
  // Show "No IP's Available" option
  else
  {
      echo '<option value="">None found</option>';
  }
  ?>
    </select>
  </td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Port: </span></td>
  <td align="left"><input type="text" value="<?php echo $port ; ?>" name="port" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Max Slots: </span></td>
  <td align="left"><input type="text" value="<?php echo $max_slots; ?>" name="max_slots" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<?php
// Only give map option if this is a game
if($url_server_type == 'game')
{
?>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Startup Map: </span></td>
  <td align="left"><input type="text" value="<?php echo $map; ?>" name="map" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<?php
}
?>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Executable: </span></td>
  <td align="left"><input type="text" value="<?php echo $executable; ?>" name="executable" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Log File: </span></td>
  <td align="left"><input type="text" value="<?php echo $log_file; ?>" name="log_file" maxlength="255" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Working Directory: </span></td>
  <td align="left"><input type="text" value="<?php echo $working_dir; ?>" name="working_dir" maxlength="255" class="userinput" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><input type="checkbox" name="show_cmd_line" id="show_cmd_line">&nbsp;<label for="show_cmd_line">Allow client to see the Command-Line</label></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2"><span class="rowz_alt">Command-Line:<br /></span>
  <textarea name="cmd_line" style="width:95%;height:80px"><?php echo $cmd_line; ?></textarea></td>
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
$param_query .= " FROM servers WHERE short_name='$post_server'";

// Query for all config options
$result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the servers table!');

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
  <td colspan="4" align="center"><input type="submit" value="Continue" name="submit" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="4">&nbsp;</td>
</tr>
</table>
<input type="hidden" name="user" value="<?php echo $_POST['user']; ?>">
<input type="hidden" name="physical_ip" value="<?php echo $_POST['physical_ip']; ?>">
<input type="hidden" name="server" value="<?php echo $_POST['server']; ?>">

<?php
// Skip Parent Check
if($skip_parent_check == "yes")
{
    echo '<input type="hidden" name="skip_parent_check" value="1">';
}
?>
</form>

</body>
</html>
<?php
}




//
// Insert new server data into database, then show SSH Connection info
//
elseif(isset($_POST['submit']) && !isset($_POST['create_server']))
{
    // Connect to the DB
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Redeclare POST values
    $user     = $_POST['user'];
    $ip       = $_POST['server_setup_ip'];
    $port     = $_POST['port'];
    $server   = $_POST['server'];
    
    // Check empty
    if(empty($user) || empty($server) || empty($_POST['ftp_password']))
    {
        die('<center><b>Error:</b> You left a required field blank.  Please go back and try again.</center>');
    }
    
    // Server's Nickname is "IP:Port"
    $server_nickname = $ip . ':' . $port;


    // Create new server
    $post_desc        = $_POST['description'];
    $post_ftp_password = md5(mysql_real_escape_string($_POST['ftp_password']));
    $post_ip          = $_POST['server_setup_ip'];
    $post_port        = $_POST['port'];
    $post_max_slots   = $_POST['max_slots'];
    $post_map         = $_POST['map'];
    $post_exe         = $_POST['executable'];
    $post_log         = $_POST['log_file'];
    $post_cl_cmd      = $_POST['show_cmd_line'];
    $post_cmd         = $_POST['cmd_line'];
    $post_working_dir = $_POST['working_dir'];
    $post_setup_dir   = $_POST['setup_dir'];
    
    // Format 'client can see cmd line'
    if($post_cl_cmd == 'on')
    {
        $post_cl_cmd = 'Y';
    }
    else
    {
        $post_cl_cmd = 'N';
    }
    
    // Get userid from username
    $query_username = @mysql_query("SELECT id FROM users WHERE username='$user'") or die('<b>Error:</b> Failed to get user ID!');
    
    while($row_username = mysql_fetch_array($query_username))
    {
        $userid = $row_username['id'];
    }

    // Begin insert query
    $insert_query = "INSERT INTO userservers VALUES('',NOW(),'$url_server_type','$server','$userid','$post_log','$post_ip','$post_port','$post_desc','$post_max_slots','$post_map','$post_exe','$post_cmd','$post_working_dir','$post_setup_dir','$post_cl_cmd',";
    
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
        $insert_query .=  "'$post_name','$post_edit','$post_value',";
    }
    $insert_query .=  "'$post_ftp_password')";

    // Insert user's server
    @mysql_query($insert_query) or die('<b>Error:</b> Failed to insert into the userservers table!');
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
      <td align="center" valign="middle"><span class="top_page_titles">Create <?php echo ucwords($url_server_type); ?> Server</span></td>
    </tr>
    </table>

    <br /><br />

    <form action="include/runcmd.php" method="post">
    <table width="400" cellpadding="0" cellspacing="0" class="tablez" style="border:1px solid black" align="center">
    <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
      <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Step 3 of 3 - <?php echo ucwords($url_server_type); ?> Server Creation</span></td>
    </tr>

    <tr>
      <td>
    <?php
    // POST Server
    $post_server = $_POST['server'];
    
    // Grab the default template for this server
    $query = "SELECT file_path FROM templates WHERE server='$post_server' AND is_default='Y' AND available='Y'";
    sqlCon($query);

    $filePath = $row['file_path'];
    
    // Die if no default server template
    if(empty($filePath))
    {
        die('<br /><center><b>Error:</b> There is no available default template set for this server!</center><br />');
    }

    // Encode all variables in base64 for traveling through POST
    $encoded_dir        = base64_encode($config['BASE_DIR']);
    $encoded_hostname   = base64_encode($config['Hostname']);
    $encoded_server     = base64_encode($_POST['server']);
    $encoded_ip         = base64_encode($_POST['server_setup_ip']);
    $encoded_file_path  = base64_encode($filePath);
    $encoded_user       = base64_encode($_POST['user']);
    $encoded_nick       = base64_encode($server_nickname);
    $skip_parent_check  = $_POST['skip_parent_check'];
    
    // Skip Parent Server Check
    if($skip_parent_check == 1)
    {
        echo '<input type="hidden" name="skip_parent_check" value="1">';
    }
    ?>
        <center>
        <input type="hidden" name="gpx_dir" value="<?php echo $encoded_dir; ?>">
        <input type="hidden" name="server" value="<?php echo $encoded_server; ?>">
        <input type="hidden" name="ip" value="<?php echo $encoded_ip; ?>">
        <input type="hidden" name="file_path" value="<?php echo $encoded_file_path; ?>">
        <input type="hidden" name="user" value="<?php echo $encoded_user; ?>">
        <input type="hidden" name="server_nickname" value="<?php echo $encoded_nick; ?>">
        <input type="hidden" name="previous_page" value="CreateServer.php?type=<?php echo $url_server_type; ?>">
        <br />
        <input type="submit" name="create_server" value="Create <?php echo ucwords($url_server_type); ?> Server">
        <br /><br />
        <b>Please Note:</b> This process can take up to 2 minutes to complete.
        </center>
      </td>
    </tr>
    </table>
    </form>

    </body>
    </html>
<?php
}
?>
