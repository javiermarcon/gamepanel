<?php
/*

GamePanelX

Description:  Create a user account/game server

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php');
include_once('include/SqlCon.php');
include_once('include/auth.php');
include_once('include/statusInfo.php');
include_once('include/functions.php');

//Make check to see if the logged in user is an admin.
$query="SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}

if (!isset($_POST['submit']))
{
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
  <td align="center" valign="middle"><span class="top_page_titles">Create User Account</span></td>
</tr>
</table>

<br /><br />

<form method="post" action="<?php echo $PHP_SELF; ?>" name="createUser">
<table border="0" width="400" cellpadding="1" cellspacing="0" class="tablez" style="border:1px solid black" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
  <td colspan="2" align="left">&nbsp;&nbsp;&nbsp;&nbsp;<span class="top_titles">Account Information</span></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><img src="images/main/add-user-64.png" border="0" /></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right" width="150"><span class="rowz_alt">Account Type:</span>&nbsp;&nbsp;</td>
  <td align="left">
    <select name="account_type" style="width:150px">
      <option value="normal">Normal</option>
      <option value="admin">Administrator</option>
    </select>
  </td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td align="right" width="150"><span class="rowz_alt">Username:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="username" class="userinput" style="width:150px" maxlength="25" value="<?php echo $_POST['username']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Password:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="password" class="userinput" style="width:150px" maxlength="35"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Repeat Password:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="password" name="pass_confirm" class="userinput" style="width:150px" maxlength="35"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">First Name:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="first_name" class="userinput" style="width:150px" maxlength="25" value="<?php echo $_POST['first_name']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Middle Initial:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="mid_initial" class="userinput" style="width:150px" maxlength="4" value="<?php echo $_POST['mid_initial']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Last Name:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="last_name" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['last_name']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Email Address:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="email" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['email']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Phone Number</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="phone" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['phone']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Website:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="website" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['website']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Gender:</span>&nbsp;&nbsp;</td>
  <td align="left">
  <select name="gender" style="width:150px"> 
    <option value="unspecified" selected="yes">Unspecified</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
  </select></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">State:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="state" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['state']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Country:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="country" class="userinput" style="width:150px" maxlength="30" value="<?php echo $_POST['country']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td align="right"><span class="rowz_alt">Zip Code:</span>&nbsp;&nbsp;</td>
  <td align="left"><input type="text" name="zip" class="userinput" style="width:150px" maxlength="6" value="<?php echo $_POST['zip']; ?>"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>

<tr class="rowz_title">
  <td colspan="2" align="center"><input type="submit" value="Create Account" name="submit" style="width:170px"></td>
</tr>

<tr class="rowz_title">
  <td colspan="2">&nbsp;</td>
</tr>
</table>

<br>

<div align="center">
&nbsp;&nbsp;
</form>
</div>
</body>
</html>
<?
}


// Insert Page
elseif(isset($_POST['submit']))
{
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
  <td align="center" valign="middle"><span class="top_page_titles">Create User Account</span></td>
</tr>
</table>

<br /><br />
<?php
    // Post values
    $first_name     = $_POST['first_name'];
    $middle_name    = $_POST['mid_initial'];
    $last_name      = $_POST['last_name'];
    $username       = $_POST['username'];
    $password       = $_POST['password'];
    $passConf       = $_POST['pass_confirm'];
    $password       = $_POST['password'];
    $gender         = $_POST['gender'];
    $email          = $_POST['email'];
    $phone          = $_POST['phone'];
    $website        = $_POST['website'];
    $state          = $_POST['state'];
    $city           = $_POST['city'];
    $address        = $_POST['address'];
    $country        = $_POST['country'];
    $zip            = $_POST['zip'];
    $game           = $_POST['game'];
    $post_acct_type = $_POST['account_type'];

    // Signup IP Address
    $orig_ip        = $_SERVER['REMOTE_ADDR'];
    $orig_host      = gethostbyaddr($orig_ip);

    // Account Type
    if ($post_acct_type == 'admin')
    {
        $is_admin = 'Y';
    }
    else
    {
        $is_admin = 'N';
    }

    // Include functions
    require_once('include/functions.php');

    // Create User Account
    if(!create_user($username,$password,$first_name,$middle_name,$last_name,$gender,$email,$phone,$website,$country,$state,$city,$address,$zip,$orig_ip,$orig_host,$is_admin))
    {
        die('<b>Error:</b> User Account Creation failed!');
    }
?>

<center>
<b>Success!</b><br /><br />
<a href="UserEditor.php">Click to view the User Editor</a>
</center>

</body>
</html>
<?php
}
?>
