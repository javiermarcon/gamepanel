<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


*/
include_once("include/SqlCon.php");
include_once("include/config.php");

//Add slashes to username / md5 the password
$_POST['usergpx'] = addslashes($_POST['usergpx']);
$_POST['passgpx'] = md5($_POST['passgpx']);

// POST Vars
$post_username  = $_POST['usergpx'];
$post_password  = $_POST['passgpx'];

$query = "SELECT COUNT(id) AS thecount FROM users WHERE username='$post_username' AND password='$post_password'";
sqlCon($query);
$num2 = mysql_result($result, 0);

// If user is already logged in and comes back to login, forward to 'Home' page
if(isset($_SESSION['usergpx']) && isset($_SESSION['passgpx']) && isset($_SESSION['online']))
{
    header('Location: Home.php');
    exit(0);
}

//If user has not yet been authenticated...
if (!isset($_POST['login']))
{
?>
<html>
<head>
<title><?php echo $config['title']; ?> | Please Login</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
</head>

<body bgcolor="#000000" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="<?php echo $config['BASE_DIR'] . '/css/' . $config['theme'] . '/img/gpx_grad.png'; ?>" height="80"><img src="<?php echo $config['BASE_DIR'] . '/' . $config['top_logo']; ?>" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<br />


<?php
//
// Check for 'install' dir
//
if(file_exists('install'))
{
    die('<br /><br /><center><b>Error:</b> You <b>must</b> move or delete the "install" directory before you can login.</center>');
}

//
// Check if the PECL SSH2 Module exists
//
if(!function_exists('ssh2_connect'))
{
    die('<br /><br /><center><b>Error:</b> The PECL SSH2 module is not installed!  You must install this to connect to Remote Servers.<br /><br /><a href="http://www.gamepanelx.com/wiki/index.php?title=Install_SSH2_Module" target="_blank" style="color:#333;text-decoration:underline">Click here for more info</a>.</center>');
}
?>


<form action="login.php" method='post'>
<table border="0" class="login_table" align="center" width="400" cellpadding="2" cellspacing="2">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles"><b><?php echo $config['CompanyName']; ?></b> - Por Favor Logueate</span></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="35" align="right"><span style="font-family:Arial;font-size:14px;color:black;font-weight:bold">Username:</span></td>
    <td width="35"><input type="text" name="usergpx" class="loginbox"></td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="35" align="right"><span style="font-family:Arial;font-size:14px;color:black;font-weight:bold">Password:</span></td>
    <td width="35"><input type="password" name="passgpx" class="loginbox"></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <input type="submit" value="Entrar" name="login" style="width:150px">
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form>

</body>
</html>
<?
}

// Failed login
elseif (!$num2)
{
?>
<html>
<head>
<title><?php echo $config['title']; ?> | Please Login</title>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
</head>

<body bgcolor="#000000" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
  <tr>
    <td align="center" width="100%" background="<?php echo $config['BASE_DIR'] . '/css/' . $config['theme'] . '/img/gpx_grad.png'; ?>" height="80"><img src="<?php echo $config['BASE_DIR'] . '/' . $config['top_logo']; ?>" border="0" alt="GamePanelX" name="gpx" /></td>
  </tr>
</table>

<br />

<form action="login.php" method='post'>
<table border="0" class="login_table" align="center" width="400" cellpadding="2" cellspacing="2">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td colspan="12" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles"><b><?php echo $config['CompanyName']; ?></b> - Please Login</span></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><b>Error:</b> Invalid username/password</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="35" align="right"><font family="Arial"><b>Username:</b></font></td>
    <td width="35"><input type="text" name="usergpx" class="loginbox"></td>
  </tr>
  <tr>
    <td width="20">&nbsp;</td>
    <td width="35" align="right"><span style="font-family:Arial;font-size:14px"><b>Password:</b></span></td>
    <td width="35"><input type="password" name="passgpx" class="loginbox"></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
      <input type="submit" value="Login" name="login" style="width:150px">
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</form>

</body>
</html>
<?
}

elseif (isset($_POST['login']) && $num)
{
    // POST username
    $user = $_POST['usergpx'];

    // Make sure we don't lock any admins out, regardless whether they are somehow suspended
    $query = "SELECT is_admin FROM users WHERE username='$user'";
    sqlCon($query);
    $is_admin = $row['is_admin'];

    // Check if they're an admin
    if ($is_admin != 'Y')
    {
        // Check if this account is suspended or not.
        $query = "SELECT active FROM users WHERE username='$user'";
        sqlCon($query);
        $is_active = $row['active'];

        // Check if active
        if ($is_active != "Y")
        {
            // Grab support email from SQL
            $query = "SELECT value FROM configuration WHERE setting='Email'";
            sqlCon($query);
            $support_email  = $row['value'];
            ?>
            <html>
            <head>
            <title><?php echo $config['title']; ?> | Please Login</title>
            <link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
            <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW, NOARCHIVE, NOSNIPPET">
            </head>

            <body bgcolor="#000000" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">

            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="border-bottom:1px solid black">
              <tr>
                <td align="center" width="100%" background="<?php echo $config['BASE_DIR'] . '/css/' . $config['theme'] . '/img/gpx_grad.png'; ?>" height="80"><img src="<?php echo $config['BASE_DIR'] . '/' . $config['top_logo']; ?>" border="0" alt="GamePanelX" name="gpx" /></td>
              </tr>
            </table>

            <br />

            <center>
            <b>Error:</b> This account is currently suspended.<br /><br />Please contact support at <a href="mailto:<?php echo $support_email; ?>"><?php echo $support_email; ?></a> as soon as possible.
            </center>

            </body>
            </html>
            <?php

            // Kill the page
            exit(0);
        }
    }

    // Looks like they authenticated and are active...allow them to log in
    session_start();

    // Username session variable
    $_SESSION['usergpx'] = $_POST['usergpx'];
    $_SESSION['passgpx'] = $_POST['passgpx'];
    $_SESSION['online'] = 1;
    
    // Update database with this login
    $user_ip    = $_SERVER['REMOTE_ADDR'];
    $user_host  = gethostbyaddr($user_ip);
    
    $query = "UPDATE users SET last_ip='$user_ip',last_host='$user_host',last_login=NOW() WHERE username='$user'";
    sqlCon($query);

    //Forward to main page
    header('Location: Home.php');
    exit(0);
}
?>
