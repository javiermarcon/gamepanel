<?php
/*

GamePanelX

Description:  Table that shows up to let a user know that a change happened

Author:       Ryan D. Gehrig
License:      GNU General Public License (GPL)

*/
include_once('include/config.php'); 
include_once('include/auth.php');
include_once('include/SqlCon.php');
include_once('include/statusInfo.php');

// Info Box
function infoBox($title,$info)
{
?>
<table bgcolor="#FFFF66" border="0" cellpadding="0" cellspacing="0" style="border:2px dashed black" width="600" height="45" align="center">
  <tr>
    <td align="center"><b><font color="black"><?php echo $title; ?></b><br /><?php echo $info; ?></font></td>
  </tr>
</table>
<?php
}
?>