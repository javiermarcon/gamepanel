<?php
/*

GamePanelX

Description:  View/edit currently supported games

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
  <td align="center" valign="middle"><span class="top_page_titles">XML File Importer</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="XMLImport.php" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">XML Import - Create a New Supported Server</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/xml-64.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center" style="font-weight:normal">Use this tool to upload a properly formatted XML document.<br />This will create a new supported game or voip server.</td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Upload XML File: </span></td>
  <td align="left"><input name="uploadedfile" type="file" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="submit" value="Upload File" name="submit" style="width:170px" /></td>
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
  <td align="center" valign="middle"><span class="top_page_titles">XML File Importer</span></td>
</tr>
</table>

<br /><br />
<?php
    // Connect to database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

    // Upload location
    $upload_dir     = "tmp/";
    $xml_filename   = $upload_dir . basename( $_FILES['uploadedfile']['name']);
    $file_type      = $_FILES['uploadedfile']['type'];
    
    // Make sure this is an XML File
    if($file_type != "text/xml")
    {
        die('<center><b>Error:</b> Invalid file type!  You must upload a valid XML file.</center>');
    }
    
    // Move uploaded XML File
    if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $xml_filename))
    {
        die('<b>Error:</b> Failed to upload XML file!');
    }

    // Load XML file
    $xml = simplexml_load_file($xml_filename);
    
    // Parse all XML data
    $server_type          = $xml->type;
    $server_short_name    = $xml->short_name;
    $server_long_name     = $xml->long_name;
    $server_available     = $xml->available;
    $server_style         = $xml->style;
    $server_log_file      = $xml->log_file;
    $server_port          = $xml->port;
    $server_res_ports     = $xml->reserved_ports;
    $udp_game_ports       = $xml->tcp_ports;
    $tcp_game_ports       = $xml->udp_ports;
    $server_executable    = $xml->executable;
    $server_max_slots     = $xml->max_slots;
    $server_map           = $xml->map;
    $server_setup_cmd     = $xml->setup_cmd;
    $server_cmd_line      = $xml->cmd_line;
    $server_working_dir   = $xml->working_dir;
    $server_setup_dir     = $xml->setup_dir;
    $server_opts          = $xml->game_opts;
    
    // Replace max players
    if(empty($server_max_slots))
    {
        $server_max_slots = $xml->max_players;
    }
    
    // Startup Map
    if(empty($server_map))
    {
        if(!empty($xml->startup_map))
        {
            $server_map = $xml->startup_map;
        }
        else
        {
            $server_map = $xml->default_map;
        }
    }
    
    // Format 'game available'
    if($server_available == 'yes')
    {
        $server_available = 'Y';
    }
    else
    {
        $server_available = 'N';
    }
    
    // Check server type
    if($server_type != 'game' && $server_type != 'voip' && $server_type != 'other' && $server_type != '')
    {
        die('<center><b>Error:</b> Invalid XML file!  The server type was incorrect. Please use "game","voip", or "other".  Please reformat the document and try again.</center>');
    }
    
    // Make sure required options weren't empty
    if(empty($server_short_name) || empty($server_long_name) || empty($server_executable) || empty($server_cmd_line))
    {
        die('<center><b>Error:</b> Invalid XML file!  A required option was left empty (short name,long name,executable,cmd line). Please reformat the document and try again.</center>');
    }
    
    // Create insert query
    $insert_query = "INSERT INTO servers VALUES('','$server_short_name','$server_long_name','$server_type','$server_available','$server_style','$server_log_file','$server_port','$server_res_ports','$tcp_game_ports','$udp_game_ports','$server_executable','$server_max_slots','$server_map','$server_setup_cmd','$server_cmd_line','$server_working_dir','$server_setup_dir',";
    
    // Loop through all 10 game options
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_value  = 'opt' . $i . '_value';
        $opt_edit   = 'opt' . $i . '_client_edit';
        
        // Current values
        $this_opt_name    = $xml->game_opts->$opt_name;
        $this_opt_value   = $xml->game_opts->$opt_value;
        $this_opt_edit    = $xml->game_opts->$opt_edit;
        
        // Add these options to the insert query
        if($i == 10)
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value')";
        }
        else
        {
            $insert_query .= "'$this_opt_name','$this_opt_edit','$this_opt_value',";
        }
    }
    
    // DEBUG:
    //echo "Insert query: '$insert_query'<br>";
    
    // Insert game into database
    @mysql_query($insert_query) or die('<b>Error:</b> Failed to insert server into database!');
    
?>

<center>
<b>Success!</b>
<br /><br />
Successfully imported your supported server.
<br /><br />
<a href="SupportedServers.php?type=<?php echo $server_type; ?>">Click here to go back</a>
</center>

</body>
</html>
<?php
}
