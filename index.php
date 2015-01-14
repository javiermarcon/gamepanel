<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.

*/

//
// Make sure nobody can log in until the install folder is deleted
//
if(file_exists('install'))
{
    header("Location: install/");
    exit;
}

########################################################################

include_once('include/config.php'); 
include_once('include/auth.php');

// Send logged in users back home
if(isset($_SESSION['usergpx']))
{
    header('Location: Home.php');
    exit;
}
// Otherwise send to login
else
{
    header('Location: login.php');
    exit;
}
?>
