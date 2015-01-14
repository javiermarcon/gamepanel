<?php
/*

GamePanelX

Description:  "Main", "Refresh", and "Back a page" on the top of all pages

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('config.php');
include_once('auth.php');
?>
<table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="border-bottom:1px solid black;background-color:333333">
  <tr>
<td valign="middle" align="left" width="600" border="0">

<?php
// Connect to database if not yet connected
if(!$db)
{
    $db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
    mysql_select_db($config['sql_db']) or die(mysql_error());
}

//Make check to see if the logged in user is an admin.
$result = mysql_query("SELECT is_admin FROM users WHERE username='$GPXuserName'") or die(mysql_error());

while($row = mysql_fetch_array($result))
{
    // Admin users see the 'AdminMain.php' page
    if ($row['is_admin'] == 'Y')
    {
        $main_location = $config['BASE_DIR'] . '/AdminMain.php';
    }
    // Normal users see the 'Main.php' page
    else
    {
        $main_location = $config['BASE_DIR'] . '/Main.php';
    }
}

mysql_close($db);

?>
&nbsp;&nbsp;<a href="<?php echo $main_location; ?>" style="color:white">Main</a>&nbsp;-&nbsp;<a href="JavaScript:location.reload(true);" style="color:white">Refresh</a>&nbsp;-&nbsp;<a href="javascript:history.go(-1)" target="_SELF" style="color:white">Back a page</a></td>
<td valign="middle" align="right" border="0"><font color="#FFFFFF"><b><?php echo date("l, F d, Y h:iA");; ?></b></font></td>
    <td></td>
  </tr>
</table>