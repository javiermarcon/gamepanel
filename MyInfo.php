<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.

*/
include('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/infobox.php');
include_once('include/statusInfo.php');
  
if (!isset($_POST['submit']))
{
    // Connect to database
    $db_host  = $config['sql_host'];
    $db_user  = $config['sql_user'];
    $db_pass  = $config['sql_pass'];
    $db_name  = $config['sql_db'];

    $db = mysql_connect($db_host,$db_user,$db_pass) or die('<b>Error:</b> Failed to connect to the database!');
    mysql_select_db($db_name) or die('<b>Error:</b> Failed to connect to the database!');

    // Get all personal info
    $result_personal = mysql_query("SELECT first_name,middle_name,last_name,email,phone,website,country,state,city,zip FROM users WHERE username='$GPXuserName'") or die('<b>Error:</b> Failed to query the database!');

    while($row_personal = mysql_fetch_array($result_personal))
    {
        // Strip all slashes off rows
        $stripped_first_name      = stripslashes($row_personal['first_name']);
        $stripped_middle_initial  = stripslashes($row_personal['middle_name']);
        $stripped_last_name       = stripslashes($row_personal['last_name']);
        $stripped_email           = stripslashes($row_personal['email']);
        $stripped_phone           = stripslashes($row_personal['phone']);
        $stripped_website         = stripslashes($row_personal['website']);
        $stripped_country         = stripslashes($row_personal['country']);
        $stripped_state           = stripslashes($row_personal['state']);
        $stripped_city            = stripslashes($row_personal['city']);
        $stripped_zip             = stripslashes($row_personal['zip']);
    }
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
  <td align="center" valign="middle"><span class="top_page_titles">Editar Mi Informacion</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="<?php echo $PHP_SELF; ?>">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="3" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Informacion Personal</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/my-info.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Contraseña: </span></td>
  <td align="left"><a href="ChangePassword.php"><b>Cambiar mi Contraseña</b></a></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Nombre: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_first_name; ?>" name="first" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt"> Inicial del Segundo N: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_middle_initial; ?>" name="middle" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Apellido: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_last_name; ?>" name="last" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt"> Email: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_email; ?>" name="email" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Telefonor: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_phone; ?>" name="phone" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Website: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_website; ?>" name="website" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Pais: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_country; ?>" name="country" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Provicia: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_state; ?>" name="state" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Ciudad: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_city; ?>" name="city" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">CP: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_zip; ?>" name="zip" maxlength="64" class="userinput" style="width:170px"></td>
</tr>


<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="3"><input type="submit" name="submit" value="Guardar Configuracion" style="width:170px"></td>
</tr>

</table>
</form>

</body>
</html>
<?php
}

// If submit is hit, update / show success
elseif (isset($_POST['submit']))
{
    // Connect to the database
    $db2           = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
                     mysql_select_db($config['sql_db']) or die(mysql_error());
    $updateError   = '<b>Error:</b> Update Failed!';

    // Escape all user input for mysql insertion
    $safe_first_name    = mysql_real_escape_string($_POST['first']);
    $safe_middle_name   = mysql_real_escape_string($_POST['middle']);
    $safe_last_name     = mysql_real_escape_string($_POST['last']);
    $safe_email         = mysql_real_escape_string($_POST['email']);
    $safe_phone         = mysql_real_escape_string($_POST['phone']);
    $safe_website       = mysql_real_escape_string($_POST['website']);
    $safe_country       = mysql_real_escape_string($_POST['country']);
    $safe_state         = mysql_real_escape_string($_POST['state']);
    $safe_city          = mysql_real_escape_string($_POST['city']);
    $safe_zip           = mysql_real_escape_string($_POST['zip']);
    
    // Connect to database
    $db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to connect to the database!');

    // Get all personal info
    $result_personal = mysql_query("SELECT first_name,middle_name,last_name,email,phone,website,country,state,city,zip FROM users WHERE username='$GPXuserName'") or die('<b>Error:</b> Failed to query the database!');

    while($row_personal = mysql_fetch_array($result_personal))
    {
        // Strip all slashes off rows
        $stripped_first_name      = stripslashes($row_personal['first_name']);
        $stripped_middle_initial  = stripslashes($row_personal['middle_name']);
        $stripped_last_name       = stripslashes($row_personal['last_name']);
        $stripped_email           = stripslashes($row_personal['email']);
        $stripped_phone           = stripslashes($row_personal['phone']);
        $stripped_website         = stripslashes($row_personal['website']);
        $stripped_country         = stripslashes($row_personal['country']);
        $stripped_state           = stripslashes($row_personal['state']);
        $stripped_city            = stripslashes($row_personal['city']);
        $stripped_zip             = stripslashes($row_personal['zip']);
    }
    
    // Make sure we don't update stuff that wasn't changed.
    if($_POST['first']    !=  $stripped_first_name)     { mysql_query("UPDATE users SET first_name='$safe_first_name' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['middle']   !=  $stripped_middle_initial) { mysql_query("UPDATE users SET middle_name='$safe_middle_name' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['last']     !=  $stripped_last_name)      { mysql_query("UPDATE users SET last_name='$safe_last_name' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['email']    !=  $stripped_email)          { mysql_query("UPDATE users SET email='$safe_email' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['phone']    !=  $stripped_phone)          { mysql_query("UPDATE users SET phone='$safe_phone' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['website']  !=  $stripped_website)        { mysql_query("UPDATE users SET website='$safe_website' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['country']  !=  $stripped_country)        { mysql_query("UPDATE users SET country='$safe_country' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['state']    !=  $stripped_state)          { mysql_query("UPDATE users SET state='$safe_state' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['city ']    !=  $stripped_city)           { mysql_query("UPDATE users SET city='$safe_city' WHERE username='$GPXuserName'") or die($updateError); }
    if($_POST['zip']      !=  $stripped_zip)            { mysql_query("UPDATE users SET zip='$safe_zip' WHERE username='$GPXuserName'") or die($updateError); }
    
    mysql_close($db2);

    // Include config again
    include_once('include/config.php');
    ?>
    <html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
    <title><?php echo $config['title']; ?> | Edit My Information</title>
    </head>

    <body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

    <div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
    <script src="include/loading.js"></script>

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
    <tr>
      <td align="center" valign="middle"><span class="top_page_titles">Edit My Information</span></td>
    </tr>
    </table>

    <br /><br />

    <center>
    <b>Success!</b>
    <br /><br />
    <a href="MyInfo.php">Click to here return</a>
    </center>

    </body>
    </html>
<?php
}
?>
