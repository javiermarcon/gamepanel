<?php
/*

Imperial Host

Description:  Main configuration file

NOTE: Make your database changes inside "include/db.php".
This file does NOT need to be changed.  All configuration is done from within the game panel.

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
session_start();
$config = array();

// Set Error Reporting to errors only
error_reporting(E_ERROR);

// Don't let the pages just hang and hang
set_time_limit(12);

// Include database credentials
include('db.php');

// Connect to database server and grab configuration settings
$db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to database (config)!');
mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database (config)!');

// Default error
$query_error = '<b>Error:</b> Couldn\'t execute configuration query';



// ---------------------------------------------------------------------------------------------------------------------------



// Company Name
$result = mysql_query("SELECT value FROM configuration WHERE setting='CompanyName'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['CompanyName'] = $row['value'];
}


// Language
$result = mysql_query("SELECT value FROM configuration WHERE setting='Language'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['Language'] = $row['value'];
}


// Version
$result = mysql_query("SELECT value FROM configuration WHERE setting='GPXVersion'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['GPX-VERSION'] = $row['value'];
}


// Hostname
$result = mysql_query("SELECT value FROM configuration WHERE setting='Hostname'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['Hostname'] = $row['value'];
}


// Base Directory
$result = mysql_query("SELECT value FROM configuration WHERE setting='BaseDir'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['BASE_DIR'] = $row['value'];
}


// Theme
$result = mysql_query("SELECT value FROM configuration WHERE setting='Theme'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['theme'] = $row['value'];
}


// Support Email
$result = mysql_query("SELECT value FROM configuration WHERE setting='Email'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['support_email'] = $row['value'];
}


// Page Title
$result = mysql_query("SELECT value FROM configuration WHERE setting='PageTitle'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['title'] = $row['value'];
}


// Top Logo Image
$result = mysql_query("SELECT value FROM configuration WHERE setting='TopLogo'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['top_logo'] = $row['value'];
}


// API Key
$result = mysql_query("SELECT value FROM configuration WHERE setting='API_Key'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['api_key'] = $row['value'];
}

// Strip Client Commands
$result = mysql_query("SELECT value FROM configuration WHERE setting='StripClientCommands'") or die($query_error);
while($row = mysql_fetch_array($result))
{
    $config['strip_client_commands'] = $row['value'];
}
?>
