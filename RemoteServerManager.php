<?php
/*

GamePanelX

Description:  Manage remote server IP Addresses

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

//Make check to see if the logged in user is an admin.
$query = "SELECT is_admin FROM users WHERE username='$GPXuserName'";
sqlCon($query);

if($row['is_admin'] != 'Y')
{
    include('Unauthorized.php');
    exit(0);
}
    
if (!isset($_POST['submit']))
{

// Get limit from URL
$limit1 = $_GET['limit1'];
$limit2 = 10;

if (empty($limit1))
{
  $limit1 = 0;
}

?>
<html>
<head>
<title><?php echo $config['title']; ?></title>
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
  <td align="center" valign="middle"><span class="top_page_titles">Remote Server Manager</span></td>
</tr>
</table>

<br /><br />

<table class="tablez" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td align="center"><span class="top_titles">#</span></td>
    <td align="center"><span class="top_titles">Date Added</span></td>
    <td align="center"><span class="top_titles">Available</span></td>
    <td align="center"><span class="top_titles">Physical</span></td>
    <td align="center"><span class="top_titles">IP Address</span></td>
    <td align="center"><span class="top_titles">Operating System</span></td>
    <td align="center"><span class="top_titles">Location</span></td>
    <td align="center"><span class="top_titles">Datacenter</span></td>
    <td align="center"><span class="top_titles">Edit</span></td>
  </tr>

<?php

//Connect to MySQL and run a loop for Remote Servers
$db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
mysql_select_db($config['sql_db']) or die(mysql_error());

$result = mysql_query("SELECT id,DATE_FORMAT(date_added, '%m/%d/%Y %T') AS date_added,available,ip,operating_system,physical,location,datacenter FROM remote ORDER BY ip LIMIT $limit1,$limit2") or die(mysql_error());
$num    = mysql_num_rows($result);

// Include alternating row colors
include('include/colors.php');

// Output for no rows
if($num == 0)
{
?>
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center" colspan="12"><span class="rowz_alt">No results to display</span></td>
</tr>
<?php
}

$maxRecords = $num / 10;

while($row = mysql_fetch_array($result))
{
    // Include alternating row colors
    include("include/colors.php");

    //Define user details for loop
    $id             = $row['id'];
    $date_added     = $row['date_added'];
    $avail          = $row['available'];
    $ip             = $row['ip'];
    $os             = $row['operating_system'];
    $phys           = $row['physical'];
    $location       = $row['location'];
    $datacenter     = $row['datacenter'];

    // Nicer "available" output 
    if ($avail == "Y")
    {
        $available = "Yes";
    }
    else
    {
        $available = "No";
    }

    // Nicer "physical" output
    if ($phys == "Y")
    {
        $physical = "Yes";
    }
    else
    {
        $physical = "No";
    }
?>
    <form method="post" action="RemoteServerManager.php">
    <tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onmouseover="style.backgroundColor='<?php echo $bgOpp; ?>'" onmouseout="style.backgroundColor='<?php echo $bgColor; ?>'">
      <td align="center"><span class="rowz_alt"><input name="delete[]" id="delete[]" type="checkbox" value="<?php echo $id; ?>"></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $date_added; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $available; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $physical; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $ip; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $os; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $location; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $datacenter; ?></span></td>
      <td align="center"><a href="RemoteServerEdit.php?id=<?php echo $id; ?>"><img src="images/edit.png" border="0"></a></td>
    </tr>
<?
}
?>
</table>

<br />

<center>
<?php
// Check if we had a previous page
//
// Show a link for 'previous page'
if (!empty($limit1))
{
?>
<< <a href="?limit1=<?php echo $limit1 - 10; ?>&limit2=<?php echo $limit2; ?>">Previous Page</a> - <a href="?limit1=<?php echo $limit1 + 10; ?>&limit2=<?php echo $limit2; ?>">Next Page >></a>
<?php
}
// Show no link for 'previous page'
elseif($limit1 >= 10 || empty($limit1))
{
?>
<< Previous Page - <a href="?limit1=10&limit2=<?php echo $limit2; ?>">Next Page >></a>
<?php
}
?>

<br /><br />

<input name="submit" type="submit" id="submit" value="Delete Selected">&nbsp;<font color="#FFFFFF">
</form>
</center>

</body>
</html>

<?
}
//Now if submit is set...
elseif (isset($_POST['submit']))
{
$checkDelete = $_REQUEST['delete'];
$delList = implode(",", $checkDelete);

//If nothing was selected...
if (empty($delList))
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
  <td align="center" valign="middle"><span class="top_page_titles">New Remote Server</span></td>
</tr>
</table>

<br /><br />

<center>
<b>Error:</b> You didn't select any rows for deletion!<br /><br />
Please go <a href="RemoteServerManager.php"><b>back</b></a> and try again.
</center>

</body>
</html>
<?
// kill
exit(0);
}

else
{
    //Delete selected servers from MySQL
    $query = "DELETE FROM remote WHERE id IN($delList)";
    sqlCon($query);
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
  <td align="center" valign="middle"><font color="#FFFFFF"><b>New Remote Server</b></font></td>
</tr>
</table>

<br /><br />

<center>
<?php
//Make sure we are correct in our english :P
if (strlen($delList) == 1)
{
  echo 'Remote Server <i>' . $delList . '</i> deleted.';
}
if (strlen($delList) >= 2)
{
  echo 'Remote Servers <i>' . $delList . '</i> deleted.';
}
?>
<br /><br />

Click <a href="RemoteServerManager.php"><b>here</b></a> to return.

</body>
</html>
<?php
  }
}
?>
