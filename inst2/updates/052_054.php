<?php
/*

GamePanelX

Description:  Update Version File

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
error_reporting(E_ERROR);


//
// Update version 0.52 to version 0.54
//

require_once('../include/config.php');

// Connect to the database
$db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
@mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');



// Create 'servers' table
$create_servers_table = "CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(11) NOT NULL auto_increment,
  `short_name` varchar(12) NOT NULL,
  `long_name` varchar(255) NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `available` enum('Y','N') NOT NULL default 'Y',
  `style` varchar(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `port` int(12) NOT NULL,
  `reserved_ports` varchar(255) NOT NULL,
  `tcp_ports` varchar(255) NOT NULL,
  `udp_ports` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `max_slots` int(12) NOT NULL default '12',
  `map` varchar(255) NOT NULL,
  `setup_cmd` varchar(255) NOT NULL,
  `cmd_line` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  `opt1_name` varchar(255) NOT NULL,
  `opt1_edit` varchar(255) NOT NULL default 'N',
  `opt1_value` varchar(255) NOT NULL,
  `opt2_name` varchar(255) NOT NULL,
  `opt2_edit` varchar(255) NOT NULL default 'N',
  `opt2_value` varchar(255) NOT NULL,
  `opt3_name` varchar(255) NOT NULL,
  `opt3_edit` varchar(255) NOT NULL default 'N',
  `opt3_value` varchar(255) NOT NULL,
  `opt4_name` varchar(255) NOT NULL,
  `opt4_edit` varchar(255) NOT NULL default 'N',
  `opt4_value` varchar(255) NOT NULL,
  `opt5_name` varchar(255) NOT NULL,
  `opt5_edit` varchar(255) NOT NULL default 'N',
  `opt5_value` varchar(255) NOT NULL,
  `opt6_name` varchar(255) NOT NULL,
  `opt6_edit` varchar(255) NOT NULL default 'N',
  `opt6_value` varchar(255) NOT NULL,
  `opt7_name` varchar(255) NOT NULL,
  `opt7_edit` varchar(255) NOT NULL default 'N',
  `opt7_value` varchar(255) NOT NULL,
  `opt8_name` varchar(255) NOT NULL,
  `opt8_edit` varchar(255) NOT NULL default 'N',
  `opt8_value` varchar(255) NOT NULL,
  `opt9_name` varchar(255) NOT NULL,
  `opt9_edit` varchar(255) NOT NULL default 'N',
  `opt9_value` varchar(255) NOT NULL,
  `opt10_name` varchar(255) NOT NULL,
  `opt10_edit` varchar(255) NOT NULL default 'N',
  `opt10_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";

@mysql_query($create_servers_table) or die('<center><b>Error:</b> Failed to create the servers table!</center>');


// Copy all 'games' table data into new 'servers' table
$result_gamez = @mysql_query("SELECT id,short_name,long_name,available,style,log_file,port,tcp_game_ports,udp_game_ports,executable,max_players,map,setup_cmd,cmd_line FROM games ORDER BY id ASC") or die('<b>Error:</b> Failed to query the games table!');

while($row_gamez = mysql_fetch_array($result_gamez))
{
    $id             = $row_gamez['id'];
    $short_name     = $row_gamez['short_name'];
    $long_name      = $row_gamez['long_name'];
    $available      = $row_gamez['available'];
    $style          = $row_gamez['style'];
    $log_file       = $row_gamez['log_file'];
    $port           = $row_gamez['port'];
    $tcp_game_ports = $row_gamez['tcp_game_ports'];
    $udp_game_ports = $row_gamez['udp_game_ports'];
    $executable     = $row_gamez['executable'];
    $max_players    = $row_gamez['max_players'];
    $map            = $row_gamez['map'];
    $setup_cmd      = $row_gamez['setup_cmd'];
    $cmd_line       = $row_gamez['cmd_line'];


    // Insert this part into the `servers` table
    @mysql_query("INSERT INTO servers (id,short_name,long_name,type,available,style,log_file,port,reserved_ports,tcp_ports,udp_ports,executable,max_slots,map,setup_cmd,cmd_line) VALUES('$id','$short_name','$long_name','game','$available','$style','$log_file','$port','','$tcp_game_ports','$udp_game_ports','$executable','$max_players','$map','$setup_cmd','$cmd_line')") or die('<b>Error:</b> Failed to insert into the servers table!');


    // Get all config options for this game
    $query_game = "SELECT";

    for($i=1; $i <= 10; $i++)
    {
        $query_game = trim($query_game);
        
        // Get options
        $query_game .= ' opt' . $i . '_name,';
        $query_game .= 'opt' . $i . '_edit,';
        
        if($i == 10)
        {
            $query_game .= 'opt' . $i . '_value';
        }
        else
        {
            $query_game .= 'opt' . $i . '_value,';
        }
    }

    $query_game .= " FROM games WHERE id='$id'";

    // Run query
    $result_game = @mysql_query($query_game) or die('<b>Error:</b> Failed to query games table!');

    while($row_game = mysql_fetch_array($result_game))
    {
        // Loop through all 10 config options
        for($i=1; $i <= 10; $i++)
        {
            // Option names
            $opt_name   = 'opt' . $i . '_name';
            $opt_edit   = 'opt' . $i . '_edit';
            $opt_value  = 'opt' . $i . '_value';
            
            // DB values
            $db_name    = $row_game[$opt_name];
            $db_edit    = $row_game[$opt_edit];
            $db_value   = $row_game[$opt_value];

            // Update this row in `servers` with the game options
            @mysql_query("UPDATE servers SET $opt_name='$db_name',$opt_value='$db_value',$opt_edit='$db_edit' WHERE id='$id'");
        }
    }
}





//
// Game Templates
//


// Create new `templates` table (merge game and voip stuff into one table)
$create_templates_table = "CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL auto_increment,
  `server` varchar(36) NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `available` enum('Y','N') NOT NULL default 'Y',
  `is_default` enum('Y','N') NOT NULL default 'N',
  `description` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY  (`id`)
)";

@mysql_query($create_templates_table) or die('<center><b>Error:</b> Failed to create the templates table!</center>');



// Copy all game templates into new `templates` table
$result_tpl = @mysql_query("SELECT id,game,description,file_path,type,ip FROM game_templates ORDER BY id ASC") or die('<b>Error:</b> Failed to query the game_templates table!');

while($row_tpl = mysql_fetch_array($result_tpl))
{
    $tpl_id             = $row_tpl['id'];
    $tpl_game           = $row_tpl['game'];
    $tpl_description    = $row_tpl['description'];
    $tpl_file_path      = $row_tpl['file_path'];
    $tpl_type           = $row_tpl['type'];
    $tpl_ip             = $row_tpl['ip'];
    
    // Type
    if($tpl_type == 'default')
    {
        $is_default = 'Y';
    }
    else
    {
        $is_default = 'N';
    }
 
    // Insert this row into the `templates` table
    @mysql_query("INSERT INTO templates VALUES('$tpl_id','$tpl_game','game','Y','$is_default','$tpl_description','$tpl_file_path','$tpl_ip')") or die('<b>Error:</b> Failed to insert into the templates table!');
}





//
// User Game / Voip Servers
//

// Create `userservers` table
$create_userservers_table = "CREATE TABLE IF NOT EXISTS `userservers` (
  `id` int(11) NOT NULL auto_increment,
  `date_created` datetime NOT NULL,
  `type` enum('game','voip','other') NOT NULL default 'other',
  `server` varchar(36) NOT NULL,
  `userid` int(11) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `port` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `max_slots` int(11) NOT NULL,
  `map` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `cmd_line` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  `show_cmd_line` enum('Y','N') NOT NULL default 'N',
  `opt1_name` varchar(255) NOT NULL,
  `opt1_edit` varchar(255) NOT NULL default 'N',
  `opt1_value` varchar(255) NOT NULL,
  `opt2_name` varchar(255) NOT NULL,
  `opt2_edit` varchar(255) NOT NULL default 'N',
  `opt2_value` varchar(255) NOT NULL,
  `opt3_name` varchar(255) NOT NULL,
  `opt3_edit` varchar(255) NOT NULL default 'N',
  `opt3_value` varchar(255) NOT NULL,
  `opt4_name` varchar(255) NOT NULL,
  `opt4_edit` varchar(255) NOT NULL default 'N',
  `opt4_value` varchar(255) NOT NULL,
  `opt5_name` varchar(255) NOT NULL,
  `opt5_edit` varchar(255) NOT NULL default 'N',
  `opt5_value` varchar(255) NOT NULL,
  `opt6_name` varchar(255) NOT NULL,
  `opt6_edit` varchar(255) NOT NULL default 'N',
  `opt6_value` varchar(255) NOT NULL,
  `opt7_name` varchar(255) NOT NULL,
  `opt7_edit` varchar(255) NOT NULL default 'N',
  `opt7_value` varchar(255) NOT NULL,
  `opt8_name` varchar(255) NOT NULL,
  `opt8_edit` varchar(255) NOT NULL default 'N',
  `opt8_value` varchar(255) NOT NULL,
  `opt9_name` varchar(255) NOT NULL,
  `opt9_edit` varchar(255) NOT NULL default 'N',
  `opt9_value` varchar(255) NOT NULL,
  `opt10_name` varchar(255) NOT NULL,
  `opt10_edit` varchar(255) NOT NULL default 'N',
  `opt10_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
)";

@mysql_query($create_userservers_table) or die('<center><b>Error:</b> Failed to create the userservers table!</center>');


// ,


// Copy everything from `user_games` into the new `userservers` table
$result_user_games = @mysql_query("SELECT id,date_created,game,userid,log_file,ip,port,description,max_players,map,executable,cmd_line,show_cmd_line FROM user_games ORDER BY id ASC") or die('<b>Error:</b> Failed to query the user_games table!');

while($row_user_games = mysql_fetch_array($result_user_games))
{
    $game_id              = $row_user_games['id'];
    $game_date_created    = $row_user_games['date_created'];
    $game_name            = $row_user_games['game'];
    $game_userid          = $row_user_games['userid'];
    $game_log_file        = $row_user_games['log_file'];
    $game_ip              = $row_user_games['ip'];
    $game_port            = $row_user_games['port'];
    $game_description     = $row_user_games['description'];
    $game_max_players     = $row_user_games['max_players'];
    $game_map             = $row_user_games['map'];
    $game_executable      = $row_user_games['executable'];
    $game_cmd_line        = $row_user_games['cmd_line'];
    $game_show_cmd_line   = $row_user_games['show_cmd_line'];


    // Insert this part into the `userservers` table
    @mysql_query("INSERT INTO userservers (id,date_created,type,server,userid,log_file,ip,port,description,max_slots,map,executable,cmd_line,show_cmd_line) VALUES ('$game_id','$game_date_created','game','$game_name','$game_userid','$game_log_file','$game_ip','$game_port','$game_description','$game_max_players','$game_map','$game_executable','$game_cmd_line','$game_show_cmd_line')") or die('<b>Error:</b> Failed to insert into the userservers table!');


    // Get all config options for this game
    $query_game = "SELECT";

    for($i=1; $i <= 10; $i++)
    {
        $query_game = trim($query_game);
        
        // Get options
        $query_game .= ' opt' . $i . '_name,';
        $query_game .= 'opt' . $i . '_edit,';
        
        if($i == 10)
        {
            $query_game .= 'opt' . $i . '_value';
        }
        else
        {
            $query_game .= 'opt' . $i . '_value,';
        }
    }

    $query_game .= " FROM user_games WHERE id='$game_id'";

    // Run query
    $result_game = @mysql_query($query_game) or die('<b>Error:</b> Failed to query games table!');

    while($row_game = mysql_fetch_array($result_game))
    {
        // Loop through all 10 config options
        for($i=1; $i <= 10; $i++)
        {
            // Option names
            $opt_name   = 'opt' . $i . '_name';
            $opt_edit   = 'opt' . $i . '_edit';
            $opt_value  = 'opt' . $i . '_value';
            
            // DB values
            $db_name    = $row_game[$opt_name];
            $db_edit    = $row_game[$opt_edit];
            $db_value   = $row_game[$opt_value];

            // Update this row in `servers` with the game options
            @mysql_query("UPDATE userservers SET $opt_name='$db_name',$opt_value='$db_value',$opt_edit='$db_edit' WHERE id='$game_id'");
        }
    }
}




// Delete old cs_16,cs_cz,cs_s,cod4,and cod2 games to make room for the newest games
@mysql_query("DELETE FROM servers WHERE short_name='cs_16' OR short_name='cs_cz' OR short_name='cs_s' OR short_name='cod2' OR short_name='cod4'") or die('<b>Error:</b> Failed to drop the old servers!');



// Add latest game/voip servers
$add_latest_servers = "INSERT INTO `servers` (`id`, `short_name`, `long_name`, `type`, `available`, `style`, `log_file`, `port`, `reserved_ports`, `tcp_ports`, `udp_ports`, `executable`, `max_slots`, `map`, `setup_cmd`, `cmd_line`, `working_dir`, `setup_dir`, `opt1_name`, `opt1_edit`, `opt1_value`, `opt2_name`, `opt2_edit`, `opt2_value`, `opt3_name`, `opt3_edit`, `opt3_value`, `opt4_name`, `opt4_edit`, `opt4_value`, `opt5_name`, `opt5_edit`, `opt5_value`, `opt6_name`, `opt6_edit`, `opt6_value`, `opt7_name`, `opt7_edit`, `opt7_value`, `opt8_name`, `opt8_edit`, `opt8_value`, `opt9_name`, `opt9_edit`, `opt9_value`, `opt10_name`, `opt10_edit`, `opt10_value`) VALUES
('', 'cs_16', 'Counter-Strike: 1.6', 'game', 'Y', 'FPS', 'cstrike/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'hlds_run', 16, 'de_dust2', 'chmod u+x ./steam ; ./steam -command update -game cstrike -dir .', './%executable% -game cstrike +ip %ip% +port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'cstrike', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'Half-Life TV', 'N', '0', 'Half-Life TV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_cz', 'Counter-Strike: Condition Zero', 'game', 'Y', 'FPS', 'czero/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'hlds_run', 16, 'de_dust2_cz', 'chmod u+x ./steam ; ./steam -command update -game czero -dir .', './%executable% -game cstrike +ip %ip% +port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'czero', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'Half-Life TV', 'N', '0', 'Half-Life TV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_s', 'Counter-Strike: Source', 'game', 'Y', 'FPS', 'cstrike/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'srcds_run', 16, 'de_dust2', 'chmod u+x ./steam ; ./steam -command update -game \"Counter-Strike Source\" dir .', './%executable% -game cstrike -ip %ip% -port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'cstrike', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cs_pm', 'Counter-Strike: Pro Mod', 'game', 'Y', 'FPS', 'cspromod/logs', 27015, '27020,27039', '1200,26900,27000_27039', '1200,27015,26900', 'srcds_run', 16, 'csp_dust2', 'chmod u+x ./steam ; ./steam -command update -game \"Counter-Strike Source\" dir .', './%executable% -game cspromod -ip %ip% -port %port% +sv_lan %opt3% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'cspromod', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod4', 'Call of Duty 4', 'game', 'Y', 'FPS', 'heya', 28960, '20500,20510,28960', '28960', '20500,20510,28960', 'cod4_lnxded', 16, 'mp_strike', './pbsetup.run -e ; ./pbsetup.run --add-game=cod4 --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', 'cod4', 'pbsetup', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', '+map_rotate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod2', 'Call of Duty 2', 'game', 'Y', 'FPS', 'games_mp.log', 28960, '', '28960', '20500,20510,28960', 'cod2_lnxded', 16, 'mp_strike', './pbsetup.run -e ; ./pbsetup.run --add-game=cod2 --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', '+map_rotate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'cod_waw', 'Call of Duty: World at War', 'game', 'Y', 'FPS', '', 28960, '', '28960', '20500,20510,28960', 'codwaw_lnxded', 16, 'mp_castle', './pbsetup.run -e ; ./pbsetup.run --add-game=codwaw --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients \"%max_players%\" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1% %opt5%', '', '', 'Server Config', 'Y', 'server.cfg', 'Dedicated', 'N', '2', 'Enable Punkbuster', 'Y', '1', 'Pure Server', 'Y', '1', 'map_rotate', 'Y', '+map_rotate', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'dod_s', 'Day of Defeat: Source', 'game', 'Y', 'FPS', 'orangebox/dod/logs', 27015, '27020,27040,27041', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'dod_anzio', './steam -command update -game dods -dir .', 'cd %working_dir% ; ./%executable% -game dod -ip %ip% -port %port% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', 'orangebox', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'Y %switch%', 'Lan Server', 'N', '0', 'Tickrate', 'N', '66', 'FPS Max', 'N', '300', 'SourceTV', 'N', '0', 'SourceTV Port', 'N', '27020', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'l4d', 'Left 4 Dead', 'game', 'Y', 'FPS', 'l4d/left4dead/logs', 27015, '27020,27039', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'l4d_airport01_greenhouse', './steam -command update -game \"l4d_full\" -dir .', 'cd %working_dir% ; ./%executable% -game left4dead -ip %ip% -port %port% +map %default_map% +exec %opt1% ', 'l4d', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'l4d_2', 'Left 4 Dead 2', 'game', 'Y', 'FPS', '%working_dir%/logs', 27015, '27015,27020,27040,27041,1200', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 16, 'c2m1_highway', './steam -command update -game \"left4dead2\" -dir .', 'cd %working_dir% ; ./%executable% -game left4dead2 -ip %ip% -port %port% +map %default_map% +exec %opt1%', 'l4d/left4dead2', '', 'Exec Config', 'Y', 'server.cfg', '-autoupdate', 'Y', 'N %switch%', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'ws_et', 'Wolfenstein: Enemy Territory', 'game', 'Y', 'FPS', '%working_dir%/etconsole.log', 27960, '27950,27952,27960,27965', '27950,27952,27960,27965', '27950,27952,27960,27965', 'etded', 16, 'oasis', './pbsetup.run -e ; ./pbsetup.run --add-game=et --add-game-path=~/_accounts/%username%/%game%/%ip%:%port%/ ; ./pbsetup.run -u', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt3% +set sv_punkbuster %opt2% +map %default_map% +exec %opt1% +set sv_maxclients %max_players%', 'etmain', '', 'Exec Server Config', 'Y', 'server.cfg', 'Enable Punkbuster', 'Y', '1', 'Dedicated', 'N', '2', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'vent', 'Ventrilo', 'voip', 'Y', '', 'ventrilo_srv.log', 3784, '3784', '3784', '3784', 'ventrilo_srv', 8, '', '', './%executable% -d', '', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'ts', 'TeamSpeak', 'voip', 'Y', '', 'server.log', 8767, '8767,12345,14534,51234', '8767,12345,14534,51234', '8767,12345,14534,51234', 'server_linux', 12, '', '', './%executable%', 'work', 'sett', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', ''),
('', 'mrm', 'Murmur', 'voip', 'Y', '', 'log', 64738, '', '64738', '64738', 'murmur.x86', 12, '', '', './%executable% -ini %opt1%', '', '', 'Config File', 'N', 'murmur.ini', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '', '', 'N', '')";

@mysql_query($add_latest_servers) or die('<b>Error:</b> Failed to add the latest servers!');


// DROP the old tables that will no longer be used
@mysql_query("DROP TABLE games") or die('<b>Error:</b> Failed to drop the games table!');
@mysql_query("DROP TABLE game_templates") or die('<b>Error:</b> Failed to drop the game_templates table!');
@mysql_query("DROP TABLE user_games") or die('<b>Error:</b> Failed to drop the user_games table!');

// Update version
if(!empty($install__version))
{
    @mysql_query("UPDATE configuration SET value = '$install__version' WHERE setting = 'GPXVersion'");
}

?>
