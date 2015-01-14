<?php
/*

GamePanelX

Description:  SSH2 Command: Delete Game Template

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once("config.php");
include_once("SqlCon.php");
include_once("auth.php");
include_once("typeInfo.php");
include_once("ssh2.php");
include_once("functions.php");

// Decode variables
$file_path  = base64_decode($_POST['file_path']);
$ipAddress  = base64_decode($_POST['ip']);
$delList    = base64_decode($_POST['del_list']);

// Add deletion for these
$allow_output = 1;

// SSH Command
$command = '$HOME' . "/_scripts/remove_game_template.sh $file_path";

//Delete selected servers from db
$query = "DELETE FROM templates WHERE id IN($delList)";
sqlCon($query);

?>
