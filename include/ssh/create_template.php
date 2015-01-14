<?php
/*

GamePanelX

Description:  SSH2 Command: Create Game Template

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('config.php');
include_once('SqlCon.php');
include_once('auth.php');
include_once('typeInfo.php');
include_once('ssh2.php');
include_once('functions.php');

// Using POST values, tar up the directory and put it inside $HOME/_templates/.
// Also use the randomly-generated text to name the tar file.
// Then send the POST value of the tar file's name to the creategamerestore.php page.




// Decode all POST variables
$server       = base64_decode($_POST['server']);
$filePath     = base64_decode($_POST['file_path']);
$desc         = base64_decode($_POST['description']);
$type         = base64_decode($_POST['type']);
$available    = base64_decode($_POST['available']);
$is_default   = base64_decode($_POST['is_default']);
$ipAddress    = base64_decode($_POST['ip']);
$random_text  = base64_decode($_POST['random_text']);

// Template file path
$template_file_path = '$HOME' . '/_templates/' . $server;


// Strip slashes off the directory path
$filePath     = stripslashes($filePath);

// Tar filename
$tar_filename = $template_file_path . '/restore-' . $random_text . '.tar.gz';

// Change directory to template, run nice and create the tar file
$command = 'mkdir -p ' . $template_file_path . ' ; $HOME' . '/_scripts/create_game_template.sh -d ' . $filePath . ' -t ' . $tar_filename;

// Allow output
$allow_output = '1';


// If they selected 'Make Default', make sure no other templates for this server are default.
if ($is_default == 'Y')
{
      $query = "UPDATE templates SET is_default='N' WHERE server='$server'";
      sqlCon($query);
    

}

// Insert the row
$query = "INSERT INTO templates VALUES('','$server','$type','$available','$is_default','$desc','$tar_filename','$ipAddress')";
sqlCon($query);

//DEBUG:
//echo "Would CD to '$filePath', game: '$server', CMD: '$command', tar filename: '$tar_filename'<br />";
//exit;

?>
