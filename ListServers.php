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

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}


// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
}


if (!isset($_POST['submit']))
{
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
<META NAME="ROBOTS" CONTENT="NONE">
<META NAME="GOOGLEBOT" CONTENT="NONE">
<META NAME="Slurp" CONTENT="NONE">
</head>

<body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

<div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
<script src="include/loading.js"></script>

<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
<tr>
  <td align="center" valign="middle"><span class="top_page_titles">
<?php
if($url_server_type == 'game')
{
    echo 'Game Servers';
}
elseif($url_server_type == 'voip')
{
    echo 'Voip Servers';
}
elseif($url_server_type == 'other')
{
    echo 'Other Servers';
}
else
{
    echo 'User Servers';
}
?>
</span></td>
</tr>
</table>

<br /><br />

<center>
<?php
if($url_server_type == 'game')
{
    echo '<form action="ListServers.php?type=game" method="post"><img src="images/main/supported_games.png" border="0" />';
}
elseif($url_server_type == 'voip')
{
    echo '<form action="ListServers.php?type=voip" method="post"><img src="images/main/supported_voip.png" border="0" />';
}
else
{
    echo '<form action="ListServers.php" method="post"><img src="images/servers/medium/unsupported.png" border="0" />';
}
?>
</center>

<br />

<table border="0" class="tablez" cellspacing="0" cellpadding="0" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;vertical-align: middle;" align="center">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" class="gametable" height="20">
    <td style="border-bottom:1px solid black" align="center" width="30"><span class="top_titles">&nbsp;</span></td>
    <td style="border-bottom:1px solid black" align="left" width="40"><span class="top_titles">&nbsp;</span></td>
    <td style="border-bottom:1px solid black" align="left" width="140"><span class="top_titles">Nombre del Server</span></td>
    <td style="border-bottom:1px solid black" align="left" width="100"><span class="top_titles">Usuario</span></td>
    <td style="border-bottom:1px solid black" align="left" width="160"><span class="top_titles">Info de Coneccion</span></td>
    <td style="border-bottom:1px solid black" align="left" width="100"><span class="top_titles">Descripcion</span></td>
    <td style="border-bottom:1px solid black" align="center" width="80"><span class="top_titles">Max Slots</span></td>
    <td style="border-bottom:1px solid black" align="center"><span class="top_titles">&nbsp;</span></td>
  </tr>
<?php
// Connect to database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

// Get all User Servers
$server_query   = "SELECT 
                    userservers.id,
                    userservers.server,
                    userservers.ip,
                    userservers.port,
                    userservers.max_slots,
                    userservers.description,
                    users.username,
                    servers.short_name,
                    servers.long_name 
                 FROM userservers 
                 LEFT JOIN users 
                    ON userservers.userid = users.id 
                 LEFT JOIN servers 
                    ON servers.short_name = userservers.server";
                 
                 
                 // Make the correct query based on the URL
                 if($url_server_type == 'game')
                 {
                    $where_query = "WHERE userservers.type = 'game'";
                 }
                 elseif($url_server_type == 'voip')
                 {
                    $where_query = "WHERE userservers.type = 'voip'";
                 }
                 elseif($url_server_type == 'other')
                 {
                    $where_query = "WHERE userservers.type = 'other'";
                 }
                 else
                 {
                    $where_query = " ";
                 }
                 
                 $server_query .= " $where_query ORDER BY userservers.date_created,userservers.server DESC";

$result_server  = @mysql_query($server_query) or die('<b>Error:</b> Failed to query the userservers table!');
$num_server     = mysql_num_rows($result_server);

// Include alternating row colors
include('include/colors.php');

// Output for no rows
if($num_server == 0)
{
?>
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onMouseOver="style.backgroundColor='<?php echo $bgOpp; ?>'" onMouseOut="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center" colspan="12"><span class="rowz_alt">No hay resultados </span></td>
</tr>
<?php
}



while($row_game = mysql_fetch_array($result_server))
{
    // Include alternating row colors
    include('include/colors.php');
            
    // Strip all slashes off rows
    $this_id                = $row_game['id'];
    $stripped_short_name    = stripslashes($row_game['short_name']);
    $stripped_long_name     = stripslashes($row_game['long_name']);
    $stripped_username      = stripslashes($row_game['username']);
    $stripped_ip            = stripslashes($row_game['ip']);
    $stripped_port          = stripslashes($row_game['port']);
    $stripped_max_slots     = stripslashes($row_game['max_slots']);
    $stripped_description   = stripslashes($row_game['description']);

    // Show table row(s)
    echo '<tr valign="middle" style="vertical-align: middle;background-color:' . $bgColor . '" onmouseover="style.backgroundColor=\'' . $bgOpp . '\'" onmouseout="style.backgroundColor=\'' . $bgColor . '\'">';
    echo '<td align="center"><input name="delete[]" id="delete[]" type="checkbox" value="' . $this_id . '"></td>';
    echo '<td align="left" valign="middle" style="vertical-align:middle;line-height:28px"><img src="images/servers/small/' . $stripped_short_name . '.png" width="28" height="28" /></b></td>';
    echo '<td align="left"><b>' . $stripped_long_name . '</b></td>';
    echo '<td align="left">' . $stripped_username . '</td>';
    echo '<td align="left">' . $stripped_ip . ':' . $stripped_port . '</td>';
    echo '<td align="left">' . $stripped_description . '</td>';
    echo '<td align="center">' . $stripped_max_slots . '</td>';
    echo '<td align="center"><input type="button" value="Edit" onclick="window.location=\'AdminServerEdit.php?id=' . $this_id . '\'"></td>';
    echo '</tr>';

}
?>
</table>

<br /><br />

<center>
  <span title="Haz clic para obtener traducciones alternativas">Nota</span><span title="Haz clic para obtener traducciones alternativas">:</span> <span title="Haz clic para obtener traducciones alternativas">Esto</span> <span title="Haz clic para obtener traducciones alternativas">sólo</span> <span title="Haz clic para obtener traducciones alternativas">elimina los servidores</span> <span title="Haz clic para obtener traducciones alternativas">de</span> <span title="Haz clic para obtener traducciones alternativas">este</span> <span title="Haz clic para obtener traducciones alternativas">panel de</span> <span title="Haz clic para obtener traducciones alternativas">control</span><span title="Haz clic para obtener traducciones alternativas">, no</span><span title="Haz clic para obtener traducciones alternativas">los archivos del servidor</span> <span title="Haz clic para obtener traducciones alternativas">real</span><span title="Haz clic para obtener traducciones alternativas">.</span>
</center>

<br /><br />

<center><input type="submit" name="submit" value="Eliminar" id="submit" style="width:170px"></center>
<input type="hidden" name="server_type" value="<?php echo $url_server_type; ?>">

</form>
</body>
</html>
<?php
}

