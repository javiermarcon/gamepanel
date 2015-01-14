<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

$isAdmin = $row['is_admin'];

if($row['is_admin'] == 'Y')
{
    // Get username from URL
    $userGET  = $_GET['user'];
}

// Show update page
if (!isset($_POST['update']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Cambiar Contraseña</span></td>
</tr>
</table>

<br /><br />

<center>
  <b>Nota:</b> Todos los campos son obligatorios
</center>

<br /><br />

<?php
if (!empty($userGET))
{
  echo '<form  method="post" action="' . $PHP_SELF . '?user=' . $userGET . '">';
}
else {
  echo '<form  method="post" action="' . $PHP_SELF . '">';
}
?>

<table border="0" class="tablez" width="400" cellpadding="0" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20" style="border-bottom:1px solid black">
  <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Cambiar Contraseña de tu Usuario</span></td>
</tr>
<?php
// Don't force giving current pass if user is an admin
if (empty($userGET))
{
?>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Contraseña Actual:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="current_pass" style="width:150px" class="userinput" maxlength="35"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<?php } ?>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Nueva Contraseña:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="new_pass" style="width:150px" class="userinput" maxlength="35"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Repita la contraseña:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="confirm_pass" style="width:150px" class="userinput" maxlength="35"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="submit" value="Update" name="update" style="width:150px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>
</table>

</body>
</html>

<?php
}

// If update is set, run check / change
elseif (isset($_POST['update']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Change Password</span></td>
</tr>
</table>

<br /><br />

<center>

<?php
// Nicer variables
$current_pass  = $_POST['current_pass'];
$new_pass      = $_POST['new_pass'];
$confirm_pass  = $_POST['confirm_pass'];
$user_name     = $_SESSION['usergpx'];
$user_pass     = $_SESSION['passgpx'];

// Use proper username for query
if (!empty($userGET) && $isAdmin == 'Y')
{
    $this_user = $userGET;
}
else
{
    $this_user = $user_name;
}

// Make SQL connection & get md5 password
$query = "SELECT password FROM users WHERE username='$this_user'";
sqlCon($query);
$sqlPass = $row['password'];
    
// If given password isn't the same as password in the database, error out.
// Otherwise, change password to new given value, as long as new passes match.
if($new_pass != $confirm_pass)
{
    die('<b>Error:</b> Your passwords do not match.  Please go back and try again.');
}

// Empty username
if (empty($userGET))
{
    if(md5($current_pass) != $sqlPass)
    {
        die('<b>Error:</b> Incorrect password specified.');
    }
}

// Make session's password become the new pass so it doesn't log us out afterwards
$new_passMD5 = md5($_POST['new_pass']);

// Change the pass
if (!empty($userGET) && $isAdmin == 'Y')
{
    $query = "UPDATE users SET password='$new_passMD5' WHERE username='$userGET'";
    sqlCon($query);
}
else
{
    $query = "UPDATE users SET password='$new_passMD5' WHERE username='$user_name'";
    sqlCon($query);
}
?>
        
<center>Password has been successfully updated!<br /><br />
<?php
if (!empty($userGET))
{
    echo '<a href="UserEditor.php" target="_SELF">Back to User Editor</a></center>';
}
else
{
    echo '<a href="MyInfo.php" target="_SELF">Back to User Details</a></center>';
}
?>
</center>

</body>
</html>
<?php
}
?>
