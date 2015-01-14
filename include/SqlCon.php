<?php
/*

GamePanelX

Description:  Include this for simple MySQL connection handling

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
function sqlCon($query)
{
    // Check for empty queries
    if(empty($query))
    {
        die('<b>Error:</b> The query was empty!');
    }
        
    include('config.php');

    //Make these global for use on other pages
    global $query, $result, $row, $num;

    // Connect to database
    $db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database (sqlcon)!');

    // Select database
    mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database (sqlcon)!');

    $result       = mysql_query($query) or die('<b>Error:</b> Failed to query the database (sqlcon)!');
    $row          = mysql_fetch_array($result);
    $num          = mysql_num_rows($result);

    // Close db connection
    mysql_close($db);
}
?>