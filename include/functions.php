<?php
/*

GamePanelX

Description:  Functions to be included where needed

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/


//
// Function to create random passphrases/etc
//
function generateRandomText($style)
{
    // Use normal for a mix of letters/numbers, numbers for only numbers, etc.
    if ($style == "normal")
    {
        $chars = "abcdefghijkmnopqrstuvwxyz023456789";
    }
    elseif ($style == "numbers")
    {
        $chars = "023456789";
    }
    elseif ($style == "letters")
    {
        $chars = "abcdefghijkmnopqrstuvwxyz";
    }
    else
    {
        die('<b>Error:</b> Invalid random text style given!');
    }
    
    srand((double)microtime()*1000000);

    $i = 0;
    $pass = "";

    while ($i <= 7)
    {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;

        $i++;
    }
    return $pass;
}



//
// New random text function
//
function gen_random_text($type,$length)
{
    // Make sure only numbers go in $length
    if(!is_numeric($length))
    {
        die('<b>Error:</b> Random text length is not a number.');
    }
    
    // Use normal for a mix of letters/numbers, numbers for only numbers, etc.
    if ($type == 'all')
    {
        $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKMNOPQRSTUVWXYZ023456789';
    }
    elseif ($type == 'numbers')
    {
        $chars = '023456789';
    }
    elseif ($type == 'letters')
    {
        $chars = 'abcdefghijkmnopqrstuvwxyz';
    }
    
    srand((double)microtime()*1000000);

    $i = 0;
    $pass = '' ;

    // Loop as long as $length
    while ($i <= $length)
    {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}



// ---------------------------------------------------------------------------------------------------



//
// Create New Remote Server function
//
function create_remote_server($ip_address,$is_available,$is_physical,$parent_server,$operating_system,$location,$datacenter,$ssh_user,$ssh_pass,$ssh_port)
{
    require('config.php');
    
    // Check for empty required values
    if(empty($ip_address))
    {
        die('<b>Error:</b> Create Remote Server: IP Address was left empty!');
    }
    
    // Get encryption key from config
    $ssh_key = $config['encrypt_key'];
    
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Prepare all values for insertion
    $safe_ip          = mysql_real_escape_string($ip_address);
    $safe_available   = mysql_real_escape_string($is_available);
    $safe_physical    = mysql_real_escape_string($is_physical);
    $safe_parent      = mysql_real_escape_string($parent_server);
    $safe_os          = mysql_real_escape_string($operating_system);
    $safe_location    = mysql_real_escape_string($location);
    $safe_datacenter  = mysql_real_escape_string($datacenter);
    $safe_ssh_user    = mysql_real_escape_string($ssh_user);
    $safe_ssh_pass    = mysql_real_escape_string($ssh_pass);
    $safe_ssh_port    = mysql_real_escape_string($ssh_port);
    
    // Non-Physical; No SSH Encryption
    if($safe_physical == 'N')
    {
        if(mysql_query("INSERT INTO remote (ip,date_added,available,physical,parent,operating_system,location,datacenter) VALUES('$safe_ip',NOW(),'$safe_available','$safe_physical','$safe_parent','$safe_os','$safe_location','$safe_datacenter')"))
        {
            // Success
            return true;
        }
        else
        {
            // Failure
            return false;
        }
    }
    
    // Physical...do AES encryption on SSH Settings
    else
    {
        if(mysql_query("INSERT INTO remote (ip,date_added,available,physical,parent,operating_system,location,datacenter,ssh_user,ssh_pass,ssh_port) VALUES('$safe_ip',NOW(),'$safe_available','$safe_physical','$safe_parent','$safe_os','$safe_location','$safe_datacenter',AES_ENCRYPT('$safe_ssh_user','$ssh_key'),AES_ENCRYPT('$safe_ssh_pass','$ssh_key'),'$safe_ssh_port')"))
        {
            // Success
            return true;
        }
        else
        {
            // Failure
            return false;
        }
    }
}




//
// Create New User function
//
function create_user($username,$password,$first_name,$middle_name,$last_name,$gender,$email,$phone,$website,$country,$state,$city,$address,$zip,$orig_ip,$orig_host,$is_admin)
{
    require('config.php');
    
    // Check for empty required values
    if(empty($username) || empty($password) || empty($is_admin))
    {
        die('<b>Error:</b> Create User Account: A required variable was left empty!');
    }

    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    // Prepare all values for insertion
    $safe_username          = mysql_real_escape_string($username);
    $safe_password          = md5(mysql_real_escape_string($password));
    $safe_first_name        = mysql_real_escape_string($first_name);
    $safe_middle_name       = mysql_real_escape_string($middle_name);
    $safe_last_name         = mysql_real_escape_string($last_name);
    $safe_gender            = mysql_real_escape_string($gender);
    $safe_email             = mysql_real_escape_string($email);
    $safe_phone             = mysql_real_escape_string($phone);
    $safe_website           = mysql_real_escape_string($website);
    $safe_country           = mysql_real_escape_string($country);
    $safe_state             = mysql_real_escape_string($state);
    $safe_city              = mysql_real_escape_string($city);
    $safe_address           = mysql_real_escape_string($address);
    $safe_zip               = mysql_real_escape_string($zip);
    $safe_orig_ip           = mysql_real_escape_string($orig_ip);
    $safe_orig_host         = mysql_real_escape_string($orig_host);
    $safe_is_admin          = mysql_real_escape_string($is_admin);
    
    // Default settings
    $is_active              = 'Y';
    $email_notify           = 'N';


    // Check if this account already exists
    $result_user = @mysql_query("SELECT COUNT(id) AS usercount FROM users WHERE username='$safe_username'") or die('<b>Error:</b> Failed to query the database!');
    
    while($row_user = mysql_fetch_array($result_user))
    {
        $user_count = $row_user['usercount'];
    }

    // If it exists, die
    if($user_count >= 1)
    {
        die('<center><b>Error:</b> That username already exists!</center>');
    }
    
    // Insert into 'users' table
    if(mysql_query("INSERT INTO users VALUES('','$safe_username','$safe_password','$is_active','$safe_first_name','$safe_middle_name','$safe_last_name','$safe_gender','$safe_email','$safe_phone','$safe_website','$safe_country','$safe_state','$safe_city','$safe_address','$safe_zip','$email_notify','$safe_orig_ip','$safe_orig_host','','','$safe_is_admin',NOW(),'','')"))
    {
        // Success
        return true;
    }
    else
    {
        // Failure
        return false;
    }
}


//
// List all available gameserver IP Addresses
//
function list_available_ips($type)
{
    require('config.php');

    //
    // Listing types
    //
    
    // All IPs
    if($type == 'all')
    {
        $query_ip = "SELECT ip FROM remote WHERE available='Y' ORDER BY ip ASC";
    }
    // Only Physical IPs
    elseif($type == 'physical')
    {
        $query_ip = "SELECT ip FROM remote WHERE available='Y' AND physical='Y' ORDER BY ip ASC";
    }
    // Only non-physical IPs
    elseif($type == 'nonphysical')
    {
        $query_ip = "SELECT ip FROM remote WHERE available='Y' AND physical!='Y' ORDER BY ip ASC";
    }
    // Empty type
    elseif(empty($type))
    {
        die('<b>Error:</b> IP Listing type was left empty!');
    }
    // Otherwise
    else
    {
        die('<b>Error:</b> Unknown listing type given!');
    }
    
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    $result_ip  = @mysql_query($query_ip) or die(mysql_error());
    $num_ip     = mysql_num_rows($result_ip);

    // No results
    if ($num_ip == 0)
    {
        echo '<option value="none">No Physical IP found</option>';
    }
    // Otherwise, loop through available IPs
    else
    {
        while($row_ip = mysql_fetch_array($result_ip))
        {
            $ip = $row_ip['ip'];
            echo '<option value="' . $ip . '">' . $ip . '</option>';
        }
    }
}


//
// Create Game Server function
//
function create_server($type,$server,$userid,$ip,$port,$description,$max_slots,$map,$executable,$log_file,$cmd,$client_cmd_line,$working_dir,$setup_dir)
{
    // Check for empty required values
    if(empty($server) || empty($userid) || empty($ip) || empty($type))
    {
        die('<b>Error:</b> Create Game Server: A required variable was left empty!');
    }
    
    require('config.php');
    
    // Get default values incase important variables are not given in the function
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    $result_gamez = @mysql_query("SELECT long_name,log_file,port,max_slots,map,executable,cmd_line,working_dir,setup_dir FROM servers WHERE short_name='$server'") or die('<b>Error:</b> Failed to query the servers table!');
    
    while($row_gamez = mysql_fetch_array($result_gamez))
    {
        $default_log_file       = stripslashes($row_gamez['log_file']);
        $default_port           = stripslashes($row_gamez['port']);
        $default_max_slots      = stripslashes($row_gamez['max_slots']);
        $default_map            = stripslashes($row_gamez['map']);
        $default_executable     = stripslashes($row_gamez['executable']);
        $default_cmd_line       = stripslashes($row_gamez['cmd_line']);
        $default_working_dir    = stripslashes($row_gamez['working_dir']);
        $default_setup_dir      = stripslashes($row_gamez['setup_dir']);
    }
    
    
    // Exchange variables for default values if they're empty.
    if(empty($port))
    {
        $port = $default_port;
    }
    if(empty($max_slots))
    {
        $max_slots = $default_max_slots;
    }
    if(empty($map))
    {
        $map = $default_map;
    }
    if(empty($executable))
    {
        $executable = $default_executable;
    }
    if(empty($log_file))
    {
        $log_file = $default_log_file;
    }
    if(empty($cmd))
    {
        $cmd = $default_cmd_line;
    }
    if(empty($client_cmd_line))
    {
        // Client cannot see the Command-Line by default
        $client_cmd_line = 'N';
    }
    if(empty($working_dir))
    {
        $working_dir = $default_working_dir;
    }
    if(empty($setup_dir))
    {
        $setup_dir = $default_setup_dir;
    }
    
        
  
    // Begin insert query
    $insert_query = "INSERT INTO userservers (date_created,type,server,userid,log_file,ip,port,description,max_slots,map,executable,cmd_line,working_dir,setup_dir,show_cmd_line) VALUES(NOW(),'$type','$server','$userid','$log_file','$ip','$port','$description','$max_slots','$map','$executable','$cmd','$working_dir','$setup_dir','$client_cmd_line')";

    // Insert user's game
    if(mysql_query($insert_query))
    {
        $insert_success = 1;
    }



    //
    // Update their server with config options
    //
    $param_query = 'SELECT';

    // Get all 10 config settings for this server
    for($i=1; $i <= 10; $i++)
    {
        trim($param_query);
        
        // Get options
        $param_query .= ' opt' . $i . '_name,';
        $param_query .= 'opt' . $i . '_edit,';
        
        if($i == 10)
        {
            $param_query .= 'opt' . $i . '_value';
        }
        else
        {
            $param_query .= 'opt' . $i . '_value,';
        }
    }

    // Finish query
    $param_query .= " FROM servers WHERE short_name='$server'";

    // Query for all config options
    $result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the servers table!');
    
    
    while($row_opt = mysql_fetch_array($result_client_fields))
    {
        // Loop through all 10 config options
        for($i=1; $i <= 10; $i++)
        {
            // Option names
            $opt_name   = 'opt' . $i . '_name';
            $opt_edit   = 'opt' . $i . '_edit';
            $opt_value  = 'opt' . $i . '_value';
            
            // Option values
            $db_name  = stripslashes($row_opt[$opt_name]);
            $db_edit  = stripslashes($row_opt[$opt_edit]);
            $db_value = stripslashes($row_opt[$opt_value]);
            
            // Insert this into the database
            if(mysql_query("UPDATE userservers SET $opt_name='$db_name',$opt_edit='$db_edit',$opt_value='$db_value' WHERE userid='$userid' AND ip='$ip' AND port='$port' AND description='$description'"))
            {
                $update_success = 1;
            }
        }
    }


    // Results
    if($insert_success == 1 && $update_success == 1)
    {
        // Success
        return true;
    }
    else
    {
        // Failure
        return false;
    }
}


//
// Build a full command line for given gameserver ID
//
function build_cmd_line($id)
{
    require('config.php');
    
    // Check for empty required values
    if(empty($id))
    {
        die('<center><b>Error:</b> Build CMD Line: The ID was left empty!</center>');
    }
    
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    //
    // Begin 10 configuration settings
    //
    $param_query = 'SELECT userid,server,ip,port,log_file,description,max_slots,map,executable,cmd_line,working_dir,setup_dir,';

    // Get all 10 config settings for this server
    for($i=1; $i <= 10; $i++)
    {
        trim($param_query);
        
        // Get options
        $param_query .= ' opt' . $i . '_name,';
        $param_query .= 'opt' . $i . '_edit,';
        
        if($i == 10)
        {
            $param_query .= 'opt' . $i . '_value';
        }
        else
        {
            $param_query .= 'opt' . $i . '_value,';
        }
    }

    // Finish query
    $param_query .= " FROM userservers WHERE id='$id'";

    // Query for all config options
    $result_client_fields = @mysql_query($param_query) or die('<b>Error:</b> Failed to query the userservers table!');

    while($row_opt = mysql_fetch_array($result_client_fields))
    {
        // Normal options
        $userid         = $row_opt['userid'];
        $server         = $row_opt['server'];
        $ip             = $row_opt['ip'];
        $port           = $row_opt['port'];
        $log_file       = $row_opt['log_file'];
        $description    = $row_opt['description'];
        $max_slots      = $row_opt['max_slots'];
        $map            = $row_opt['map'];
        $executable     = $row_opt['executable'];
        $orig_cmd_line  = $row_opt['cmd_line'];
        $working_dir    = $row_opt['working_dir'];
        $setup_dir      = $row_opt['setup_dir'];
        
        // Get username from userid
        $result_username = @mysql_query("SELECT username FROM users WHERE id='$userid'");
        
        while($row_username = mysql_fetch_array($result_username))
        {
            $server_username = $row_username['username'];
        }
        
        // Parse the command-line
        $cmd_line  = str_replace("./%executable% ", "", $orig_cmd_line);      // Remove executable
        $cmd_line  = str_replace("%ip%", $ip, $cmd_line);                     // IP Address
        $cmd_line  = str_replace("%port%", $port, $cmd_line);                 // Port
        $cmd_line  = str_replace("%log_file%", $log_file, $cmd_line);         // Log File
        $cmd_line  = str_replace("%username%", $server_username, $cmd_line);  // Username
        $cmd_line  = str_replace("%server%", $server, $cmd_line);             // Server Name
        $cmd_line  = str_replace("%working_dir%", $working_dir, $cmd_line);   // Working Directory
        $cmd_line  = str_replace("%setup_dir%", $setup_dir, $cmd_line);       // Setup Directory
        
        // Variation on the "Map" value
        $cmd_line  = str_replace("%map%", $map, $cmd_line);             // Startup Map
        $cmd_line  = str_replace("%default_map%", $map, $cmd_line);           // Startup Map
        $cmd_line  = str_replace("%startup_map%", $map, $cmd_line);     // Startup Map
        
        // Variation on the "Max Slots" value
        $cmd_line  = str_replace("%max_slots%", $max_slots, $cmd_line);       // Max Slots
        $cmd_line  = str_replace("%max_players%", $max_slots, $cmd_line);     // Max Slots
        
        
        // Loop through all 10 config options
        for($i=1; $i <= 10; $i++)
        {
            // Option names
            $opt_name   = 'opt' . $i . '_name';
            $opt_edit   = 'opt' . $i . '_edit';
            $opt_value  = 'opt' . $i . '_value';
            
            // Option values
            $this_name  = $row_opt[$opt_name];
            $this_edit  = $row_opt[$opt_edit];
            $this_value = $row_opt[$opt_value];
            
            
            // Replace this option with the current value
            $this_opt = '%opt' . $i . '%';
            $cmd_line  = str_replace($this_opt, $this_value, $cmd_line);
        }
    }
    
    // Return the full command-line
    return $cmd_line;
}

//
// Create Server (SSH) - SSH into the given server ID (Run this _after_ the 'create_server' function) to extract the template
//
function create_server_SSH($id)
{
    require('config.php');
    require('ssh2.php');
    
    // Check for empty required values
    if(empty($id))
    {
        die('<center><b>Error:</b> Create Server (SSH): The ID was left empty!</center>');
    }
    // ID is given; check if it's invalid
    elseif(!empty($id) && !is_numeric($id))
    {
        die('<center><b>Error:</b> Create Server (SSH): Invalid ID!</center>');
    }
    
    // Connect to the database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    
    // Get details about this gameserver
    $result_server = @mysql_query("SELECT userservers.server,userservers.ip,userservers.port,userservers.executable,users.username FROM userservers LEFT JOIN users ON userservers.userid = users.id WHERE userservers.id='$id'") or die('<center><b>Error:</b> Failed to query the userservers table!</center>');
    
    while($row_server = mysql_fetch_array($result_server))
    {
        $server_namez     = $row_server['server'];
        $server_ip        = $row_server['ip'];
        $server_port      = $row_server['port'];
        $server_exe       = $row_server['executable'];
        $server_username  = $row_server['username'];
    }



    //-------------------------------------------------------------------------------------------------------------------------------------------

    
    //
    // The following is to get all details about the physical server to SSH into it
    //

    // Get parent IP Address
    $result_parent  = @mysql_query("SELECT parent FROM remote WHERE ip='$server_ip' AND physical='N' AND available='Y'") or die('<b>Error:</b> Failed to query the remote table!');
    $num_result     = mysql_num_rows($result_parent);


    // Check if there is a parent server for this
    if($num_result >= 1)
    {
        while($row_parent = mysql_fetch_array($result_parent))
        {
            // Number of games returned
            $parent_server = $row_parent['parent_server'];
        }
    }
    // Otherwise, use the game IP
    else
    {
        $parent_server = $server_ip;
    }


    $result_ssh_info = @mysql_query("SELECT id,ip,ssh_port FROM remote WHERE ip='$parent_server'") or die('<b>Error:</b> Failed to query the remote table!');

    while ($row_ssh_info = mysql_fetch_array($result_ssh_info))
    {
        $id             = $row_ssh_info['id'];
        $ipAddress      = $row_ssh_info['ip'];
        $ssh_port       = $row_ssh_info['ssh_port'];
    }

    // Get SSH Key from config
    $ssh_key = $config['encrypt_key'];

    // SSH Username
    $result_user  = @mysql_query("SELECT AES_DECRYPT(ssh_user, '$ssh_key') AS decrypted_user FROM remote WHERE id='$id'") or die('<b>Error:</b> Failed to get the SSH Username!');

    while ($row_user = mysql_fetch_array($result_user))
    {
        $ssh_user = $row_user['decrypted_user'];
    }

    // SSH Password
    $result_pass  = @mysql_query("SELECT AES_DECRYPT(ssh_pass, '$ssh_key') AS decrypted_pass FROM remote WHERE id='$id'") or die('<b>Error:</b> Failed to get the SSH Password!');

    while ($row_pass = mysql_fetch_array($result_pass))
    {
        $ssh_pass = $row_pass['decrypted_pass'];
    }

    
    
    //
    // Get default template for this game
    //
    $result_template = @mysql_query("SELECT file_path FROM templates WHERE server='$server_namez' AND type='default'") or die('<b>Error:</b> Failed to get the default template!');
    
    while($row_template = mysql_fetch_array($result_template))
    {
        $template_path = $row_template['file_path'];
    }
    
    // Die if no default game template
    if(empty($template_path))
    {
        die('<br /><center><b>Error:</b> Create Server (SSH): There is no default Template set for this server!</center><br />');
    }

    
    // Server nickname (IP:Port)
    $server_nickname = $server_ip . ':' . $server_port;

    // Build the full Command-Line for this gameserver
    $full_cmd_line = build_cmd_line($id);
    
    // Random number to seperate this from other scripts
    $style          = 'normal';
    $random_number  = generateRandomText($style);

    // Create gameserver command
    $command = '$HOME' . "/_scripts/create_game_server.sh -u $server_username -g $server_namez -N $server_nickname -t $template_path";


    // Return data, don't print
    $allow_return = '2';

    
    
    // Run SSH Command
    $ssh_output = @connect_ssh($ipAddress, $ssh_port, $ssh_user, $ssh_pass, $command, $allow_return, $ssh_timeout);
    
    // Success
    if(!empty($ssh_output))
    {
        return true;
    }
    // Failure
    else
    {
        return false;
    }
}
?>