// If delete was pressed
elseif (isset($_POST['submit']))
{
?>
    <html>
    <head>
    <link rel="stylesheet" type="text/css" href="css/<?php echo $config['theme']; ?>/main.css">
    <META NAME="ROBOTS" CONTENT="NONE">
    <META NAME="GOOGLEBOT" CONTENT="NONE">
    <META NAME="Slurp" CONTENT="NONE">
    </head>

    <body leftmargin="0px" topmargin="0px" marginwidth="0px" marginheight="0px" onLoad="init()">

    <div id="loading" style="position:absolute; top:60px; left:5px; overflow: hidden;"><img src="images/loading.gif" border="0"></div>
    <script src="include/loading.js"></script>

    <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" height="40" background="css/<?php echo $config['theme']; ?>/img/largeGrad.png">
    <tr>
      <td align="center" valign="middle"><span class="top_page_titles">
<?php
if($url_server_type == 'game')
{
    echo 'Game Servers';
}
elseif($url_server_type == 'voip')
{
    echo 'Voip Servers';
}
elseif($url_server_type == 'other')
{
    echo 'Other Servers';
}
else
{
    echo 'User Servers';
}
?>
      </span></td>
    </tr>
    </table>

    <br /><br />
<?php
    $post_delete  = $_REQUEST['delete'];
    $delete_list  = implode(",", $post_delete);
    
    // Empty list
    if(empty($delete_list))
    {
        die('<center><b>Error:</b> You didn\'t select any servers for deletion!</center>');
    }

    // For every user, delete their User Servers
    $arr_del_list = explode(",", $delete_list);
    
    foreach($arr_del_list as $single_id)
    {
        // Delete all User Servers
        $query = "DELETE FROM userservers WHERE id='$single_id'";
        sqlCon($query);
    }
    
    // Get server type from previous page
    $post_server_type = $_POST['server_type'];
    ?>
    <center>
    <b>Success!</b>
    <br /><br />
    <a href="ListServers.php?type=<?php echo $post_server_type; ?>">Click to here return</a>
    </center>

    </body>
    </html>
<?php
}
?>
