<?php
/*

GamePanelX

Description:  SSH2 Command: Start / Stop / Restart game servers

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('config.php');
include_once('auth.php');
include_once('typeInfo.php');
include_once('ssh2.php');
include_once('functions.php');

$action       = $_POST['action'];
$post_id      = base64_decode($_POST['main_id']);
$short_name   = base64_decode($_POST['main_server']);
$ipAddress    = base64_decode($_POST['ip']);
$allow_output = 1;

// Build the Command Line
$cmd_line = build_cmd_line($post_id);

// Strip off "./exe" from cmd line
$cmd_line = str_replace("./$executable ", "", $cmd_line);

// Get server options
$result_options = @mysql_query("SELECT id,userid,server,ip,port,executable FROM userservers WHERE id='$post_id'") or die('<b>Error:</b> Failed to query the userservers table!');

while($row_options = mysql_fetch_array($result_options))
{
    $server_id      = $row_options['id'];
    $server_userid  = $row_options['userid'];
    $server_name    = $row_options['server'];
    $server_ip      = $row_options['ip'];
    $server_port    = $row_options['port'];
    $server_exe     = $row_options['executable'];
}

// Get username from userid
$result_user = @mysql_query("SELECT username FROM users WHERE id='$server_userid'") or die('<b>Error:</b> Failed to query for username!');

while($row_user = mysql_fetch_array($result_user))
{
    $server_user = $row_user['username'];
}

// Gameserver nickname (IP:Port)
$server_nickname = $server_ip . ':' . $server_port;

// Full Command Line
$command = '$HOME' . "/_scripts/status.sh -i $server_user -g $server_name -e $server_exe -p '$cmd_line' -n $server_name" . "_" . $server_user . "_" . $server_nickname . " -j " . $server_nickname . " -r " . $randomNumber . " -k ";

// Restart Command
if ($action == 'restart')
{
    $command .= 'restart';
}

// Stop Command
elseif($action == 'stop')
{
    $command .= 'stop';
}

?>
