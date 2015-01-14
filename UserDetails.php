<?php
/*

GamePanelX

Description:  Edit user info

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include('include/config.php');
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

// User is administrator
$is_admin = $row['is_admin'];

if($is_admin != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}


// Show first page
if(!isset($_POST['update']))
{
    // Display information on a user - games, voice server, etc that they have.
    $userNameGET = $_GET['username'];
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
  <td align="center" valign="middle"><span class="top_page_titles">User Details</span></td>
</tr>
</table>

<br /><br />

<?
// Make sure we actually have a username with GET
if (!empty($userNameGET))
{
    // Connect to database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');

    // User Information Variables
    $result_personal = mysql_query("SELECT first_name,middle_name,last_name,gender,email,phone,website,country,city,state,address,zip,active,email_notify,original_ip,original_host,is_admin,DATE_FORMAT(date_signup, '%m/%d/%Y %T') AS date_signup,date_expire FROM users WHERE username='$userNameGET'") or die('<b>Error:</b> Failed to query the database (users)!');

    while($row_personal = mysql_fetch_array($result_personal))
    {
        $first            = $row_personal['first_name'];
        $middle           = $row_personal['middle_name'];
        $last             = $row_personal['last_name'];
        $gender           = $row_personal['gender'];
        $email            = $row_personal['email'];
        $phone            = $row_personal['phone'];
        $website          = $row_personal['website'];
        $country          = $row_personal['country'];
        $state            = $row_personal['state'];
        $city             = $row_personal['city'];
        $address          = $row_personal['address'];
        $zip              = $row_personal['zip'];
        $active           = $row_personal['active'];
        $email_notify     = $row_personal['email_notify'];
        $orig_ip          = $row_personal['original_ip'];
        $orig_host        = $row_personal['original_host'];
        $is_admin         = $row_personal['is_admin'];
        $date_signup      = $row_personal['date_signup'];
        $date_expire      = $row_personal['date_expire'];
    }
}
// No username given
else
{
    echo '<b>Error:</b> No username specified!';
    exit(0);
}
?>
<form method="post" action="UserDetails.php">

<?php
//
// Only show gameservers if user is not an administrator
//
if($is_admin != 'Y')
{
?>

<!-- GAME SECTION -->
<table border="0" style="border:1px solid black" cellpadding="2" cellspacing="0" width="500" align="center">

<tr class="rowz_title">
  <td align="center" colspan="3"><img src="images/main/supported_games.png" border="0" /><br /><font color="blue">Game Servers</font><br /><br /></td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td align="left"><span class="top_titles">Server</span></td>
  <td align="left"><span class="top_titles">Connection Info</span></td>
  <td align="left"><span class="top_titles">Description</span></td>
</tr>


<?php
// Get userid from username
$result_userid = @mysql_query("SELECT id FROM users WHERE username='$userNameGET'") or die('<b>Error:</b> Failed to get the user ID!');

while($row_userid = mysql_fetch_array($result_userid))
{
    $userid = $row_userid['id'];
}
    
// Check what games this user owns
$result_check_games     = @mysql_query("SELECT id,server,ip,port,description FROM userservers WHERE type='game' AND userid='$userid'") or die('<b>Error:</b> Failed to query the userservers table!');
$num_check_games        = mysql_num_rows($result_check_games);

if($num_check_games >= 1)
{
    while($row_check_games = mysql_fetch_array($result_check_games))
    {
        $server_name  = $row_check_games['server'];
        $server_id    = $row_check_games['id'];
        $server_ip    = $row_check_games['ip'];
        $server_port  = $row_check_games['port'];
        $server_desc  = stripslashes($row_check_games['description']);
        
        // Get long name of game
        $result_long_name = @mysql_query("SELECT long_name FROM servers WHERE short_name='$server_name'") or die('<b>Error:</b> Failed to get server long name!');

        while($row_long_name = mysql_fetch_array($result_long_name))
        {
            $server_long_name = $row_long_name['long_name'];
        }
    
        echo '<tr class="rowz_title">';
        echo '<td align="left"><span class="rowz_alt"><a href="AdminServerEdit.php?id=' . $server_id . '">' . $server_long_name . '</a></span></td>';
        echo '<td align="left"><span class="rowz_alt">' . $server_ip . ':' . $server_port . '</span></td>';
        echo '<td align="left"><span class="rowz_alt">' . $server_desc . '</span></td>';
        echo '</tr>';
    }
}
else
{
    echo '<tr class="rowz_title"><td align="center" colspan="3"><span class="rowz_alt">User has no servers.  <a href="CreateServer.php?type=game&id=' . $userid . '">Click here to add one.</a></span></td></tr>';
}
?>
</table>

<br /><br />



<!-- VOIP SECTION -->
<table border="0" style="border:1px solid black" cellpadding="2" cellspacing="0" width="500" align="center">

<tr class="rowz_title">
  <td align="center" colspan="3"><img src="images/main/supported_voip.png" border="0" /><br /><font color="blue">Voip Servers</font><br /><br /></td>
</tr>

<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td align="left"><span class="top_titles">Server</span></td>
  <td align="left"><span class="top_titles">Connection Info</span></td>
  <td align="left"><span class="top_titles">Description</span></td>
</tr>


<?php
// Get userid from username
$result_userid = @mysql_query("SELECT id FROM users WHERE username='$userNameGET'") or die('<b>Error:</b> Failed to get the user ID!');

while($row_userid = mysql_fetch_array($result_userid))
{
    $userid = $row_userid['id'];
}
    
// Check what games this user owns
$result_check_games     = @mysql_query("SELECT id,server,ip,port,description FROM userservers WHERE type='voip' AND userid='$userid'") or die('<b>Error:</b> Failed to query the userservers table!');
$num_check_games        = mysql_num_rows($result_check_games);

if($num_check_games >= 1)
{
    while($row_check_games = mysql_fetch_array($result_check_games))
    {
        $server_name  = $row_check_games['server'];
        $server_id    = $row_check_games['id'];
        $server_ip    = $row_check_games['ip'];
        $server_port  = $row_check_games['port'];
        $server_desc  = stripslashes($row_check_games['description']);
        
        // Get long name of game
        $result_long_name = @mysql_query("SELECT long_name FROM servers WHERE short_name='$server_name'") or die('<b>Error:</b> Failed to get server long name!');

        while($row_long_name = mysql_fetch_array($result_long_name))
        {
            $server_long_name = $row_long_name['long_name'];
        }
    
        echo '<tr class="rowz_title">';
        echo '<td align="left"><span class="rowz_alt"><a href="AdminServerEdit.php?id=' . $server_id . '">' . $server_long_name . '</a></span></td>';
        echo '<td align="left"><span class="rowz_alt">' . $server_ip . ':' . $server_port . '</span></td>';
        echo '<td align="left"><span class="rowz_alt">' . $server_desc . '</span></td>';
        echo '</tr>';
    }
}
else
{
    echo '<tr class="rowz_title"><td align="center" colspan="3"><span class="rowz_alt">User has no servers.  <a href="CreateServer.php?type=voip&id=' . $userid . '">Click here to add one.</a></span></td></tr>';
}
?>
</table>

<br /><br />

<?php
} // End admin/servers check
?>

<table border="0" style="border:1px solid black" cellpadding="2" cellspacing="0" width="400" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left"><span class="top_titles">&nbsp;&nbsp;&nbsp;&nbsp;Personal Information</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Username:</span>&nbsp;&nbsp;</td>
  <td align="left"><?php echo $userNameGET; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Password:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="button" value="Change Password" style="width:100%" onClick="window.location='ChangePassword.php?user=<?php echo $userNameGET; ?>'"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Status:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="active" style="width:100px">
    <?php
    // Display active or not
    if($active == 'Y')
    {
        echo '  <option value="Y" selected>Active</option>';
        echo '  <option value="N">Suspended</option>';
    }
    else
    {
        echo '  <option value="Y">Active</option>';
        echo '  <option value="N" selected>Suspended</option>';
    }
    ?>
    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">First Name:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="first_name" class="userinput" value="<?php echo $first; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Middle Name:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="middle_name" class="userinput" value="<?php echo $middle; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Last Name:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="last_name" class="userinput" value="<?php echo $last; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Gender:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="gender" style="width:100px">
    <?php
    // Male
    if($gender == 'male')
    {
        echo '  <option value="male" selected>Male</option>';
        echo '  <option value="female">Female</option>';
    }
    // Female
    elseif($gender == 'female')
    {
        echo '  <option value="male">Male</option>';
        echo '  <option value="female" selected>Female</option>';
    }
    // Otherwise
    else
    {
        echo '  <option value="" selected></option>';
        echo '  <option value="male">Male</option>';
        echo '  <option value="female">Female</option>';
    }
    ?>
    </select>
  </td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Email:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="email" class="userinput" value="<?php echo $email; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Phone:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="phone" class="userinput" value="<?php echo $phone; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Website:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="website" class="userinput" value="<?php echo $website; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Country:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="country" class="userinput" value="<?php echo $country; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">City:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="city" class="userinput" value="<?php echo $city; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">State:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="state" class="userinput" value="<?php echo $state; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Address:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="address" class="userinput" value="<?php echo $address; ?>"></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Zip Code:&nbsp;&nbsp;</span></td>
  <td align="left"><input type="text" name="zip" class="userinput" value="<?php echo $zip; ?>"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Email Notifications:&nbsp;&nbsp;</span></td>
  <td align="left">
    <select name="email_notify" style="width:100px">
    <?php
    // Display active or not
    if($email_notify == 'Y')
    {
        echo '  <option value="Y" selected>Yes</option>';
        echo '  <option value="N">No</option>';
    }
    else
    {
        echo '  <option value="Y">Yes</option>';
        echo '  <option value="N" selected>No</option>';
    }
    ?>
    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Expiration Date:&nbsp;&nbsp;</span></td>
  <td align="left">
  <input type="text" name="date_expire" value="<?php echo $date_expire; ?>"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Admin User:&nbsp;&nbsp;</span></td>
  <td align="left"><?php if($is_admin=="Y") echo "Yes"; else echo "No"; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Date Added:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $date_signup; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Signup IP:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $orig_ip; ?></td>
</tr>
<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Signup Hostname:&nbsp;&nbsp;</span></td>
  <td align="left"><?php echo $orig_host; ?></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="submit" name="update" value="Update" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

</table>
<input type="hidden" name="username" value="<?php echo $userNameGET; ?>">
</form>

<br />

</body>
</html>
<?php
}

// Update Page
elseif(isset($_POST['update']))
{
    include('include/config.php');
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
  <td align="center" valign="middle"><span class="top_page_titles">User Details</span></td>
</tr>
</table>

<br /><br />

<?php
    $post_username = $_POST['username'];

    // Connect to database
    $db = @mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
    @mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to select the database!');
    
    $post_first_name    = $_POST['first_name'];
    $post_middle_name   = $_POST['middle_name'];
    $post_last_name     = $_POST['last_name'];
    $post_gender        = $_POST['gender'];
    $post_email         = $_POST['email'];
    $post_phone         = $_POST['phone'];
    $post_website       = $_POST['website'];
    $post_country       = $_POST['country'];
    $post_state         = $_POST['state'];
    $post_city          = $_POST['city'];
    $post_address       = $_POST['address'];
    $post_zip           = $_POST['zip'];
    $post_active        = $_POST['active'];
    $post_email_notify  = $_POST['email_notify'];
    $post_date_expire   = $_POST['date_expire'];
    
    // Update user details
    if(mysql_query("UPDATE users SET first_name='$post_first_name',middle_name='$post_middle_name',last_name='$post_last_name',gender='$post_gender',email='$post_email',phone='$post_phone',website='$post_website',country='$post_country',state='$post_state',city='$post_city',address='$post_address',zip='$post_zip',active='$post_active',email_notify='$post_email_notify',date_expire='$post_date_expire' WHERE username='$post_username'"))
    {
        echo '<center><b>Success!</b><br /><br /><a href="UserDetails.php?username=' . $post_username . '">Click to go back to User Details</a></center>';
    }
    else
    {
        echo '<center><b>Failure!</b><br /><br />Failed to update user info: <i>' . mysql_error() . '</i></center>';
    }

}
?>
