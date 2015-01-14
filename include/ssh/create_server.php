<?php
/*

GamePanelX

Description:  SSH2 Command: Create new game server

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once("config.php");
include_once("SqlCon.php");
include_once("auth.php");
include_once("typeInfo.php");
include_once("ssh2.php");
include_once("functions.php");

// Decode all variables encoded in base64
$decoded_dir        = base64_decode($_POST['gpx_dir']);
$decoded_hostname   = base64_decode($_POST['hostname']);
$decoded_server     = base64_decode($_POST['server']);
$ipAddress          = base64_decode($_POST['ip']);
$decoded_file_path  = base64_decode($_POST['file_path']);
$decoded_user       = base64_decode($_POST['user']);
$decoded_nick       = base64_decode($_POST['server_nickname']);

// SSH Command
$command = '$HOME' . "/_scripts/create_game_server.sh -u $decoded_user -g $decoded_server -N $decoded_nick -t $decoded_file_path";

// Allow output
$allow_output = 1;

?>
