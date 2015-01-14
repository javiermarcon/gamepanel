<?php
/*

GamePanelX

Description:  SSH2 Command: Create new game server

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('config.php');
include_once('SqlCon.php');
include_once('auth.php');
include_once('typeInfo.php');
include_once('ssh2.php');
include_once('functions.php');

$command      = 'echo "Test connection successful!"';

// Allow output
$allow_output = 1;

?>
