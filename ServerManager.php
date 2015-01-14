<?php
/*

GamePanelX

Description:  Manage game servers

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/SqlCon.php');
include_once('include/auth.php');
include_once('include/statusInfo.php');

//Set the user name
$GPXuserName = $_SESSION['usergpx'];

// Get server type from URL
$url_server_type = $_GET['type'];

// No funny business with the URL
if($url_server_type != 'game' && $url_server_type != 'voip' && $url_server_type != 'other' && !empty($url_server_type))
{
    die('<center><b>Error:</b> Invalid type in the URL!</center>');
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
  <td align="center" valign="middle"><span class="top_page_titles"><?php echo ucwords($url_server_type); ?> Server Manager</span></td>
</tr>
</table>

<br /><br />

<center><b>Note:</b> Click on the server name to view server details and start/stop your server.</center>

<br /><br />

<form action="include/runcmd.php" method="post">
<table border="0" class="tablez" width="620" cellspacing="0" cellpadding="0" style="border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;vertical-align: middle;" align="center">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" class="gametable" height="20">
    <td style="border-bottom:1px solid black" width="50"><span class="top_titles">&nbsp;</span></td>
    <td style="border-bottom:1px solid black" width="210" align="left"><span class="top_titles">Server</span></td>
    <td style="border-bottom:1px solid black" width="150" align="left"><span class="top_titles">Connection Info</span></td>
    <td style="border-bottom:1px solid black" width="120" align="left"><span class="top_titles">Description</span></td>
    <td style="border-bottom:1px solid black" width="60" align="left"><span class="top_titles">Status</span></td>
  </tr>
<?php
    // Connect to the DB again
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Get user ID
    $result_userid = mysql_query("SELECT id FROM users WHERE username='$GPXuserName'") or die(mysql_error());

    while($row_userid = mysql_fetch_array($result_userid))
    {
        $user_id = $row_userid['id'];
    }

        // Get details for gameserver
        $server_query = "SELECT 
                            userservers.id,
                            userservers.server,
                            userservers.ip,
                            userservers.port,
                            userservers.description,
                            servers.long_name 
                         FROM userservers 
                         LEFT JOIN servers ON 
                            userservers.server = servers.short_name 
                         WHERE 
                            userservers.type = '$url_server_type' 
                         AND 
                            userservers.userid='$user_id' 
                         ORDER BY 
                            userservers.server,userservers.ip 
                         DESC";
        
        $result_server  = @mysql_query($server_query) or die('<b>Error:</b> Failed to query the userservers table!');
        $num_server     = mysql_num_rows($result_server);
        
        // If user has no servers, kill the page
        if($num_server == 0)
        {
            // Include alternating row colors
            include('include/colors.php');
            
            echo '<tr valign="middle" style="vertical-align: middle;background-color:' . $bgColor . '" onmouseover="style.backgroundColor=\'' . $bgOpp . '\'" onmouseout="style.backgroundColor=\'' .   $bgColor . '\'">';
            echo '<td colspan="5" align="center">You have no ' . $url_server_type . ' servers</td></tr>';
            exit;
        }

        while($row_server = mysql_fetch_array($result_server))
        {
            $server_id            = $row_server['id'];
            $server_name          = $row_server['server'];
            $server_ip            = $row_server['ip'];
            $server_port          = $row_server['port'];
            $server_description   = stripslashes($row_server['description']);
            $server_long_name     = $row_server['long_name'];
            
            // SSH to gameserver, get game status
            include('include/server_status.php');

            // Include alternating row colors
            include('include/colors.php');
            ?>
            
            <tr valign="middle" style="vertical-align: middle;background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">

            <!-- Game Image -->
            <td width="30" valign="middle" align="left">
            <?php
            // Show icon for the game.  If no icon exists, use the 'unsupported' icon
            $icon_loc = 'images/servers/small/' . $server_name . '.png';
            
            if(file_exists($icon_loc))
            {
                echo '<img src="' . $icon_loc . '" border="0" width="28" height="28" />';
            }
            else
            {
                echo '<img src="images/servers/medium/unsupported.png" border="0" width="28" height="28" />';
            }
            ?>
            </td>
            
            <!-- Game Name -->
            <td width="210" valign="middle" align="left" class="game_tablez"><a style="color: #333333" href="ServerEdit.php?id=<?php echo $server_id; ?>" title="View Server Settings"><b><?php echo $server_long_name; ?></b></a></td>

            <!-- Display IP Address -->
            <td width="150" align="left" class="game_tablez">

            <?php
            //Show IP Address
            if (!empty($server_ip) && !empty($server_port))
            {
                echo $server_ip . ':' . $server_port;
            }
            // Empty ip/port
            else
            {
                echo 'No IP or Port Found';
            }
            ?></td>

            <!-- Game Description -->
            <td width="180" align="left" class="game_tablez">
            
            <?php
            // Show game description
            if (!empty($server_description))
            {
                // If description is too long, shorten and add "..." to it
                if (strlen($server_description) >= 20)
                {
                    echo substr($server_description, 0, 20) . ' ...';
                }
                // Otherwise, just echo it
                else
                {
                    echo $server_description;
                }
            }
            // No description
            else
            {
                echo '&nbsp;';
            }
            ?>
            </td>

            <!-- Online Status -->
            <td align="left">
            <?php
            // After SSH'ing into the server, this is the status returned
            $gameserver_status = trim($gs_status);

            // Online Status
            if($gameserver_status == 'online')
            {
                echo '<font color="green"><b>Online</b></font>';
            }
            
            // Offline Status
            elseif($gameserver_status == 'offline')
            {
                echo '<font color="red"><b>Offline</b></font>';
            }

            // Otherwise
            else
            {
                echo '<font color="orange"><b>Unknown</b></font>';
            }
            ?></td>
    <?php
        }
    ?>
        </td>
      </tr>
</table>
</form>

</body>
</html>
