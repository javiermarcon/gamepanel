<?php
/*

GamePanelX

Description:  Export game data into an XML file

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


// Connect to the DB
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get game info
$result_gamez = @mysql_query("SELECT short_name,long_name,type,available,style,log_file,port,reserved_ports,tcp_ports,udp_ports,executable,max_slots,map,setup_cmd,cmd_line,working_dir,setup_dir FROM servers WHERE id='$url_id'") or die('<b>Error:</b> Failed to query the servers table!');

while($row_gamez = mysql_fetch_array($result_gamez))
{
    $short_name     = stripslashes($row_gamez['short_name']);
    $long_name      = stripslashes($row_gamez['long_name']);
    $type           = stripslashes($row_gamez['type']);
    $available      = stripslashes($row_gamez['available']);
    $style          = stripslashes($row_gamez['style']);
    $log_file       = stripslashes($row_gamez['log_file']);
    $port           = stripslashes($row_gamez['port']);
    $res_ports      = stripslashes($row_gamez['reserved_ports']);
    $tcp_ports      = stripslashes($row_gamez['tcp_ports']);
    $udp_ports      = stripslashes($row_gamez['udp_ports']);
    $executable     = stripslashes($row_gamez['executable']);
    $max_slots      = stripslashes($row_gamez['max_slots']);
    $map            = stripslashes($row_gamez['map']);
    $setup_cmd      = stripslashes($row_gamez['setup_cmd']);
    $cmd_line       = stripslashes($row_gamez['cmd_line']);
    $working_dir    = stripslashes($row_gamez['working_dir']);
    $setup_dir      = stripslashes($row_gamez['setup_dir']);
}


// Switch the available wording
if($available == 'Y')
{
    $xml_available = 'yes';
}
else
{
    $xml_available = 'no';
}

//
// Begin writing XML file for download
//

// File location (use local "tmp" directory)
$tmp_xml_file = "tmp/$short_name.xml";
$xml_error    = "<b>Error:</b> Unable to write the beginning to the XML file \'$tmp_xml_file\'!";

$open_xml = @fopen($tmp_xml_file, 'w') or die("<center><b>Error:</b> Unable to open the XML file '$tmp_xml_file' for writing.  Make sure your webserver has write permissions to the GamePanelX 'tmp' directory.</center>");

// Begin XML File
$xml_data = '<game>' . "\n";
@fwrite($open_xml, $xml_data) or die();

// Add all game options
@fwrite($open_xml, "  <short_name>$short_name</short_name>\n") or die($xml_error);
@fwrite($open_xml, "  <long_name>$long_name</long_name>\n") or die($xml_error);
@fwrite($open_xml, "  <type>$type</type>\n") or die($xml_error);
@fwrite($open_xml, "  <available>$xml_available</available>\n") or die($xml_error);
@fwrite($open_xml, "  <style>$style</style>\n") or die($xml_error);
@fwrite($open_xml, "  <log_file>$log_file</log_file>\n") or die($xml_error);
@fwrite($open_xml, "  <port>$port</port>\n") or die($xml_error);
@fwrite($open_xml, "  <reserved_ports>$res_ports</reserved_ports>\n") or die($xml_error);
@fwrite($open_xml, "  <tcp_ports>$tcp_ports</tcp_ports>\n") or die($xml_error);
@fwrite($open_xml, "  <udp_ports>$udp_ports</udp_ports>\n") or die($xml_error);
@fwrite($open_xml, "  <executable>$executable</executable>\n") or die($xml_error);
@fwrite($open_xml, "  <max_slots>$max_slots</max_slots>\n") or die($xml_error);
@fwrite($open_xml, "  <map>$map</map>\n") or die($xml_error);
@fwrite($open_xml, "  <setup_cmd>$setup_cmd</setup_cmd>\n") or die($xml_error);
@fwrite($open_xml, "  <cmd_line>$cmd_line</cmd_line>\n") or die($xml_error);
@fwrite($open_xml, "  <working_dir>$working_dir</working_dir>\n") or die($xml_error);
@fwrite($open_xml, "  <setup_dir>$setup_dir</setup_dir>\n") or die($xml_error);
@fwrite($open_xml, "  <game_opts>\n") or die($xml_error);



//
// Get all 10 config options, write those to the XML file
//
$query_game = "SELECT";

// Get all 10 config settings for this server
for($i=1; $i <= 10; $i++)
{
    $query_game = trim($query_game);
    
    // Get options
    $query_game .= ' opt' . $i . '_name,';
    $query_game .= 'opt' . $i . '_edit,';
    
    if($i == 10)
    {
        $query_game .= 'opt' . $i . '_value';
    }
    else
    {
        $query_game .= 'opt' . $i . '_value,';
    }
}

$query_game .= " FROM servers WHERE id='$url_id'";

// Run query
$result_game = @mysql_query($query_game) or die('<b>Error:</b> Failed to query the servers table!');

while($row_game = mysql_fetch_array($result_game))
{
    // Loop through all 10 config options
    for($i=1; $i <= 10; $i++)
    {
        // Option names
        $opt_name   = 'opt' . $i . '_name';
        $opt_edit   = 'opt' . $i . '_edit';
        $opt_value  = 'opt' . $i . '_value';
        
        // DB values
        $db_name    = $row_game[$opt_name];
        $db_edit    = $row_game[$opt_edit];
        $db_value   = $row_game[$opt_value];
        
        
        // Write this option to the XML file
        @fwrite($open_xml, "    <$opt_name>$db_name</$opt_name>\n") or die($xml_error);
        @fwrite($open_xml, "    <$opt_value>$db_value</$opt_value>\n") or die($xml_error);
        @fwrite($open_xml, "    <$opt_edit>$db_edit</$opt_edit>\n") or die($xml_error);
        
        // Add a newline if before 10 for looks
        if($i < 10)
        {
            @fwrite($open_xml, "\n") or die($xml_error);
        }
        
    }
}

// End game_opts
@fwrite($open_xml, "  </game_opts>\n") or die($xml_error);

// End XML File
$xml_data = '</game>' . "\n";
@fwrite($open_xml, $xml_data) or die("<b>Error:</b> Unable to write the end to the XML file \'$tmp_xml_file\'!");

fclose($open_xml);
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
  <td align="center" valign="middle"><span class="top_page_titles">Export XML File</span></td>
</tr>
</table>

<br /><br />

<center>
<b>Successfully created the file!</b>

<br /><br />

To save the file, right click, and select <b>Save Link As</b> or <b>Save target as</b>

<br /><br /><br />

<a href="<?php echo $tmp_xml_file; ?>" style="color:black">Click here to download the XML file</a>
</center>

</body>
</html>