<?php
/*

GamePanelX

Description:  Default email templates for any mailing on the system

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
function gpx_mail_restart($action)
{
$longDate = date("l, F d, Y h:iA");

$query = "SELECT first_name,email FROM users WHERE username='$GPXuserName'";
sqlCon($query);

$emailName    = $row['first_name'];
$emailAddress = $row['email'];

$eol="\r\n";
      $name = $emailName;
      $userName = $GPXuserName;
      $fromname = "Imperial Host";
      $fromaddress = "info@imperialhost.com.ar";

$to  = $emailAddress;
$subject = $reason;
$message = '
<html>
<head>
  <title>Game Panel - Imperial Host</title>
</head>
<body>

<center>
<h2>Server Change</h2>
</center>

This email is to notify you of a recent change done do your server.<br>
Information about this is below:<br><br>

Action: ' . $action . '<br>
Time: ' . $longDate . '<br>

<br><br>

<center>
<b>You may turn off these status notifications by going to your control panel, under "Settings".</b>

<br><br>
<i>This automated email was sent using the GamePanelX control panel</i>
</center>

</body>
</html>';

$headers  = 'MIME-Version: 1.0' .$eol;
$headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
$headers .= 'Content-type: text/html; charset=iso-8859-1' .$eol;

mail($to, $subject, $message, $headers) or die("<b>Error:</b> Failed to send mail message!<br><br>");
}

?>