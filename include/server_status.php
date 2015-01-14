<?php
/*

GamePanelX

Description:  Script to SSH to a gameserver an get server status

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
require_once('ssh2.php');

$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get parent IP Address
$result_parent  = @mysql_query("SELECT parent FROM remote WHERE ip='$server_ip' AND physical='N' AND available='Y'") or die('<b>Error:</b> Failed to query the database!');
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
    $parent_server = $server_ip;
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

// Echo back
$command = '$HOME' . '/_scripts/check_status.sh -i ' . $server_ip . ' -p ' . $server_port;

// Allow printing on other pages
$allow_return = '2';

// Run SSH Command
$gs_status = @connect_ssh($ipAddress, $ssh_port, $ssh_user, $ssh_pass, $command, $allow_return, $ssh_timeout);

?>
