<?php
/*

GamePanelX

Description:  Include this file for authentication

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/

// Start the login session
session_start();

//Define simpler login variables
$GPXuserName = $_SESSION['usergpx'];

//Make user/pass global for use on other pages
global $GPXuserName, $GPXuserPass;

// Check for unauthenticated user
if(!isset($_SESSION['usergpx']))
{
    $header_location = $config['BASE_DIR'] . '/login.php';
    
    // Send them to login page
    header("Location: $header_location");
    exit(0);
}

?>