<?php
/*

GamePanel

Description:  Sistema de administracion de servidores de juegos online

Author:       Luiz Fernando gritti
License:      Todos los derechos reservados a Imperial Host de Argentina S.A.


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

if (!isset($_POST['submit']) && !isset($_POST['search']))
{
    // Use URL to sort users
    $sort_by    = $_GET['sort'];
    $sort_way   = $_GET['way'];

    //Check if sort by is empty
    if(empty($sort_by))
    {
        $sort_by = "id";
    }

    //Check if sort way is empty
    if(empty($sort_way))
    {
        $sort_way = "DESC";
        $sort_way_change = "DESC";
    }
    else
    {
        if($sort_way == "ASC")
        {
            $sort_way_change = "DESC";
        }
        elseif($sort_way == "DESC")
        {
            $sort_way_change = "ASC";
        }
    }

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
  <td align="center" valign="middle"><span class="top_page_titles">User Editor</span></td>
</tr>
</table>

<br /><br />

<div align="center"><span title="Haz clic para obtener traducciones alternativas">Sugerencia</span><span title="Haz clic para obtener traducciones alternativas">: Escriba</span> <span title="Haz clic para obtener traducciones alternativas">su</span> <span title="Haz clic para obtener traducciones alternativas">consulta de</span> <span title="Haz clic para obtener traducciones alternativas">búsqueda</span> <span title="Haz clic para obtener traducciones alternativas">en el</span> <span title="Haz clic para obtener traducciones alternativas">siguiente</span><br /><br />

<form method="post" action="<?php echo $PHP_SELF; ?>" name="searcher">
<input type="text" name="search_text" id="search_text" value="" class="userinput" maxlenth="50" style="width:150px">

<select name="search_class" class="dropdown">
  <option value="username">Username</option>
  <option value="first_name">First Name</option>
  <option value="last_name" selected>Last Name</option>
  <option value="email">Email Address</option>
  <option value="phone">Phone Number</option>
</select>

<select name="search_type" class="dropdown">
  <option value="similar" selected>Similar</option>
  <option value="exact">Exact</option>
</select>

<input type="submit" name="search" id="search" value="Search User">
</form>

</div>

<br />

<table border="0" class="tablez" cellpadding="2" cellspacing="0" align="center" width="700">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td align="center"><span class="top_titles">#</span></td>
    <td align="center"><span class="top_titles"><a href="?sort=id&way=<?php echo $sort_way_change; ?>">id</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=is_admin&way=<?php echo $sort_way_change; ?>">Type</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=username&way=<?php echo $sort_way_change; ?>">Username</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=first_name&way=<?php echo $sort_way_change; ?>">First Name</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=last_name&way=<?php echo $sort_way_change; ?>">Last Name</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=email&way=<?php echo $sort_way_change; ?>">Email</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=date_signup&way=<?php echo $sort_way_change; ?>">Date added</a></span></td>
    <td align="center"><span class="top_titles"><a href="?sort=active&way=<?php echo $sort_way_change; ?>">Status</a></span></td>
    <td align="center"><span class="top_titles">Edit</span></td>
  </tr>

<?
//Connect to MySQL and run a loop for users
$db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die('<b>Error:</b> Failed to connect to the database!');
mysql_select_db($config['sql_db']) or die('<b>Error:</b> Failed to connect to the database!');

// Personal -- Main query for select
$result = mysql_query("SELECT id,username,first_name,last_name,email,active,is_admin,DATE_FORMAT(date_signup, '%m/%d/%Y %T') AS date_signup FROM users ORDER BY $sort_by $sort_way") or die(mysql_error());
$num    = mysql_num_rows($result);

// Include alternating row colors
include("include/colors.php");

// Output for no rows
if($num == 0)
{
?>
  <tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onMouseOver="style.backgroundColor='<?php echo $bgOpp; ?>'" onMouseOut="style.backgroundColor='<?php echo $bgColor; ?>'">
    <td align="center" colspan="12"><span class="rowz_alt">No results to display</span></td>
  </tr>
<?php
}

while($row = mysql_fetch_array($result))
{
    // Include alternating row colors
    include("include/colors.php");

    //Define user details for loop
    $id           = $row['id'];
    $isAdmin      = $row['is_admin'];
    $userName     = $row['username'];
    $first        = $row['first_name'];
    $last         = $row['last_name'];
    $email        = $row['email'];
    $date_added   = $row['date_signup'];
    $active       = $row['active'];

    // Nicer active output
    if($active == "Y")
    {
        $activeNice = "<font color=green>Active</font>";
    }
    else
    {
        $activeNice = "<font color=red>Suspended</font>";
    }

    // Nicer admin output
    if($isAdmin == "Y")
    {
        $account_type = "<b>Admin</b>";
    }
    else
    {
        $account_type = "User";
    }
    ?>
    <form method="post" action="<?php echo $PHP_SELF; ?>">
    <tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onMouseOver="style.backgroundColor='<?php echo $bgOpp; ?>'" onMouseOut="style.backgroundColor='<?php echo $bgColor; ?>'">
      <td align="center" class="rowz_alt"><input name="delete[]" id="delete[]" type="checkbox" value="<?php echo $id; ?>"></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $id; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $account_type; ?></span></td>
      <td align="center"><span class="rowz_alt"><b><?php echo $userName; ?></b></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $first; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $last; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $email; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $date_added; ?></span></td>
      <td align="center"><span class="rowz_alt"><?php echo $activeNice; ?></span></td>
      <td align="center"><span class="rowz_alt"><a href="UserDetails.php?username=<?php echo $userName; ?>"><img src="images/edit.png" border="0"></a></td>
    </tr>
<?
}
?>
</table>

<br />

<center><input name="submit" type="submit" id="submit" value="Delete User" style="width:170px"></center>
</form>

</body>
</html>
<?
}

// Delete button has been pressed
elseif (isset($_POST['submit']) && !isset($_POST['search']))
{
$checkDelete  = $_REQUEST['delete'];
$delList      = implode(",", $checkDelete);


// If nothing was selected...
if(empty($delList))
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
  <td align="center" valign="middle"><span class="top_page_titles">User Editor</span></td>
</tr>
</table>

<br /><br />

<center>
<b>Failure!</b>
<br /><br />
No users were selected.
<br /><br />
<a href="UserEditor.php">Click here</a> to go back.
</center>

</body>
</html>
<?
}

// Otherwise, run delete
else
{
    // For every user, delete their gameservers then account
    $arr_del_list = explode(",", $delList);
    
    foreach($arr_del_list as $single_id)
    {
        // Delete all Game Servers
        $query = "DELETE FROM userservers WHERE userid='$single_id'";
        sqlCon($query);
        
        // Delete this user account
        $query = "DELETE FROM users WHERE id='$single_id'";
        sqlCon($query);
    }
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
  <td align="center" valign="middle"><span class="top_page_titles">User Editor</span></td>
</tr>
</table>

<br /><br />

<center>

<b>Success!</b>

<br /><br />

User account(s) <b><?php echo $delList; ?></b> deleted.

<br /><br />

<a href="UserEditor.php">Click to go back to the User Editor</a>

</center>

</body>
</html>
<?
  }
}

elseif (isset($_POST['search']))
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
  <td align="center" valign="middle"><span class="top_page_titles">User Editor</span></td>
</tr>
</table>

<br /><br />

<?php
$text   = $_POST['search_text'];
$class  = $_POST['search_class'];
$type   = $_POST['search_type'];

switch($class)
{
  case "username":
      
      $nice_class_text  = "Username";
      break;

  case "first_name":
      
      $nice_class_text  = "First Name";
      break;

  case "last_name":
      
      $nice_class_text  = "Last Name";
      break;

  case "email":
      
      $nice_class_text  = "Email Address";
      break;

  case "phone":
      
      $nice_class_text  = "Phone Number";
      break;
}

switch($type)
{
  case "exact":
      
      $nice_match_text  = "exactly like";
      break;

  case "similar":
      
      $nice_match_text  = "similar to";
      break;
}

echo "<center>Searching field '$nice_class_text' that is <u>$nice_match_text</u> \"<b>$text</b>\"</center><br />";

?>
<table border="1" class="tablez" cellpadding="2" cellspacing="0" align="center">
<tr background="css/<?php echo $config['theme']; ?>/img/smallGrad.png" height="20">
    <td align="center"><span class="top_titles">#</span></td>
    <td align="center"><span class="top_titles">Admin</span></td>
    <td align="center"><span class="top_titles">Username</span></td>
    <td align="center"><span class="top_titles">First Name</span></td>
    <td align="center"><span class="top_titles">Last Name</span></td>
    <td align="center"><span class="top_titles">Email</span></td>
    <td align="center"><span class="top_titles">Date added</span></td>
    <td align="center"><span class="top_titles">Gender</span></td>
    <td align="center"><span class="top_titles">Phone Number</span></td>
    <td align="center"><span class="top_titles">State</span></td>
    <td align="center"><span class="top_titles">Active</span></td>
    <td align="center"><span class="top_titles">Action</span></td>
  </tr>
<?php
// Connect to db
$db = mysql_connect($config['sql_host'],$config['sql_user'],$config['sql_pass']) or die(mysql_error());
mysql_select_db($config['sql_db']) or die(mysql_error());

// Exact search
if($type == "exact")
{
    $query = "SELECT * FROM users WHERE $class='$text'";
}

// Similar search
elseif($type == "similar")
{
    $query = "SELECT * FROM users WHERE $class LIKE '%$text%'";
}

// Otherwise
else
{
    die("<b>Error:</b> Unknown option!");
}

$result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result))
{
    // Include alternating row colors
    include("include/colors.php");

    //Define user details for loop
    $id           = $row['id'];
    $userName     = $row['username'];
    $first        = $row['first_name'];
    $last         = $row['last_name'];
    $email        = $row['email'];
    $date_added   = $row['date_signup'];
    $gender       = $row['gender'];
    $phone        = $row['phone'];
    $state        = $row['state'];
    $active       = $row['active'];
    $isAdmin      = $row['is_admin'];

    // Nicer active output
    if($active=="Y")
    {
        $activeNice="Yes";
    }
    else
    {
        $activeNice="No";
    }

    // Nicer admin output
    if($isAdmin=="Y")
    {
        $adminNice="Yes";
    }
    else
    {
        $adminNice="No";
    }
?>
<form method="post" action="<?php echo $PHP_SELF; ?>">
<tr valign="middle" style="background-color:<?php echo $bgColor; ?>" onMouseOver="style.backgroundColor='<?php echo $bgOpp; ?>'" onMouseOut="style.backgroundColor='<?php echo $bgColor; ?>'">
  <td align="center" class="rowz_alt"><input name="delete[]" id="delete[]" type="checkbox" value="<?php echo $id; ?>"></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $id; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $adminNice; ?></span></td>
  <td align="center"><span class="rowz_alt"><b><?php echo $userName; ?></a></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $first; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $last; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $email; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $date_added; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $gender; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $phone; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $state; ?></span></td>
  <td align="center"><span class="rowz_alt"><?php echo $activeNice; ?></span></td>
  <td align="center"><span class="rowz_alt"><input type="button" value="Edit" style="width:100%" onClick="window.location='UserDetails.php?username=<?php echo $userName; ?>'"></span></td>
</tr>

<?php
}
?>
</table>

</body>
</html>
<?php
}
?>
