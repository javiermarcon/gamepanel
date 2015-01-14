<?php
/*

GamePanelX

Description:  Script to SSH to a gameserver and send a command to a GNU Screen process

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
require_once('config.php');
require_once('auth.php');
require_once('ssh2.php');

// POST Stuff
$game_id  = $_POST['server_id'];
$game_ip  = $_POST['server_ip'];

$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get parent IP Address
$result_parent  = @mysql_query("SELECT parent FROM remote WHERE ip='$game_ip' AND physical='N' AND available='Y'") or die('<b>Error:</b> Failed to query the database!');
$num_result     = mysql_num_rows($result_parent);


// Check if there is a parent server for this
if($num_result >= 1)
{
    while($row_parent = mysql_fetch_array($result_parent))
    {
        // Number of games returned
        $parent_server = $row_parent['parent_server'];
    }
}
// Otherwise, use the game IP
else
{
    $parent_server = $game_ip;
}


$result_ssh_info = @mysql_query("SELECT id,ip,ssh_port FROM remote WHERE ip='$parent_server'") or die('<b>Error:</b> Failed to query the database!');

while ($row_ssh_info = mysql_fetch_array($result_ssh_info))
{
    $id             = $row_ssh_info['id'];
    $ipAddress      = $row_ssh_info['ip'];
    $ssh_port       = $row_ssh_info['ssh_port'];
}

// Get SSH Key from config
$ssh_key = $config['encrypt_key'];

// SSH Username
$result_user  = @mysql_query("SELECT AES_DECRYPT(ssh_user, '$ssh_key') AS decrypted_user FROM remote WHERE id='$id'") or die('<b>Error:</b> Failed to query the database!');

while ($row_user = mysql_fetch_array($result_user))
{
    $ssh_user = $row_user['decrypted_user'];
}

// SSH Password
$result_pass  = @mysql_query("SELECT AES_DECRYPT(ssh_pass, '$ssh_key') AS decrypted_pass FROM remote WHERE id='$id'") or die('<b>Error:</b> Failed to query the database!');

while ($row_pass = mysql_fetch_array($result_pass))
{
    $ssh_pass = $row_pass['decrypted_pass'];
}

// Screen Name/ID (game_username_ip:port)
$screen_nick  = $_POST['game_name'] . '_' . $_POST['game_username'] . '_' . $_POST['game_ip'] . ':' . $_POST['game_port'];


// Check user input
$game_cmd = addslashes($_POST['game_cmd']);
$game_cmd = str_replace(";", "", $game_cmd);
$game_cmd = str_replace('"', "", $game_cmd);
$game_cmd = str_replace("'", "", $game_cmd);

// GNU Screen Command
$command  = "screen -p 0 -S $screen_nick -X eval 'stuff \"$game_cmd\"\\015' ; echo success";

// Allow printing on other pages
$allow_return = '2';

// Run SSH Command
@connect_ssh($ipAddress, $ssh_port, $ssh_user, $ssh_pass, $command, $allow_return, $ssh_timeout);

// Redirect back to game edit
header("Location: ../AdminServerEdit.php?id=$game_id");
exit(0);

?>

