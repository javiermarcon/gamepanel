<?php
/*

GamePanelX

Description:  Update Version File

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
error_reporting(E_ERROR);


//
// Update version 0.60 to version 0.62
//

require_once('../include/config.php');

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

//
// No database changes in this release
//

// Update version
if(!empty($install__version))
{
    @mysql_query("UPDATE configuration SET value = '$install__version' WHERE setting = 'GPXVersion'");
}

?>
