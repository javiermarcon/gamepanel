<?php
/*

GamePanelX

Description:  Installation file

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/

// Only print errors
error_reporting(E_ERROR);

// User IP Info
$ip           = $_SERVER['REMOTE_ADDR'];
$hostaddress  = gethostbyaddr($ip);


//
// Set the defaults here for install
//
$install__version     = '0.60';
$install__page_title  = 'GamePanelX | The Game Server Control Panel';
$install__top_logo    = 'css/default/img/gpx.png';


// Function to create random passphrases/etc
function generate_api_key($api_key_length)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i <= $api_key_length) {

        $num = rand() % 33;

        $tmp = substr($chars, $num, 1);

        $pass = $pass . $tmp;

        $i++;

    }

    return $pass;
}

if (!isset($_POST['gpx_confirm']) && !isset($_POST['gpx_start']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/default/main.css">
<title>GamePanelX | Installation</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="../include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="../css/default/img/gpx_grad.png" height="80"><img src="../css/default/img/gpx.png" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>


<?php
//
// Check if the PECL SSH2 Module exists
//
if(!function_exists('ssh2_connect'))
{
    die('<br /><br /><center><b>Error:</b> The PECL SSH2 module is not installed!  You must install this to connect to Remote Servers.<br /><br /><a href="http://www.gamepanelx.com/wiki/index.php?title=Install_SSH2_Module" target="_blank" style="color:#333;text-decoration:underline">Click here for more info</a>.</center>');
}
?>


<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/default/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">GamePanelX Installation Page 1 of 3</span></td>
</tr>
</table>

<br /><br />

<center>
Thank you for choosing GamePanelX!  Please fill out the following forms to complete the installation.<br /><br />
<b>Version <?php echo $install__version; ?></b>
</center>

<br /><br />

<form method="post" action="<?php echo $PHP_SELF; ?>">
<table class="tablez" border="0" cellpadding="0" cellspacing="0" width="400" align="center">
<?php
// Reason for failure
if (!empty($getReason) && $getFail == 1)
{
  echo '  <tr height="20">';
  echo '    <td bgcolor="#c0c0c0"colspan="3" align="center"><font face="verdana" size="2" color="red">';
  echo '<b>Error:</b>&nbsp;&nbsp;Problem with "' . $getReason . '".';
  echo '    </font></td>';
  echo '</tr>';
}
?>
  <tr background="../css/default/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Database Setup</span></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Database Type:&nbsp;&nbsp;</span></td>
    <td align="left"><select name="gpx_sql_type" style="width:150px"><option value="MySQL 4/5">MySQL 5</option></select></td>
  </tr>
 
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Database Hostname:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_sql_hostname" value="localhost" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Database Name:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_sql_db" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Database Username:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_sql_user" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Database Password:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="password" name="gpx_sql_pass" class="userinput" style="width:150px"></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr background="../css/default/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Administration Settings</span></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Language:&nbsp;&nbsp;</span></td>
    <td align="left"><select name="gpx_language" style="width:150px"><option value="English">English</option></select></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Email Address:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_email" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Admin Username:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_admin_user" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Admin Password:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="password" name="gpx_admin_pass" class="userinput" style="width:150px"></td>
  </tr>
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Admin Password (confirm):&nbsp;&nbsp;</span></td>
    <td align="left"><input type="password" name="gpx_admin_pass_confirm" class="userinput" style="width:150px"></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td align="right"><span class="rowz_alt">Script Path:&nbsp;&nbsp;</span></td>
    <td align="left"><input type="text" name="gpx_script_path" value="/GamePanelX" class="userinput" style="width:150px"></td>
  </tr>

  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2" align="center"><input type="submit" name="gpx_confirm" value="Confirm Settings" style="width:170px"></td>
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

// Confirmation Page
if (isset($_POST['gpx_confirm']) && !isset($_POST['gpx_start']))
{
    // Check section for all form elements
    if (empty($_POST['gpx_sql_hostname']))
    {
      $fail   = 1;
      $reason = "Hostname";
    }
    
    elseif (empty($_POST['gpx_sql_db']))
    {
      $fail   = 1;
      $reason = "Database";
    }
    
    elseif (empty($_POST['gpx_sql_user']))
    {
      $fail   = 1;
      $reason = "Database Username";
    }

    elseif (empty($_POST['gpx_sql_pass']))
    {
      $fail   = 1;
      $reason = "Database Password";
    }

    elseif (empty($_POST['gpx_email']))
    {
      $fail   = 1;
      $reason = "Email Address";
    }
    
    elseif (empty($_POST['gpx_admin_user']))
    {
      $fail   = 1;
      $reason = "Admin User";
    }

    elseif (empty($_POST['gpx_admin_pass']))
    {
      $fail   = 1;
      $reason = "Admin Password";
    }

    elseif (empty($_POST['gpx_admin_pass_confirm']))
    {
      $fail   = 1;
      $reason = "Admin Password Confirmation";
    }

    // Make sure admin passes are the same
    elseif ($_POST['gpx_admin_pass'] != $_POST['gpx_admin_pass_confirm'])
    {
      $fail   = 1;
      $reason = "Admin Passwords Do Not Match";
    }

    
    elseif (empty($_POST['gpx_script_path']))
    {
      $fail   = 1;
      $reason = "Script Path";
    }
    
    // Otherwise
    else
    {
      $fail = 0;
    }
    
    // If any of the above fail, header back to this page and give the reason it failed
    if ($fail == 1)
    {
        die('<b>Error:</b> ' . $reason);
    }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/default/main.css">
<title>GamePanelX | Installation</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="../include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="../css/default/img/gpx_grad.png" height="80"><img src="../css/default/img/gpx.png" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/default/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">GamePanelX Installation Page 2 of 3</span></td>
</tr>
</table>

<br /><br />

<center>
Please confirm the following to be sure you set them correctly.<br />
</center>

<br /><br />

<form method="post" action="<?php echo $PHP_SELF; ?>">
<table class="tablez" border="0" cellpadding="0" cellspacing="0" width="400" align="center">
  <tr background="../css/default/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Database Setup</span></td>
  </tr>

  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td align="right">Database Type:&nbsp;&nbsp;</td>
    <td align="left"><b><?php echo $_POST['gpx_sql_type']; ?></b></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Database Hostname:&nbsp;&nbsp;</td>
    <td align="left"><b><?php echo $_POST['gpx_sql_hostname']; ?></b></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Database Name:&nbsp;&nbsp;</td>
    <td align="left"><b><?php echo $_POST['gpx_sql_db']; ?></b></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Database Username:&nbsp;&nbsp;</td>
    <td align="left"><b><?php echo $_POST['gpx_sql_user']; ?></b></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Database Password:&nbsp;&nbsp;</td>
    <td align="left"><b>************</b></td>
  </tr>

  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr background="../css/default/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Administration Settings</span></td>
  </tr>

  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td align="right">Language:&nbsp;&nbsp;</td>
    <td align="left"><b><?php echo $_POST['gpx_language']; ?></b></td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>

  <tr class="rowz_title">
    <td align="right">Email Address:&nbsp;&nbsp;</td>
    <td align="left"><?php echo $_POST['gpx_email']; ?></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Admin Username:&nbsp;&nbsp;</td>
    <td align="left"><?php echo $_POST['gpx_admin_user']; ?></td>
  </tr>
  <tr class="rowz_title">
    <td align="right">Admin Password:&nbsp;&nbsp;</td>
    <td align="left">***</td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td align="right">Script Path:&nbsp;&nbsp;</td>
    <td align="left"><?php echo $_POST['gpx_script_path']; ?></td>
  </tr>

  <tr class="rowz_title">
    <td colspan="2">&nbsp;</td>
  </tr>
  
  <tr class="rowz_title">
    <td colspan="2" align="center">
<input type="submit" name="gpx_start" value="Start Install">
<input type="reset" name="gpx_reset" value="Reset Form">
<input type="hidden" name="gpx_language" value="<?php echo $_POST['gpx_language']; ?>">
<input type="hidden" name="gpx_sql_type" value="<?php echo $_POST['gpx_sql_type']; ?>">
<input type="hidden" name="gpx_sql_hostname" value="<?php echo $_POST['gpx_sql_hostname']; ?>">
<input type="hidden" name="gpx_sql_db" value="<?php echo $_POST['gpx_sql_db']; ?>">
<input type="hidden" name="gpx_sql_user" value="<?php echo $_POST['gpx_sql_user']; ?>">
<input type="hidden" name="gpx_sql_pass" value="<?php echo $_POST['gpx_sql_pass']; ?>">
<input type="hidden" name="gpx_admin_user" value="<?php echo $_POST['gpx_admin_user']; ?>">
<input type="hidden" name="gpx_admin_pass" value="<?php echo $_POST['gpx_admin_pass']; ?>">
<input type="hidden" name="gpx_admin_pass_confirm" value="<?php echo $_POST['gpx_admin_pass_confirm']; ?>">
<input type="hidden" name="gpx_email" value="<?php echo $_POST['gpx_email']; ?>">
<input type="hidden" name="gpx_script_path" value="<?php echo $_POST['gpx_script_path']; ?>">
<input type="hidden" name="gpx_main_ip" value="<?php echo $_POST['gpx_main_ip']; ?>">
    </td>
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
 
if (isset($_POST['gpx_start']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/default/main.css">
<title>GamePanelX | Installation</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="../images/loading.gif" border="0"></div>
<script src="../include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="../css/default/img/gpx_grad.png" height="80"><img src="../css/default/img/gpx.png" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="../css/default/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">GamePanelX Installation Page 3 of 3</span></td>
</tr>
</table>

<br /><br />

<?php
// Include functions
require_once('../include/functions.php');


// Generate an encryption key to be used for any encrypted database info
$encryption_key  = gen_random_text("all","64");

// Get POST values
$post_type          = $_POST['gpx_sql_type'];     // Mysql 4/5
$post_host          = $_POST['gpx_sql_hostname']; // Hostname
$post_user          = $_POST['gpx_sql_user'];     // Username
$post_pass          = $_POST['gpx_sql_pass'];     // Password
$post_db            = $_POST['gpx_sql_db'];       // DB Name
$post_language      = $_POST['gpx_language'];     // Language
$post_email         = $_POST['gpx_email'];        // Email Address
$post_script_path   = $_POST['gpx_script_path'];  // Script Path











// Insert DB info into "include/db.php"
$dbFile = "../include/db.php";

$fh = @fopen($dbFile, 'w') or die("<b>Error:</b> Unable to open database file '$dbFile' for writing.  Make sure the 'install' directory has write permissions set.");

// Begin PHP File
$stringData = '<?php' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write BEGIN to the database file \'$dbFile\'!");

// Add comments
$stringData = '// This file was automatically generated by the GamePanelX installer script.' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the Comment to the database file \'$dbFile\'!");

// Add host
$stringData = '$config[\'sql_host\'] = \'' . $post_host . '\';' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the Hostname to the database file \'$dbFile\'!");

// Add username
$stringData = '$config[\'sql_user\'] = \''. $post_user .'\';' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the Username to the database file \'$dbFile\'!");

// Add password
$stringData = '$config[\'sql_pass\'] = \''. $post_pass .'\';' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the Password to the database file \'$dbFile\'!");

// Add database
$stringData = '$config[\'sql_db\'] = \'' . $post_db . '\';' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the DB Name to the database file \'$dbFile\'!");

// Add encryption key for the database
$stringData = '$config[\'encrypt_key\'] = \'' . $encryption_key . '\';' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the Encryption Key to the database file \'$dbFile\'!");

// End PHP File
$stringData = '?>' . "\n";
@fwrite($fh, $stringData) or die("<b>Error:</b> Unable to write the END to the database file \'$dbFile\'!");

fclose($fh);












// Start MySQL Table creation, etc
$db = @mysql_connect($post_host,$post_user,$post_pass) or die("<b>Error:</b> Unable to connect to the database!");
@mysql_select_db($post_db) or die("<b>Error:</b> Unable to select the database!");

// Create configuration table
echo "Creating configuration table...<br /><br />";

$sql_configuration_create = "CREATE TABLE IF NOT EXISTS `configuration` (
  `setting` varchar(64) NOT NULL,
  `value` text NOT NULL
)";

@mysql_query($sql_configuration_create) or die("<b>Error:</b> Unable to create the 'configuration' table!");




//Define API key length
$api_key_length = 964;
//
// Generate an API key
$api_key = generate_api_key($api_key_length);

// Get server hostname
$server_hostname = $_SERVER["SERVER_NAME"];

// Insert configuration settings
@mysql_query("INSERT INTO configuration (setting,value) VALUES('Email','$post_email')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('Language','$post_language')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('CompanyName','GamePanelX')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('Hostname','$server_hostname')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('BaseDir','$post_script_path')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('Theme','default')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('PageTitle','$install__page_title')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('GPXVersion','$install__version')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('TopLogo','$install__top_logo')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('API_Key','$api_key')") or die("<b>Error:</b> Unable to create the specified configuration!");
@mysql_query("INSERT INTO configuration (setting,value) VALUES('StripClientCommands','Y')") or die("<b>Error:</b> Unable to create the specified configuration!");





// 'users' SQL Setup
$sql_users_create = "CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active` enum('Y','N') NOT NULL default 'Y',
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone`varchar(255) NOT NULL,
  `website` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `email_notify` enum('Y','N') NOT NULL default 'N',
  `original_ip` varchar(20) NOT NULL,
  `original_host` varchar(255) NOT NULL,
  `last_ip` varchar(20) NOT NULL,
  `last_host` varchar(255) NOT NULL,
  `is_admin` enum('Y','N') NOT NULL default 'N',
  `date_signup` datetime NOT NULL,
  `date_expire` date NOT NULL,
  `last_login` datetime NOT NULL,
  PRIMARY KEY  (`id`)
)";

// Create 'users' table
@mysql_query($sql_users_create) or die("<b>Error:</b> Unable to create the 'users' table!");





// Create 'remote' table
$create_remote_table = "CREATE TABLE IF NOT EXISTS `remote` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `date_added` datetime NOT NULL,
  `ip` varchar(20) NOT NULL,
  `available` enum('Y','N') default 'Y',
  `physical` enum('Y','N') default 'N',
  `parent` varchar(20) NOT NULL,
  `operating_system` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `datacenter` varchar(255) NOT NULL,
  `ssh_user` blob NOT NULL,
  `ssh_pass` blob NOT NULL,
  `ssh_port` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY  (`id`)
)";
@mysql_query($create_remote_table) or die("<b>Error:</b> Unable to create the 'remote' table!");



// Create Templates table
echo "Creating Templates table...<br /><br />";

$create_templates = "CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `server` varchar(64) NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `available` enum('Y','N') NOT NULL default 'Y',
  `is_default` enum('Y','N') NOT NULL default 'N',
  `description` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`)
)";
@mysql_query($create_templates) or die("<b>Error:</b> Unable to create the 'templates' table!");




// Create Servers table
echo "Creating Servers table...<br /><br />";

$create_servers_table = "CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `short_name` varchar(12) NOT NULL,
  `long_name` varchar(255) NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `available` enum('Y','N') NOT NULL default 'Y',
  `style` varchar(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `port` int(11) UNSIGNED NOT NULL,
  `reserved_ports` varchar(255) NOT NULL,
  `tcp_ports` varchar(255) NOT NULL,
  `udp_ports` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `max_slots` int(11) UNSIGNED NOT NULL default '12',
  `map` varchar(255) NOT NULL,
  `setup_cmd` varchar(255) NOT NULL,
  `cmd_line` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  `opt1_name` varchar(255) NOT NULL,
  `opt1_edit` varchar(255) NOT NULL default 'N',
  `opt1_value` varchar(255) NOT NULL,
  `opt2_name` varchar(255) NOT NULL,
  `opt2_edit` varchar(255) NOT NULL default 'N',
  `opt2_value` varchar(255) NOT NULL,
  `opt3_name` varchar(255) NOT NULL,
  `opt3_edit` varchar(255) NOT NULL default 'N',
  `opt3_value` varchar(255) NOT NULL,
  `opt4_name` varchar(255) NOT NULL,
  `opt4_edit` varchar(255) NOT NULL default 'N',
  `opt4_value` varchar(255) NOT NULL,
  `opt5_name` varchar(255) NOT NULL,
  `opt5_edit` varchar(255) NOT NULL default 'N',
  `opt5_value` varchar(255) NOT NULL,
  `opt6_name` varchar(255) NOT NULL,
  `opt6_edit` varchar(255) NOT NULL default 'N',
  `opt6_value` varchar(255) NOT NULL,
  `opt7_name` varchar(255) NOT NULL,
  `opt7_edit` varchar(255) NOT NULL default 'N',
  `opt7_value` varchar(255) NOT NULL,
  `opt8_name` varchar(255) NOT NULL,
  `opt8_edit` varchar(255) NOT NULL default 'N',
  `opt8_value` varchar(255) NOT NULL,
  `opt9_name` varchar(255) NOT NULL,
  `opt9_edit` varchar(255) NOT NULL default 'N',
  `opt9_value` varchar(255) NOT NULL,
  `opt10_name` varchar(255) NOT NULL,
  `opt10_edit` varchar(255) NOT NULL default 'N',
  `opt10_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";
@mysql_query($create_servers_table) or die("<b>Error:</b> Unable to create the 'servers' table!");





// Create User Servers Table
echo "Creating the User Servers table...<br /><br />";

$create_user_servers  = "CREATE TABLE IF NOT EXISTS `userservers` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `date_created` datetime NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `server` varchar(36) NOT NULL,
  `userid` int(11) UNSIGNED NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `port` int(11) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `max_slots` int(11) UNSIGNED NOT NULL,
  `map` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `cmd_line` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  `show_cmd_line` enum('Y','N') NOT NULL default 'N',
  `opt1_name` varchar(255) NOT NULL,
  `opt1_edit` varchar(255) NOT NULL default 'N',
  `opt1_value` varchar(255) NOT NULL,
  `opt2_name` varchar(255) NOT NULL,
  `opt2_edit` varchar(255) NOT NULL default 'N',
  `opt2_value` varchar(255) NOT NULL,
  `opt3_name` varchar(255) NOT NULL,
  `opt3_edit` varchar(255) NOT NULL default 'N',
  `opt3_value` varchar(255) NOT NULL,
  `opt4_name` varchar(255) NOT NULL,
  `opt4_edit` varchar(255) NOT NULL default 'N',
  `opt4_value` varchar(255) NOT NULL,
  `opt5_name` varchar(255) NOT NULL,
  `opt5_edit` varchar(255) NOT NULL default 'N',
  `opt5_value` varchar(255) NOT NULL,
  `opt6_name` varchar(255) NOT NULL,
  `opt6_edit` varchar(255) NOT NULL default 'N',
  `opt6_value` varchar(255) NOT NULL,
  `opt7_name` varchar(255) NOT NULL,
  `opt7_edit` varchar(255) NOT NULL default 'N',
  `opt7_value` varchar(255) NOT NULL,
  `opt8_name` varchar(255) NOT NULL,
  `opt8_edit` varchar(255) NOT NULL default 'N',
  `opt8_value` varchar(255) NOT NULL,
  `opt9_name` varchar(255) NOT NULL,
  `opt9_edit` varchar(255) NOT NULL default 'N',
  `opt9_value` varchar(255) NOT NULL,
  `opt10_name` varchar(255) NOT NULL,
  `opt10_edit` varchar(255) NOT NULL default 'N',
  `opt10_value` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";

@mysql_query($create_user_servers) or die("<b>Error:</b> Unable to create the 'userservers' table!");




// Insert supported servers into `servers`
$insert_all_servers = "INSERT INTO `servers` (`id`, `short_name`, `long_name`, `type`, `available`, `style`, `log_file`, `port`, `reserved_ports`, `tcp_ports`, `udp_ports`, `executable`, `max_slots`, `map`, `setup_cmd`, `cmd_line`, `working_dir`, `setup_dir`, `opt1_name`, `opt1_edit`, `opt1_value`, `opt2_name`, `opt2_edit`, `opt2_value`, `opt3_name`, `opt3_edit`, `opt3_value`, `opt4_name`, `opt4_edit`, `opt4_value`, `opt5_name`, `opt5_edit`, `opt5_value`, `opt6_name`, `opt6_edit`, `opt6_value`, `opt7_name`, `opt7_edit`, `opt7_value`, `opt8_name`, `opt8_edit`, `opt8_value`, `opt9_name`, `opt9_edit`, `opt9_value`, `opt10_name`, `opt10_edit`, `opt10_value`) VALUES
('', 'cs_16', 'Counter-Strike: 1.6', 'game', 'Y', 'FPS', 'cstrike/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'hlds_run', 16, 'de_dust2', 'chmod u+x ./steam ; ./steam -command update -game cstrike -dir .', './%executable% -game cstrike +ip %ip% +port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'cstrike', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'Half-Life TV', 'N', '0', 'Half-Life TV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_cz', 'Counter-Strike: Condition Zero', 'game', 'Y', 'FPS', 'czero/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'hlds_run', 16, 'de_dust2_cz', 'chmod u+x ./steam ; ./steam -command update -game czero -dir .', './%executable% -game cstrike +ip %ip% +port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'czero', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'Half-Life TV', 'N', '0', 'Half-Life TV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_s', 'Counter-Strike: Source', 'game', 'Y', 'FPS', 'cstrike/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'srcds_run', 16, 'de_dust2', 'chmod u+x ./steam ; ./steam -command update -game \"Counter-Strike Source\" dir .', 'cd %working_dir% ; ./%executable% -game cstrike -ip %ip% -port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'orangebox/', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_pm', 'Counter-Strike: Pro Mod', 'game', 'Y', 'FPS', 'cspromod/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'srcds_run', 16, 'csp_dust2', 'chmod u+x ./steam ; ./steam -command update -game \"Counter-Strike Source\" dir .', './%executable% -game cspromod -ip %ip% -port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'cspromod/', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod4', 'Call of Duty 4', 'game', 'Y', 'FPS', 'heya', 28960, '20500,20510,28960', '28960', '20500,20510,28960', 'cod4_lnxded', 16, 'mp_strike', './pbsetup.run -e ; ./pbsetup.run --add-game=cod4 --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', 'cod4', 'pbsetup', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', '+map_rotate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod2', 'Call of Duty 2', 'game', 'Y', 'FPS', 'games_mp.log', 28960, '', '28960', '20500,20510,28960', 'cod2_lnxded', 16, 'mp_strike', './pbsetup.run -e ; ./pbsetup.run --add-game=cod2 --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', '+map_rotate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod_waw', 'Call of Duty: World at War', 'game', 'Y', 'FPS', '', 28960, '', '28960', '20500,20510,28960', 'codwaw_lnxded', 16, 'mp_castle', './pbsetup.run -e ; ./pbsetup.run --add-game=codwaw --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1% %opt5%', '', '', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', 'map_rotate', 'Y', '+map_rotate', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'dod_s', 'Day of Defeat: Source', 'game', 'Y', 'FPS', 'orangebox/dod/logs', 27015, '27020,27040,27041', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'dod_anzio', './steam -command update -game dods -dir .', 'cd %working_dir% ; ./%executable% -game dod -ip %ip% -port %port% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'orangebox', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'Y %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'l4d', 'Left 4 Dead', 'game', 'Y', 'FPS', 'l4d/left4dead/logs', 27015, '27020,27039', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'l4d_airport01_greenhouse', './steam -command update -game \"l4d_full\" -dir .', 'cd %working_dir% ; ./%executable% -game left4dead -ip %ip% -port %port% +map %default_map% +exec %opt1% ', 'l4d', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'l4d_2', 'Left 4 Dead 2', 'game', 'Y', 'FPS', '%working_dir%/logs', 27015, '27015,27020,27040,27041,1200', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'c2m1_highway', './steam -command update -game \"left4dead2\" -dir .', 'cd %working_dir% ; ./%executable% -game left4dead2 -ip %ip% -port %port% +map %default_map% +exec %opt1%', 'l4d/left4dead2', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'ws_et', 'Wolfenstein: Enemy Territory', 'game', 'Y', 'FPS', '%working_dir%/etconsole.log', 27960, '27950,27952,27960,27965', '27950,27952,27960,27965', '27950,27952,27960,27965', 'etded', 16, 'oasis', './pbsetup.run -e ; ./pbsetup.run --add-game=et --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt3% +set sv_punkbuster %opt2% +map %default_map% +exec %opt1% +set sv_maxclients %max_players%', 'etmain', '', 'Exec Server Config', 'Y', 'server.cfg', 'Enable Punkbuster', 'Y', '1', 'Dedicated', 'N', '2', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'vent', 'Ventrilo', 'voip', 'Y', '', 'ventrilo_srv.log', 3784, '3784', '3784', '3784', 'ventrilo_srv', 8, '', '', './%executable% -d', '', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'ts', 'TeamSpeak', 'voip', 'Y', '', 'server.log', 8767, '8767,12345,14534,51234', '8767,12345,14534,51234', '8767,12345,14534,51234', 'server_linux', 12, '', '', './%executable%', 'work', 'sett', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'mrm', 'Murmur', 'voip', 'Y', '', 'log', 64738, '', '64738', '64738', 'murmur.x86', 12, '', '', './%executable% -ini %opt1%', '', '', 'Config File', 'N', 'murmur.ini', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '')";


@mysql_query($insert_all_servers) or die("<b>Error:</b> Unable to insert supported servers into the 'servers' table!");




////////////////////////////////////////////////////////////////////////////



$adminUser    = $_POST['gpx_admin_user'];
$adminPass    = $_POST['gpx_admin_pass'];
$adminEmail   = $_POST['gpx_admin_email'];
$ip           = $_SERVER['REMOTE_ADDR'];
$hostaddress  = gethostbyaddr($ip);

echo "Creating the admin account ...<br />";


// Insert admin user into 'users' table
@mysql_query("INSERT INTO users (username,password,active,email,is_admin,date_signup) VALUES('$adminUser',md5('$adminPass'),'Y','$adminEmail','Y',NOW())") or die("<b>Error:</b> Unable to create the admin account!");


// Close database connection
mysql_close($db);
?>

<br /><br />

<center>
<h2>Installation Successful!</h2><br />
<b>Note:</b> The 'install' directory <b>MUST</b> be deleted before you log in.<br /><br />

<a href="../login.php" style="color:#444">Click here to login</a>
</center>

</body>
</html>
<?php
 }
?>
