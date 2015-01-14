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

//Make sure the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);
$isAdmin = $row['is_admin'];

if($isAdmin != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}
  
if (!isset($_POST['submit']))
{
    // Strip all slashes off rows
    $stripped_Hostname      = stripslashes($config['Hostname']);
    $stripped_base_dir      = stripslashes($config['BASE_DIR']);
    $stripped_theme         = stripslashes($config['theme']);
    $stripped_support_email = stripslashes($config['support_email']);
    $stripped_company_name  = stripslashes($config['CompanyName']);
    $stripped_title         = stripslashes($config['title']);
    $stripped_top_logo      = stripslashes($config['top_logo']);
    $stripped_api_key       = stripslashes($config['api_key']);
    $stripped_client_cmd    = stripslashes($config['strip_client_commands']);

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<title><?php echo $config['title']; ?> | User Details</title>
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">Cambiar la Configuracion del Panel de Control</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="Configuration.php">
<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="400">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Editar Configuracion</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/settings.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Hostname: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_Hostname; ?>" name="hostname" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Base Directory: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_base_dir; ?>" name="base_dir" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Default Theme: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_theme; ?>" name="theme" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Support Email: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_support_email; ?>" name="support_email" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Company Name: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_company_name; ?>" name="company_name" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Page Title: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_title; ?>" name="title" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Top Logo: </span></td>
  <td align="left"><input type="text" value="<?php echo $stripped_top_logo; ?>" name="top_logo" maxlength="64" class="userinput" style="width:170px"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">API Key: </span></td>
  <td align="left"><textarea style="width:170px;height:150px" name="api_key" maxlength="964"><?php echo $stripped_api_key; ?></textarea></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Configuraciones del Game Server</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="center" colspan="2">
  <?php
  // Strip Out Client Commands
  if($stripped_client_cmd == 'Y')
  {
      echo '<input type="checkbox" name="strip_client_commands" id="strip_client_commands" checked="yes">';
  }
  else
  {
      echo '<input type="checkbox" name="strip_client_commands" id="strip_client_commands">';
  }
  ?>
&nbsp;<span class="rowz_alt"><label for="strip_client_commands">Strip out client commands</label></span>
    </td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center" style="font-weight: normal">(This will remove any + or - characters from the beginning and end of any client-editable settings.)</td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
    <td align="center" colspan="2"><input type="submit" name="submit" value="Update" style="width:150px"></td>
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
    $db2 = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<center><b>Error:</b> Failed to connect to the database!</center>');
    @mysql_select_db($config['sql_db']) or die('<center><b>Error:</b> Failed to select the database!</center>');
    
    $updateError   = '<b>Error:</b> Update Failed!';

    // Escape all user input for mysql insertion
    $safe_hostname          = mysql_real_escape_string($_POST['hostname']);
    $safe_base_dir          = mysql_real_escape_string($_POST['base_dir']);
    $safe_theme             = mysql_real_escape_string($_POST['theme']);
    $safe_support_email     = mysql_real_escape_string($_POST['support_email']);
    $safe_title             = mysql_real_escape_string($_POST['title']);
    $safe_copyright         = mysql_real_escape_string($_POST['copyright']);
    $safe_top_logo          = mysql_real_escape_string($_POST['top_logo']);
    $safe_api_key           = mysql_real_escape_string($_POST['api_key']);
    $safe_company_name      = mysql_real_escape_string($_POST['company_name']);
    $safe_strip_client_cmd  = $_POST['strip_client_commands'];
    
    // Format 'strip client commands'
    if($safe_strip_client_cmd == 'on')
    {
        $safe_strip_client_cmd = 'Y';
    }
    else
    {
        $safe_strip_client_cmd = 'N';
    }
    
    // Make sure we don't update stuff that wasn't changed.
    if($safe_hostname           !=  $config['Hostname'])                { mysql_query("UPDATE configuration SET value='$safe_hostname' WHERE setting='Hostname'") or die($updateError); }
    if($safe_base_dir           !=  $config['BASE_DIR'])                { mysql_query("UPDATE configuration SET value='$safe_base_dir' WHERE setting='BaseDir'") or die($updateError); }
    if($safe_theme              !=  $config['theme'])                   { mysql_query("UPDATE configuration SET value='$safe_theme' WHERE setting='Theme'") or die($updateError); }
    if($safe_support_email      !=  $config['support_email'])           { mysql_query("UPDATE configuration SET value='$safe_support_email' WHERE setting='Email'") or die($updateError); }
    if($safe_title              !=  $config['title'])                   { mysql_query("UPDATE configuration SET value='$safe_title' WHERE setting='PageTitle'") or die($updateError); }
    if($safe_copyright          !=  $config['copyright'])               { mysql_query("UPDATE configuration SET value='$safe_copyright' WHERE setting='PageCopyright'") or die($updateError); }
    if($safe_top_logo           !=  $config['top_logo'])                { mysql_query("UPDATE configuration SET value='$safe_top_logo' WHERE setting='TopLogo'") or die($updateError); }
    if($safe_api_key            !=  $config['api_key'])                 { mysql_query("UPDATE configuration SET value='$safe_api_key' WHERE setting='API_Key'") or die($updateError); }
    if($safe_company_name       !=  $config['CompanyName'])             { mysql_query("UPDATE configuration SET value='$safe_company_name' WHERE setting='CompanyName'") or die($updateError); }
    if($safe_strip_client_cmd   !=  $config['strip_client_commands'])   { mysql_query("UPDATE configuration SET value='$safe_strip_client_cmd' WHERE setting='StripClientCommands'") or die($updateError); }
    
    mysql_close($db2);

    // Include config again
    include_once('include/config.php');
    ?>
    <html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
    <title><?php echo $config['title']; ?> | Configuration</title>
    </head>

    <body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

    <div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
    <script src="include/loading.js"></script>

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
    <tr>
      <td align="center" valign="middle"><span class="top_page_titles">Change GamePanelX Configuration</span></td>
    </tr>
    </table>

    <br /><br />

    <center>
    <b>Success!</b>
    <br /><br />
    Changes saved successfully.
    <br /><br />
    <a href="Configuration.php">Click here to return.</a>
    </center>

    </body>
    </html>
<?php
}
?>
