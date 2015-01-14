<?php
/*

GamePanelX

Description:  SSH2 Function for connecting to remote servers

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('auth.php');

// Function to connect via SSH2 to Remote Servers
function connect_ssh($ip,$port,$user,$pass,$cmd,$allow_return,$timeout)
{
    // Set time limit
    set_time_limit(12);
    
    ####################################################################
    
    // Check that the host/port is up and working
    $test_host = fsockopen($ip,$port,$errno,$errstr,12);
    if(!$test_host)
    {
        die('<b>Error:</b> Unable to connect to the specified IP Address and Port!');
    }
    
    ####################################################################

    // Make sure the ssh2 function exists
    if (!function_exists('ssh2_connect'))
    {
        die('<b>Error:</b> The SSH2 Module doesn\'t exist!  Please check your installation and try again.');
    }

    // Make sure we don't get an empty variables
    if(empty($ip))
    {
        die('<b>Error:</b> A required variable of the SSH2 function was left out: <b>IP Address</b>!');
    }
    elseif(empty($user))
    {
        die('<b>Error:</b> A required variable of the SSH2 function was left out: <b>SSH Username</b>!');
    }
    elseif(empty($pass))
    {
        die('<b>Error:</b> A required variable of the SSH2 function was left out: <b>SSH Password</b>!');
    }
    elseif(empty($cmd))
    {
        die('<b>Error:</b> A required variable of the SSH2 function was left out: <b>SSH Command</b>!');
    }
    elseif(empty($allow_return))
    {
        $allow_return = '2';
    }

    // Connect to the server
    $ssh2 = ssh2_connect($ip,$port);

    ssh2_auth_password($ssh2, $user, $pass);
    
    $stream = ssh2_exec($ssh2, $cmd);
    
    //usleep(200000);
    
    stream_set_blocking($stream, true);
    $data = '';
    
    while($buf = fread($stream,4096))
    {
        $data .= $buf;

        // Print our data from the SSH2 Connection
        if ($allow_return == 1)
        {
            if (empty($data))
            {
                echo '<b>Unknown Error:</b> The command didn\'t return any output!';
            }
            else echo $data;
        }
        
        // Use option 2 for just printing $data wherever you need it
        elseif ($allow_return == 2)
        {
            return $data;
        }
    }

  // Close the stream
  fclose($stream);

}

?>
